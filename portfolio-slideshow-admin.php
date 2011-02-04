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
 		wp_enqueue_script('vtip');
		wp_register_style('vtip', plugins_url( 'lib/css/vtip.css', __FILE__ ), false, '2.2', 'screen'); 
 		wp_enqueue_style('vtip');
    }
}


function portfolio_slideshow_options_page() {
	
	global $ps_trans, $ps_speed, $ps_size, $ps_support, $ps_titles, $ps_captions, $ps_descriptions, $ps_timeout, $ps_navpos, $ps_thumbs, $ps_thumbs_hp, $ps_showhash, $ps_version, $ps_showloader, $ps_nowrap, $ps_descriptionisURL;
	
	// Output the options page ?>
	<div class="wrap" style="width:500px">
		
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
	<th scope="row">Autoplay timeout<span class="vtip" title="Anything other than 0 here will turn on autoplay by default. Time is displayed in ms&mdash;e.g. 1000 = 1 second per slide.">?</span></th>
	<td><input type="text" size="6" name="portfolio_slideshow_timeout" value="<?php echo $ps_timeout;?>"/></td>
	</tr>	
	
	<tr valign="top">
	<th scope="row">Disable slideshow wrapping? <span class="vtip" title="Should the slideshow play through to the beginning after it gets to the end, or simply stop?">?</span></th>
	<td><input type="checkbox" name="portfolio_slideshow_nowrap" value="true" <?php if ($ps_nowrap=="true") {echo' checked="checked"'; }?>/></td>
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
	<th scope="row">Navigation position</th>
	<td><select name="portfolio_slideshow_nav_position" value="<?php echo get_option('portfolio_slideshow_nav_position'); ?>" />
		<option value="top" <?php if($ps_navpos == top) echo " selected='selected'";?>>top</option>
		<option value="middle" <?php if($ps_navpos == middle) echo " selected='selected'";?>>below images</option>
		<option value="bottom" <?php if($ps_navpos == bottom) echo " selected='selected'";?>>bottom</option>
		<option value="disabled" <?php if($ps_navpos == disabled) echo " selected='selected'";?>>disabled</option>
	</select>
	</td>
	</tr>	
	
	<tr valign="top">
	<th scope="row">Show thumbnails on single posts/pages</th>
	<td><input type="checkbox" name="portfolio_slideshow_show_thumbs" value="true" <?php if ($ps_thumbs=="true") {echo' checked="checked"'; }?>/>
	</td>
	</tr>	
	
	<tr valign="top">
	<th scope="row">Show thumbnails on homepage/archive pages</th>
	<td><input type="checkbox" name="portfolio_slideshow_show_thumbs_hp" value="true" <?php if ($ps_thumbs_hp=="true") {echo' checked="checked"'; }?>/>
	</td>
	</tr>	
	
	<tr valign="top">
	<th scope="row">Allow links to<br />external URLs <span class="vtip" title="Checking this box allows you to add URLs to your images. For example, if you want your slide to link to a portfolio page or to an external site, you would use this feature. This feature disables the <em>click slide to advance</em> function and will cause problems if you've got anything but a URL in the field, so use it wisely.">?</span></th>
	<td><input type="checkbox" name="portfolio_slideshow_descriptionisURL" value="true" <?php if ($ps_descriptionisURL=="true") {echo' checked="checked"'; }?>/></td>
	</tr>	
	
	<tr valign="top">
	<th scope="row">Update URL with slide numbers<span class="vtip" title='You can enable this feature to udpate the URL of the you page with the slide number. Example: http://example.com/slideshow/#3 will link directly to the third slide in the slideshow.'>?</span></th>
	<td><input type="checkbox" name="portfolio_slideshow_showhash" value="true" <?php if ($ps_showhash=="true") {echo' checked="checked"'; }?>/>
	</td>
	</tr>	
	
	</table>
	
	<input type="hidden" name="page_options" value="portfolio_slideshow_size, portfolio_slideshow_transition, portfolio_slideshow_transition_speed, portfolio_slideshow_nowrap, portfolio_slideshow_show_captions, portfolio_slideshow_show_titles,
	portfolio_slideshow_show_descriptions, portfolio_slideshow_timeout, portfolio_slideshow_nav_position, portfolio_slideshow_show_thumbs, portfolio_slideshow_show_thumbs_hp, portfolio_slideshow_showhash, portfolio_slideshow_descriptionisURL, portfolio_slideshow_showloader" />
	<input type="hidden" name="action" value="update" />	
	<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>
	</form>
	
	<h2>Support this plugin</h2>
	
	<div<?php if ($ps_support=="true"){echo ' style="display:none"';}?>>
	
	
	<p>Donations for this software are welcome:</p> 
	
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> 
	<input type="hidden" name="cmd" value="_s-xclick"> 
	<input type="hidden" name="hosted_button_id" value="2ANTEK4HG6XCW"> 
	<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"> 
	<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"><br /> 
	</form> 
	
	<p>One more thing: we love <a href="http://daltn.com/x/a2">A2 Hosting</a>! We've been using them for years, and they provide the best web host service and support in the industry. If you sign up through the link below, we get a referral fee, which helps us maintain this software. Their one-click WordPress install will have you up and running in just a couple of minutes.</p> 
	<p><a  href="http://daltn.com/x/a2"><img style="margin:10px 0;" src="http://daltonrooney.com/portfolio/wp-content/uploads/2010/01/green_234x60.jpeg" alt="" title="green_234x60" width="234" height="60" class="alignnone size-full wp-image-148" /></a></p> 
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
	
	<h2>Shortcode Attributes</h2>
	
	<p>The following attributes are available to modify the slideshow behavior on an individual basis. Options are the same as above.</p>
	
	<p><strong>Image size:</strong></p>
	
	<code>[portfolio_slideshow size=thumbnail]</code>
	
	<p><strong>Image transition on a per-slideshow basis:</strong></p>
	
	<code>[portfolio_slideshow trans=scrollHorz]</code>
	
	<p>(Top secret! You can use this shortcode attribute to supply any transition effect supported by jQuery Cycle, even if they're not in the plugin! List of supported transitions <a href="http://jquery.malsup.com/cycle/begin.html" target="_blank">here</a>.</p>
	
	<p><strong>Autoplay timeout on a per-slideshow basis:</strong></p>
	
	<code>[portfolio_slideshow timeout=4000]</code>
	
	<p><strong>Disable slideshow wrapping:</strong></p>
	
	<code>[portfolio_slideshow nowrap=true]</code>
	
	<p>or enable it like this:</p>
	
	<code>[portfolio_slideshow nowrap=0]</code>

	<p><strong>Show thumbnails:</strong></p>
	
	<code>[portfolio_slideshow thumbs=true]</code>
	
	<p>or</p> 
	
	<code>[portfolio_slideshow thumbs=false]</code>
	
	<p><strong>Navigation position:</strong></p>
	
	<code>[portfolio_slideshow nav=bottom]</code>
	
	<p>alternately, disable navigation with</p>
	
	<code>[portfolio_slideshow nav=false]</code>
	
	<p><strong>Include or exclude</strong></p>
	
	<code>[portfolio_slideshow include="1,2,3,4"]</code>
	
	<code>[portfolio_slideshow exclude="1,2,3,4"]</code>
	
	<p>You need to specify the attachment ID, which you can find in your <a href="<?php bloginfo('wpurl')?>/wp-admin/upload.php">Media Library</a> by hovering over the thumbnail. You can only include attachments which are attached to the current post. Do not use these attributes simultaneously, they are mutually exclusive.</p>
	
	<p><strong>Multiple slideshows per post/page:</strong></p>
	
	<p>You can insert multiple slideshows per post/page by including different attachment IDs in your shortcode. </p>
	
	<p>Example:
	<code>[portfolio_slideshow include="1,2,3"]</code><code>[portfolio_slideshow include="4,5,6"]</code>
	This example will create two slideshows on the page with two sets of images. Remember, the attachment ID can be found in your <a href="<?php bloginfo('wpurl')?>/wp-admin/upload.php">Media Library</a> by hovering over the thumbnail. You can only include attachments which are attached to the current post.</p>
	<a href="http://madebyraygun.com"><img style="margin-top:30px;" src="<?php echo plugins_url( 'lib/images/logo.png', __FILE__ );?>" width="225" height="70" alt="Made by Raygun" /></a>
	<p>You're using Portfolio Slideshow v. <?php echo $ps_version;?>, made by <a href="http://madebyraygun">Raygun</a>. Check out our <a href="http://madebyraygun.com/lab/" target="_blank">other plugins</a>, and if you have any problems, stop by our <a href="http://madebyraygun.com/support/forum/" target="_blank">support forum</a>!
	</div>
	<?php 
} //options page ?>