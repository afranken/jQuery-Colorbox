<?php
/**
 * @package Techotronic
 * @subpackage jQuery Colorbox
 *
 * @since 3.2
 * @author Arne Franken
 * @author Fabian Wolf (http://usability-idealist.de/)
 * @author Jason Stapels (jstapels@realmprojects.com)
 *
 * Colorbox Javascript
 */
?>
<script type="text/javascript">
    // <![CDATA[
    jQuery(document).ready(function($) {
    <?php //gets all "a" elements that have a nested "img" ?>
        $("a:has(img):not(.colorbox-off)").each(function(index, obj) {
        <?php //only go on if link points to an image ?>
            if ($(obj).attr("href").match(/\.(?:jpe?g|gif|png|bmp)/i)) {
            <?php //in this context, the first child is always an image if fundamental Wordpress functions are used ?>
                var $nestedElement = $(obj).children(0);
                if ($nestedElement.is("img")) {
                    var $nestedElementClassAttribute = $nestedElement.attr("class");
                <?php //either the groupId has to be the automatically created colorbox-123 or the manually added colorbox-manual ?>
                    var $groupId = $nestedElementClassAttribute.match('colorbox-[0-9]+') || $nestedElementClassAttribute.match('colorbox-manual');
                <?php //only call Colorbox if there is a groupId for the image and the image is not excluded ?>
                    if ($groupId && !$nestedElementClassAttribute.match('colorbox-off')) {
                    <?php //convert groupId to string for easier use ?>
                        $groupId = $groupId.toString();
                    <?php  //if groudId is colorbox-manual, set groupId to "nofollow" so that images with that class are not grouped ?>
                        if ($groupId == "colorbox-manual") {
                            $groupId = "nofollow";
                        }
                    <?php  //call Colorbox function on each img. elements with the same groupId in the class attribute are grouped
                    //the title of the img is used as the title for the Colorbox. ?>
                        $(obj).colorbox({
                            rel:$groupId,
                            title:$nestedElement.attr("title"),
                            <?php echo $this->colorboxSettings['maxWidth'] == "false" ? '' : 'maxWidth:"' . $this->colorboxSettings['maxWidthValue'] . $this->colorboxSettings['maxWidthUnit'] . '",';
                            echo $this->colorboxSettings['maxHeight'] == "false" ? '' : 'maxHeight:"' . $this->colorboxSettings['maxHeightValue'] . $this->colorboxSettings['maxHeightUnit'] . '",';
                            echo $this->colorboxSettings['height'] == "false" ? '' : 'height:"' . $this->colorboxSettings['heightValue'] . $this->colorboxSettings['heightUnit'] . '",';
                            echo $this->colorboxSettings['width'] == "false" ? '' : 'width:"' . $this->colorboxSettings['widthValue'] . $this->colorboxSettings['widthUnit'] . '",';
                            echo !$this->colorboxSettings['slideshow'] ? '' : 'slideshow:true,';
                            echo $this->colorboxSettings['slideshowAuto'] ? '' : 'slideshowAuto:false,';
                            echo $this->colorboxSettings['scalePhotos'] ? '' : 'scalePhotos:false,';
                            echo $this->colorboxSettings['preloading'] ? '' : 'preloading:false,';
                            echo $this->colorboxSettings['overlayClose'] ? '' : 'overlayClose:false,';
                            echo !$this->colorboxSettings['displayScrollbar']||$this->colorboxSettings['draggable'] ? '' : 'scrolling:false,';?>
                            opacity:"<?php echo $this->colorboxSettings['opacity']; ?>",
                            transition:"<?php echo $this->colorboxSettings['transition']; ?>",
                            speed:<?php echo $this->colorboxSettings['speed']; ?>,
                            slideshowSpeed:<?php echo $this->colorboxSettings['slideshowSpeed']; ?>,
                            close:"<?php _e('close', JQUERYCOLORBOX_TEXTDOMAIN); ?>",
                            next:"<?php _e('next', JQUERYCOLORBOX_TEXTDOMAIN); ?>",
                            previous:"<?php _e('previous', JQUERYCOLORBOX_TEXTDOMAIN); ?>",
                            slideshowStart:"<?php _e('start slideshow', JQUERYCOLORBOX_TEXTDOMAIN); ?>",
                            slideshowStop:"<?php _e('stop slideshow', JQUERYCOLORBOX_TEXTDOMAIN); ?>",
                            current:"<?php _e('{current} of {total} images', JQUERYCOLORBOX_TEXTDOMAIN); ?>"
                        });
                    }
                }
            }
        });
    });
    // ]]>
</script>