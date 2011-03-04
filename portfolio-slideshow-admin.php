<?php

// create the admin menu

function add_portfolio_slideshow_option_page() {
	// hook in the options page function
	add_options_page('Portfolio Slideshow', 'Portfolio Slideshow', 6, 'portfolio-slideshow', 'portfolio_slideshow_options_page');
}

// hook in the action for the admin options page
add_action('admin_menu', 'add_portfolio_slideshow_option_page');

if (isset($_GET['page'])) { //don't love this, but the officially supported way wasn't working

    if ($_GET['page'] == "portfolio-slideshow") {
  		wp_enqueue_script('jquery');
 		wp_register_script('vtip', plugins_url( 'lib/vtip-min.js', __FILE__ ), false, '2', true); 
 		wp_enqueue_script('jquery-ui-core');
 		wp_enqueue_script('jquery-ui-tabs');
 		wp_register_script('portfolio-slideshow-admin', plugins_url( 'lib/portfolio-slideshow-admin.js', __FILE__ ), false, '2', true); 
 		wp_enqueue_script('portfolio-slideshow-admin');
		wp_register_style('portfolio-slideshow-admin', plugins_url( 'lib/portfolio-slideshow-admin.css', __FILE__ ), false, '2.2', 'screen'); 
 		wp_enqueue_style('portfolio-slideshow-admin');
    }
}


