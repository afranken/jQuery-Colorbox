/*
 * @package Techotronic
 * @subpackage jQuery Colorbox
 *
 * @since 3.1
 * @author Arne Franken
 *
 * adds colorbox-manual to ALL img tags that are found in the HTML output
 */
jQuery(document).ready(function($) {
    $("img").each( function(index,obj){
        $classValue = $(obj).attr("class");
        if(!$classValue){
            $(obj).attr("class","colorbox-manual");
        }
        if(!$classValue.match('colorbox')) {
            $classValue += " colorbox-manual";
            $(obj).attr("class",$classValue);
        }
    });
});