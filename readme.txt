=== Portfolio Slideshow ===
Contributors: ggwicz, daltonrooney 
Tags: slideshow, responsive, gallery, images, photos, photographs, portfolio, jquery, cycle, mobile, iphone, slider
Requires at least: 4.1
Tested up to: 4.2.4
Stable tag: 1.10.0

Add a clean, responsive JavaScript slideshow to your site.

== Description ==

><strong>Requirements:</strong> PHP 5.3 or higher is required to use this plugin.
>
><strong>Support:</strong> I'll do my best to help troubleshoot bugs, but should set expectations early that customer support is not provided for this plugin at this time.

Portfolio Slideshow adds a simple slideshow builder to posts and pages on your site. Upload images there, drag them around to order them as desired, and publish the post or page.

Then just drop the handy `[portfolio_slideshow]` shortcode anywhere in that post or page, and voila – a simple, customizable slideshow will appear on your site.

More information about this plugin is coming soon – as is version 1.10.0, which is already full of many improvements. Stay tuned!

== Installation ==

Extract the zip file and upload the contents to the wp-content/plugins/ directory of your WordPress installation and then activate the plugin from plugins page. 

The settings & reference page for the plugin is in "Settings → Portfolio Slideshow" 

To use the plugin, upload your photos to your post or page using the "Upload and Manage Images" button in the Portfolio Slideshow metabox. Use the [portfolio_slideshow] shortcode to display the slideshow in your page or post (see screenshots for an example).

By default, the slideshow will use the medium version of the image that WordPress generates when you upload an image. You can change this default in the Settings panel or on a per-slideshow basis. The image sizes available are  "thumbnail", "medium", "large", and "full". 

**The shortcode supports the following attributes:**

If you would like to customize your slideshows on a per-slideshow basis, you can add the following attributes to the shortcode, which will temporarily override the defaults.

To select a different page parent ID for the images:

[portfolio_slideshow id=xxx]

To change the image size you would use the size attribute in the shortcode like this:

[portfolio_slideshow size=thumbnail], [portfolio_slideshow size=medium], [portfolio_slideshow size=large], [portfolio_slideshow size=full]

This setting can use any custom image size that you've registered in WordPress.

You can add a custom slide container height:

[portfolio_slideshow slideheight=400]

Useful if you don't want the page height to adjust with the slideshow.

Image transition FX:

[portfolio_slideshow trans=scrollHorz]

You can use this shortcode attribute to supply any transition effect supported by jQuery Cycle, even if they're not in the plugin! List of supported transitions here Not all transitions will work with all themes, if in doubt, stick with fade or none.

Transition speed:

[portfolio_slideshow speed=400]

Add a delay to the beginning of the slideshow:


[portfolio_slideshow showtitles=true], [portfolio_slideshow showcaps=true], [portfolio_slideshow showdesc=true] (use false to disable)

Time per slide when slideshow is playing (timeout):

[portfolio_slideshow timeout=4000]

Autoplay:

[portfolio_slideshow autoplay=true]

Exclude featured image:

[portfolio_slideshow exclude_featured=true]

Disable slideshow wrapping:

[portfolio_slideshow nowrap=true]

or enable it like this:

[portfolio_slideshow nowrap=false]

Clicking on a slideshow image::

Clicking on a slideshow image can advance the slideshow or open a custom URL (set in the media uploader):

[portfolio_slideshow click=advance] or [portfolio_slideshow click=openurl]

Navigation links can be placed at the top:

[portfolio_slideshow navpos=top]

or at the bottom:

[portfolio_slideshow navpos=bottom]

Use [portfolio_slideshow navpos=disabled] to disable navigation altogether. Slideshow will still advance when clicking on slides, using the pager, or with autoplay.

Pager (thumbnails) position can be selected: [portfolio_slideshow pagerpos=top]

or at the bottom:

[portfolio_slideshow pagerpos=bottom]

or disabled :

[portfolio_slideshow pagerpos=disabled]

Include or exclude slides:

[portfolio_slideshow include="1,2,3,4"]

[portfolio_slideshow exclude="1,2,3,4"]

You need to specify the attachment ID, which you can find in your Media Library by hovering over the thumbnail. You can only include attachments which are attached to the current post. Do not use these attributes simultaneously, they are mutually exclusive.

Multiple slideshows per post/page:

You can insert as many slideshows as you want in a single post or page by using the include/exclude attributes,

[portfolio_slideshow include="1,2,3"]

[portfolio_slideshow include="4,5,6"]

This example will create two slideshows on the page with two sets of images. Remember, the attachment ID can be found in your *Media Library* by hovering over the thumbnail. You can only include attachments which are attached to the current post.

**Additional features from the settings page**

Autoplay: Where timeout equals the time per slide in milliseconds. Leave this set to 0 for the default manual advance slideshow.

