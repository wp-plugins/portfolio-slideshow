<?php
/*
Plugin Name: Portfolio Slideshow
Plugin URI: http://daltonrooney.com/portfolio
Description: A shortcode that inserts a clean and simple jQuery + cycle powered slideshow of all image attachments on a post or page. Use shortcode [portfolio_slideshow] to activate.
Author: Dalton Rooney
Version: 0.3.10
Author URI: http://daltonrooney.com
*/ 

// add our default options if they're not already there:
add_option("portfolio_slideshow_size", 'full'); 
add_option("portfolio_slideshow_transition", 'fade'); 
add_option("portfolio_slideshow_transition_speed", '400'); 
add_option("portfolio_slideshow_show_support", 'false'); 
add_option("portfolio_slideshow_show_titles", 'true'); 
add_option("portfolio_slideshow_show_captions", 'true'); 

// now let's grab the options table data
$ps_size = get_option('portfolio_slideshow_size'); 
$ps_trans = get_option('portfolio_slideshow_transition'); 
$ps_speed = get_option('portfolio_slideshow_transition_speed'); 
$ps_support = get_option('portfolio_slideshow_show_support'); 
$ps_titles = get_option('portfolio_slideshow_show_titles');
$ps_captions = get_option('portfolio_slideshow_show_captions');

// create the shortcode
add_shortcode('portfolio_slideshow', 'portfolio_shortcode');

