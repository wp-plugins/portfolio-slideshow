<?php
/*
Plugin Name: Portfolio Slideshow
Plugin URI: http://daltonrooney.com/portfolio
Description: A shortcode that inserts a clean and simple jQuery + cycle powered slideshow of all image attachments on a post or page. Use shortcode [portfolio_slideshow] to activate.
Author: Dalton Rooney
Version: 0.3
Author URI: http://daltonrooney.com
*/ 

//wondertwin powers activate
add_shortcode('portfolio_slideshow', 'portfolio_shortcode');

//what's your function?
function portfolio_shortcode($attr) {

	echo '<div class="slideshow-nav"><a class="slideshow-prev" href="#">Prev</a>|<a class="slideshow-next" href="#">Next</a><span id="slideshow-info"></span> 
	</div> 
	
	<div class="portfolio-slideshow">';
	
	$portfolio_slideshow_size = get_option('portfolio_slideshow_size'); //loads the size option from the options table, if it's set
	
	if (!empty($portfolio_slideshow_size) ) { // if we've set the size
	$args =  array(
		'order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => $portfolio_slideshow_size
	) ;	
	
	} else {	// else use the default 'large'
	$args = array(			
		'order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => 'large'
	); }
	
	$attachments = get_posts($args);
	if ($attachments) {
		foreach ($attachments as $attachment) {
			echo "<div class='slideshow-next'>";
			
			echo wp_get_attachment_image($attachment->ID, $args['size'], false, false);
			
			$title = $attachment->post_title;
			if (isset($title)) { 
				echo '<p class="slideshow-title">'.$title.'</p>'; 
			}	
			
			$caption = $attachment->post_excerpt;
			if (isset($caption)) { 
				echo '<p class="slideshow-caption">'.$caption.'</p>'; 
			}
			
			echo "</div>";
		} // end slideshow loop
	} // end if ($attachments)
	
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





//let's create an admin menu now

// Hook in the action for the admin options page
add_action('admin_menu', 'add_portfolio_slideshow_option_page');

function add_portfolio_slideshow_option_page() {
	// Hook in the options page function
	add_options_page('Portfolio Slideshow', 'Portfolio Slideshow', 6, __FILE__, 'portfolio_slideshow_options_page');
}


function portfolio_slideshow_options_page() {

// Output a simple options page ?>


<div class="wrap" style="width:500px">
<h2>Portfolio Slideshow Options</h2>
<form method="post" action="options.php">

<?php wp_nonce_field('update-options'); ?>

<table class="form-table">
<tr valign="top">
<th scope="row">Slideshow Size</th>
<td><select name="portfolio_slideshow_size" value="<?php echo get_option('portfolio_slideshow_size'); ?>" />
<option value="thumbnail" <?php if(get_option(portfolio_slideshow_size) == thumbnail) echo " selected='selected'";?>>Thumbnail</option>
<option value="medium" <?php if(get_option(portfolio_slideshow_size) == medium) echo " selected='selected'";?>>Medium</option>
<option value="large" <?php if(get_option(portfolio_slideshow_size) == large) echo " selected='selected'";?>>Large</option>
<option value="full" <?php if(get_option(portfolio_slideshow_size) == full) echo " selected='selected'";?>>Full</option>
</select>
</td>
</tr>	
</table>

<input type="hidden" name="page_options" value="portfolio_slideshow_size" />
<input type="hidden" name="action" value="update" />	
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
<p>The slideshow size refers to the default image sizes that WordPress creates when you upload an image. You can customize these image sizes in the <a href="<?php bloginfo('wpurl');?>/wp-admin/options-media.php">Media Settings</a> control panel. </p>
<h2>Support this plugin</h2>
<p>I don&#8217;t accept donations for this software, but I do have a recommendation for a web host if you&#8217;re interested. I&#8217;ve been using <a href="http://www.a2hosting.com/1107.html">A2 Hosting</a> for years, and they provide fantastic service and support. If you sign up through the link below, I get a referral fee, which helps me maintain this plugin. Their one-click WordPress install will have you up and running in just a couple of minutes.</p> 
<p><a  href="http://www.a2hosting.com/1107.html"><img style="margin:10px 0;" src="http://daltonrooney.com/portfolio/wp-content/uploads/2010/01/green_234x60.jpeg" alt="" title="green_234x60" width="234" height="60" class="alignnone size-full wp-image-148" /></a></p>

</div>


<?php } ?>