<?php
/*
Plugin Name: Portfolio Slideshow
Plugin URI: http://madebyraygun.com/lab/portfolio-slideshow
Description: A shortcode that inserts a clean and simple jQuery + cycle powered slideshow of all image attachments on a post or page. Use shortcode [portfolio_slideshow] to activate.
Author: Dalton Rooney
Version: 1.1.8
Author URI: http://madebyraygun.com
*/


//Define static variables
define( "PORTFOLIO_SLIDESHOW_VERSION", "1.1.8" );
define( "PORT_SLDPLUGINPATH", "/" . plugin_basename( dirname(__FILE__) ) . "/" );
define( "PORT_SLDPLUGINFULLURL", WP_PLUGIN_URL . PORT_SLDPLUGINPATH );

//get ready for local
$currentLocale = get_locale();
if( $currentLocale ) {
	//load local
	load_plugin_textdomain( 'port_slide', PORT_SLDPLUGINFULLURL . 'lang', PORT_SLDPLUGINPATH . 'lang' );
}

// Get the admin page
if ( is_admin() ) { 
	require( 'portfolio-slideshow-admin.php' );
}	

// add our default options if they're not already there:



if ( get_option( 'portfolio_slideshow_version' )  < PORTFOLIO_SLIDESHOW_VERSION ) { // add and update our default options if version numbers don't match
    update_option( 'portfolio_slideshow_version', PORTFOLIO_SLIDESHOW_VERSION);
	add_option( "portfolio_slideshow_size", 'full' ); 
	add_option( "portfolio_slideshow_transition", 'fade' ); 
	add_option( "portfolio_slideshow_transition_speed", '400' ); 
	add_option( "portfolio_slideshow_show_support", 'false' ); 
	add_option( "portfolio_slideshow_show_titles", 'true' ); 
	add_option( "portfolio_slideshow_show_captions", 'true' ); 
	add_option( "portfolio_slideshow_show_descriptions", 'false' ); 
	add_option( "portfolio_slideshow_show_thumbs", 'false' );
	add_option( "portfolio_slideshow_show_thumbs_hp", 'false' );
	add_option( "portfolio_slideshow_nav_position", 'top' ); 
	add_option( "portfolio_slideshow_nowrap", '' );
	add_option( "portfolio_slideshow_showhash", '' ); 
	add_option( "portfolio_slideshow_timeout", '0' ); 
	add_option( "portfolio_slideshow_showloader", '' ); 
	add_option( "portfolio_slideshow_descriptionisURL", '' );
	add_option( "portfolio_slideshow_jquery_version", '1.4.4' );
} //end update

// now let's grab the options table data
$ps_version = get_option( 'portfolio_slideshow_version' ); 
$ps_size = get_option( 'portfolio_slideshow_size' ); 
$ps_trans = get_option( 'portfolio_slideshow_transition' ); 
$ps_speed = get_option( 'portfolio_slideshow_transition_speed' ); 
$ps_support = get_option( 'portfolio_slideshow_show_support' ); 
$ps_titles = get_option( 'portfolio_slideshow_show_titles' );
$ps_captions = get_option( 'portfolio_slideshow_show_captions' );
$ps_descriptions = get_option( 'portfolio_slideshow_show_descriptions' );
$ps_thumbs = get_option( 'portfolio_slideshow_show_thumbs' );
$ps_thumbs_hp = get_option( 'portfolio_slideshow_show_thumbs_hp' );
$ps_navpos = get_option( 'portfolio_slideshow_nav_position' );
$ps_nowrap = get_option( 'portfolio_slideshow_nowrap' );
$ps_timeout = get_option( 'portfolio_slideshow_timeout' );
$ps_showhash = get_option( 'portfolio_slideshow_showhash' );
$ps_version = get_option( 'portfolio_slideshow_version' );
$ps_showloader = get_option( 'portfolio_slideshow_showloader' );
$ps_descriptionisURL = get_option( 'portfolio_slideshow_descriptionisURL' );
$ps_jquery = get_option( 'portfolio_slideshow_jquery_version' );

