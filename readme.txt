=== Portfolio Slideshow ===
Contributors: ggwicz, daltonrooney 
Tags: slideshow, responsive, gallery, images, photos, photographs, portfolio, jquery, cycle, mobile, iphone, slider
Requires at least: 4.1
Tested up to: 4.2.4
Stable tag: 1.9.9

Add a clean, responsive JavaScript slideshow to your site.

><strong>Requirements:</strong> PHP 5.3 or higher is required to use this plugin.
><strong>Support:</strong> I'll do my best to help troubleshoot bugs, but should set expectations early that customer support is not for this plugin at this time.

More information about this plugin – and version 1.9.9 – is coming soon.

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

Q: How do I insert a slideshow into a post or page?

A: Upload your photos to the post or page using the media uploader. The media uploader also allows you to assign titles and captions, sort, and delete photos. Then add the shortcode [portfolio_slideshow] where you want the slideshow to appear in the page. See screenshots 2 and 3 for an example. [Here's a video](http://www.youtube.com/watch?v=K1mNLv4GfgU) of how to upload images in WordPress 3.5.

One common mistake is to insert the images into the post using the content editor. This is not necessary--the plugin detects all images attached to the post and creates the slideshow automatically. 


Q: Does the plugin support images that are not uploaded via the media uploader?

A: No, the plugin does not support random folders of images or images on a third-party site. All images must be uploaded using the media uploader, which creates the database entries the plugin relies on to generate the slideshow. This behavior will not change in future versions of the plugin.


Q: Why isn't my slideshow loading?

A: If you can see the first slide, but clicking doesn't do anything, this is often caused by a jQuery library conflict. View the HTML source of the page which is supposed to show the slideshow. Do you see more than one copy of jQuery or the Cycle plugin being loaded? This plugin uses the wp_enqueue() function to load the necessary javascript libraries, which is the generally accepted way to do it. If your theme or other plugins load those same files directly, you will have a conflict.

Try disabling other plugins and switching to the default theme and see if that fixes the problem. You may need to get in touch with the author of that plugin to make sure they are loading jQuery correctly.

One other problem that I've seen is the missing "cycle" plugin. View your source to see if "jquery.cycle.all.min.js" is being loaded. If not, make sure your theme has the line <?php wp_footer() ?> in footer.php, which is where the cycle script is loaded. All themes should have this line, but every once in a while it goes missing.

Q: How do I change the size of the images?

A: By default, the slideshow uses the large-size images that are generated by WordPress when you upload an image. You can change this default in the settings panel for the plugin, or on a per-page basis using the size attribute (see installation instructions for usage).

If you would like to change the size of the images system-wide (for example, you want a large image to be 800px instead of 1025px) you can change the WordPress settings in the "Settings -> Media" control panel. You will need to regenerate your thumbnails to make the setting retroactive.

== Upgrade Notice ==

= 1.5 =
WordPress 3.5 removed the ability to sort your attached images so we've added a custom metabox to bring back this functionality. We'll be adding more flexible media management tools in Portfolio Slideshow 2.0!

== Screenshots ==

1. Example gallery.

2. Use the "Upload and Manage Images" button to add, sort, and delete your photos.

3. Insert the slideshow by using this shortcode, exactly as shown. Do not insert the photos into the post.

4. Settings control panel.

5. Finding the attachment ID for your images.

6. Adding an external URL to a slide.

== Changelog ==

1.9.9

* Ported the existing plugin to PHP 5.3-compatible code and laid the foundation for some major changes in the next few versions: 1.10.x, and then  2.0.0

For the archived changelog for versions 1.5.1 and below, please see http://portfolioslideshow.com/changelog.