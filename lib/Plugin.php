<?php

namespace Portfolio_Slideshow;

defined( 'WPINC' ) or die;

class Plugin {

	const VERSION = '1.10.0';

	protected static $instance;

	public static $options;
	protected static $defaults;

	protected static $plugin_dir;
	protected static $plugin_path;
	protected static $plugin_url;
	protected static $dot_min;

	/**
	 * @since 1.9.9
	 *
	 * @return Portfolio_Slideshow
	 */
	public static function instance() {
		return ( ! self::$instance ? new Plugin : self::$instance );
	}

	/**
	 * @todo – Get automated settings working, so there's not a separate function for each single callback.
	 * @todo – once that works fine, move that into class
	 * @todo – trim unnecesarry files and file includes
	 *
	 * @since 1.9.9
	 *
	 * @return void
	 */
	protected function __construct() {

		self::$plugin_path = trailingslashit( dirname( dirname( __FILE__ ) ) );
		self::$plugin_dir  = trailingslashit( basename( self::$plugin_path ) );
		self::$plugin_url  = plugins_url( self::$plugin_dir );
		
		self::$dot_min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		self::$options  = $this->get_options();
		self::$defaults = $this->get_defaults();

		require_once self::$plugin_path . 'lib/Settings.php';

		require_once self::$plugin_path . 'public/functions.php';
		require_once self::$plugin_path . 'lib/Shortcode.php';
		require_once self::$plugin_path . 'lib/Upgrader.php';
		require_once self::$plugin_path . 'lib/Slideshow.php';

		add_action( 'admin_init', [ '\Portfolio_Slideshow\Settings', 'register_settings' ] );

		add_action( 'admin_menu', [ $this, 'add_options_page' ] );

		add_action( 'add_meta_boxes', [ $this, 'register_uploader_meta_box' ] );

		add_action( 'media_row_actions', [ $this, 'add_attachment_id_to_media' ] );
		
		add_action( 'save_post', [ $this, 'save_uploader_meta_box' ], 99 );
		add_action( 'edit_post', [ $this, 'save_uploader_meta_box' ], 99 );

		add_action( 'admin_enqueue_scripts', [ $this, 'load_admin_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_public_scripts' ] );

		add_action( 'wp_head', [ $this, 'wp_head' ] );
		add_action( 'wp_footer', [ $this, 'wp_footer' ] );

		add_shortcode( 'portfolio_slideshow', [ '\Portfolio_Slideshow\Shortcode', 'do_shortcode' ] );
	}

	/**
	 * @since 1.9.9
	 *
	 * @return void
	 */
	public function add_options_page() {
		add_options_page(
			__( 'Portfolio Slideshow', 'portfolio-slideshow' ),
			__( 'Portfolio Slideshow', 'portfolio-slideshow' ),
			'manage_options',
			'portfolio_slideshow',
			[ $this, 'load_options_page' ]
		);
	}

	/**
	 * @since 1.9.9
	 *
	 * @return void
	 */
	public function load_options_page() {
		require_once self::$plugin_path . 'admin/settings.php';
	}

	/**
	 * @since 1.9.9
	 *
	 * @return array
	 */
	public function get_supported_types() {
		return apply_filters( 'portfolio_slideshow_get_supported_types', [ 'post', 'page' ] );
	}

	/**
	 * @since 1.9.9
	 *
	 * @param string $hook The slug of the current admin page.
	 * @return void
	 */
	public function load_admin_scripts( $hook ) {

		$js_deps  = [ 'jquery', 'underscore', 'jquery-ui-core', 'jquery-ui-tabs' ];
		$css_deps = [];

		$slugs = [ 'post.php', 'post-new.php', 'edit.php' ];	

		if ( 'settings_page_portfolio_slideshow' == $hook ) {
			$js_deps[]  = 'jquery-ui-tooltip';
			$css_deps[] = 'portfolio-slideshow-tooltip-css';
			wp_register_style( 'portfolio-slideshow-tooltip-css', self::asset_url( 'vendor/jquery-ui/jquery-ui.css' ), [], self::VERSION, 'all' );
		}

		if ( in_array( $hook, $slugs ) && in_array( get_post_type(), $this->get_supported_types() ) || 'settings_page_portfolio_slideshow' == $hook ) {
			wp_enqueue_style( 'portfolio-slideshow-admin-css', self::asset_url( 'admin.css' ), $css_deps, self::VERSION, 'all' );
			wp_enqueue_script( 'portfolio-slideshow-admin-js', self::asset_url( 'admin.js', true ), $js_deps, self::VERSION, true );

			wp_localize_script( 'portfolio-slideshow-admin-js', 'portfolio_slideshow_admin_i18n', [
				'strings' => [
					'slide_singular'  => __( 'Slide', 'portfolio-slideshow' ),
					'edit_singular'   => __( 'Edit slide', 'portfolio-slideshow' ),
					'delete_singular' => __( 'Delete slide', 'portfolio-slideshow' ),
					'add_plural'      => __( 'Add Slides', 'portfolio-slideshow' )
				]
			] );
	 	}
	}

