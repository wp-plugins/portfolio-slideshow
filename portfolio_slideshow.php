<?php
/*
Plugin Name: Portfolio Slideshow
Plugin URI: http://daltonrooney.com/portfolio
Description: A shortcode that inserts a clean and simple jQuery + cycle powered slideshow of all image attachments on a post or page. Use shortcode [portfolio_slideshow] to activate.
Author: Dalton Rooney
Version: 0.4.2
Author URI: http://daltonrooney.com
*/ 

// add our default options if they're not already there:
add_option("portfolio_slideshow_size", 'full'); 
add_option("portfolio_slideshow_transition", 'fade'); 
add_option("portfolio_slideshow_transition_speed", '400'); 
add_option("portfolio_slideshow_show_support", 'false'); 
add_option("portfolio_slideshow_show_titles", 'true'); 
add_option("portfolio_slideshow_show_captions", 'true'); 
add_option("portfolio_slideshow_show_thumbs", 'false'); 
add_option("portfolio_slideshow_nav_position", 'top'); 
add_option("portfolio_slideshow_timeout", '0'); 

// now let's grab the options table data
$ps_size = get_option('portfolio_slideshow_size'); 
$ps_trans = get_option('portfolio_slideshow_transition'); 
$ps_speed = get_option('portfolio_slideshow_transition_speed'); 
$ps_support = get_option('portfolio_slideshow_show_support'); 
$ps_titles = get_option('portfolio_slideshow_show_titles');
$ps_captions = get_option('portfolio_slideshow_show_captions');
$ps_thumbs = get_option('portfolio_slideshow_show_thumbs');
$ps_navpos = get_option('portfolio_slideshow_nav_position');
$ps_timeout = get_option('portfolio_slideshow_timeout');

// create the shortcode
add_shortcode('portfolio_slideshow', 'portfolio_shortcode');



function add_post_id($content) { // this puts the attachment ID on the media page

   $showlink = "Attachment ID:" . get_the_ID($post->ID, true);
    $content[] = $showlink;
    return $content;
}

add_filter ( 'media_row_actions', 'add_post_id');