// define the shortcode function
function portfolio_shortcode($atts) {

	global $ps_trans, $ps_speed, $ps_size, $ps_titles, $ps_captions;
	
	extract(shortcode_atts(array(
		'size' => $ps_size,
		'timeout' => 0,
	), $atts));
	
//autoplay and hash updating are limited to single posts and pages only. Someday I figure out how to have multiple slideshows running with extras on the same page.

if (is_page() || is_single()) 
	{
	echo '<script type="text/javascript"> 
	jQuery(document).ready(function($) {
		$(document).ready(function() {
		
			$(function() {
				var index = 0, hash = window.location.hash;
				if (hash) {
				index = /\d+/.exec(hash)[0];
				index = (parseInt(index) || 1) - 1; // slides are zero-based
			}
		
		$(\'.portfolio-slideshow\').each(function() {
				var p = this.parentNode;
				$(this).cycle({
					fx: \''. $ps_trans . '\',
					speed: '. $ps_speed . ',
					timeout: '. $timeout . ',
					next: $(\'.slideshow-next\', p),
					startingSlide: index,
					prev: $(\'.slideshow-prev\', p),
					after:     onAfter
				});
			});
		});

	$(\'.pause\').click(function() { 
		$(\'.portfolio-slideshow\').cycle(\'toggle\'); 
	});
	
	function onAfter(curr,next,opts) {
		window.location.hash = opts.currSlide + 1;
		var caption = (opts.currSlide + 1) + \' of \' + opts.slideCount;
		$(\'#slideshow-info\').html(caption);
	}	}); });
	</script>'; } //end the full featured slideshow. If on an index page, output the basic version:
else { 
echo '<script type="text/javascript"> 
	jQuery(document).ready(function($) {
		$(document).ready(function() {
		
					
		$(\'.portfolio-slideshow\').each(function() {
				var p = this.parentNode;
				$(this).cycle({
					fx: \''. $ps_trans . '\',
					speed: '. $ps_speed . ',
					timeout: 0,
					next: $(\'.slideshow-next\', p),
					prev: $(\'.slideshow-prev\', p)
				});
			});
		});
	 });
	</script>';

} //end homepage version of the slideshow configuration and start HTML

	if (!is_feed()){ //don't output the nav stuff in feeds
	
	$slideshow = '<div class="slideshow-nav">';
	
	if ($timeout!=0) { if (is_page() || is_single()) { //if autoplay is set and we're showing extras, output a pause button
	$slideshow .='<a class="pause" href="#">Pause</a> ';
	} }
	
	$slideshow .='<a class="slideshow-prev" href="#">Prev</a>|<a class="slideshow-next" href="#">Next</a>';
	
if (is_page() || is_single()) //only shows slideshow number if we're showing extras
	{ $slideshow .= '<span id="slideshow-info"></span>';}
	
	$slideshow .= '</div>'; } // end if !is_feed
	
	$slideshow .= '<div class="portfolio-slideshow">';
	
	$args =  array(
		'order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => $size
	) ;	
	
	$i=1;
	$attachments = get_posts($args);
	if ($attachments) {
		foreach ($attachments as $attachment) {
		if ($i == "1") {
			$slideshow .= "<div class='first slideshow-next'>";} else {
			$slideshow .= "<div class='slideshow-next'>";}
										
			$slideshow .= wp_get_attachment_image($attachment->ID, $args['size'], false, false);
						
			if ($ps_titles=="true") {
			if (isset($title)) { 
				$slideshow .= '<p class="slideshow-title">'.$title.'</p>'; 
			} }
			
			if ($ps_captions=="true") {			
			$caption = $attachment->post_excerpt;
			if (isset($caption)) { 
				$slideshow .= '<p class="slideshow-caption">'.$caption.'</p>'; 
			}}
			
			$slideshow .= "</div>";
			
			$i++;
					
		}  // end slideshow loop
	} // end if ($attachments)
	
	$slideshow .= "</div>";

return $slideshow;	
} //ends the portfolio_shortcode function


// Output the javascript & css for the header here

if( !is_admin()){
   wp_deregister_script('jquery'); 
   wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"), false, '1.4.2', false); 
   wp_enqueue_script('jquery');
}

//load the cycle script
 $url = plugins_url( 'portfolio-slideshow/jquery.cycle.all.min.js' );
 wp_register_script('cycle', $url, false, '2.7.3', true); 
 wp_enqueue_script('cycle');
   
function portfolio_head() {
	echo '
<!-- loaded by Portfolio Slideshow Plugin-->
<style>
    div.portfolio-slideshow div.slideshow-next {display: none;}
	div.portfolio-slideshow div.first.slideshow-next{display: block;}
	.slideshow-nav {padding:0 0 6px 0}
	.slideshow-nav a {text-decoration:underline; color: #444444;}
	.slideshow-nav a.pause {margin: 0 15px 0 0;}
	.slideshow-nav a.slideshow-prev {margin: 0 10px 0 0;}
	.slideshow-nav a.slideshow-next {margin: 0 25px 0 10px;}
	.portfolio-slideshow {margin: 0 0 15px 0;}
</style>
<!-- end Portfolio Slideshow Plugin -->
';
} // ends portfolio_head function

add_action('wp_head', 'portfolio_head');

//publishes a note when the plugin is updated

function my_plugin_update_info() {
	if ( $info = wp_remote_fopen("http://www.daltonrooney.com/portfolio/wp-content/uploads/portfolio-slideshow-changelog.txt") )
		echo '<br />' . strip_tags( $info, "<br><a><b><i><span>" );
}

add_action('in_plugin_update_message-'.plugin_basename(__FILE__), 'my_plugin_update_info');

// create the admin menu

// hook in the action for the admin options page
add_action('admin_menu', 'add_portfolio_slideshow_option_page');

function add_portfolio_slideshow_option_page() {
// hook in the options page function
add_options_page('Portfolio Slideshow', 'Portfolio Slideshow', 6, __FILE__, 'portfolio_slideshow_options_page');
}

function portfolio_slideshow_options_page() {

global $ps_trans, $ps_speed, $ps_size, $ps_support, $ps_titles, $ps_captions;



// Output the options page ?>
<div class="wrap" style="width:500px">
<h2>Portfolio Slideshow Options</h2>

<form method="post" action="options.php">

<?php wp_nonce_field('update-options'); ?>

<table class="form-table">
<tr valign="top">
<th scope="row">Slideshow Size *</th>
<td><select name="portfolio_slideshow_size" value="<?php $ps_size;?>" />
	<option value="thumbnail" <?php if($ps_size == thumbnail) echo " selected='selected'";?>>Thumbnail</option>
	<option value="medium" <?php if($ps_size == medium) echo " selected='selected'";?>>Medium</option>
	<option value="large" <?php if($ps_size == large) echo " selected='selected'";?>>Large</option>
	<option value="full" <?php if($ps_size == full) echo " selected='selected'";?>>Full</option>
</select>
</td>
</tr>	

<tr valign="top">
<th scope="row">Transition FX</th>
<td><select name="portfolio_slideshow_transition" value="<?php echo get_option('portfolio_slideshow_transition'); ?>" />
	<option value="fade" <?php if($ps_trans == fade) echo " selected='selected'";?>>fade</option>
	<option value="scrollHorz" <?php if($ps_trans == scrollHorz) echo " selected='selected'";?>>scrollHorz</option>
	<option value="none" <?php if($ps_trans == none) echo " selected='selected'";?>>none</option>
</select>
</td>
</tr>	
<tr valign="top">
<th scope="row">Transition Speed</th>
<td><select name="portfolio_slideshow_transition_speed" value="<?php echo get_option('portfolio_slideshow_transition_speed'); ?>" />
	<option value="200" <?php if($ps_speed == 200) echo " selected='selected'";?>>200</option>
	<option value="400" <?php if($ps_speed == 400) echo " selected='selected'";?>>400</option>
	<option value="600" <?php if($ps_speed == 600) echo " selected='selected'";?>>600</option>
	<option value="800" <?php if($ps_speed == 800) echo " selected='selected'";?>>800</option>
	<option value="1000" <?php if($ps_speed == 1000) echo " selected='selected'";?>>1000</option>
	<option value="1500" <?php if($ps_speed == 1500) echo " selected='selected'";?>>1500</option>
	<option value="2000" <?php if($ps_speed == 2000) echo " selected='selected'";?>>2000</option>
	<option value="2500" <?php if($ps_speed == 2500) echo " selected='selected'";?>>2500</option>
</select>
</td>
</tr>	

<tr valign="top">
<th scope="row">Captions and Titles</th>
<td><input type="checkbox" name="portfolio_slideshow_show_titles" value="true" <?php if ($ps_titles=="true") {echo' checked="checked"'; }?>/> Show titles</td>
<td><input type="checkbox" name="portfolio_slideshow_show_captions" value="true" <?php if ($ps_captions=="true") {echo' checked="checked"'; }?>/> Show captions</td>
</tr>	

</table>

<input type="hidden" name="page_options" value="portfolio_slideshow_size, portfolio_slideshow_transition, portfolio_slideshow_transition_speed, portfolio_slideshow_show_captions, portfolio_slideshow_show_titles" />
<input type="hidden" name="action" value="update" />	
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>

<p style="font-size:10px;">*The slideshow size refers to the default image sizes that WordPress creates when you upload an image. You can customize these image sizes in the <a href="<?php bloginfo('wpurl');?>/wp-admin/options-media.php">Media Settings</a> control panel. </p>

<div<?php if ($ps_support=="true"){echo ' style="display:none"';}?>>

<h2>Support this plugin</h2>

<p>Donations for this software are welcome:</p> 

<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> 
<input type="hidden" name="cmd" value="_s-xclick"> 
<input type="hidden" name="hosted_button_id" value="2ANTEK4HG6XCW"> 
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"> 
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"><br /> 
</form> 

<p>One other plug: I have a recommendation for a web host if you&#8217;re interested. I&#8217;ve been using <a href="http://www.a2hosting.com/1107.html">A2 Hosting</a> for years, and they provide fantastic service and support. If you sign up through the link below, I get a referral fee, which helps me maintain this software. Their one-click WordPress install will have you up and running in just a couple of minutes.</p> 
<p><a  href="http://www.a2hosting.com/1107.html"><img style="margin:10px 0;" src="http://daltonrooney.com/portfolio/wp-content/uploads/2010/01/green_234x60.jpeg" alt="" title="green_234x60" width="234" height="60" class="alignnone size-full wp-image-148" /></a></p> 
</div>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<input type="checkbox" name="portfolio_slideshow_show_support" value="true"<?php if ($ps_support=="true"){echo ' checked="checked"';}?>> I have donated to the plugin, don't show these ads.<br />
<input type="hidden" name="page_options" value="portfolio_slideshow_show_support" />
<input type="hidden" name="action" value="update" />	

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save') ?>" />
</p>

</form>
</div>
<?php } ?>