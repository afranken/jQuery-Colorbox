=== Plugin Name ===
Contributors: techotronic
Donate link: http://www.techotronic.de/index.php/donate/
Tags: jquery, colorbox, lightbox, images, pictures, photos, gallery, javascript, overlay
Requires at least: 2.8.5
Tested up to: 2.9.1
Stable tag: 3.1

Adds Colorbox/Lightbox functionality to images. They are grouped by post or page. Also works for WordPress galleries. Comes with different themes.

== Description ==

Yet another Colorbox/Lightbox plugin for Wordpress.

jQuery Colorbox features 9 themes from which you can choose. See screenshots tab.

When adding an image to a post or page, usually a thumbnail is inserted and linked to the image in original size.
All images in posts and pages can be displayed in a layer when the thumbnail is clicked.
Images are grouped as galleries when linked in the same post or page. Groups can be displayed in an automatic slideshow.

Individual images can be excluded by adding a special CSS class.

See the <a href="http://www.techotronic.de/index.php/plugins/jquery-colorbox/">plugin page</a> for demo pages.

For more information visit the <a href="http://wordpress.org/extend/plugins/jquery-colorbox/faq/">FAQ</a>.
If you have questions or problems, feel free to write an email to blog [at] techotronic.de or write a entry at <a href="http://wordpress.org/tags/jquery-colorbox?forum_id=10">the jQuery Colorbox WordPress.org forum</a>

Localization

* English (en_EN) by <a href="http://www.techotronic.de/">Arne Franken</a>
* German (de_DE) by <a href="http://www.techotronic.de/">Arne Franken</a>
* Turkish (tr_TR) by <a href="http://www.serhatyolacan.com/">Serhat Yolaçan</a>
* Portuguese (pt_BR) by <a href="http://twitter.com/gervasioantonio">Gervásio Antônio</a>
* Bosnian (bs_BA) by <a href="http://www.vjucon.com/">Vedran Jurincic</a>
* French (fr_FR) by <a href="http://www.tolingo.com/">Tolingo Translations</a>

Is your native language missing? Translating the plugin is easy if you understand english and are fluent in another language. Just send me an email.

Includes <a href="http://colorpowered.com/colorbox/">ColorBox</a> 1.3.6 jQuery plugin from Jack Moore. Colorbox is licensed under the <a href="http://www.opensource.org/licenses/mit-license.php">MIT License</a>.
jQuery Colorbox uses the jQuery library version 1.3.2 bundled with Wordpress. Works with jQuery 1.4.2 too.
The picture I used for the screenshots was designed by <a href="http://wallpapers.vintage.it/">Davide Vicariotto</a>

== Installation ==

###Updgrading From A Previous Version###

To upgrade from a previous version of this plugin, delete the entire folder and files from the previous version of the plugin and then follow the installation instructions below.

###Installing The Plugin###

Extract all files from the ZIP file, making sure to keep the file structure intact, and then upload it to `/wp-content/plugins/`. Then just visit your admin area and activate the plugin. That's it!

###Configuring The Plugin###

Go to the settings page and choose one of the themes bundled with the plugin and other settings.
Do not forget to activate auto Colorbox if you want Colorbox to work for all images.

**See Also:** <a href="http://codex.wordpress.org/Managing_Plugins#Installing_Plugins">"Installing Plugins" article on the WP Codex</a>

== Screenshots ==

1. Theme #1
2. Theme #2
3. Theme #3
4. Theme #4
5. Theme #5
6. Theme #6 (not compatible with IE6)
7. Theme #7
8. Theme #8 (not compatible with IE6)
9. Theme #9

== Frequently Asked Questions ==
* I have installed and activated (or updated) jQuery Colorbox, but it doesn't show up when I click on a thumbnail in my blog. Is the plugin broken?

Since version 2.0, jQuery Colorbox' automatic behaviour can be switched on and off in the settings. That way, you can apply the Colorbox functionality manually to single images.

The default ist OFF.

New in 3.0: If the plugin is activated, but the auto Colorbox feature is turned off, a warning is displayed.

* How does jQuery Colorbox work?

When inserting a picture, the field "Link URL" needs to contain the link to the full-sized image. (press the button "Link to Image" below the field)
When rendering the blog, a special CSS class ("colorbox-postId", e.g. "colorbox-123") is added to linked images.
This CSS class is then passed to the colorbox JavaScript.

