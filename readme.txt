=== Plugin Name ===
Contributors: techotronic
Donate link: http://www.techotronic.de/index.php/donate/
Tags: jquery, colorbox, lightbox, images, gallery, javascript, overlay
Requires at least: 2.8.5
Tested up to: 2.8.6
Stable tag: 1.1

Automatically adds Colorbox/Lightbox functionality to all images on the blog. Images are grouped by post.

== Description ==

Yet another Colorbox plugin for Wordpress.

All images on the blog are displayed in a layer when clicked. Images are grouped as galleries when linked in the same blogpost.

Includes <a href="http://colorpowered.com/colorbox/">ColorBox</a> 1.3.5 jQuery plugin from Jack Moore. Colorbox is licensed under the <a href="http://www.opensource.org/licenses/mit-license.php">MIT License</a>.
jQuery Colorbox uses the jQuery library bundled with Wordpress.

Localization

* English (en_EN)
* German (de_DE)

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
* How does jQuery Colorbox group images?

When rendering content, a special colorbox-class is added to linked images. The class is then passed to colorbox.

== Changelog ==

= 1.1 =
* fixes critical bug which would break rendering the blog. Sorry, was not aware that the plugin would be listed before I tagged the files as 1.0 in subversion...

= 1.0 =
* Initial release.