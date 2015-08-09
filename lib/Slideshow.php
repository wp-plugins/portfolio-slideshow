<?php

namespace Portfolio_Slideshow;

use WP_Query;

defined( 'WPINC' ) or die;

class Slideshow {

	public $args;
	public $ID;
	public $slides;

	public $key;

	public function __construct( $args = [] ) {
		$this->args = $args;

		$maybe_id     = $this->arg( 'id' );
		$this->ID     = ! empty( $maybe_id ) ? absint( $maybe_id ) : get_the_ID();
		$this->key    = rand( 1, 999 );
		$this->slides = $this->get_slides();

		// Some option-forces from previous version.
		// @TODO – Clean up these forces.
		if ( 'true' == $this->arg( 'thumbs' ) ) {
			$this->args['pagerpos'] = 'bottom';
		}

		if ( 'false' == $this->arg( 'nowrap' ) || 'true' == $this->arg( 'loop' ) ) {
			$this->args['loop'] = 'true';
		}
	}

	/**
	 * @since 2.0.0
	 *
	 * @param string $arg
	 * @return string|array|false
	 */
	public function arg( $arg ) {
		return isset( $this->args[ $arg ] ) ? $this->args[ $arg ] : false;
	}

	/**
	 * @since 2.0.0
	 *
	 * @return array
	 */
	public function get_slides() {

		$slides   = get_post_meta( $this->ID, '_portfolio_slideshow', true );
		$excluded = [];


		if ( empty( $slides ) ) {
			
			$slides = [];
			
			$slides_query_args = [
				'post_parent'    => $this->ID,
				'post_type'      => 'attachment',
				'post_status'    => 'any',
				'post_mime_type' => 'image',
				'orderby'        => ( 'true' == $this->arg( 'random' ) ? 'rand' : 'menu_order' ),
				'order'          => 'ASC',
				'posts_per_page' => -1, // Get _all_ images – works around bug where limited by Reading settings.
				'meta_query'     => [
					'relation' => 'OR',
					[
						'key'     => '_is_excluded_from_portfolio_slideshow',
						'value'   => 0,
						'type'    => 'numeric',
						'compare' => 'IN'
					],
					[
						'key'     => '_is_excluded_from_portfolio_slideshow',
						'value'   => '',
						'compare' => 'NOT EXISTS'
					]
				]
			];
	
			$slides_query_args = apply_filters( 'portfolio_slideshow_slides_query_args', $slides_query_args );
	
			$slides_query = new WP_Query( $slides_query_args );

			if ( $slides_query->have_posts() ) {

				while ( $slides_query->have_posts() ) {
					$slides_query->the_post();

					$slides[] = [
						'image'   => absint( $slides_query->post->ID ),
						'caption' => isset( $slides_query->post->post_excerpt ) && is_string( $slides_query->post->post_excerpt ) ? $slides_query->post->post_excerpt : '',
						'url'     => sanitize_text_field( get_post_meta( $slides_query->post->ID, '_ps_image_link', true ) )
					];

				}
			}

			wp_reset_postdata();
		}

		if ( ! is_array( $slides ) || empty( $slides ) ) {
			return $this->public_add_slides_notice();
		}

		if ( 'true' == $this->arg( 'random' ) ) {
			shuffle( $slides );
		}

		if (  'true' == $this->arg( 'exclude_featured' ) && current_theme_supports( 'post-thumbnails' ) ) {
			$excluded[] = get_post_thumbnail_id( $this->ID );
		}

		if ( ! empty( $this->args['exclude'] ) ) {
			foreach ( explode( ',', $this->args['exclude'] ) as $attachment_id ) {
				$excluded[] = $attachment_id;
			}
		}

		if ( ! empty( $this->args['include'] ) ) {
			$included = explode( ',', $this->args['include'] );
			
			foreach ( wp_list_pluck( $slides, 'image' ) as $key => $slide_id ) {
				if ( ! in_array( $slide_id, $included ) ) {
					$excluded[] = $slide_id;
				}
			}
		}

		foreach ( $slides as $key => $slide ) {
			if ( in_array( $slide['image'], $excluded ) ) {
				unset( $slides[ $key ] );
			}
		}

		return array_values( $slides );
	}

