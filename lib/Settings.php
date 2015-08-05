<?php

namespace Portfolio_Slideshow;

defined( 'WPINC' ) or die;

class Settings {

	protected static $settings = [];


	/**
	 * Gets the regsitered settings with filters applied.
	 *
	 * @since 1.9.9
	 * @return array
	 */
	public static function get_registered_settings() {

		self::$settings['display'] = [

			'size' => [ // select - default medium
				'id'      => 'size',
				'name'    => __( 'Slideshow size', 'portfolio-slideshow' ),
				'desc'    => esc_attr__( 'You can change the default image sizes in the Media Settings control panel, or add a new custom image size of your own. If you change the settings for an existing (WordPress built-in) image size, you will need to regenerate your thumbnails to see the changes reflected in existing images. Search the WordPress plugin repository for the Regenerate Thumbnails plugin for information on how to do this.', 'portfolio-slideshow' ),
				'type'    => 'select',
				'options' => self::get_image_sizes(),
				'std'     => 'medium'
			],

			'customsize' => [ // text - one for width, one for height - default 400 x 400
				'id'   => 'customsize',
				'name' => __( 'Custom size', 'portfolio-slideshow' ),
				'desc' => esc_attr__( 'The size in pixels of your new default image size. This can be overridden in the shortcode with the height and width attributes, e.g.: [portfolio_slideshow width=500].', 'portfolio-slideshow' ),
				'type' => 'text', /** @TODO – MAKE CUSTOM FIELD TYPE FOR DOUBLE TEXT INPUT HERE https://cloudup.com/c1w2vysN7Ti */
				'size' => 'medium',
				'std'  => '400x400'
			],

			'crop' => [ // "Crop images?" checkbox - default false
				'id'   => 'crop',
				'name' => __( 'Crop images', 'portfolio-slideshow' ),
				'desc' => esc_attr__( 'Force the images to crop to the exact dimensions specified. This setting only affects custom image sizes and sizes set directly in the shortcode.', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			],

			'trans' => [ // "transition FX" select - default fade
				'id'      => 'trans',
				'name'    => __( 'Transition effects', 'portfolio-slideshow' ),
				'desc'    => esc_attr__( 'You can override these in the shortcode with any option that the jQuery Cycle plugin supports.', 'portfolio-slideshow' ),
				'type'    => 'select',
				'std'     => 'fade',
				'options' => [
					'fade'       => __( 'Fade', 'portfolio-slideshow' ),
					'scrollHorz' => __( 'ScrollHorz', 'portfolio-slideshow' )
				]
			],

			'speed' => [// "Transition Speed" text input - default 400
				'id'   => 'speed',
				'name' => __( 'Transition speed', 'portfolio-slideshow' ),
				'desc' => esc_attr__( 'How long should the transition last when the slideshow is advanced? Enter in milliseconds, e.g. 400 = 0.4 seconds per transition.', 'portfolio-slideshow' ),
				'type' => 'text',
				'size' => 'small',
				'std'  => '400'
			],

			'showtitles' => [// todo– try to make it like original cloudup.com/cE_pPkw2j91
				'id'   => 'showtitles',
				'name' => __( 'Show titles', 'portfolio-slideshow' ),
				'desc' => esc_attr__( '', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			],

			'showcaps' => [
				'id'   => 'showcaps',
				'name' => __( 'Show captions', 'portfolio-slideshow' ),
				'desc' => esc_attr__( '', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			],

			'showdesc' => [
				'id'   => 'showdesc',
				'name' => __( 'Show descriptions', 'portfolio-slideshow' ),
				'desc' => esc_attr__( '', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			],

			'metapos'=> [
				'id'      => 'metapos',
				'name'    => __( 'Meta content position', 'portfolio-slideshow' ),
				'desc'    => esc_attr__( '', 'portfolio-slideshow' ),
				'type'    => 'select',
				'std'     => 'bottom',
				'options' => [
					'top'    => __( 'Top', 'portfolio-slideshow' ),
					'middle' => __( 'Middle', 'portfolio-slideshow' ),
					'bottom' => __( 'Bottom', 'portfolio-slideshow' )
				]
			],

			'centered' => [
				'id'   => 'centered',
				'name' => __( 'Center slideshows', 'portfolio-slideshow' ),
				'desc' => esc_attr__( 'Centers slideshow, nav, & pager within the parent content area.', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			]

		];
		
		self::$settings['behavior'] = [

			'timeout' => [ // text - default 3000
				'id'   => 'timeout',
				'name' => __( 'Slideshow timing', 'portfolio-slideshow' ),
				'desc' => esc_attr__( 'How long should each slide be displayed during an automatic slideshow? Enter in milliseconds, e.g. 3000 = 3 seconds per slide.', 'portfolio-slideshow' ),
				'type' => 'text',
				'size' => 'small',
				'std'  => '3000'
			],

			'autoplay' => [ // checkbox - default true
				'id'   => 'autoplay',
				'name' => __( 'Enable autoplay', 'portfolio-slideshow' ),
				'desc' => esc_attr__( 'Starts slideshows automatically when the page is loaded.', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			],

			'random' => [ // checkbox - default false
				'id'   => 'random',
				'name' => __( 'Randomize slideshow', 'portfolio-slideshow' ),
				'desc' => esc_attr__( 'Play the slideshow back in a random order.', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			],

			'exclude_featured' => [ // checkbox, default true, but do we even need this now?
				'id'   => 'exclude_featured',
				'name' => __( 'Exclude featured images from slideshows', 'portfolio-slideshow' ),
				'desc' => esc_attr__( 'If you use the featured image function to create gallery thumbnails but don\'t want those images to appear in the slideshow, use this option.', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => true
			],

			'showloader' => [
				'id'   => 'showloader',
				'name' => __( 'Show loading animation', 'portfolio-slideshow' ),
				'desc' => esc_attr__( 'If you\'ve got a slow connection or lots of images, sometimes the slideshow can take a little while to load. Selecting this option will include a loading gif to show that something is happening. You can customize the placement of the loading gif with CSS.', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => true
			],

			'background_images' => [
				'id'   => 'background_images',
				'name' => __( 'Load images in background', 'portfolio-slideshow' ),
				'desc' => __( 'Slideshows will load images in the background for better performance when this option is enabled. Disable this if you have a lot of slideshows on a single page or are concerned about bandwidth, and images will only be loaded when the slideshow is advanced.', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => true
			],

			'loop' => [ // checkbox, default - true
				'id'   => 'loop',
				'name' => __( 'Loop the slideshow', 'portfolio-slideshow' ),
				'desc' => __( 'Should the slideshow cycle back to the beginning after it gets to the end, or simply stop?', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => true
			],

			'click' => [ // select - default, advance
				'id'      => 'click',
				'name'    => __( 'Clicking on a slideshow image', 'portfolio-slideshow' ),
				'desc'    => __( 'URLs for the "Opens a custom URL" option are set in the image uploader. The lighbox option links to the full-size version of the image, so make sure your full-size images aren\'t too big.', 'portfolio-slideshow' ),
				'type'    => 'select',
				'std'     => 'advance',
				'options' => [
					'advance'    => __( 'Advance', 'portfolio-slideshow' ),
					'openurl'    => __( 'Opens a custom URL', 'portfolio-slideshow' ),
					'lightbox'   => __( 'Opens the image in a lightbox', 'portfolio-slideshow' ),
					'fullscreen' => __( 'Opens the image in a fullscreen slideshow', 'portfolio-slideshow' ),
					'none'       => __( 'Does nothing', 'portfolio-slideshow' )
				]
			],

			'target' => [ // select - default New Window
				'id'      => 'target',
				'name'    => __( 'New URL opens in', 'portfolio-slideshow' ),
				'desc'    => __( '', 'portfolio-slideshow' ),
				'type'    => 'select',
				'std'     => '_self',
				'options' => [
					'_self'   => __( 'Same window', 'portfolio-slideshow' ),
					'_blank'  => __( 'New window', 'portfolio-slideshow' ),
					'_parent' => __( 'Parent window', 'portfolio-slideshow' ),
				]
			],

			'showhash' => [ // checkbox - default False
				'id'   => 'showhash',
				'name' => __( 'Update URL with slide IDs', 'portfolio-slideshow' ),
				'desc' => __( 'You can enable this feature to udpate the URL of the page with the slide ID number, e.g: http://example.com/slideshow/#3 will link directly to the third slide in the slideshow.', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			]

		];

		self::$settings['navigation'] = [

			'navstyle' => [ // select, default text,
				'id'      => 'navstyle',
				'name'    => __( 'Navigation style', 'portfolio-slideshow' ),
				'desc'    => __( 'What kind of navigation would you like to use?', 'portfolio-slideshow' ),
				'type'    => 'select',
				'std'     => 'text',
				'options' => [
					'text'       => __( 'Text', 'portfolio-slideshow' ),
					'graphical'  => __( 'Graphical', 'portfolio-slideshow' ),
				]
			],

			/** make showplay look like original: https://cloudup.com/cogYx5kdbDM */
			'showplay' => [ // check, default true
				'id'   => 'showplay',
				'name' => __( 'Show play button', 'portfolio-slideshow' ),
				'desc' => __( '', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => true
			],

			'showinfo' => [ // check, default true
				'id'   => 'showinfo',
				'name' => __( 'Show slide numbers', 'portfolio-slideshow' ),
				'desc' => __( '', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => true
			],

			/** @todo – make these look like original https://cloudup.com/cNK6orsODSp */
			'infotext' => [ // text input, default " / " (no quotes tho)
				'id'   => 'infotext',
				'name' => __( '', 'portfolio-slideshow' ),
				'desc' => __( '', 'portfolio-slideshow' ),
				'type' => 'text',
				'size' => 'small',
				'std'  => ' / '
			],

			'touchswipe' => [ // checkbox, default true
				'id'   => 'touchswipe',
				'name' => __( 'Enable touch/swipe controls', 'portfolio-slideshow' ),
				'desc' => __( '', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => true
			],

			'keyboardnav' => [ // checkbox, default false
				'id'   => 'keyboardnav',
				'name' => __( 'Enable keyboard navigation', 'portfolio-slideshow' ),
				'desc' => __( '', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			],

			'navpos' => [ // select, default Top
				'id'      => 'navpos',
				'name'    => __( 'Navigation position', 'portfolio-slideshow' ),
				'desc'    => __( 'Where should the navigation controls appear?', 'portfolio-slideshow' ),
				'type'    => 'select',
				'std'     => 'top',
				'options' => [
					'top'     => __( 'Top', 'portfolio-slideshow' ),
					'middle'  => __( 'Middle', 'portfolio-slideshow' ),
					'bottom'  => __( 'Bottom', 'portfolio-slideshow' )
				]
			],

			'pagerstyle' => [ // select, default Thumbs
				'id'      => 'pagerstyle',
				'name'    => __( 'Pager style', 'portfolio-slideshow' ),
				'desc'    => __( 'Which type of slideshow pager would you like to use?', 'portfolio-slideshow' ),
				'type'    => 'select',
				'std'     => 'thumbs',
				'options' => [
					'thumbs'   => __( 'Thumbs', 'portfolio-slideshow' ),
					'carousel' => __( 'Carousel', 'portfolio-slideshow' ),
					'numbers'  => __( 'Numbers', 'portfolio-slideshow' ),
					'bullets'  => __( 'Bullets', 'portfolio-slideshow' ),
					'titles'   => __( 'Titles', 'portfolio-slideshow' )
				]
			],

			'thumbsize' => [ // text input, default 75
				'id'   => 'thumbsize',
				'name' => __( 'Thumbnail size', 'portfolio-slideshow' ),
				'desc' => __( 'You can specify the size of the thumbnails in the pager. (px)', 'portfolio-slideshow' ),
				'type' => 'text',
				'size' => 'small',
				'std'  => '75'
			],

			'thumbnailmargin' => [ // text, default 8
				'id'   => 'thumbnailmargin',
				'name' => __( 'Thumbnail margin', 'portfolio-slideshow' ),
				'desc' => __( 'How much space between each thumbnail? (px)', 'portfolio-slideshow' ),
				'type' => 'text',
				'size' => 'small',
				'std'  => '8'
			],

			'proportionalthumbs' => [ // checkbox, default FALSE
				'id'   => 'proportionalthumbs',
				'name' => __( 'Proportional thumbnails', 'portfolio-slideshow' ),
				'desc' => __( 'Preserve thumbnail aspect ratio?', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			],

			'carouselsize' => [ // text, default 7
				'id'   => 'carouselsize',
				'name' => __( 'Carousel size', 'portfolio-slideshow' ),
				'desc' => __( 'Number of thumbnails to display per carousel row.', 'portfolio-slideshow' ),
				'type' => 'text',
				'size' => 'small',
				'std'  => '7'
			],

			'carousel_thumbsize' => [ // text, default 75
				'id'   => 'carousel_thumbsize',
				'name' => __( 'Carousel thumbnail size', 'portfolio-slideshow' ),
				'desc' => __( 'What size should we display the image thumbnails in the carousel?', 'portfolio-slideshow' ),
				'type' => 'text',
				'size' => 'small',
				'std'  => '75'
			],

			'carousel_thumbnailmargin' => [ // text, default 8,
				'id'   => 'thumbsize',
				'name' => __( 'Carousel thumbnail margin', 'portfolio-slideshow' ),
				'desc' => __( 'How much space between each carousel thumbnail? (px)', 'portfolio-slideshow' ),
				'type' => 'text',
				'size' => 'small',
				'std'  => '8'
			],

			'togglethumbs' => [ // checkbox, default FALSE
				'id'   => 'togglethumbs',
				'name' => __( 'Thumbnail/carousel toggle?', 'portfolio-slideshow' ),
				'desc' => __( 'Hides thumbnails and carousel by default with an option to show them.', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			],

			'toggle_state' => [ // select, default Closed
				'id'      => 'toggle_state',
				'name'    => __( 'Initial toggle state', 'portfolio-slideshow' ),
				'desc'    => __( 'Should the thumbs/carousel be visible by default?', 'portfolio-slideshow' ),
				'type'    => 'select',
				'std'     => 'bottom',
				'options' => [
					'closed' => __( 'Closed', 'portfolio-slideshow' ),
					'open'   => __( 'Open', 'portfolio-slideshow' ),
				]
			],

			'pagerpos' => [ // select, default bottom
				'id'      => 'pagerpos',
				'name'    => __( 'Pager position', 'portfolio-slideshow' ),
				'desc'    => __( 'Where should the slideshow pager appear?', 'portfolio-slideshow' ),
				'type'    => 'select',
				'std'     => 'bottom',
				'options' => [
					'top'     => __( 'Top', 'portfolio-slideshow' ),
					'middle'  => __( 'Middle', 'portfolio-slideshow' ),
					'bottom'  => __( 'Bottom', 'portfolio-slideshow' )
				]
			],

			'fancygrid' => [ // checkbox, default False
				'id'   => 'fancygrid',
				'name' => __( 'Enable Fancy Grid', 'portfolio-slideshow' ),
				'desc' => __( 'The fancy grid toggles between a thumbnail view and a slideshow view.', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			],

			'fullscreen' => [ // checkbox, default False
				'id'   => 'fullscreen',
				'name' => __( 'Enable fullscreen slideshows', 'portfolio-slideshow' ),
				'desc' => __( 'Adds an icon to launch a full-screen, mobile friendly slideshow, even if the "click" option for slides is set to something other than the "Open in fullscreen".', 'portfolio-slideshow' ),
				'type' => 'checkbox',
				'std'  => false
			]

		];

		return apply_filters( 'portfolio_slideshow_get_registered_settings', self::$settings );
	}

	/**
	 * Register the plugin settings.
	 *
	 * @since 1.9.9
	 * @return array
	 */
	public static function get_tabs() {
		return apply_filters( 'portfolio_slideshow_get_settings_tabs', array(
			'display_and_behavior' => __( 'Display & Behavior', 'portfolio-slideshow' ),
			'pager_and_navigation' => __( 'Pager & Navigation', 'portfolio-slideshow' ),
			'documentation'        => __( 'Documentation', 'portfolio-slideshow' )
		) );
	}

	/**
	 * Register the plugin settings.
	 *
	 * @since 1.9.9
	 * @return array
	 */
	public static function register_settings() {
		
		if ( false == get_option( 'portfolio_slideshow_options' ) ) {
			add_option( 'portfolio_slideshow_options' );
		}

		$registered_settings = self::get_registered_settings();

		foreach ( $registered_settings as $tab => $settings ) :

			add_settings_section(
				'portfolio_slideshow_' . $tab,
				__return_null(),
				'__return_false',
				'portfolio_slideshow'
			);

			foreach ( $settings as $option ) {

				$name = isset( $option['name'] ) ? $option['name'] : '';

				add_settings_field(
					'portfolio_slideshow_' . $option['id'],
					$name,
					array( __CLASS__, $option['type'] . '_callback' ),
					'portfolio_slideshow',
					'portfolio_slideshow_' . $tab,
					array(
						'section'     => $tab,
						'id'          => isset( $option['id'] )     ? $option['id']   : null,
						'desc'        => ! empty( $option['desc'] ) ? $option['desc'] : '',
						'name'        => isset( $option['name'] )   ? $option['name']    : null,
						'size'        => isset( $option['size'] )        ? $option['size']    : null,
						'options'     => isset( $option['options'] )     ? $option['options'] : '',
						'std'         => isset( $option['std'] )         ? $option['std']     : '',
						'min'         => isset( $option['min'] )         ? $option['min']     : null,
						'max'         => isset( $option['max'] )         ? $option['max']     : null,
						'step'        => isset( $option['step'] )        ? $option['step']    : null,
						'placeholder' => isset( $option['placeholder'] ) ? $option['placeholder'] : null
					)
				);
			}

		endforeach;

		register_setting(
			'portfolio_slideshow_options',
			'portfolio_slideshow_options',
			array( __CLASS__, 'sanitize_options' )
		);

		// register_setting(
		// 	'portfolio_slideshow_pro_license',
		// 	'portfolio_slideshow_pro_license_key',
		// 	'portfolio_slideshow_pro_sanitize_license'
		// );

		// register_setting(
		// 	'portfolio_slideshow_pro_version'
		// 	//'portfolio_slideshow_pro_version',
		// 	// 'sanitize_version'
		// );

	}

	/**
	 * Get registered thumbnail sizes. Taken from Tim Kinnane's script at http://git.io/N1Wi.
	 *
	 * @since 1.9.9
	 * @return array
	 */
	public static function get_image_sizes() {
		
		global $_wp_additional_image_sizes;

		$sizes = array();
		$intermediate_sizes = get_intermediate_image_sizes();

		// Create the full array with sizes and crop info
		foreach ( $intermediate_sizes as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
				$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
				$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop']
				);
			}
		}

		return $sizes; 
	}

	/**
	 * Basic settings sanitization.
	 *
	 * @param $input
	 *
	 * @since 1.9.9
	 * @return string
	 */
	public static function sanitize_options( $input = [] ) {

		if ( empty( $_POST['_wp_http_referer'] ) ) {
			return $input;
		}

		parse_str( $_POST['_wp_http_referer'], $referrer );

		$tab = isset( $referrer['tab'] ) ? $referrer['tab'] : 'display_and_behavior';

		$options = Plugin::$options;
		
		$raw_settings = self::get_registered_settings();

		// Merges the settings into a single "flat" array for simpler handling.
		$settings = array_merge( $raw_settings['display'], $raw_settings['behavior'], $raw_settings['navigation'] );	

		foreach ( $input as $key => $value ) {

			$type = isset( $settings[ $key ]['type'] ) ? $settings[ $key ]['type'] : false;

			if ( 'checkbox' == $type ) {
				$input[ $key ] = ( isset( $input[ $key ] ) && true == $input[ $key ] ? 1 : 0 );
			}
		}

		// This prevents options from another tab from being overwritten.
		// if ( 'display_and_behavior' == $tab || 'pager_and_navigation' == $tab ) {
		// 	foreach ( $raw_settings[ $tab ] as $key => $value ) {
		// 		if ( empty( $input[ $key ] ) ) {
		// 			unset( $options[ $key ] );
		// 		}
		// 	}
		// }

		add_settings_error( 'portfolio-slideshow-notices', '', __( 'Settings updated.', 'portfolio-slideshow' ), 'updated' );
		
		return array_merge( $options, $input );
	}

	/**
	 * Sanitize text fields.
	 *
	 * @param $input
	 *
	 * @since 1.9.9
	 * @return string
	 */
	public static function sanitize_text_field( $input ) {
		return trim( $input );
	}

	/**
	 * Callback for rendering settings section headers.
	 *
	 * @param $args
	 *
	 * @since 1.9.9
	 * @return void
	 */
	public static function header_callback( $args ) {}

	/**
	 * Callback for rendering checkbox fields.
	 *
	 * @param $args
	 *
	 * @since 1.9.9
	 * @return void
	 */
	public static function checkbox_callback( $args ) {
		$options =  Plugin::$options;

		$checked = checked( 1, isset( $options[ $args[ 'id' ] ] ) ? $options[ $args[ 'id' ] ] : 0, false );
		
		$html = self::get_tooltip_label( $args );

		$html .= '<input type="checkbox" id="portfolio_slideshow_options[' . $args['id'] . ']" name="portfolio_slideshow_options[' . $args['id'] . ']" value="1" ' . $checked . '>';

		echo $html;
	}

	/**
	 * Callback for rendering text fields.
	 *
	 * @param $args
	 *
	 * @since 1.9.9
	 * @return void
	 */
	public static function text_callback( $args ) {
		$options =  Plugin::$options;

		if ( isset( $options[ $args['id'] ] ) )
			$value = $options[ $args['id'] ];
		else
			$value = isset( $args['std'] ) ? $args['std'] : '';

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';

		$html = self::get_tooltip_label( $args );

		$html .= '<input type="text" class="' . $size . '-text" id="portfolio_slideshow_options[' . $args['id'] . ']" name="portfolio_slideshow_options[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';

		echo $html;
	}

	/**
	 * Callback for rendering select fields.
	 *
	 * @param $args
	 *
	 * @since 1.9.9
	 * @return void
	 */
	public static function select_callback( $args ) {
		$options = Plugin::$options;

		if ( isset( $options[ $args['id'] ] ) )
			$value = $options[ $args['id'] ];
		else
			$value = isset( $args['std'] ) ? $args['std'] : '';

		if ( isset( $args['placeholder'] ) )
			$placeholder = $args['placeholder'];
		else
			$placeholder = '';

		$html = self::get_tooltip_label( $args );

		$html .= '<select id="portfolio_slideshow_options[' . $args['id'] . ']" name="portfolio_slideshow_options[' . $args['id'] . ']" data-placeholder="' . esc_attr( $placeholder ) . '" />';

		foreach ( $args['options'] as $option => $name ) :
			$selected = selected( $option, $value, false );

			if ( 'size' == $args['id'] ) {
				$name = sprintf( __( 'Width: %s, Height: %s', 'portfolio-slideshow' ), $name['width'], $name['height'] );
			}

			$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
		endforeach;

		$html .= '</select>';

		echo $html;
	}

	/**
	 * Callback for rendering show_meta_components checkbox fields.
	 *
	 * @param $args
	 *
	 * @since 1.9.9
	 * @return void
	 */
	public static function show_meta_components_callback( $args ) {
		/**
		 * @todo Build single-row of the three "Show titles", caps, desc checkboxes like in original plugin.
		 * @todo Build other callbacks for other custom field callbacks.
		 */
	}

	/**
	 * Genreates the markup for an option's <label> tag.
	 *
	 * @since 1.9.9
	 * @return string
	 */	
	public static function get_tooltip_label( $args ) {
		$image = '<img width="18" class="portfolio-slideshow-tooltip" title="' . esc_attr( $args['desc'] ) . '" src="' . Plugin::plugin_url() . '/assets/images/q.png' .'" alt="?">';
		return ! empty( $args['desc'] ) ? sprintf( '<label for="portfolio_slideshow_options[' . $args['id'] . ']">%s</label>', $image ) : '';
	}

}