Allow links to external URLs: By checking this box, you can enable a custom field in the photo gallery manager to hold a URL - for example, if you want your slide to link to a portfolio page or to an external site. This disables the "click slide to advance feature" and will cause problems if you've got anything but a URL in the that field, so use it wisely.

Disable slideshow wrapping: By default, a slideshow can continue cycling indefinitely; that is, if you get to the last slide, clicking "Next" will take you back to the first slide. You can disable this behavior with this setting.

Update URL with slide numbers: 

On single posts and pages, you can enable this feature to udpate the URL of the page with the slide number. Example: http://example.com/slideshow/#3 will link directly to the third slide in the slideshow.


== Frequently Asked Questions ==

= Q: How do I insert a slideshow into a post or page? =

A: Upload your photos to the post or page using the "Add Slides" metabox. You can drag-n-drop images into the uploader that pops up, add captions and descriptions to these images, etc.

Then, simply add the shortcode `[portfolio_slideshow]` where you want the slideshow to appear in the page, and publish the post. Nice!

One common mistake is to insert the images into the post using the content editor. This is not necessary – the plugin detects all images attached to the post and creates the slideshow automatically. 

= Q: Does the plugin support images that are not uploaded via the media uploader? =

A: No, the plugin does not support random folders of images or images on a third-party site at this time.

= Q: Why isn't my slideshow loading? =

A: If the images show up fine in the "Add Slides" metabox in your admin, but don't on the front-end, this could be a theme conflict. Your best option is to inspect the page with a browser console open, and/or to set `WP_DEBUG` to `true` in your site's `wp-config.php` file to see if any PHP errors show up on your site.

If you can see the first slide of the slideshow, but clicking doesn't do anything, this is almost always a JavaScript conflict from your theme (or another plugin). It could caused by a jQuery library conflict. View the HTML source of the page which is supposed to show the slideshow. Do you see more than one copy of jQuery or the Cycle plugin being loaded? If your theme or other plugins load those same files directly, you will have a conflict. Your best option here is to manually dequeue conflicting scripts. Check out [the `wp_dequeue_script()` function](https://codex.wordpress.org/Function_Reference/wp_dequeue_script) for more information on doing so.

= Q: I haven't found multiple versions of a conflicting file. In fact, I can't see jQuery Cycle or Portfolio Slideshow's CSS loading at all! What gives? =

If you don't see the jQuery Cycle plugin or Portfoliio Slideshow JavaScript and CSS files being loaded on posts and pages with the `[portfolio_slideshow]`, then 99 times out of 100 this means that your theme is not using the required WordPress theme functions `wp_head()` and/or `wp_footer()`. These functions are *required* for almost all plugins to work correctly, not just Portfolio Slideshow. Consider the lobster. Then consider using a different theme.

= Q: How do I change the size of the images? =

A: By default, the slideshow uses the large-size images that are generated by WordPress when you upload an image. You can change this default in the settings panel for the plugin, or on a per-page basis using the size attribute (see installation instructions for usage).

If you would like to change the size of the images system-wide (for example, you want a large image to be 800px instead of 1025px) you can change the WordPress settings in the "Settings -> Media" control panel. You will need to regenerate your thumbnails to make the setting retroactive. If you're in this situation, I cannot recommend the aptly named Regenerate Thumbnails plugin enough! [Download it here](https://wordpress.org/plugins/regenerate-thumbnails/). I have used that on sites with thousands and thousands of large images, and it works wonders.

== Upgrade Notice ==

= 1.10.0 =

Some users have reported their slideshows not working after updating. While you should backup your site before installing or updating *any* theme or plugin, it's important to note that your slideshow data *is not gone*. It's just not showing up.

If you encounter issues like this, your best option is to temporarily downgrade to version 1.5.1, [which you can download here](https://wordpress.org/plugins/portfolio-slideshow/developers/).

== Screenshots ==

1. Example gallery.

2. Use the "Upload and Manage Images" button to add, sort, and delete your photos.

3. Insert the slideshow by using this shortcode, exactly as shown. Do not insert the photos into the post.

4. Settings control panel.

5. Finding the attachment ID for your images.

6. Adding an external URL to a slide.

== Changelog ==

= 1.10.0 =

* FIX: A few fixes to address the retrieval of slides, which should mean your pre-1.9.9 slideshows will work fine in many more cases than with the 1.9.9 release itself.

* FIX: Fixed some "Undefined Index" PHP notices with a few slideshow arguments.

* FIX: Removal of unnecessary "protected" access on several class methods and properties.

* FIX: Removal of a handful of unnecessary JavaScript and CSS files that could cause 404 errors on pages if loaded.

= 1.9.9 =

* Ported the existing plugin to PHP 5.3-compatible code and laid the foundation for some major changes in the next few versions: 1.10.x, and then  2.0.0

For the archived changelog for versions 1.5.1 and below, please see http://portfolioslideshow.com/changelog.