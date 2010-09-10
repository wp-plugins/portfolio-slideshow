<?php
/*
Plugin Name: Portfolio Slideshow
Plugin URI: http://daltonrooney.com/portfolio
Description: A shortcode that inserts a clean and simple jQuery + cycle powered slideshow of all image attachments on a post or page. Use shortcode [portfolio_slideshow] to activate.
Author: Dalton Rooney
Version: 0.5.0
Author URI: http://daltonrooney.com
*/ 

$ps_version = "0.5.0";

// add our default options if they're not already there:

if (get_option('portfolio_slideshow_version')  != $ps_version) {
    update_option('portfolio_slideshow_version', $ps_version);}
add_option("portfolio_slideshow_size", 'full'); 
add_option("portfolio_slideshow_transition", 'fade'); 
add_option("portfolio_slideshow_transition_speed", '400'); 
add_option("portfolio_slideshow_show_support", 'false'); 
add_option("portfolio_slideshow_show_titles", 'true'); 
add_option("portfolio_slideshow_show_captions", 'true'); 
add_option("portfolio_slideshow_show_descriptions", 'false'); 
add_option("portfolio_slideshow_show_thumbs", 'false');
add_option("portfolio_slideshow_show_thumbs_hp", 'false');
add_option("portfolio_slideshow_nav_position", 'top'); 
add_option("portfolio_slideshow_timeout", '0'); 

// now let's grab the options table data
$ps_version = get_option('portfolio_slideshow_version'); 
$ps_size = get_option('portfolio_slideshow_size'); 
$ps_trans = get_option('portfolio_slideshow_transition'); 
$ps_speed = get_option('portfolio_slideshow_transition_speed'); 
$ps_support = get_option('portfolio_slideshow_show_support'); 
$ps_titles = get_option('portfolio_slideshow_show_titles');
$ps_captions = get_option('portfolio_slideshow_show_captions');
$ps_descriptions = get_option('portfolio_slideshow_show_descriptions');
$ps_thumbs = get_option('portfolio_slideshow_show_thumbs');
$ps_thumbs_hp = get_option('portfolio_slideshow_show_thumbs_hp');
$ps_navpos = get_option('portfolio_slideshow_nav_position');
$ps_timeout = get_option('portfolio_slideshow_timeout');
$ps_version = get_option('portfolio_slideshow_version');

function add_post_id($content) { // this puts the attachment ID on the media page
   $showlink = "Attachment ID:" . get_the_ID($post->ID, true);
    $content[] = $showlink;
    return $content;}
add_filter ( 'media_row_actions', 'add_post_id');

// create the shortcode
add_shortcode('portfolio_slideshow', 'portfolio_shortcode');

