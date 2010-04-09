/*
 * jQuery Colorbox Wordpress Plugin.
 *
 * @author Arne Franken
 *
 * handles automatic hiding of flash object and embed tags
 */
jQuery(document).ready(function($) {
    $(document).bind('cbox_open', function(){
        var flashObjects = document.getElementsByTagName("object");
        for (i = 0; i < flashObjects.length; i++) {
            flashObjects[i].style.visibility = "hidden";
        }
        var flashEmbeds = document.getElementsByTagName("embed");
        for (i = 0; i < flashEmbeds.length; i++) {
            flashEmbeds[i].style.visibility = "hidden";
        }
    });
    $(document).bind('cbox_closed', function(){
        var flashObjects = document.getElementsByTagName("object");
        for (i = 0; i < flashObjects.length; i++) {
            flashObjects[i].style.visibility = "visible";
        }
        var flashEmbeds = document.getElementsByTagName("embed");
        for (i = 0; i < flashEmbeds.length; i++) {
            flashEmbeds[i].style.visibility = "visible";
        }
    });
});