function portfolio_slideshow_options_page() {
	
	global $ps_trans, $ps_speed, $ps_size, $ps_support, $ps_titles, $ps_captions, $ps_descriptions, $ps_timeout, $ps_navpos, $ps_thumbs, $ps_thumbs_hp, $ps_showhash, $ps_version, $ps_showloader, $ps_nowrap, $ps_descriptionisURL, $ps_jquery;
	
	// Output the options page ?>
	<div class="wrap" style="width:75%">
	
	<div class="updated fade"><p style="line-height: 1.4em;">Thanks for downloading Portfolio Slideshow! If you like it, please be sure to give us a positive rating in the <a href="http://wordpress.org/extend/plugins/portfolio-slideshow/">WordPress repository</a>, it means a lot to us.<br />
	
	<p style="line-height: 1.4em;">If you like Portfolio Slideshow but need more advanced slideshow features, check out our newest plugin, <a href="http://madebyraygun.com/lab/portfolio-slideshow">Portfolio Slideshow Pro</a>.</div>
	
	<h2>Portfolio Slideshow</h2>
		
	<form method="post" action="options.php">
	
	<?php wp_nonce_field('update-options'); ?>
	
	<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Slideshow Settings</a></li>
		<li><a href="#tabs-2">Documentation</a></li>
	</ul>
	
	<div id="tabs-1">
		<p>Options changed here become the default for all slideshows. Most options can also be changed on a per-slideshow basis by using the slideshow attributes.</p>
	
	<h3>Slideshow display</h3>
			
			<ul class="options">
			<li><label>Slideshow size<span class="vtip" title="The slideshow size refers to the default image sizes that WordPress creates when you upload an image. You can customize these image sizes in the Media Settings control panel.">?</span></label>
			
			<select name="portfolio_slideshow_size" value="<?php $ps_size;?>" />
				<option value="thumbnail" <?php if($ps_size == thumbnail) echo " selected='selected'";?>>thumbnail</option>
				<option value="medium" <?php if($ps_size == medium) echo " selected='selected'";?>>medium</option>
				<option value="large" <?php if($ps_size == large) echo " selected='selected'";?>>large</option>
				<option value="full" <?php if($ps_size == full) echo " selected='selected'";?>>full</option>
			</select></li>
			
			<li><label>Transition FX</label>
			
			<select name="portfolio_slideshow_transition" value="<?php echo get_option('portfolio_slideshow_transition'); ?>" />
				<option value="fade" <?php if($ps_trans == fade) echo " selected='selected'";?>>fade</option>
				<option value="scrollHorz" <?php if($ps_trans == scrollHorz) echo " selected='selected'";?>>scrollHorz</option>
			</select></li>
			
			<li><label>Transition speed</label>
			
			<select name="portfolio_slideshow_transition_speed" value="<?php echo get_option('portfolio_slideshow_transition_speed'); ?>" />
				<option value="1" <?php if($ps_speed == 1) echo " selected='selected'";?>>1</option>
				<option value="200" <?php if($ps_speed == 200) echo " selected='selected'";?>>200</option>
				<option value="400" <?php if($ps_speed == 400) echo " selected='selected'";?>>400</option>
				<option value="600" <?php if($ps_speed == 600) echo " selected='selected'";?>>600</option>
				<option value="800" <?php if($ps_speed == 800) echo " selected='selected'";?>>800</option>
				<option value="1000" <?php if($ps_speed == 1000) echo " selected='selected'";?>>1000</option>
				<option value="1500" <?php if($ps_speed == 1500) echo " selected='selected'";?>>1500</option>
				<option value="2000" <?php if($ps_speed == 2000) echo " selected='selected'";?>>2000</option>
				<option value="2500" <?php if($ps_speed == 2500) echo " selected='selected'";?>>2500</option>
			</select></li>
			
			<li><label>Captions and titles</label>
			
			<input type="checkbox" name="portfolio_slideshow_show_titles" value="true" <?php if ($ps_titles=="true") {echo' checked="checked"'; }?>/><span>Show titles</span>
			
			<input type="checkbox" name="portfolio_slideshow_show_captions" value="true" <?php if ($ps_captions=="true") {echo' checked="checked"'; }?>/><span>Show captions</span>
			
			<input type="checkbox" name="portfolio_slideshow_show_descriptions" value="true" <?php if ($ps_descriptions=="true") {echo' checked="checked"'; }?>/><span>Show descriptions</span>
			
			</li>
			
			</ul>
			
	<h3>Slideshow Behavior</h3>
		
		<ul class="options">
			
			<li><label>Autoplay timeout <span class="vtip" title="Anything other than 0 here will turn on autoplay by default. Time is displayed in ms&mdash;e.g. 1000 = 1 second per slide.">?</span></label>
			<input type="text" size="6" name="portfolio_slideshow_timeout" value="<?php echo $ps_timeout;?>"/></li>
			
			<li><label>Show loading<br />animation <span class="vtip" title="If you've got a slow connection or lots of images, sometimes the slideshow can take a little while to load. Selecting this option will include a loading gif to show that something is happening. You may want to adjust the padding on the image to center it for your slideshow.">?</span></label>
			<input type="checkbox" name="portfolio_slideshow_showloader" value="true" <?php if ($ps_showloader=="true") {echo' checked="checked"'; }?>/></li>
			
			<li><label>Disable slideshow wrapping? <span class="vtip" title="Should the slideshow play through to the beginning after it gets to the end, or simply stop?">?</span></label>
			<input type="checkbox" name="portfolio_slideshow_nowrap" value="true" <?php if ($ps_nowrap=="true") {echo' checked="checked"'; }?>/></li>
			
			<li><label>Allow links to external URLs <span class="vtip" title="Checking this box allows you to add URLs to your images. For example, if you want your slide to link to a portfolio page or to an external site, you would use this feature. This feature disables the <em>click slide to advance</em> function and will cause problems if you've got anything but a URL in the field, so use it wisely.">?</span></label>
			<input type="checkbox" name="portfolio_slideshow_descriptionisURL" value="true" <?php if ($ps_descriptionisURL=="true") {echo' checked="checked"'; }?>/></li>
			
			<li><label>Update URL with slide numbers  <span class="vtip" title='You can enable this feature to udpate the URL of the page with the slide number. Example: http://example.com/slideshow/#3 will link directly to the third slide in the slideshow.'>?</span></label>
			<input type="checkbox" name="portfolio_slideshow_showhash" value="true" <?php if ($ps_showhash=="true") {echo' checked="checked"'; }?>/></li>
		</ul>	
		
	<h3>Navigation</h3>
		
		<ul class="options">
			
			<li><label>Navigation position</label>
			<select name="portfolio_slideshow_nav_position" value="<?php echo get_option('portfolio_slideshow_nav_position'); ?>" />
			<option value="top" <?php if($ps_navpos == top) echo " selected='selected'";?>>top</option>
			<option value="middle" <?php if($ps_navpos == middle) echo " selected='selected'";?>>below images</option>
			<option value="bottom" <?php if($ps_navpos == bottom) echo " selected='selected'";?>>bottom</option>
			<option value="disabled" <?php if($ps_navpos == disabled) echo " selected='selected'";?>>disabled</option>
			</select></li>
			
	
			<li><label>Show thumbnails on single posts/pages</label>
			<input type="checkbox" name="portfolio_slideshow_show_thumbs" value="true" <?php if ($ps_thumbs=="true") {echo' checked="checked"'; }?>/></li>
	
			<li><label>Show thumbnails on homepage/archive pages</label>
			<input type="checkbox" name="portfolio_slideshow_show_thumbs_hp" value="true" <?php if ($ps_thumbs_hp=="true") {echo' checked="checked"'; }?>/></li>	
			
		</ul>
		
	<h3>Diagnostic</h3>
		
		<ul class="options">
			<li><label>jQuery version <span class="vtip" title="If you're having trouble with the Javascript effects, you can try an older version of jQuery, or disable it altogether. This sometimes helps if you have plugins or themes that rely on their own version of jQuery. Note that the container height calculations for the slideshow rely on features in 1.4.4, so you may experience issues with your container height if you change this from the default.">?</span></label>
			<select name="portfolio_slideshow_jquery_version" value="<?php echo get_option('portfolio_slideshow_jquery_version'); ?>" />
				<option value="1.4.4" <?php if($ps_jquery == "1.4.4") echo " selected='selected'";?>>1.4.4</option>
				<option value="1.4.2" <?php if($ps_jquery == "1.4.2") echo " selected='selected'";?>>1.4.2</option>
				<option value="disabled" <?php if($ps_jquery == disabled) echo " selected='selected'";?>>disabled</option>
			</select>
			</li>	
	
		</ul>
		
	<input type="hidden" name="page_options" value="portfolio_slideshow_size, portfolio_slideshow_transition, portfolio_slideshow_transition_speed, portfolio_slideshow_nowrap, portfolio_slideshow_show_captions, portfolio_slideshow_show_titles,
	portfolio_slideshow_show_descriptions, portfolio_slideshow_timeout, portfolio_slideshow_nav_position, portfolio_slideshow_show_thumbs, portfolio_slideshow_show_thumbs_hp, portfolio_slideshow_showhash, portfolio_slideshow_descriptionisURL, portfolio_slideshow_showloader, portfolio_slideshow_jquery_version" />
	<input type="hidden" name="action" value="update" />	
	<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
	</form>
	
	</div><!--#tabs-1-->
	
	<div id="tabs-2">
	
	<h3>General usage</h3>
	
	<p>To use the plugin, upload your photos to a post or page using the WordPress media uploader. Use the [portfolio_slideshow] shortcode to display the slideshow in your page or post.</p>
		
	<img src="<?php echo plugins_url( 'screenshot-2.png', __FILE__ );?>" alt="" title="portfolio-slideshow screenshot 2" width="405" height="200" class="size-full wp-image-660" /><p class="wp-caption-text">Use the media uploader to add, sort, and delete your photos.</p>
	
	<img src="<?php echo plugins_url( 'screenshot-3.png', __FILE__ );?>" alt="" title="portfolio-slideshow screenshot 3" width="580" height="373" class="size-full wp-image-661" /><p class="wp-caption-text">Insert the slideshow by using this shortcode, exactly as shown. Do not insert the photos into the post. </p>
		
	<h3>Shortcode Attributes</h3>
	<p>If you would like to customize your slideshows on a per-slideshow basis, you can add the following attributes to the shortcode, which will temporarily override the defaults.
	<p>
	To change the <strong>image size</strong> you would use the size attribute in the shortcode like this:
	</p>
	
	<p>
	<code>[portfolio_slideshow size=thumbnail], [portfolio_slideshow size=medium], [portfolio_slideshow size=large], [portfolio_slideshow size=full]</code>
	</p>
	
	<p>
	<strong>Image transition FX</strong>: 
	</p>
	
	<p>
	<code>[portfolio_slideshow trans=scrollHorz]</code>
	</p>
	
	<p>
	(Top secret! You can use this shortcode attribute to supply any transition effect supported by jQuery Cycle, even if they're not in the plugin! List of supported transitions <a href="http://jquery.malsup.com/cycle/begin.html">here</a>.
	</p>
	
	<p>
	<strong>Show titles, captions, or descriptions</strong>:
	</p>
	
	<p>
	<code>[portfolio_slideshow showtitles=true], [portfolio_slideshow showcaps=true], [portfolio_slideshow showdesc=true]</code>
	(use false to disable)</p>
	
	<p>
	<strong>Autoplay timeout</strong>:
	</p>
	
	<p>
	<code>[portfolio_slideshow timeout=4000]</code>
	</p>
	
	<p>
	<strong>Disable slideshow wrapping</strong>: 
	</p>
	
	<p>
	<code>[portfolio_slideshow nowrap=true]</code>
	</p>
	
	<p>
	or enable it like this:
	</p>
	
	<p>
	<code>[portfolio_slideshow nowrap=0]</code>
	</p>
	<p><strong>Show thumbnails:</strong></p>
	
	<code>[portfolio_slideshow thumbs=true]</code>
	
	<p>or</p> 
	
	<code>[portfolio_slideshow thumbs=false]</code>
	
	<p><strong>Navigation position:</strong></p>
	
	<code>[portfolio_slideshow nav=bottom]</code>
	
	<p>alternately, disable navigation with</p>
	
	<code>[portfolio_slideshow nav=false]</code>
	
	<p><strong>Include or exclude slides</strong>:</p>
	</p>
	
	<p>
	<code>[portfolio_slideshow include="1,2,3,4"]</code>
	</p>
	
	<p>
	<code>[portfolio_slideshow exclude="1,2,3,4"]</code>
	</p>
	
	<p>You need to specify the attachment ID, which you can find in your <a href="<?php bloginfo('wpurl')?>/wp-admin/upload.php">Media Library</a> by hovering over the thumbnail. You can only include attachments which are attached to the current post. Do not use these attributes simultaneously, they are mutually exclusive.</p>
	
	<p>
	<strong>Multiple slideshows per post/page</strong>:
	</p>
	
	<p>
	you can insert multiple slideshows per post/page by including different attachment ids in your shortcode. Example:

	<code>[portfolio_slideshow include="1,2,3"]
	
	[portfolio_slideshow include="4,5,6"]</code>
	
	This example will create two slideshows on the page with two sets of images. Remember, the attachment ID can be found in your <a href="<?php bloginfo('wpurl')?>/wp-admin/upload.php">Media Library</a> by hovering over the thumbnail. You can only include attachments which are attached to the current post.</p>
		</p>
</div><!--#tabs-2-->
</div><!--#tabs-->

	<a href="http://madebyraygun.com"><img style="margin-top:30px;" src="<?php echo plugins_url( 'lib/images/logo.png', __FILE__ );?>" width="225" height="70" alt="Made by Raygun" /></a>
	<p>You're using Portfolio Slideshow v. <?php echo $ps_version;?>, made by <a href="http://madebyraygun">Raygun</a>. Check out our <a href="http://madebyraygun.com/lab/" target="_blank">other plugins</a>, and if you have any problems, stop by our <a href="http://madebyraygun.com/support/forum/" target="_blank">support forum</a>!
	</div>
	<?php 
} //options page ?>