	/**
	 * @since 1.9.9
	 *
	 * @param string $hook The slug of the current admin page.
	 * @return void
	 */
	public function register_public_scripts() {

		global $ps_options;

		$css_deps = [];
		$js_deps  = [];

		wp_register_style( 'psp-noscript-css', self::asset_url( 'noscript.css' ), [], self::VERSION, 'all' );
		wp_register_style( 'psp-photoswipe-css', self::asset_url( 'vendor/photoswipe.css', true ), [], self::VERSION, 'all' );

		wp_register_script( 'psp-scrollable', self::asset_url( 'vendor/scrollable.js', true ), [ 'jquery' ], '1.2.5', true );
		wp_register_script( 'psp-photoswipe-js', self::asset_url( 'vendor/code.photoswipe.jquery-3.0.4.js' ), [ 'jquery' ], self::VERSION, true );
		wp_register_script( 'psp-cycle', self::asset_url( 'vendor/jquery-cycle/jquery.cycle.all.min.js' ), [ 'jquery' ], '2.99', true );

		if ( 'true' == self::get_option( 'scrollable' ) ) {
			$js_deps[] = 'psp-scrollable';
		}

		if ( 'true' == self::get_option( 'photoswipe' ) ) {
			$css_deps[] = 'psp-photoswipe-css';
			$js_deps[]  = 'psp-photoswipe-js';
		}

		//$css_deps[] = 'psp-noscript-css';
		$js_deps[]  = 'psp-cycle';

		wp_register_style( 'ps-public-css', self::asset_url( 'public.css' ), $css_deps, self::VERSION, 'all' );
		wp_register_script( 'ps-public-js', self::asset_url( 'public.js' ), $js_deps, self::VERSION, true );
	}

	/**
	 * @todo Remove
	 *
	 * @since 1.5.0
	 *
	 * @return void
	 */
	public function wp_head() {
		global $ps_count;
		global $ps_options;

		echo '<noscript><link rel="stylesheet" type="text/css" href="' .  plugins_url( "css/portfolio-slideshow-noscript.css?ver=" . $ps_options['version'], dirname(__FILE__) ) . '" /></noscript>';
		echo '<script type="text/javascript">/* <![CDATA[ */var psTimeout = new Array();  var psAutoplay = new Array();  var psFluid = new Array(); var psTrans = new Array(); var psSpeed = new Array(); var psLoop = new Array();/* ]]> */</script>';
	}

	/**
	 * @todo Remove
	 *
	 * @since 1.5.0
	 *
	 * @return void
	 */
	public function wp_footer() {

		if ( ! is_singular() ) {
			self::$options['showhash'] = 0;
		}

		printf(
			'<script type="text/javascript">
				/* <![CDATA[ */
				var portfolioSlideshowOptions = {
					psHash   : "%s",
					psLoader : "%s",
					psFluid  : "%s"
				}; 
				/* ]]> */
			 </script>',

			self::get_option( 'showhash' ),
			self::get_option( 'showloader' ),
			self::get_option( 'allowfluid' )
		);
	}


	/**
	 * @since 1.9.9
	 *
	 * @param string $post_type The current post type.
	 * @return void
	 */
	public function register_uploader_meta_box( $post_type ) {
		if ( in_array( $post_type, $this->get_supported_types() ) ) {
			add_meta_box( 'portfolio_slideshow_uploader_meta_box', __( 'Portfolio Slideshow', 'portfolio-slideshow' ), [ $this, 'render_uploader_meta_box' ], $post_type, 'normal', 'high' );
		}
	}

	/**
	 * @since 1.9.9
	 *
	 * @return void
	 */
	public function render_uploader_meta_box( $post ) {
		$options = self::$options;

		require_once self::$plugin_path . 'admin/uploader.php';
	}

	/**
	 * @since 1.9.9
	 *
	 * @param int $post_id The ID of the post being saved.
	 * @return void
	 */
	public function save_uploader_meta_box( $post_id ) {

		if ( ! isset( $_POST[ 'portfolio_slideshow_metabox_slides_' . $post_id ] ) ) {
			return $post_id;
		}
		
		if ( empty( $_POST[ 'portfolio_slideshow_metabox_slides_' . $post_id ] ) || ! wp_verify_nonce( $_POST[ 'portfolio_slideshow_metabox_slides_' . $post_id ], 'portfolio_slideshow_save_metabox_slides' ) ) {
			wp_die( __( 'It doesn\'t seem like you have permission to create or edit slideshows.', 'portfolio-slideshow' ) );
		}

		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return $post_id;
		}