//set up defaults if these fields are empty
if ( ! $ps_showloader ) { $ps_showloader = "false"; }
if ( ! $ps_descriptionisURL ) { $ps_descriptionisURL = "false"; }
if ( ! $ps_showhash ) {$ps_showhash = "false";}
if ( ! $ps_nowrap ) {$ps_nowrap = "0";}

// put the attachment ID on the media page
function add_post_id( $content ) { 
   $showlink = "Attachment ID:" . get_the_ID( $post->ID, true );
    $content[] = $showlink;
    return $content;}
add_filter ( 'media_row_actions', 'add_post_id' );

//action link http://www.wpmods.com/adding-plugin-action-links
function ps_action_links( $links, $file ) {
    static $this_plugin;
 
    if ( ! $this_plugin ) {
        $this_plugin = plugin_basename(__FILE__);
    }
 
    // check to make sure we are on the correct plugin
    if ( $file == $this_plugin ) {
        // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
        $settings_link = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/options-general.php?page=portfolio-slideshow">Settings</a>';
        // add the link to the list
        array_unshift($links, $settings_link);
    }
 
    return $links;
}

add_filter( 'plugin_action_links', 'ps_action_links', 10, 2 );


//Adds custom fields to attachment page. Via Frank Bültge, http://bueltge.de/ ref: http://wpengineer.com/2076/add-custom-field-attachment-in-wordpress/

if ( $ps_descriptionisURL == "true" ) {
	
	function ps_image_attachment_fields_to_edit( $form_fields, $post ) {  
		$form_fields["ps_image_link"] = array(  
			"label" => __( 'Slideshow image links to URL:', 'port_slide' ),
			"input" => "text",
			"value" => get_post_meta( $post->ID, "_ps_image_link", true )  
		);        
		
		return $form_fields;  
	}  
	
	function ps_image_attachment_fields_to_save( $post, $attachment ) {
		if( isset( $attachment['ps_image_link']) ){
			update_post_meta( $post['ID'], '_ps_image_link', $attachment['ps_image_link'] );
		}  
		return $post;  
	}  
	
	add_filter( "attachment_fields_to_edit", "ps_image_attachment_fields_to_edit", null, 2 );
	add_filter( "attachment_fields_to_save", "ps_image_attachment_fields_to_save", null, 2 );
}

// create the shortcode
add_shortcode( 'portfolio_slideshow', 'portfolio_shortcode' );