* How do I exclude an image from Colorbox in a page or post?

Add the CSS class "colorbox-off" to the image you want to exclude.
jQuery Colorbox does not add the colorbox effect to images that have the CSS class "colorbox-off".

* How does jQuery Colorbox group images?

For all images in a post or page, the same CSS class is added. All images with the same CSS class are grouped.

* I have Flash (e.g. Youtube videos) embedded on my website. Why do they show up over the layer when I click on an image?

This is a Flash issue, but relatively easy to solve. Just activate the automatic hiding of embedded flash objects on the settings page.

Adobe described on these sites what the problem is and how to fix it manually:
<a href="http://kb2.adobe.com/cps/155/tn_15523.html">Adobe Knowledgebase 1</a>
<a href="http://kb2.adobe.com/cps/142/tn_14201.html">Adobe Knowledgebase 2</a>

* I installed your plugin, but when I click on a thumbnail, the original picture is loaded directly instead of in the Colorbox. What could be the problem?

Make sure that your theme uses the `wp_head();` function in the HTML head-tag.

I have seen problems where other plugins include their own versions of the jQuery library my plugin uses.
Chances are that the other jQuery libary is loaded after the one that I load. If that happens, the colorbox features are overwritten.

Maybe the images you want jQuery Colorbox to work on are added by a plugin and the images are added after jQuery Colorbox manipulates the HTML when rendering your blog.

Sometimes I have seen Images without the "class" attribute. If there is no "class" attribute present in the IMG-Tag, jQuery Colorbox can't add the necessary CSS class and won't work on that image.

* Why is jQuery Colorbox not available in my language?

I speak German and English fluently, but unfortunately no other language well enough to do a translation.

Would you like to help? Translating the plugin is easy if you understand English and are fluent in another language.

* My question isn't answered here. What do I do now?

Feel free to write an email to blog [at] techotronic.de or write a entry at <a href="http://wordpress.org/tags/jquery-colorbox?forum_id=10">the jQuery Colorbox WordPress.org forum</a>.

I'll include new FAQs in every new version. Promise.

== Changelog ==
= 3.1 (2010-04-10) =
* BUGFIX: Automatic hiding of embedded flash objects under Colorbox layer now works in Internet Explorer.
* NEW: Added theme#9, a modified version of theme#4.
* NEW: French translation by <a href="http://www.tolingo.com/">Tolingo Translations</a>
* NEW: If auto colorbox is switched on, plugin now adds Colorbox functionality to every image regardless of position
* CHANGE: <a href="http://www.serhatyolacan.com/">Serhat Yolaçan</a> updated the turkish translation.

= 3.0.1 (2010-03-31) =
* BUGFIX: Settings are NOW REALLY not overridden any more every time the plugin gets activated.

= 3.0 (2010-03-31) =
* NEW: Decided to move from 2.0.1 to 3.0 because I implemented many new features
* BUGFIX: Slideshow speed setting works now.
* BUGFIX: Settings are not overridden any more every time the plugin gets activated.
* BUGFIX: jQuery Colorbox now works again for links with uppercase suffixes (IMG,JPG etc) thx to Jason Stapels (jstapels@realmprojects.com) for the bug report and fix!
* NEW: Added theme#6, a modified version of theme#1. (not compatible with IE6) thx to <a href="http://twitter.com/gervasioantonio">Gervásio Antônio</a> for all the hard work!
* NEW: Added theme#7, a modified version of theme#1. thx to <a href="http://www.vjucon.com/">Vedran Jurincic</a> for the feature request!
* NEW: Added theme#8, a modified version of theme#6.
* NEW: Added screenshots of all themes, screenshot of selected theme is shown in admin menu
* NEW: Added warning if the plugin is activated but not set to work for all images on the blog. Warning can be turned off on the settings page.
* NEW: Added setting for automatic hiding of embedded flash objects under Colorbox layer. Default: false
* NEW: Added switch for preloading of "previous" and "next" images. Default: false
* NEW: Added switch for closing of Colorbox on click on opacity layer. Default: false
* NEW: Added setting for transition type. Default: elastic
* NEW: Added setting for transition speed. Default: 250 milliseconds
* NEW: Added setting for overlay opacity. Default: 0.85
* CHANGE: Fixed fonts in Colorbox to Arial 12px
* NEW: Turkish translation by <a href="http://www.serhatyolacan.com/">Serhat Yolaçan</a>
* NEW: Portuguese translation by <a href="http://twitter.com/gervasioantonio">Gervásio Antônio</a>
* NEW: Bosnian translation by <a href="http://www.vjucon.com/">Vedran Jurincic</a>
* NEW: Plugin should be WPMU compatible now. Haven't tested myself, though. Would appreciate any feedback.
* NEW: Successfully tested jQuery Colorbox with jQuery 1.4.2
* CHANGE: Fixed "slideshow" offset from right hand side in the Colorbox of theme#4

