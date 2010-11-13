<?php
/*
Plugin Name: Portfolio Slideshow
Plugin URI: http://daltonrooney.com/portfolio
Description: A shortcode that inserts a clean and simple jQuery + cycle powered slideshow of all image attachments on a post or page. Use shortcode [portfolio_slideshow] to activate.
Author: Dalton Rooney
Version: 0.5.9
Author URI: http://daltonrooney.com
*/ 

$ps_version = "0.5.9";

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
add_option("portfolio_slideshow_showhash", 'true'); 
add_option("portfolio_slideshow_timeout", '0'); 
add_option("portfolio_slideshow_showloader", 'false'); 
add_option("portfolio_slideshow_descriptionisURL", 'false'); 

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
$ps_showhash = get_option('portfolio_slideshow_showhash');
$ps_version = get_option('portfolio_slideshow_version');
$ps_showloader = get_option('portfolio_slideshow_showloader');
$ps_descriptionisURL = get_option('portfolio_slideshow_descriptionisURL');


function add_post_id($content) { // this puts the attachment ID on the media page
   $showlink = "Attachment ID:" . get_the_ID($post->ID, true);
    $content[] = $showlink;
    return $content;}
add_filter ( 'media_row_actions', 'add_post_id');

// create the shortcode
add_shortcode('portfolio_slideshow', 'portfolio_shortcode');