	/**
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function the_slideshow() {
		
		if ( ! is_array( $this->slides ) || empty( $this->slides ) ) {
			return;
		}

		if ( is_feed() ) {
			$this->public_is_feed_notice();
		}

		ob_start();

			$vars_html = '';

			$vars_raw = [
				'psTimeout'  => $this->arg( 'timeout' ),
				'psAutoplay' => $this->arg( 'autoplay' ),
				'psTrans'    => $this->arg( 'trans' ),
				'psLoop'     => $this->arg( 'loop' ),
				'psSpeed'    => $this->arg( 'speed')
			];

			foreach ( $vars_raw as $name => $value ) {
				$vars_html .= sprintf( '%s[%s]=\'%s\';', $name, $this->key, $value );
			}

			printf( '<script type="text/javascript">/* <![CDATA[ */ %s /* ]]> */</script>', $vars_html );

			print '<div id="slideshow-wrapper' . $this->key . '" class="slideshow-wrapper clearfix';

			if ( 'true' == Plugin::get_option( 'showloader' ) ) {
				print ' showloader';
			}

			print '">';

			if ( 'top' == $this->arg( 'navpos' ) ) $this->the_nav();

			if ( 'top' == $this->arg( 'pagerpos' ) ) $this->the_pager();

			$this->the_slides();

			if ( 'true' == $this->arg( 'showtitles' ) || 'true' == $this->arg( 'showcaps' ) || 'true' == $this->arg( 'showdesc' ) ) {
				$this->the_meta();
			}

			if ( 'bottom' == $this->arg( 'navpos' ) ) $this->the_nav();

			if ( 'bottom' == $this->arg( 'pagerpos' ) ) $this->the_pager();

			print '</div><!--#slideshow-wrapper-->';

		return ob_get_clean();
	}

	/**
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function the_slides() {
		$key = absint( $this->key );
		$slides_count     = count( $this->slides );
		$maybe_min_height = '';
		$slides = $this->slides;
		$click = $this->arg( 'click' );
		$loop = $this->arg( 'loop' );
		$placeholder = Plugin::asset_url( '/images/tiny.png' );
		$size = $this->arg( 'size' );

		if ( 0 < absint( $this->arg( 'slideheight' ) ) )  {
			$maybe_min_height = sprintf( 'min-height: %spx !important;', $this->arg( 'slideheight' ) );
		}

		include Plugin::plugin_path() . 'views/slides.php';
	}

	/**
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function the_nav() {
		$key = absint( $this->key );

		include Plugin::plugin_path() . 'views/nav/text.php';
	}

	/**
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function the_pager() {
		$key    = absint( $this->key );
		$slides = $this->slides;

		include Plugin::plugin_path() . 'views/pager/thumbs.php';
	}

	/**
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function the_meta() {
		$slides     = $this->slides;
		$showtitles = $this->arg( 'showtitles' );
		$showcaps   = $this->arg( 'showcaps' );
		$showdesc   = $this->arg( 'showdesc' );

		include Plugin::plugin_path() . 'views/meta.php';
	}

	/**
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function public_add_slides_notice() {
		$post_type    = get_post_type( $this->ID );
		$current_user = get_current_user_id();

		if ( ! $current_user || ! current_user_can( 'edit_' . $post_type, $current_user ) ) {
			return apply_filters( 'portfolio_slideshow_logged_out_no_slideshow_found', '' );
		}

		return apply_filters( 'portfolio_slideshow_logged_in_no_slideshow_found', sprintf(
			'<strong>%s – <a href="%s" target="_blank">%s</a>.</strong>',
			esc_html__( 'No slides found for this slideshow', 'portfolio-slideshow' ),
			esc_url( get_edit_post_link( $this->ID ) ),
			esc_html__( 'add slides here', 'portfolio-slideshow' )
		) );
	}

	/**
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function public_is_feed_notice() {
		echo wp_get_attachment_image( $this->slides[0]['image'], $this->arg( 'size' ) );
	}
}