= 2.0.1 (2010-02-11) =
* BUGFIX: slideshow does not start automatically any more if the setting is not checked on the settings page.

= 2.0 (2010-02-11) =
* NEW: Decided to move from 1.3.3 to 2.0 because I implemented many new features.
* BUGFIX: fixed relative paths for theme1 and theme4 by adding the CSS for the Internet Explorer workaround directly into the page. Thx to <a href="http://www.deepport.net/">Andrew Radke</a> for the suggestion!
* NEW: switch adding of "colorbox-postId" classes to images in posts and pages on and off through setting. Default: off.
* NEW: now works for images outside of posts (e.g. sidebar or header) if CSS class "colorbox-manual" is added manually
* NEW: jQuery Colorbox now working for WordPress attachment pages
* NEW: Added switch that adds slideshow functionality to all Colorbox groups. (no way to add slideshows individually yet)
* NEW: Added switch that adds automatic start to slideshows (no way to add slideshows individually yet)
* NEW: Added configuration of slideshow speed
* NEW: Added switch that allows the user to decide whether Colorbox scales images
* NEW: Added demos of the plugin on the <a href="http://www.techotronic.de/index.php/plugins/jquery-colorbox/">plugin page</a>
* NEW: Added configuration for adding colorbox class only to WordPress galleries
* NEW: Automatically resets settings if settings of a version prior to 1.4 are found upon activation
* NEW: width and height can now be configured as percent relative to browser window size or in pixels (default is percent)
* CHANGE: jQuery Colorbox is now only working on Image links (of type jpeg, jpg, gif, png, bmp)
* CHANGE: Improved translation. Thx to <a href="http://usability-idealist.de/">Fabian Wolf</a> for the help!
* CHANGE: updated the <a href="http://wordpress.org/extend/plugins/jquery-colorbox/faq/">FAQ</a>
* CHANGE: Updated readme.
* CHANGE: Updated descriptions and translations

= 1.3.3 (2010-01-21) =
* BUGFIX: fixed settings page, options can be saved now
* NEW: added settings deletion on uninstall and "delete settings from database" functionality to settings page
* CHANGE: moved adding of CSS class priority lower, hopefully now the CSS class is added to pictures after other plugins update the HTML
* CHANGE: updated the <a href="http://wordpress.org/extend/plugins/jquery-colorbox/faq/">FAQ</a>

= 1.3.2 (2010-01-19) =
* CHANGE: moved back to regexp replacement and implemented a workaround in the JavaScript to still allow images to be excluded by adding the class "colorbox-off"

= 1.3.1 (2010-01-18) =
* CHANGE: changed include calls for Colorbox JavaScript and CSS to version 1.3.6
* CHANGE: optimized modification of the_content

= 1.3 =
* NEW: jQuery-Colorbox won't add Colorbox functionality to images that have the CSS class "colorbox-off"
* CHANGE: Updated Colorbox version to 1.3.6
* CHANGE: should be compatible to jQuery 1.4, still using 1.3.2 at the moment because it is bundled in WordPress 2.9.1
* CHANGE: changed the way that the Colorbox CSS class is added to images to be more reliable
* CHANGE: changed layout of settings page
* CHANGE: updated the <a href="http://wordpress.org/extend/plugins/jquery-colorbox/faq/">FAQ</a>

= 1.2 =
* BUGFIX: fixes bug where colorbox was not working if linked images were used (by the theme) outside of blog posts and pages.
* NEW: adds configuration for Colorbox and picture resizing

= 1.1 =
* BUGFIX: fixes critical bug which would break rendering the blog. Sorry, was not aware that the plugin would be listed before I tagged the files as 1.0 in subversion...

= 1.0 =
* NEW: Initial release.
* NEW: Added Colorbox version 1.3.5