		$existing = get_post_meta( $post_id, '_portfolio_slideshow', true );

		if ( ! isset( $_POST['portfolio_slideshow_metabox_slides_order'] ) ) {
			if ( empty( $existing ) ) {
				return $post_id;
			}

			update_post_meta( $post_id, '_portfolio_slideshow', ' ' );
		}

		$to_save = [];

		$attachments = array_map( 'absint', $_POST['portfolio_slideshow_metabox_slides_order'] );

		foreach ( $attachments as $attachment_id ) {

			if ( 0 == $attachment_id ) continue;
			
			$attachment      = get_post( $attachment_id );
			$attachment_meta = get_post_meta( $attachment_id );

			$to_save[] = [
				'image'   => $attachment_id,
				'caption' => isset( $attachment->post_excerpt ) && is_string( $attachment->post_excerpt ) ? sanitize_text_field( $attachment->post_excerpt ) : '',
				'url'     => isset( $attachment_meta['_ps_image_link'] ) && is_string( $attachment_meta['_ps_image_link'] ) ? sanitize_text_field( $attachment_meta['_ps_image_link'] ) : '',
			];
		}

		update_post_meta( $post_id, '_portfolio_slideshow', $to_save );
	}

	/**
	 * @since 1.9.9
	 *
	 * @return array
	 */
	public function add_attachment_id_to_media( $content ) {

		if ( ! is_array( $content ) || ! is_object( $post ) ) {
			return $content;
		}

		$attachment_id = isset( $post->ID ) ? $post->ID : get_the_ID();
		$content[]     = sprintf( esc_html__( 'Attachment ID: %s', 'portfolio-slideshow' ), absint( $attachment_id ) );

		return $content;
	}

	/**
	 * @since 1.9.9 
	 *
	 * @return array
	 */
	public function get_defaults() {

		return apply_filters( 'portfolio_slideshow_option_defaults', [
			'size'             => 400,
			'nowrap'           => false,
			'loop'             => false,
			'speed'            => 4000,
			'trans'            => 'fade',
			'timeout'          => 3000,
			'exclude_featured' => false,
			'autoplay'         => true,
			'pagerpos'         => 'bottom',
			'navpos'           => 'top',
			'showcaps'         => false,
			'showtitles'       => false,
			'showdesc'         => false,
			'click'            => 'advance',
			'fluid'            => '',// @TODO - REMOVE this option, responsive by default,
			'thumbs'           => '', // @TODO – REMOVE this option too, possiblt
			'slideheight'      => false,
			'id'               => '', // per-shortcode, no default
			'exclude'          => '', // per-shortcode, no default
			'include'          => '', // per-shortcode, no default
		] );
	}

	/**
	 * @since 1.9.9
	 *
	 * @return array
	 */
	public static function get_options() {
		$options = get_option( 'portfolio_slideshow_options' );
		return $options ? $options : [];
	}

	/**
	 * @todo Fill out original defaults!
	 *
	 * @since 1.9.9
	 *
	 * @param string $option Name of the option whose default value to retrieve. 
	 * @return array
	 */
	public static function get_option( $option ) {

		if ( isset( self::$options[ $option ] ) ) {
			return self::$options[ $option ];
		}

		if ( isset( self::$defaults[ $option ] ) ) {
			return self::$defaults[ $option ];
		}

		return false;
	}

	/**
	 * @since 1.9.9
	 *
	 * @return string
	 */
	public static function plugin_dir() {
		return self::$plugin_dir;
	}

	/**
	 * @since 1.9.9
	 *
	 * @return string
	 */
	public static function plugin_path() {
		return self::$plugin_path;
	}

	/**
	 * @since 1.9.9
	 *
	 * @return string
	 */
	public static function plugin_url() {
		return self::$plugin_url;
	}

	/**
	 * @since 1.9.9
	 *
	 * @param string $asset The file name of the un-minified asset to load.
	 * @param bool $has_min Whether or not this asset has a minified version.
	 * @return string
	 */
	public static function asset_url( $asset = '', $has_min = false ) {

		if ( '' == $asset ) {
			return self::$plugin_url . 'assets/';
		}

		if ( ! $has_min ) {
			return esc_url( self::$plugin_url . 'assets/' . $asset );
		}

		$file    = pathinfo( $asset );
		$sub_dir = '.' !== $file['dirname'] ? $file['dirname'] . '/' : '';

		return esc_url( self::$plugin_url . 'assets/' . $sub_dir . $file['filename'] . self::$dot_min .'.'. $file['extension'] );
	}

}

Plugin::instance();