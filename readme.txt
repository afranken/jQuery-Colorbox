=== Plugin Name ===
Contributors: techotronic
Donate link: http://www.techotronic.de/index.php/donate/
Tags: jquery, colorbox, lightbox, images, gallery, javascript, overlay
Requires at least: 2.8.5
Tested up to: 2.9.1
Stable tag: 1.3.1

Automatically adds Colorbox/Lightbox functionality to all images on the blog. Images are grouped by post.

== Description ==

Yet another Colorbox plugin for Wordpress.

When adding an image to a post or page, usually a thumbnail is inserted and linked to the image in original size.
All images in posts and pages are displayed in a layer when the tumbnail is clicked. 
Images are grouped as galleries when linked in the same blog post or page.

Images can be excluded by giving them a special CSS class.

For more information visit the <a href="http://wordpress.org/extend/plugins/jquery-colorbox/faq/">FAQ</a>.

Localization

* English (en_EN) by <a href="http://www.techotronic.de/">Arne Franken</a>
* German (de_DE) by <a href="http://www.techotronic.de/">Arne Franken</a>

Includes <a href="http://colorpowered.com/colorbox/">ColorBox</a> 1.3.6 jQuery plugin from Jack Moore. Colorbox is licensed under the <a href="http://www.opensource.org/licenses/mit-license.php">MIT License</a>.
jQuery Colorbox uses the jQuery library bundled with Wordpress.

== Demo ==

Click on any image on [My Blog](http://www.techotronic.de/) to see jQuery Colorbox in action.

== Installation ==

###Updgrading From A Previous Version###

To upgrade from a previous version of this plugin, delete the entire folder and files from the previous version of the plugin and then follow the installation instructions below.

###Installing The Plugin###

Extract all files from the ZIP file, making sure to keep the file structure intact, and then upload it to `/wp-content/plugins/`. Then just visit your admin area and activate the plugin. That's it!

###Configuring The Plugin###

Go to the settings page and choose one of the five themes bundled with the plugin.

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== Frequently Asked Questions ==
* How does jQuery Colorbox work?

When inserting a picture, the field "Link URL" needs to contain the link to the full-sized image. (press the button "Link to Image" below the field)
When rendering the blog, a special CSS class ("colorbox-postId", e.g. "colorbox-123") is added to linked images.
This CSS class is then passed to the colorbox JavaScript.

* How do I exclude an image?

Add the CSS class "colorbox-off" to the image you want to exclude.
jQuery Colorbox does not add the colorbox CSS class to images that already contain the CSS class "colorbox-off".

* How does jQuery Colorbox group images?

For all images in a post or page, the same CSS class is added. All images with the same CSS class are grouped.

== Changelog ==

= 1.3.1 =
* changed include calls for Colorbox JavaScript and CSS to version 1.3.6
* optimized modification of the_content

= 1.3 =
* jQuery-Colorbox won't add Colorbox functionality to images that have the CSS class "colorbox-off"
* Updated Colorbox version to 1.3.6
* should be compatible to jQuery 1.4, still using 1.3.2 at the moment because it is bundled in WordPress 2.9.1
* changed the way that the Colorbox CSS class is added to images to be more reliable
* changed layout of settings page
* updated the <a href="http://wordpress.org/extend/plugins/jquery-colorbox/faq/">FAQ</a>

= 1.2 =
* fixes bug where colorbox was not working if linked images were used (by the theme) outside of blog posts and pages.
* adds configuration for Colorbox and picture resizing

= 1.1 =
* fixes critical bug which would break rendering the blog. Sorry, was not aware that the plugin would be listed before I tagged the files as 1.0 in subversion...

= 1.0 =
* Initial release.
* Added Colorbox version 1.3.5