// define the shortcode function
function portfolio_shortcode($atts) {
	
	STATIC $i=0;
	
	//count the attachments
	
	global $ps_trans, $ps_speed, $ps_size, $ps_titles, $ps_captions, $ps_descriptions, $ps_thumbs, $ps_navpos, $ps_timeout, $ps_thumbs_hp, $ps_showhash, $ps_showloader, $ps_descriptionisURL, $ps_nowrap;
	
	extract( shortcode_atts( array(
		'size' => $ps_size,
		'nowrap' => $ps_nowrap,
		'speed' => $ps_speed,
		'trans' => $ps_trans,
		'timeout' => $ps_timeout,
		'thumbs' => $ps_thumbs,
		'nav' => $ps_navpos,
		'showcaps' => $ps_captions,
		'showtitles' => $ps_titles,
		'showdesc' => $ps_descriptions,
		'id' => '',
		'exclude' => '',
		'include' => ''
	), $atts ) );
	
	if ( ! $id ) { $id = get_the_ID(); }
	
	$attachments = get_children( array ( 'post_parent' => $id, 'post_type' => 'attachment', 'post_mime_type' => 'image' ) );
	$ps_count = count( $attachments );
		
	if( !is_feed() && $ps_showloader == "true" ) { //show the loader.gif if necessary
		$slideshow .= '<div class="slideshow-holder"></div>';
	}
	
	$jindex = $i;
	
	if ( !is_feed() ) { 
		$slideshow .= '<script type="text/javascript">/* <![CDATA[ */ psTimeout['.$jindex.']='.$timeout.';psTrans['.$jindex.']=\''.$trans.'\';psNoWrap['.$jindex.']='.$nowrap.';psSpeed['.$jindex.']='.$speed.';/* ]]> */</script>'; 
	} 
			
	$slideshow .= '<div id="slideshow-wrapper'.$i.'" class="slideshow-wrapper">
	';	//wrap the whole thing in a div for styling	
	
		// Navigation

		$ps_nav .= '<div class="slideshow-nav'.$i.' slideshow-nav">';
		if ( $timeout !=0 ) { //if autoplay is set
		$ps_nav .= '<a class="pause" href="javascript: void(0)">' . __( 'Pause', 'port_slide' ) . '</a><a class="play" style="display:none" href="javascript: void(0)">' . __( 'Play', 'port_slide' ) . '</a>';} // end autoplay

		$ps_nav .= '<a class="slideshow-prev" href="javascript: void(0)">' . __( 'Prev', 'port_slide' ) . '</a><span class="sep">|</span><a class="slideshow-next" href="javascript: void(0)">' . __( 'Next', 'port_slide' ) . '</a>';
		$ps_nav .= '<span class="slideshow-info'.$i.' slideshow-info"></span>';
		$ps_nav .= '</div>
		';	
	
	if ( !is_feed() && $nav == "top" && $ps_count > 1) { 
		$slideshow .= $ps_nav;
	}
		
	$slideshow .= '<div id="portfolio-slideshow'.$i.'" class="portfolio-slideshow">
	';

	$slideID = 1;
	
	if ( $include ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$attachments = get_posts( array( 'order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => $id,
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => $size,
		'include'		 => $include) );
	} elseif ( $exclude ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_posts( array( 'order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => $id,
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => $size,
		'exclude'		 => $exclude) );
	} else {
		$attachments = get_posts( array( 'order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => $id,
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => $size) );
	}

	if ( $attachments ) { //if attachments are found, run the slideshow
	
		//begin the slideshow loop
		foreach ( $attachments as $attachment ) {
			
			$slideshow .= '<div class="';
			if ( $slideID != "1" ) {
				$slideshow .= "not-first ";
			}
			$slideshow .= 'slideshow-next slideshow-content">
			';
			
			//this section sets up the external links if the option is selected
			
			if ( $ps_descriptionisURL=="true" ) {			
				$imagelink = get_post_meta( $attachment->ID, '_ps_image_link', true );
					if ( $imagelink ) { $slideshow .= '<a href="'.$imagelink.'" target="_blank">';
				}				
			} else { 
				$slideshow .= '<a href="javascript: void(0);" class="slideshow-next">';
			}
			
			//holy smokes, those are the images!
			$slideshow .= wp_get_attachment_image( $attachment->ID, $size, false, false );
			
			//don't forget to end the links if we've got them
			if ( $ps_descriptionisURL == "true" ) {			
				if ( $imagelink ) { 
					$slideshow .= "</a>";
				}				
			} else { 
				$slideshow .= "</a>";
			}				
			
			if ($nav == "middle" && $ps_count > 1) { 
				$slideshow .= $ps_nav;
			}

			//if titles option is selected
			if ( $showtitles == "true" ) {
				$title = $attachment->post_title;
				if ( $title ) { 
					$slideshow .= '<p class="slideshow-title">'.$title.'</p>'; 
				} 
			}
			
			//if captions option is selected
			if ( $showcaps == "true" ) {			
				$caption = $attachment->post_excerpt;
				if ( $caption ) { 
					$slideshow .= '<p class="slideshow-caption">'.$caption.'</p>'; 
				}
			}
			
			//if descriptions option is selected and we're not using the description field for external links
			if ( $showdesc=="true" ) {			
				$description = $attachment->post_content;
				if ( $description ) { 
					$slideshow .= '<p class="slideshow-description">'.$description.'</p>'; 
				}
			}
			
			$slideshow .= "</div>
			";
			
			$slideID++;
					
		}  // end slideshow loop
	} // end if ($attachments)

	$slideshow .= "</div><!--#portfolio-slideshow-->";
	
	//here come the thumbnails!
	if ( !is_feed() && is_singular() && $thumbs=="true" && $ps_count > 1 || !is_feed() && !is_singular() && $ps_thumbs_hp == "true" && $ps_count > 1 ) {
		$slideshow .= '<div class="slideshow-thumbs">
							<ul id="slides'.$i.'" class="slides">';
		
		if ( $include ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$attachments = get_posts( array( 'order'          => 'ASC',
			'orderby' 		 => 'menu_order ID',
			'post_type'      => 'attachment',
			'post_parent'    => $id,
			'post_mime_type' => 'image',
			'post_status'    => null,
			'numberposts'    => -1,
			'size'			 => 'thumbnail',
			'include'		 => $include) );
		} elseif ( $exclude ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_posts( array( 'order'          => 'ASC',
			'orderby' 		 => 'menu_order ID',
			'post_type'      => 'attachment',
			'post_parent'    => $id,
			'post_mime_type' => 'image',
			'post_status'    => null,
			'numberposts'    => -1,
			'size'			 => 'thumbnail',
			'exclude'		 => $exclude) );
		} else {
			$attachments = get_posts( array( 'order'          => 'ASC',
			'orderby' 		 => 'menu_order ID',
			'post_type'      => 'attachment',
			'post_parent'    => $id,
			'post_mime_type' => 'image',
			'post_status'    => null,
			'numberposts'    => -1,
			'size'			 => 'thumbnail' ) );
		}
	
		if ( empty( $attachments ) )
			return '';
		
		if ( $attachments ) {
			foreach ( $attachments as $attachment ) {
			$slideshow .="<li><a href=\"javascript: void(0)\">";
			$slideshow .= wp_get_attachment_image($attachment->ID, 'thumbnail', false, false);
			$slideshow .= "</a></li>";		
			}
		}
		
		$slideshow .= "</ul></div><!-- end thumbs-->";
	
	}  //end thumbs

	if ( !is_feed() && $nav == "bottom" && $ps_count > 1 ) { 
		$slideshow .= $ps_nav;
	}

	$slideshow .='</div><!--#slideshow-wrapper-->';
	$i++;
	
	return $slideshow;	

} //ends the portfolio_shortcode function