// define the shortcode function
function portfolio_shortcode($atts) {

	$postid = get_the_ID();

	global $ps_trans, $ps_speed, $ps_size, $ps_titles, $ps_captions, $ps_descriptions, $ps_thumbs, $ps_navpos, $ps_timeout, $ps_thumbs_hp;
	
	extract(shortcode_atts(array(
		'size' => $ps_size,
		'timeout' => $ps_timeout,
		'thumbs' => $ps_thumbs,
		'nav' => $ps_navpos,
		'exclude' => '',
		'include' => ''
	), $atts));

	echo '<script type="text/javascript"> 
	jQuery(document).ready(function($) {
	$(window).load(function() {

		$(\'div.portfolio-slideshow\').fadeIn();
		$(\'div.slideshow-nav\').fadeIn();
		$(\'div.slideshow-thumbs\').fadeIn();
		
		$(function() {
				var index = 0, hash = window.location.hash;
				if (hash) {
				index = /\d+/.exec(hash)[0];
				index = (parseInt(index) || 1) - 1; // slides are zero-based
		} 	
			
		$(\'#portfolio-slideshow'.$postid.'\').cycle({
				fx: \''. $ps_trans . '\',
				speed: '. $ps_speed . ',
				timeout: '. $timeout . ',
				next: \'.slideshow-nav'.$postid.' a.slideshow-next\',
				startingSlide: index,
				prev: \'.slideshow-nav'.$postid.' a.slideshow-prev\',
				after:     onAfter,
				pager:  \'#slides'.$postid.'\',
				manualTrump: false,
				pagerAnchorBuilder: function(idx, slide) {
				// return sel string for existing anchor
				return \'#slides'.$postid.'  li:eq(\' + (idx) + \') a\'; }
		});
	

		$(\'.slideshow-nav'.$postid. ' a.pause\').click(function() { 
			$(\'#portfolio-slideshow'.$postid.'\').cycle(\'pause\');
			$(\'.slideshow-nav'.$postid. ' a.pause\').hide();
			$(\'.slideshow-nav'.$postid. ' a.play\').show();
		});
	
		$(\'.slideshow-nav'.$postid. ' a.play\').click(function() { 
			$(\'#portfolio-slideshow'.$postid.'\').cycle(\'resume\');
			$(\'.slideshow-nav'.$postid. ' a.play\').hide();
			$(\'.slideshow-nav'.$postid. ' a.pause\').show();
		});
		
		function onAfter(curr,next,opts) {
			var $ht = $("img",this).attr("height");
			var $oht = $("p.slideshow-caption", this).outerHeight(\'true\');
			var $pht = $("p.slideshow-description", this).outerHeight(\'true\');
			var $qht = $("p.slideshow-title", this).outerHeight(\'true\');
			//set the container\'s height to that of the current slide
			$(this).parent().css("height", $oht + $pht + $ht + $qht - 30);';
			
			if (is_page() || is_single()) {
	  echo 'window.location.hash = opts.currSlide + 1;';}
			
	  echo 'var caption = (opts.currSlide + 1) + \' of \' + opts.slideCount;
			$(\'#slideshow-info'.$postid.'\').html(caption);
	} }); }); });</script>'; 

if ($nav == "top") { //determine whether the nav goes at the top or the bottom
	
	if (!is_feed()){ //don't output the nav stuff in feeds
		$slideshow = '<div class="slideshow-nav'.$postid.' slideshow-nav">';
			if ($timeout!=0) { //if autoplay is set
				$slideshow .='<a class="pause" href="javascript: void(0)">Pause</a><a class="play" style="display:none" href="javascript: void(0)">Play</a>';} // end autoplay
		
		$slideshow .= '<a class="slideshow-prev" href="javascript: void(0)">Prev</a>|<a class="slideshow-next" href="javascript: void(0)">Next</a>';
		$slideshow .= '<span id="slideshow-info'.$postid.'" class="slideshow-info"></span>';
		$slideshow .= '</div>';} // end if !is_feed 
	
	$slideshow .= '<div id="portfolio-slideshow'.$postid.'" class="portfolio-slideshow">';} 
else 
	{$slideshow = '<div id="portfolio-slideshow'.$postid.'" class="portfolio-slideshow">';} // end if nav=top
	
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
			$slideshow .= "<div class=\"slideshow-nav".$postid." first slideshow-next\">";} else {
			$slideshow .= "<div class=\"slideshow-nav".$postid." slideshow-next\">";}
			$slideshow .= "<a href=\"javascript: void(0)\" class=\"slideshow-next\">";
			$slideshow .= wp_get_attachment_image($attachment->ID, $size, false, false);
			$slideshow .= "</a>";
			
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
			
			if ($ps_descriptions=="true") {			
			$description = $attachment->post_content;
			if (isset($description)) { 
				$slideshow .= '<p class="slideshow-description">'.$description.'</p>'; 
			}}
			
			$slideshow .= "</div>";
			
			$i++;
					
		}  // end slideshow loop
	} // end if ($attachments)

$slideshow .= "</div><!--//end portfolio-slideshow div-->";

//here come the thumbnails!
if (is_page() || is_single() || $ps_thumbs_hp == "true") 
	{	
	if ($thumbs=="true") {

	$slideshow .= '<div class="slideshow-thumbs">
						<ul id="slides'.$postid.'" class="slides">';
	
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
		$slideshow .="<li><a href=\"javascript: void(0)\">";
		$slideshow .= wp_get_attachment_image($attachment->ID, 'thumbnail', false, false);
		$slideshow .= "</a></li>";		
		}
	}
	
	$slideshow .= "</ul></div><!-- end thumbs-->
	<br style=\"clear:both\" />";

} } //end thumbs

if ($nav == "bottom") { //determine whether the nav goes at the top or the bottom

	if (!is_feed()){ //don't output the nav stuff in feeds
	
	$slideshow .= '<div class="slideshow-nav'.$postid.' slideshow-nav">';
	
	if ($timeout!=0) { //if autoplay is set
	$slideshow .='<a class="pause" href="javascript: void(0)">Pause</a><a class="play" style="display:none" href="javascript: void(0)">Play</a>';
	}
	
	$slideshow .='<a class="slideshow-prev" href="javascript: void(0)">Prev</a>|<a class="slideshow-next" href="javascript: void(0)">Next</a>';
	
	$slideshow .= '<span id="slideshow-info'.$postid.'" class="slideshow-info"></span>';
	
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

global $ps_trans, $ps_speed, $ps_size, $ps_support, $ps_titles, $ps_captions, $ps_descriptions, $ps_timeout, $ps_navpos, $ps_thumbs, $ps_thumbs_hp, $ps_version;



// Output the options page ?>
<div class="wrap" style="width:500px">
<h2>Portfolio Slideshow Options</h2>
<p>Options changed here become the default for all slideshows. Most options can also be changed on a per-slideshow basis by using the slideshow attributes.</p>

<form method="post" action="options.php">

<?php wp_nonce_field('update-options'); ?>

<table class="form-table">
<tr valign="top">
<th scope="row">Slideshow size<sup>1</sup></th>
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
<th scope="row">Autoplay timeout<sup>2</sup></th>
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
<td><input type="checkbox" name="portfolio_slideshow_show_descriptions" value="true" <?php if ($ps_descriptions=="true") {echo' checked="checked"'; }?>/> Show descriptions</td>
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
<th scope="row">Show thumbnails on single posts/pages</th>
<td><select name="portfolio_slideshow_show_thumbs" value="<?php echo get_option('portfolio_slideshow_show_thumbs'); ?>" />
	<option value="true" <?php if($ps_thumbs == "true") echo " selected='selected'";?>>true</option>
	<option value="false" <?php if($ps_thumbs == "false") echo " selected='selected'";?>>false</option>
</select>
</td>
</tr>	

<tr valign="top">
<th scope="row">Show thumbnails on homepage/archive pages<sup>3</sup></th>
<td><select name="portfolio_slideshow_show_thumbs_hp" value="<?php echo get_option('portfolio_slideshow_show_thumbs_hp'); ?>" />
	<option value="true" <?php if($ps_thumbs_hp == "true") echo " selected='selected'";?>>true</option>
	<option value="false" <?php if($ps_thumbs_hp == "false") echo " selected='selected'";?>>false</option>
</select>
</td>
</tr>	

</table>

<input type="hidden" name="page_options" value="portfolio_slideshow_size, portfolio_slideshow_transition, portfolio_slideshow_transition_speed, portfolio_slideshow_show_captions, portfolio_slideshow_show_titles,
portfolio_slideshow_show_descriptions, portfolio_slideshow_timeout, portfolio_slideshow_nav_position, portfolio_slideshow_show_thumbs, portfolio_slideshow_show_thumbs_hp" />
<input type="hidden" name="action" value="update" />	
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>

<p style="font-size:10px;"><strong>1.</strong> The slideshow size refers to the default image sizes that WordPress creates when you upload an image. You can customize these image sizes in the <a href="<?php bloginfo('wpurl');?>/wp-admin/options-media.php">Media Settings</a> control panel. </p>

<p style="font-size:10px;"><strong>2.</strong> Anything other than 0 here will turn on autoplay by default. Time is displayed in ms&mdash;e.g. 1000 = 1 second per slide. Can be overridden on a per slideshow basis by using timeout=0 in the shortcode.</p>

<p style="font-size:10px;"><strong>3.</strong> This was not part of earlier versions of the plugin, so it's off by default. There is no override attribute for this in the shortcode.</p>

<h2>Shortcode Attributes</h2>

<p>The following attributes are available to modify the slideshow behavior on an individual basis. Options are the same as above. </p>

<p><strong>Image size:</strong></p>

<code>[portfolio_slideshow size=thumbnail]</code>

<p><strong>Autoplay:</strong></p>

<code>[portfolio_slideshow timeout=5000]</code>

<p>Where timeout equals the time per slide in milliseconds.</p>

<p><strong>Show thumbnails</strong> (shown on single posts and pages only):</p>

<code>[portfolio_slideshow thumbs=true]</code>

or 

<code>[portfolio_slideshow thumbs=false]</code>

<p><strong>Navigation position:</strong></p>

<code>[portfolio_slideshow nav=bottom]</code>

alternately, disable navigation with

<code>[portfolio_slideshow nav=false]</code>

<p><strong>Include or exclude</strong></p>

<code>[portfolio_slideshow include="1,2,3,4"]</code>

<code>[portfolio_slideshow exclude="1,2,3,4"]</code>

<p>You need to specify the attachment ID, which you can find on the <a href="<?php bloginfo('wpurl')?>/wp-admin/upload.php">media library</a> page by hovering over the thumbnail. You can only include attachments which are attached to the current post for now.</p>

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
<p>You're using Portfolio Slideshow v. <?php echo $ps_version;?> by <a href="http://daltonrooney.com/wordpress">Dalton Rooney</a>.
</div>
<?php } ?>