// define the shortcode function
function portfolio_shortcode($atts) {

	global $ps_trans, $ps_speed, $ps_size, $ps_titles, $ps_captions, $ps_thumbs, $ps_navpos, $ps_timeout;
	
	extract(shortcode_atts(array(
		'size' => $ps_size,
		'timeout' => $ps_timeout,
		'thumbs' => $ps_thumbs,
		'nav' => $ps_navpos,
		'exclude' => '',
		'include' => ''
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
					after:     onAfter,
					pager:  \'#slides\',
					pagerAnchorBuilder: function(idx, slide) {
					// return sel string for existing anchor
					return \'#slides li:eq(\' + (idx) + \') a\'; }
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

if ($nav == "top") { //determine whether the nav goes at the top or the bottom

	if (!is_feed()){ //don't output the nav stuff in feeds
	
	$slideshow = '<div class="slideshow-nav">';
	
	if ($timeout!=0) { if (is_page() || is_single()) { //if autoplay is set and we're showing extras, output a pause button
	$slideshow .='<a class="pause" href="#">Pause</a> ';
	} }
	
	$slideshow .='<a class="slideshow-prev" href="#">Prev</a>|<a class="slideshow-next" href="#">Next</a>';
	
	if (is_page() || is_single()) //only shows slideshow number if we're showing extras
	{ $slideshow .= '<span id="slideshow-info"></span>';}
	
	$slideshow .= '</div>'; } // end if !is_feed 

} // end if nav=top 
	
	if ($nav=="top"){ $slideshow .= '<div class="portfolio-slideshow">'; } else {$slideshow = '<div class="portfolio-slideshow">';}
	
	$i=1;
	
	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$attachments = get_posts( array('order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => $size,
		'include'		 => $include) );
		
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_posts( array('order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => $size,
		'exclude'		 => $exclude) );
	} else {
		$attachments = get_posts( array('order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => $size) );
	}

	if ( empty($attachments) )
		return '';

	if ($attachments) {
		foreach ($attachments as $attachment) {
		if ($i == "1") {
			$slideshow .= "<div class='first slideshow-next'>";} else {
			$slideshow .= "<div class='slideshow-next'>";}
										
			$slideshow .= wp_get_attachment_image($attachment->ID, $size, false, false);
						
			if ($ps_titles=="true") {
			$title = $attachment->post_title;
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

$slideshow .= "</div><!--//end .portfolio-slideshow-->";

//here come the thumbnails!
if (is_page() || is_single()) {
if ($thumbs=="true") {

	$slideshow .= "<div class='slideshow-thumbs'>
						<ul id='slides'>";
	
	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$attachments = get_posts( array('order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => 'thumbnail',
		'include'		 => $include) );
		
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_posts( array('order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => 'thumbnail',
		'exclude'		 => $exclude) );
	} else {
		$attachments = get_posts( array('order'          => 'ASC',
		'orderby' 		 => 'menu_order ID',
		'post_type'      => 'attachment',
		'post_parent'    => get_the_ID(),
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
		'size'			 => 'thumbnail') );
	}

	if ( empty($attachments) )
		return '';
	
	if ($attachments) {
		foreach ($attachments as $attachment) {
		$slideshow .="<li><a href=\"#\">";
		$slideshow .= wp_get_attachment_image($attachment->ID, 'thumbnail', false, false);
		$slideshow .= "</a></li>";		
		}
	}
	
	$slideshow .= "</ul></div><!-- end thumbs-->
	<br style=\"clear:both\" />";

} }//thumbs

if ($nav == "bottom") { //determine whether the nav goes at the top or the bottom

	if (!is_feed()){ //don't output the nav stuff in feeds
	
	$slideshow .= '<div class="slideshow-nav">';
	
	if ($timeout!=0) { if (is_page() || is_single()) { //if autoplay is set and we're showing extras, output a pause button
	$slideshow .='<a class="pause" href="#">Pause</a> ';
	} }
	
	$slideshow .='<a class="slideshow-prev" href="#">Prev</a>|<a class="slideshow-next" href="#">Next</a>';
	
	if (is_page() || is_single()) //only shows slideshow number if we're showing extras
	{ $slideshow .= '<span id="slideshow-info"></span>';}
	
	$slideshow .= '</div>'; } // end if !is_feed 

} // end if ($nav=="bottom")
	
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
<link rel="stylesheet" type="text/css" href="' .  get_bloginfo('wpurl') . '/wp-content/plugins/portfolio-slideshow/style.css" />
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

global $ps_trans, $ps_speed, $ps_size, $ps_support, $ps_titles, $ps_captions, $ps_timeout, $ps_navpos, $ps_thumbs;



// Output the options page ?>
<div class="wrap" style="width:500px">
<h2>Portfolio Slideshow Options</h2>
<p>Options changed here become the default for all slideshows. Most options can also be changed on a per-slideshow basis by using the slideshow attributes.</p>

<form method="post" action="options.php">

<?php wp_nonce_field('update-options'); ?>

<table class="form-table">
<tr valign="top">
<th scope="row">Slideshow size *</th>
<td><select name="portfolio_slideshow_size" value="<?php $ps_size;?>" />
	<option value="thumbnail" <?php if($ps_size == thumbnail) echo " selected='selected'";?>>thumbnail</option>
	<option value="medium" <?php if($ps_size == medium) echo " selected='selected'";?>>medium</option>
	<option value="large" <?php if($ps_size == large) echo " selected='selected'";?>>large</option>
	<option value="full" <?php if($ps_size == full) echo " selected='selected'";?>>full</option>
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
<th scope="row">Autoplay timeout**</th>
<td><input type="text" size="6" name="portfolio_slideshow_timeout" value="<?php echo $ps_timeout;?>"/></td>
</tr>	


<tr valign="top">
<th scope="row">Transition speed</th>
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
<th scope="row">Captions and titles</th>
<td><input type="checkbox" name="portfolio_slideshow_show_titles" value="true" <?php if ($ps_titles=="true") {echo' checked="checked"'; }?>/> Show titles</td>
<td><input type="checkbox" name="portfolio_slideshow_show_captions" value="true" <?php if ($ps_captions=="true") {echo' checked="checked"'; }?>/> Show captions</td>
</tr>	

<tr valign="top">
<th scope="row">Navigation position</th>
<td><select name="portfolio_slideshow_nav_position" value="<?php echo get_option('portfolio_slideshow_nav_position'); ?>" />
	<option value="top" <?php if($ps_navpos == top) echo " selected='selected'";?>>top</option>
	<option value="bottom" <?php if($ps_navpos == bottom) echo " selected='selected'";?>>bottom</option>
</select>
</td>
</tr>	

<tr valign="top">
<th scope="row">Show thumbnails</th>
<td><select name="portfolio_slideshow_show_thumbs" value="<?php echo get_option('portfolio_slideshow_show_thumbs'); ?>" />
	<option value="true" <?php if($ps_thumbs == "true") echo " selected='selected'";?>>true</option>
	<option value="false" <?php if($ps_thumbs == "false") echo " selected='selected'";?>>false</option>
</select>
</td>
</tr>	


</table>

<input type="hidden" name="page_options" value="portfolio_slideshow_size, portfolio_slideshow_transition, portfolio_slideshow_transition_speed, portfolio_slideshow_show_captions, portfolio_slideshow_show_titles, portfolio_slideshow_timeout, portfolio_slideshow_nav_position, portfolio_slideshow_show_thumbs" />
<input type="hidden" name="action" value="update" />	
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>

<p style="font-size:10px;">*The slideshow size refers to the default image sizes that WordPress creates when you upload an image. You can customize these image sizes in the <a href="<?php bloginfo('wpurl');?>/wp-admin/options-media.php">Media Settings</a> control panel. </p>

<p style="font-size:10px;">**Anything other than 0 here will turn on autoplay by default. (Works on single posts and pages only). Time is displayed in ms&mdash;e.g. 1000 = 1 second per slide. Can be overridden on a per slideshow basis by using timeout=0 in the shortcode.</p>

<h2>Shortcode Attributes</h2>

<p>The following attributes are available to modify the slideshow behavior on an individual basis. Options are the same as above. </p>

<p><strong>Image size:</strong></p>

<code>[portfolio_slideshow size=thumbnail]</code>

<p><strong>Autoplay:</strong></p>

<code>[portfolio_slideshow timeout=5000]</code>

<p>Where timeout equals the time per slide in milliseconds.</p>

<p><strong>Show thumbnails</strong> (shown on single posts and pages only):</p>

<code>[portfolio_slideshow thumbs=true]</code>

<p><strong>Navigation position:</strong></p>

<code>[portfolio_slideshow nav=bottom]</code>

<p><strong>Include or exclude</strong></p>

<code>[portfolio_slideshow include="1,2,3,4"]</code>

<code>[portfolio_slideshow exclude="1,2,3,4"]</code>

<p>You need to specify the attachment ID, which you can find on the media library page by hovering over the thumbnail. You can only include attachments which are attached to the current post for now.</p>

<h2>Support this plugin</h2>

<div<?php if ($ps_support=="true"){echo ' style="display:none"';}?>>

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
<input type="checkbox" name="portfolio_slideshow_show_support" value="true"<?php if ($ps_support=="true"){echo ' checked="checked"';}?>> I have donated to the plugin, don't show ads.<br />
<input type="hidden" name="page_options" value="portfolio_slideshow_show_support" />
<input type="hidden" name="action" value="update" />	

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save') ?>" />
</p>

</form>
</div>
<?php } ?>