// Output the javascript & css here

if ( !is_admin() ) {
   
	switch ($ps_jquery) {
	
	case "1.4.2" :	
		wp_deregister_script( 'jquery' ); 
		wp_register_script( 'jquery', ( "http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ), false, '1.4.2', false); 
		wp_enqueue_script( 'jquery' );
		break;
	
	case "disabled" :
		// do nothing
		break;
		
	default :
		wp_deregister_script( 'jquery' ); 
		wp_register_script( 'jquery', ( "http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js" ), false, '1.4.4', false); 
		wp_enqueue_script( 'jquery' );
		break;
	}

	//malsup cycle script
	 wp_register_script( 'cycle', plugins_url( 'lib/jquery.cycle.all.min.js', __FILE__ ), false, '2.7.3', true);
	 wp_enqueue_script( 'cycle' );

	 //our script
	 wp_register_script( 'portfolio-slideshow', plugins_url( 'lib/portfolio-slideshow.js', __FILE__ ), false, $ps_version, true); 
	 wp_enqueue_script( 'portfolio-slideshow' );
}
 
function portfolio_head() {
	global $ps_version;
	echo '
	<!-- Portfolio Slideshow-->
	<link rel="stylesheet" type="text/css" href="' .  plugins_url( "portfolio-slideshow.css?ver=", __FILE__ ) . $ps_version . '" />
	<noscript><link rel="stylesheet" type="text/css" href="' .  plugins_url( "portfolio-slideshow-noscript.css?ver=". $ps_version, __FILE__ ) . '" /></noscript>
	<script type="text/javascript">/* <![CDATA[ */var psTimeout = new Array(); var psTrans =  new Array(); var psSpeed =  new Array(); var psNoWrap =  new Array();/* ]]> */</script>
	<!--//Portfolio Slideshow-->
	';
} // end portfolio_head 

add_action( 'wp_head', 'portfolio_head' );

function portfolio_foot() {
	// Set up js variables
	global $ps_trans, $ps_speed, $ps_timeout, $ps_showhash, $ps_showloader, $ps_nowrap;
	//$ps_showhash should always be false on any non-singular page
	if (!is_singular()) {$ps_showhash = "false";}
echo '<script type="text/javascript">/* <![CDATA[ */var portfolioSlideshowOptions = {psHash: \''.$ps_showhash.'\',psLoader: \''.$ps_showloader.'\'};/* ]]> */</script>'; }    

add_action( 'wp_footer', 'portfolio_foot' );