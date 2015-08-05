<?php

defined( 'WPINC' ) or die;

/*

List of plugin filters in new 2.0.0:

portfolio_slideshow_get_supported_types

portfolio_slideshow_get_settings_tabs
*/
?>
<div class="wrap">
<h3>General usage</h3>
		
<p>To use the plugin, upload your photos directly to a post or page using the WordPress media uploader. Use the [portfolio_slideshow] shortcode to display the slideshow in your page or post.</p>
	
<h3>Shortcode Attributes</h3>

<p>If you would like to customize your slideshows on a per-slideshow basis, you can add the following attributes to the shortcode, which will temporarily override the defaults.

<p>To select a <strong>different page parent ID</strong> for the images:</p>
<p><code>[portfolio_slideshow id=xxx]</code></p>

<p>
To change the <strong>image size</strong> you would use the size attribute in the shortcode like this:
</p>

<p>
<code>[portfolio_slideshow size=thumbnail], [portfolio_slideshow size=medium], [portfolio_slideshow size=large], [portfolio_slideshow size=full]</code>
</p>
<p>This setting can use any custom image size that you've registered in WordPress. 

<p>
You can add a custom <strong>slide container height</strong>: 
</p>

<p>
<code>[portfolio_slideshow slideheight=400]</code>
</p>

<p>
Useful if you don't want the page height to adjust with the slideshow.
</p>

<p>
<strong>Image transition FX</strong>: 
</p>

<p>
<code>[portfolio_slideshow trans=scrollHorz]</code>
</p>

<p>
You can use this shortcode attribute to supply any transition effect supported by jQuery Cycle, even if they're not in the plugin! List of supported transitions <a href="http://jquery.malsup.com/cycle/begin.html">here</a> Not all transitions will work with all themes, if in doubt, stick with fade or none.
</p>
		
<p>
<strong>Transition speed</strong>:
</p>

<p>
<code>[portfolio_slideshow speed=400]</code>
</p>


<strong>Show titles, captions, or descriptions</strong>:
</p>

<p>
<code>[portfolio_slideshow showtitles=true], [portfolio_slideshow showcaps=true], [portfolio_slideshow showdesc=true]</code>
(use false to disable)</p>	
<p>
<strong>Time per slide when slideshow is playing (timeout)</strong>:
</p>

<p>
<code>[portfolio_slideshow timeout=4000]</code>
</p>


<p>
<strong>Autoplay</strong>:
</p>
<p>
<code>[portfolio_slideshow autoplay=true]</code>
</p>

<p>
<strong>Exclude featured image</strong>:
</p>
<p>
<code>[portfolio_slideshow exclude_featured=true]</code>
</p>

<p>
<strong>Loop the slideshow</strong>: 
</p>

<p>
<code>[portfolio_slideshow loop=true]</code>
</p>

<p>
or disable slideshow looping like this:
</p>

<p>
<code>[portfolio_slideshow loop=false]</code>
</p>


<p>
<strong>Clicking on a slideshow image:</strong>:
</p>
<p>Clicking on a slideshow image can advance the slideshow or open a custom URL (set in the media uploader):
<p>
<code>[portfolio_slideshow click=advance] or [portfolio_slideshow click=openurl] </code>
</p>

<p>
<strong>Navigation links</strong> can be placed at the top:
</p>

<p>
<code>[portfolio_slideshow navpos=top]</code>
</p>

<p>
or at the bottom:
</p>

<p>
<code>[portfolio_slideshow navpos=bottom]</code></p>

<p>Use <code>[portfolio_slideshow navpos=disabled]</code> to disable navigation altogether. Slideshow will still advance when clicking on slides, using the pager, or with autoplay.</p>


<p><strong>Pager (thumbnails) position</strong> can be selected:

e>[portfolio_slideshow pagerpos=top]</code> 
</p>

<p>
or at the bottom:
</p>

<p>
<code>[portfolio_slideshow pagerpos=bottom]</code></p> 

<p>or disabled :
</p>

<p>
<code>[portfolio_slideshow pagerpos=disabled]</code></p>
<p>


<p>
<strong>Include or exclude slides</strong>:
</p>

<p>
<code>[portfolio_slideshow include="1,2,3,4"]</code>
</p>

<p>
<code>[portfolio_slideshow exclude="1,2,3,4"]</code>
</p>

<p>You need to specify the attachment ID, which you can find in your <a href="<?php bloginfo( 'wpurl' )?>/wp-admin/upload.php">Media Library</a> by hovering over the thumbnail. You can only include attachments which are attached to the current post. Do not use these attributes simultaneously, they are mutually exclusive.</p>

<p>
<strong>Multiple slideshows per post/page</strong>:
</p>

<p>
You can insert as many slideshows as you want in a single post or page by using the include/exclude attributes,.</p>
</p>
</div>