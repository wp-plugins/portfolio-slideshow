<?php
/*
Plugin Name: Portfolio Slideshow
Plugin URI: http://daltonrooney.com/portfolio
Description: A shortcode that inserts a clean and simple jQuery + cycle powered slideshow of all image attachments on a post or page. Use shortcode [portfolio_slideshow] to activate.
Author: Dalton Rooney
Version: 0.2
Author URI: http://daltonrooney.com
*/ 

//wondertwin powers activate
add_shortcode('portfolio_slideshow', 'portfolio_shortcode');

//what's your function?
function portfolio_shortcode($attr) {

	echo '<div class="slideshow-nav"><a class="slideshow-prev" href="#">Prev</a>|<a class="slideshow-next" href="#">Next</a><span id="slideshow-info"></span> 
	</div> 
	
	<div class="portfolio-slideshow">';
					
	$args = array(
		'order'          => 'ASC',
		'orderby' 		 => 'menu_order',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => 'large' //Change this to specify a different image size. The options are thumbnail, medium, large, or full.
	);
	
	$attachments = get_posts($args);
	if ($attachments) {
		foreach ($attachments as $attachment) {
		echo "<div class='slideshow-next'>";
			echo wp_get_attachment_image($attachment->ID, $args['size'], false, false);
		$caption = $attachment->post_excerpt;
		if (isset($caption)) { echo '<p class="slideshow-caption">'.$caption.'</p>'; }
		echo "</div>";
		}
	}
	
	echo "</div><!-- ends the portfolio-slideshow div-->";

} //ends the portfolio_shortcode function


// Output the javascript & css for the header here

function portfolio_head() {
	
	wp_enqueue_script('jquery');
	echo '
<!-- loaded by Portfolio Slideshow Plugin-->

	';
	echo '<script src="';
	echo plugins_url( 'portfolio_slideshow/jquery.cycle.min.js' );
	echo '" type="text/javascript" language="javascript"></script>';
	echo '
	<script type="text/javascript"> 
	jQuery(document).ready(function($) {
		
		$(\'.portfolio-slideshow\').cycle({
			fx: \'fade\', 
			speed: \'fast\', 
			timeout: 0, 
			next: \'.slideshow-next\', 
			prev: \'.slideshow-prev\', 
			after: onAfter
		});
	
		function onAfter(curr,next,opts) {
			var caption = (opts.currSlide + 1) + \' of \' + opts.slideCount;
			$(\'#slideshow-info\').html(caption);
		}
	});
	</script> 

	<style>
		.slideshow-nav {padding:0 0 6px 0}
		.slideshow-nav a {text-decoration:underline; color: #444444;}
		.slideshow-nav a.slideshow-prev {margin: 0 15px 0 0;}
		.slideshow-nav a.slideshow-next {margin: 0 25px 0 15px;}
		.slideshow {margin: 0 0 10px 0;}
	</style>

<!-- end Portfolio Slideshow Plugin -->
';

} // ends the javascript & css for head

add_action('wp_head', 'portfolio_head');
?>