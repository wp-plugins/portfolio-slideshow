=== Portfolio Slideshow ===
Contributors: daltonrooney 
Donate link: http://madebyraygun.com/donate/
Tags: slideshow, gallery, images, photos, photographs, portfolio, jquery, cycle, indexexhibit
Requires at least: 3.0
Tested up to: 3.0.6
Stable tag: 1.0.0

A shortcode that inserts a clean and simple jQuery + cycle powered slideshow of all image attachments on a post or page. Degrades gracefully for users without javascript.

== Installation ==

Extract the zip file and upload the contents to the wp-content/plugins/ directory of your WordPress installation and then activate the plugin from plugins page. 

The settings & reference page for the plugin is in "Settings -> Portfolio Slideshow" 

To use the plugin, upload your photos to your post or page using the WordPress media uploader. Use the [portfolio_slideshow] shortcode to display the slideshow in your page or post (see screenshots for an example).

By default, the slideshow will use the large version of the image that WordPress generates when you upload an image. You can change this default in the Settings panel or on a per-slideshow basis. The image sizes available are  "thumbnail", "medium", "large", and "full".

**The shortcode supports the following attributes:**

Image size on a per-slideshow basis. Use the size attribute in the shortcode like this:

[portfolio_slideshow size=thumbnail]

Navigation thumbnails can be displayed:

[portfolio_slideshow thumbs=true] or [portfolio_slideshow thumbs=false]

Navigation links can be placed at the bottom:

[portfolio_slideshow nav=bottom] 

Use [portfolio_slideshow nav=false] to disable navigation altogether. Slideshow will still advance with click or autoplay.

Include or exclude

[portfolio_slideshow include="1,2,3,4"]

[portfolio_slideshow exclude="1,2,3,4"]

You need to specify the attachment ID, which you can find in "Media -> Library" by hovering over the thumbnail. You can only include attachments which are attached to the current post.
Do not use these attributes simultaneously, they are mutually exclusive.

Multiple slideshows per post/page:

you can insert multiple slideshows per post/page by including different attachment ids in your shortcode. Example:

[portfolio_slideshow include="1,2,3"]

[portfolio_slideshow include="4,5,6"]

This example will create two slideshows on the page with two sets of images. Remember, the attachment ID can be found in your *Media Library* by hovering over the thumbnail. You can only include attachments which are attached to the current post.

**Additional features from the settings page**

Autoplay: Where timeout equals the time per slide in milliseconds. Leave this set to 0 for the default manual advance slideshow.

Description links image to URL: By checking this box, you can use the image description field to hold a URL - for example, if you want your slide to link to a portfolio page or to an external site. This disables the "click slide to advance feature" and will cause problems if you've got anything but a URL in the description field, so use it wisely.

Update URL with slide numbers: 

On single posts and pages, you can enable this feature to udpate the URL of the page with the slide number. Example: http://example.com/slideshow/#3 will link directly to the third slide in the slideshow.


== Frequently Asked Questions ==


Q: How do I insert a slideshow into a post or page?

A: Upload your photos to the post or page using the media uploader. The media uploader also allows you to assign titles and captions, sort, and delete photos. Then add the shortcode [portfolio_slideshow] where you want the slideshow to appear in the page. See screenshots 2 and 3 for an example.

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


Q: Can you modify the plugin to do X, Y, or Z?

A: I won't be adding any new features until 1.0 is released, sometime in early 2011. If you have ideas I'll happily accept them, but they won't be considered for the plugin until after 1.0 is complete.

== Screenshots ==

1. Example gallery.

2. Use the media uploader to add, sort, and delete your photos.

3. Insert the slideshow by using this shortcode, exactly as shown. Do not insert the photos into the post.

4. Settings control panel.
5. Finding the attachment ID for your images.

== Changelog ==


1.0.0

* Removed most inline Javascript.
* Refactored a lot of PHP code.
* Autoplay no longer supported on a per/slideshow basis. Enable/disable via options panel only.
* Reorganized options panel a bit.

0.6.0 

