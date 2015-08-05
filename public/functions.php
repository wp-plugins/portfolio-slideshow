<?php

defined( 'WPINC' ) or die;

/**
 * @since 2.0.0
 *
 * @param string $plugin_var Filename (without extenstion) of the pugin to look for.
 * @return array
 */
function portfolio_slideshow_is_plugin_active( $plugin_var ) {
	$plugin_file = sanitize_file_name( sprintf( '%s/%s.php', $plugin_var ) );
	
	if ( ! is_string( $plugin_file ) ) {
		return false;
	}
	
	return in_array( [ $plugin_file ], apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}

/**
 * @since 2.0.0
 *
 * @param string $plugin_var Filename (without extenstion) of the pugin to look for.
 * @return array
 */
function portfolio_slideshow_get_image_sizes() {
	global $ps_options;

	// Get the intermediate image sizes, add full & custom sizes size to the array.
	$sizes   = get_intermediate_image_sizes();
	$sizes[] = 'full';

	// Loop through each of the image sizes.
	foreach ( $sizes as $size ) {
		printf( '<option value="%s" %s>%s</option>', esc_attr( $size ), ( $ps_options['size'] == $size ? 'selected="selected"' : '' ), esc_html( $size ) );
	}
}


/**
 * Navigates through an array or object applying WordPress' own sanitize_text_field() function.
 *
 * @since 2.0.0
 *
 * @param array|object $value The value to be sanitized.
 * @return array|object Sanitized  value.
 */
function portfolio_slideshow_sanitize_text_field_deep( $value ) {

	if ( is_array( $value ) ) {
		$value = array_map( 'portfolio_slideshow_sanitize_text_field_deep', $value );
	}

	if ( is_object( $value ) ) {
		$vars = get_object_vars( $value );
		foreach ( $vars as $key => $data ) {
			$value->{ $key } = portfolio_slideshow_sanitize_text_field_deep( $data );
		}
	}

	if ( is_string( $value ) ) {
		$value = sanitize_text_field( $value );
	}

	return $value;
}


// function ps_action_links( $links, $file ) {
// 	 	static $this_plugin;

// 	    if ( !$this_plugin ) {
// 	        $this_plugin = PORTFOLIO_SLIDESHOW_LOCATION;
// 	    }

// 	    // check to make sure we are on the correct plugin
// 	    if ( $file == $this_plugin ) {
// 	        // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
// 	        $settings_link = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page=portfolio_slideshow">Settings</a>';
// 	        // add the link to the list
// 	        array_unshift( $links, $settings_link );
// 	    }
// 	    return $links;
// 	}
// 	add_filter( 'plugin_action_links', 'ps_action_links', 10, 2 );


/*

//Adds custom fields to attachment page http://wpengineer.com/2076/add-custom-field-attachment-in-wordpress/
function ps_image_attachment_fields_to_edit( $form_fields, $post) {  
	$form_fields['ps_image_link'] = [
		"label" => __( "<span style='color:#c43; padding:0'>Portfolio Slideshow<br />Slide link URL</span>" ),  
		"input" => "text",
		"value" => get_post_meta( $post->ID, "_ps_image_link", true )  
	];        
	return $form_fields;  
}  
add_filter( "attachment_fields_to_edit", "ps_image_attachment_fields_to_edit", null, 2 ); 
function ps_image_attachment_fields_to_save( $post, $attachment) {    
	if( isset( $attachment['ps_image_link'] ) ){  
		update_post_meta( $post['ID'], '_ps_image_link', $attachment['ps_image_link'] );  
	}  
	return $post;  
}  
add_filter( "attachment_fields_to_save", "ps_image_attachment_fields_to_save", null, 2 );}	

*/