// define the shortcode function
function portfolio_shortcode($atts) {

	STATIC $i=1;

	global $ps_trans, $ps_speed, $ps_size, $ps_titles, $ps_captions, $ps_descriptions, $ps_thumbs, $ps_navpos, $ps_timeout, $ps_thumbs_hp, $ps_showhash, $ps_showloader, $ps_descriptionisURL;
	
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
	$(window).load(function() {';
	
	if($ps_showloader=="true"){
			echo '$(\'div.slideshow-holder\').delay(1500).fadeOut(\'fast\', function() {';}

	echo   '$(\'div.portfolio-slideshow\').fadeIn();
			$(\'div.slideshow-nav\').fadeIn();
			$(\'div.slideshow-thumbs\').fadeIn();';
			
	if($ps_showloader=="true"){ 
			echo '});';}
		
	echo   '$(function() {
				var index = 0, hash = window.location.hash;
				if (hash) {
				index = /\d+/.exec(hash)[0];
				index = (parseInt(index) || 1) - 1; // slides are zero-based
		} 	
			
		$(\'#portfolio-slideshow'.$i.'\').cycle({
				fx: \''. $ps_trans . '\',
				speed: '. $ps_speed . ',
				timeout: '. $timeout . ',
				next: \'.slideshow-nav'.$i.' a.slideshow-next\',
				startingSlide: index,
				prev: \'.slideshow-nav'.$i.' a.slideshow-prev\',
				after:     onAfter,
				pager:  \'#slides'.$i.'\',
				manualTrump: false,
				cleartypeNoBg: true,
				pagerAnchorBuilder: function(idx, slide) {
				// return sel string for existing anchor
				return \'#slides'.$i.'  li:eq(\' + (idx) + \') a\'; }
		});
	

		$(\'.slideshow-nav'.$i. ' a.pause\').click(function() { 
			$(\'#portfolio-slideshow'.$i.'\').cycle(\'pause\');
			$(\'.slideshow-nav'.$i. ' a.pause\').hide();
			$(\'.slideshow-nav'.$i. ' a.play\').show();
		});
	
		$(\'.slideshow-nav'.$i. ' a.play\').click(function() { 
			$(\'#portfolio-slideshow'.$i.'\').cycle(\'resume\');
			$(\'.slideshow-nav'.$i. ' a.play\').hide();
			$(\'.slideshow-nav'.$i. ' a.pause\').show();
		});
		
		function onAfter(curr,next,opts) {
			var $ht = $("img",this).attr("height");
			var $oht = $("p.slideshow-caption", this).outerHeight(\'true\');
			var $pht = $("p.slideshow-description", this).outerHeight(\'true\');
			var $qht = $("p.slideshow-title", this).outerHeight(\'true\');
			//set the container\'s height to that of the current slide
			$(this).parent().css("height", $oht + $pht + $ht + $qht);';
					
			if (is_page() || is_single() && $ps_showhash=="true") {
	  echo 'window.location.hash = opts.currSlide + 1;';}
			
	  echo 'var caption = (opts.currSlide + 1) + \' of \' + opts.slideCount;
			$(\'#slideshow-info'.$i.'\').html(caption);
	} }); }); });</script>'; 
		
if($ps_showloader=="true"){ //show the loader.gif if necessary
				$slideshow .= '<div class="slideshow-holder"></div>';}

if ($nav == "top") { //determine whether the nav goes at the top or the bottom
	if (!is_feed()){ //don't output the nav stuff in feeds
		
		$slideshow .= '<div class="slideshow-nav'.$i.' slideshow-nav">';
			
			if ($timeout!=0) { //if autoplay is set
				$slideshow .='<a class="pause" href="javascript: void(0)">Pause</a><a class="play" style="display:none" href="javascript: void(0)">Play</a>';} // end autoplay
		
		$slideshow .= '<a class="slideshow-prev" href="javascript: void(0)">Prev</a>|<a class="slideshow-next" href="javascript: void(0)">Next</a>';
		$slideshow .= '<span id="slideshow-info'.$i.'" class="slideshow-info"></span>';
		$slideshow .= '</div>';} // end if !is_feed 
	
	$slideshow .= '<div id="portfolio-slideshow'.$i.'" class="portfolio-slideshow">';} 
else 
	{$slideshow .= '<div id="portfolio-slideshow'.$i.'" class="portfolio-slideshow">';} // end if nav=top
	
	$slideID=1;
	
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

	
	if ($attachments) { //if attachments are found, run the slideshow, otherwise it's  blank
	
		//begin the slideshow loop
		foreach ($attachments as $attachment) {
		if ($slideID == "1") {
			$slideshow .= "<div class=\"slideshow-nav".$i." first slideshow-next\">";} else {
			$slideshow .= "<div class=\"slideshow-nav".$i." slideshow-next\">";}
			
			//this section sets up the external links if the option is selected
			
			if ($ps_descriptionisURL=="true") {			
				$description = $attachment->post_content;
					if (!empty($description)) { $slideshow .= '<a href="'.$description.'">';}				
				} else { $slideshow .= '<a href="javascript: void(0);" class="slideshow-next">';}
			
			//holy smokes, those are the images! (finally)
			$slideshow .= wp_get_attachment_image($attachment->ID, $size, false, false);
			
			
			//don't forget to end the links if we've got them
			if ($ps_descriptionisURL=="true") {			
				$description = $attachment->post_content;
					if (!empty($description)) { $slideshow .= "</a>";}				
				} else { $slideshow .= "</a>";}				
			
			//if titles option is selected
			if ($ps_titles=="true") {
			$title = $attachment->post_title;
			if (isset($title)) { 
				$slideshow .= '<p class="slideshow-title">'.$title.'</p>'; 
			} }
			
			//if captions option is selected
			if ($ps_captions=="true") {			
			$caption = $attachment->post_excerpt;
			if (isset($caption)) { 
				$slideshow .= '<p class="slideshow-caption">'.$caption.'</p>'; 
			}}
			
			//if descriptions option is selected and we're not using the description field for external links
			if ($ps_descriptions=="true" && $ps_descriptionisURL !="true") {			
			$description = $attachment->post_content;
			if (isset($description)) { 
				$slideshow .= '<p class="slideshow-description">'.$description.'</p>'; 
			}}
			
			$slideshow .= "</div>";
			
			$slideID++;
					
		}  // end slideshow loop
	} // end if ($attachments)

$slideshow .= "</div><!--//end portfolio-slideshow div-->";

//here come the thumbnails!
if (is_page() || is_single() || $ps_thumbs_hp == "true") 
	{	
	if ($thumbs=="true") {

	$slideshow .= '<div class="slideshow-thumbs">
						<ul id="slides'.$i.'" class="slides">';
	
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

	$slideshow .= '<div class="slideshow-nav'.$i.' slideshow-nav">';
	
	if ($timeout!=0) { //if autoplay is set
	$slideshow .='<a class="pause" href="javascript: void(0)">Pause</a><a class="play" style="display:none" href="javascript: void(0)">Play</a>';
	}
	
	$slideshow .='<a class="slideshow-prev" href="javascript: void(0)">Prev</a>|<a class="slideshow-next" href="javascript: void(0)">Next</a>';
	
	$slideshow .= '<span id="slideshow-info'.$i.'" class="slideshow-info"></span>';
	
	$slideshow .= '</div>'; } // end if !is_feed 

} // end if ($nav=="bottom")

$i++;

return $slideshow;	

} //ends the portfolio_shortcode function


// Output the javascript & css for the header here
if (!is_admin){
   wp_deregister_script('jquery'); 
   wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"), false, '1.4.2', false); 
   wp_enqueue_script('jquery');
 }

//load the cycle script
 $url = plugins_url( 'portfolio-slideshow/lib/jquery.cycle.all.min.js' );
 wp_register_script('cycle', $url, false, '2.7.3', true); 
 wp_enqueue_script('cycle');
   
function portfolio_head() {
	echo '
<!-- loaded by Portfolio Slideshow Plugin-->
<link rel="stylesheet" type="text/css" href="' .  get_bloginfo('wpurl') . '/wp-content/plugins/portfolio-slideshow/portfolio-slideshow.css?ver=0.6.0" />
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

function add_portfolio_slideshow_option_page() {
// hook in the options page function
add_options_page('Portfolio Slideshow', 'Portfolio Slideshow', 6, 'portfolio-slideshow', 'portfolio_slideshow_options_page');
}

// hook in the action for the admin options page
add_action('admin_menu', 'add_portfolio_slideshow_option_page');

if (isset($_GET['page'])) { 
    if ($_GET['page'] == "portfolio-slideshow") {
        wp_deregister_script('jquery'); 
  		wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"), 	false, '1.4.2', false); 
  		wp_enqueue_script('jquery');
        $url = plugins_url( 'portfolio-slideshow/lib/vtip-min.js' );
		$styleurl = plugins_url( 'portfolio-slideshow/lib/css/vtip.css' );
 		wp_register_script('vtip', $url, false, '2', true); 
 		wp_enqueue_script('vtip');
		wp_register_style('vtip', $styleurl, false, '2.2', 'screen'); 
 		wp_enqueue_style('vtip');
    }
}

function portfolio_slideshow_options_page() {

global $ps_trans, $ps_speed, $ps_size, $ps_support, $ps_titles, $ps_captions, $ps_descriptions, $ps_timeout, $ps_navpos, $ps_thumbs, $ps_thumbs_hp, $ps_showhash, $ps_version, $ps_showloader, $ps_descriptionisURL;



// Output the options page ?>
<div class="wrap" style="width:500px">


<h2>Support this plugin</h2>

<div<?php if ($ps_support=="true"){echo ' style="display:none"';}?>>

<p>Donations for this software are welcome:</p> 

<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> 
<input type="hidden" name="cmd" value="_s-xclick"> 
<input type="hidden" name="hosted_button_id" value="2ANTEK4HG6XCW"> 
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"> 
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"><br /> 
</form> 

<p>Another way to help out: I&#8217;ve been using <a href="http://www.a2hosting.com/1107.html">A2 Hosting</a> for years, and they provide fantastic service and support. If you sign up through the link below, I get a referral fee, which helps me maintain this software. Their one-click WordPress install will have you up and running in just a couple of minutes.</p> 
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

<h2>Portfolio Slideshow Options</h2>
<p>Options changed here become the default for all slideshows. Most options can also be changed on a per-slideshow basis by using the slideshow attributes.</p>

<form method="post" action="options.php">

<?php wp_nonce_field('update-options'); ?>

<table class="form-table">
<tr valign="top">
<th scope="row">Slideshow size<span class="vtip" title="The slideshow size refers to the default image sizes that WordPress creates when you upload an image. You can customize these image sizes in the Media Settings control panel.">?</span></th>
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
</select>
</td>
</tr>	

<tr valign="top">
<th scope="row">Autoplay timeout<span class="vtip" title="Anything other than 0 here will turn on autoplay by default. Time is displayed in ms&mdash;e.g. 1000 = 1 second per slide. Can be overridden on a per slideshow basis by using timeout=0 in the shortcode.">?</span></th>
<td><input type="text" size="6" name="portfolio_slideshow_timeout" value="<?php echo $ps_timeout;?>"/></td>
</tr>	


<tr valign="top">
<th scope="row">Transition speed</th>
<td><select name="portfolio_slideshow_transition_speed" value="<?php echo get_option('portfolio_slideshow_transition_speed'); ?>" />
	<option value="1" <?php if($ps_speed == 1) echo " selected='selected'";?>>1</option>
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
<th scope="row">Show loading animation <span class="vtip" title="If you've got a slow connection or lots of images, sometimes the slideshow can take a little while to load. Selecting this option will include a loading gif to show that something is happening. You may want to adjust the padding on the image to center it for your slideshow.">?</span></th>
<td><input type="checkbox" name="portfolio_slideshow_showloader" value="true" <?php if ($ps_showloader=="true") {echo' checked="checked"'; }?>/></td>
</tr>	


<tr valign="top">
<th scope="row">Captions and titles</th>
<td><input type="checkbox" name="portfolio_slideshow_show_titles" value="true" <?php if ($ps_titles=="true") {echo' checked="checked"'; }?>/> Show titles</td>
<td><input type="checkbox" name="portfolio_slideshow_show_captions" value="true" <?php if ($ps_captions=="true") {echo' checked="checked"'; }?>/> Show captions</td>
<td><input type="checkbox" name="portfolio_slideshow_show_descriptions" value="true" <?php if ($ps_descriptions=="true") {echo' checked="checked"'; }?>/> Show descriptions</td>
</tr>	

<tr valign="top">
<th scope="row">Description links <br />image to URL <span class="vtip" title='If this option is checked, you can add a URL to the description field that will link the image to another page or site. This disables the "click image to advance slideshow" function and hides the description field that normally displays beneath the slideshow.'>?</span></th>
<td><input type="checkbox" name="portfolio_slideshow_descriptionisURL" value="true" <?php if ($ps_descriptionisURL=="true") {echo' checked="checked"'; }?>/></td>
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
<th scope="row">Show thumbnails on homepage/archive pages</th>
<td><select name="portfolio_slideshow_show_thumbs_hp" value="<?php echo get_option('portfolio_slideshow_show_thumbs_hp'); ?>" />
	<option value="true" <?php if($ps_thumbs_hp == "true") echo " selected='selected'";?>>true</option>
	<option value="false" <?php if($ps_thumbs_hp == "false") echo " selected='selected'";?>>false</option>
</select>
</td>
</tr>	

<tr valign="top">
<th scope="row">Update URL with slide numbers</th>
<td><select name="portfolio_slideshow_showhash" value="<?php echo get_option('portfolio_slideshow_showhash'); ?>" />
	<option value="true" <?php if($ps_showhash == "true") echo " selected='selected'";?>>true</option>
	<option value="false" <?php if($ps_showhash == "false") echo " selected='selected'";?>>false</option>
</select>
</td>
</tr>	


</table>

<input type="hidden" name="page_options" value="portfolio_slideshow_size, portfolio_slideshow_transition, portfolio_slideshow_transition_speed, portfolio_slideshow_show_captions, portfolio_slideshow_show_titles,
portfolio_slideshow_show_descriptions, portfolio_slideshow_timeout, portfolio_slideshow_nav_position, portfolio_slideshow_show_thumbs, portfolio_slideshow_show_thumbs_hp, portfolio_slideshow_showhash, portfolio_slideshow_descriptionisURL, portfolio_slideshow_showloader" />
<input type="hidden" name="action" value="update" />	
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>

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

<p>You're using Portfolio Slideshow v. <?php echo $ps_version;?> by <a href="http://daltonrooney.com/wordpress">Dalton Rooney</a>.
</div>
<?php } ?>