* Change how jQuery is loaded so error is not generated on HTTPS sites.
* Fixed height calculation bug for first slide
* Fixed overlapping text during transitions for captions and descriptions.
* Fixed loading.gif display in Chrome & Firefox when scrollHorz transition is enabled.
* Added noscript stylesheet so slideshow degrades gracefully to for users without javascript.
* Improved documentation

0.5.9.2 

* Bugfix for URL hashes showing up on single pages even when disabled in settings.


* Cleaned up SVN repository

0.5.9.1 



* Fixed a totally embarrassing bug that was causing jQuery not to load in certain situations.


0.5.9



* Multiple slideshows now possible on a single post/page.


0.5.8 

* Bugfix: nav="false" hiding slideshow
* Bugfix: Option to disable URL hash updating was not working properly
* Bugfix: No longer applying the content filter to slideshow output, as it was interfering with other plugins.


0.5.7 

* Bugfix: new loading gif not working when slideshow navigation is at the bottom.

0.5.6 



* Bugfix: Cycle plugin not loading properly.

0.5.5 

* Fixed improper jQuery loading on admin introduced in previous version.

0.5.3

* Added an option to add a loading gif if your slideshows take a little while to load.

* Disabled transition "none", because of a bug in the cycle plugin. Will be added back in when the author of that plugin fixes the bug. Use the fade transition with a speed of 1ms to simulate the "none" transition.



* Added an option for "Description" field to hold a URL that links the image to an external site.



* Removed some negative padding from the auto-rezise slideshow container calculation. This may cause some themes to display too much space between the image & the page content - if so, you can change the padding via CSS.


0.5.1 

* Option to disable URL updating (slide number hashes in URL)

0.5

* Thumbnails, slide numbers, and autoplay now work on all pages, including index (homepage) & category pages.

* Slideshow content area is dynamically resized to conform to actual size of content.

* Fixed display bug with TwentyTen theme

* Fix for bug introduced in 0.4.3 which broke some slideshows.

* Upgraded to latest version of cycle plugin

* Support for descriptions


0.4.3



* Links in captions are now clickable.



* Documented nav="false" attribute.


0.4.2

* Include and exclude attributes for shortcode. Thanks to Raoni Del Persio from Central WordPress http://www.centralwordpress.com.br for sponsoring this feature.

0.4.1

* Teeny tiny bugfix

0.4.0

* Moved styles to external file instead of loading them inline and fixed validation issue

* Fixed titles display

* Added autoplay option to options panel 

* Added nav position option to shortcode and options panel

* Thumbnails! Enable them in the shortcode or the options panel. 

0.3.10


* Continue to improve the way jQuery is loaded so it is compatible with the most number of plugin/theme combinations. 

0.3.9 

* Bugfix

0.3.8

* Improved compatibility with other cycle based plugins and themes.

* Code cleanup.

0.3.7 

* Clarified FAQs and added additional screenshots

* Added ability to hide donation request

* Added autoplay support via shortcode attribute "timeout", defaults to 0. (Thanks Rino3000 in the WP forums for the idea)

* Added capability to turn off titles and captions by default

* Eliminated the flash of unstyled content that is sometimes shown if a page is slow to load.

0.3.6

* Added the size attribute to the shortcode so you can select the size of the images on a per-slideshow basis. 

* Added image permalinks so you can link to a specific image in the slideshow. (Pages and single posts only)

* Added support for slide numbering on single posts as well as pages.

0.3.5 

* Fixed a bug where the slideshow was always at the top of the page, no matter where it is supposed to be in the content editor.

0.3.4

* Slideshow navigation no longer appears in the RSS feed. Images are embedded in the feed sequentially.

* Added configuration settings for transition fx and transition time.

* Added status notification in plugin upgrade area

* Added support for slide info (slide number). Works for pages only.

0.3.3 

* Fixed issue where multiple slideshows on the same page did not advance properly.

0.3.2 

* Javascript & typo fix

0.3.1 

* Small javascript fix

0.3  

* Added settings panel to select image size. 
* Added support for image titles as well as captions.

* Fixed small issue related to slideshow order if the menu order isn't explicity set.


0.2: 

* First public release.



0.1: 

* Use the included WordPress version of jQuery and properly load it with enqueue.