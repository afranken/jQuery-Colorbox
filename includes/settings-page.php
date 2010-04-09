<?php
/**
 * @package Techotronic
 * @subpackage jQuery Colorbox
 *
 * @since 3.1
 *
 * HTML for settings page
 */
?>
<script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready(function($) {
        //delete value from maxWidthValue if maxWidth radio button is selected
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxWidth]']").click(function() {
            if ("jquery-colorbox-maxWidth-custom-radio" != $(this).attr("id"))
                $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxWidthValue]']").val("");
        });
        //set maxWidth radio button if cursor is set into maxWidthValue
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxWidthValue]']").focus(function() {
            $("#jquery-colorbox-maxWidth-custom-radio").attr("checked", "checked");
        });

        //delete value from maxHeightValue if maxHeight radio button is selected
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxHeight]']").click(function() {
            if ("jquery-colorbox-maxHeight-custom-radio" != $(this).attr("id"))
                $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxHeightValue]']").val("");
        });
        //set maxHeight radio button if cursor is set into maxHeightValue
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxHeightValue]']").focus(function() {
            $("#jquery-colorbox-maxHeight-custom-radio").attr("checked", "checked");
        });

        //delete value from widthValue if width radio button is selected
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[width]']").click(function() {
            if ("jquery-colorbox-width-custom-radio" != $(this).attr("id"))
                $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[widthValue]']").val("");
        });
        //set width radio button if cursor is set into widthValue
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[widthValue]']").focus(function() {
            $("#jquery-colorbox-width-custom-radio").attr("checked", "checked");
        });

        //delete value from heightValue if height radio button is selected
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[height]']").click(function() {
            if ("jquery-colorbox-height-custom-radio" != $(this).attr("id"))
                $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[heightValue]']").val("");
        });
        //set height radio button if cursor is set into heightValue
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[heightValue]']").focus(function() {
            $("#jquery-colorbox-height-custom-radio").attr("checked", "checked");
        });

        //only one of the checkboxes is allowed to be selected.
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorbox]']").click(function() {
            if ($("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorbox]']").is(':checked')) {
                $("#jquery-colorbox-autoColorboxGalleries").attr("checked", false);
            }
        });
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorboxGalleries]']").click(function() {
            if ($("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorboxGalleries]']").is(':checked')) {
                $("#jquery-colorbox-autoColorbox").attr("checked", false);
            }
        });

        //activate warning if auto Colorbox is activated
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorbox]']").click(function() {
            if ($("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorbox]']").is(':checked')) {
                $("#jquery-colorbox-colorboxWarningOff").attr("checked", true);
            }
        });

        //activate warning if auto Colorbox is deactivated
        $("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorbox]']").click(function() {
            if (!$("input[name='<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorbox]']").is(':checked')) {
                $("#jquery-colorbox-colorboxWarningOff").attr("checked", false);
            }
        });

        //change screenshot if new theme is selected
        $("#jquery-colorbox-theme").change(function() {
            var src = $("option:selected", this).val().match(/\d$/i);
            if ( src != "" ){
                var $imgTag = "<img src=\"" + "<?php echo JQUERYCOLORBOX_PLUGIN_URL; echo '/screenshot-' ; ?>" + src  + ".jpg\" />";
                $("#jquery-colorbox-theme_screenshot_image").empty().html($imgTag).fadeIn();
            }
        });
    });
    //]]>
</script>
<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php printf(__('%1$s Settings', JQUERYCOLORBOX_TEXTDOMAIN), JQUERYCOLORBOX_NAME); ?></h2>
    <br class="clear"/>

    <?php settings_fields(JQUERYCOLORBOX_SETTINGSNAME); ?>

    <div id="poststuff" class="ui-sortable meta-box-sortables">
        <div id="jquery-colorbox-settings" class="postbox">
            <h3 id="settings"><?php _e('Settings', JQUERYCOLORBOX_TEXTDOMAIN); ?></h3>

            <div class="inside">
                <form name="jquery-colorbox-settings-update" method="post" action="admin-post.php">
                <?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('jquery-colorbox-settings-form'); ?>
                
                <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="jquery-colorbox-theme"><?php _e('Theme', JQUERYCOLORBOX_TEXTDOMAIN); ?></label>
                    </th>
                    <td>
                        <select name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[colorboxTheme]" id="jquery-colorbox-theme" class="postform" style="margin:0">
                        <?php
                            foreach ($this->colorboxThemes as $theme => $name) {
                                echo '<option value="' . esc_attr($theme) . '"';
                                selected($this->colorboxSettings['colorboxTheme'], $theme);
                                echo '>' . htmlspecialchars($name) . "</option>\n";
                            }
                        ?>
                        </select>
                        <br/><?php _e('Select the theme you want to use on your blog.', JQUERYCOLORBOX_TEXTDOMAIN); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-theme_screenshot_image"><?php _e('Theme screenshot', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td height="310px">
                        <div id="jquery-colorbox-theme_screenshot_image">
                            <img src="<?php echo JQUERYCOLORBOX_PLUGIN_URL; echo '/screenshot-' ; preg_match('/\d$/i',$this->colorboxSettings['colorboxTheme'],$matches); echo $matches[0] ?>.jpg"/>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-autoColorbox"><?php printf(__('Automate %1$s for all images', JQUERYCOLORBOX_TEXTDOMAIN), JQUERYCOLORBOX_NAME); ?>:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorbox]" id="jquery-colorbox-autoColorbox" value="true" <?php echo ($this->colorboxSettings['autoColorbox']) ? 'checked="checked"' : '';?>/>
                        <br/><?php _e('Automatically add colorbox-class to images in posts and pages. Also adds colorbox-class to galleries. Images in one page or post are grouped automatically.', JQUERYCOLORBOX_TEXTDOMAIN); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-autoColorboxGalleries"><?php printf(__('Automate %1$s for images in WordPress galleries', JQUERYCOLORBOX_TEXTDOMAIN), JQUERYCOLORBOX_NAME); ?>:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorboxGalleries]" id="jquery-colorbox-autoColorboxGalleries" value="true" <?php echo ($this->colorboxSettings['autoColorboxGalleries']) ? 'checked="checked"' : '';?>/>
                        <br/><?php _e('Automatically add colorbox-class to images in WordPress galleries, but nowhere else. Images in one page or post are grouped automatically.', JQUERYCOLORBOX_TEXTDOMAIN); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-slideshow"><?php _e('Add Slideshow to groups', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[slideshow]" id="jquery-colorbox-slideshow" value="true" <?php echo ($this->colorboxSettings['slideshow']) ? 'checked="checked"' : '';?>/>
                        <br/><?php printf(__('Add Slideshow functionality for %1$s Groups', JQUERYCOLORBOX_TEXTDOMAIN), JQUERYCOLORBOX_NAME); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-slideshowAuto"><?php _e('Start Slideshow automatically', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[slideshowAuto]" id="jquery-colorbox-slideshowAuto" value="true" <?php echo ($this->colorboxSettings['slideshowAuto']) ? 'checked="checked"' : '';?>/>
                        <br/><?php printf(__('Start Slideshow automatically if slideshow functionality is added to %1$s Groups', JQUERYCOLORBOX_TEXTDOMAIN), JQUERYCOLORBOX_NAME); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-slideshowSpeed"><?php _e('Speed of the slideshow', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="text" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[slideshowSpeed]" id="jquery-colorbox-slideshowSpeed" value="<?php echo $this->colorboxSettings['slideshowSpeed'] ?>" size="5" maxlength="5"/>ms
                        <br/><?php _e('Sets the speed of the slideshow, in milliseconds', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-maxWidthValue"><?php _e('Maximum width of an image', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxWidth]" id="jquery-colorbox-maxWidth-false-radio" value="false" <?php echo ($this->colorboxSettings['maxWidth']) == 'false' ? 'checked="checked"' : ''; ?>"/>
                        <label for="jquery-colorbox-maxWidth-false-radio"><?php _e('Do not set width', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                        <br/>
                        <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxWidth]" id="jquery-colorbox-maxWidth-custom-radio" value="custom" <?php echo ($this->colorboxSettings['maxWidth']) == 'custom' ? 'checked="checked"' : ''; ?>"/>
                        <label for="jquery-colorbox-maxWidth-custom-radio"><?php _e('Set maximum width of an image', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                        <input type="text" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxWidthValue]" id="jquery-colorbox-maxWidthValue" value="<?php echo $this->colorboxSettings['maxWidthValue'] ?>" size="3" maxlength="3"/>
                        <select name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxWidthUnit]" id="jquery-colorbox-maxWidth-unit" class="postform" style="margin:0">
                        <?php
                        foreach ($this->colorboxUnits as $unit => $name) {
                            echo '<option value="' . esc_attr($unit) . '"';
                            selected($this->colorboxSettings['maxWidthUnit'], $unit);
                            echo '>' . htmlspecialchars($name) . "</option>\n";
                        }
                        ?>
                        </select>
                        <br/><?php _e('Set the maximum width of the image in the Colorbox in relation to the browser window in percent or pixels. If maximum width is not set, image is as wide as the Colorbox', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-maxHeightValue"><?php _e('Maximum height of an image', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxHeight]" id="jquery-colorbox-maxHeight-false-radio" value="false" <?php echo ($this->colorboxSettings['maxHeight']) == 'false' ? 'checked="checked"' : ''; ?>"/>
                        <label for="jquery-colorbox-maxHeight-false-radio"><?php _e('Do not set height', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                        <br/>
                        <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxHeight]" id="jquery-colorbox-maxHeight-custom-radio" value="custom" <?php echo ($this->colorboxSettings['maxHeight']) == 'custom' ? 'checked="checked"' : ''; ?>"/>
                        <label for="jquery-colorbox-maxHeight-custom-radio"><?php _e('Set maximum height of an image', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                        <input type="text" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxHeightValue]" id="jquery-colorbox-maxHeightValue" value="<?php echo $this->colorboxSettings['maxHeightValue'] ?>" size="3" maxlength="3"/>
                        <select name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxHeightUnit]" id="jquery-colorbox-maxHeight-unit" class="postform" style="margin:0">
                        <?php
                        foreach ($this->colorboxUnits as $unit => $name) {
                            echo '<option value="' . esc_attr($unit) . '"';
                            selected($this->colorboxSettings['maxHeightUnit'], $unit);
                            echo '>' . htmlspecialchars($name) . "</option>\n";
                        }
                        ?>
                        </select>
                        <br/><?php _e('Set the maximum height of the image in the Colorbox in relation to the browser window to a value in percent or pixels. If maximum height is not set, the image is as high as the Colorbox', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-widthValue"><?php _e('Maximum width of the Colorbox', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[width]" id="jquery-colorbox-width-false-radio" value="false" <?php echo ($this->colorboxSettings['width']) == 'false' ? 'checked="checked"' : ''; ?>"/>
                        <label for="jquery-colorbox-width-false-radio"><?php _e('Do not set width', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                        <br/>
                        <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[width]" id="jquery-colorbox-width-custom-radio" value="custom" <?php echo ($this->colorboxSettings['width']) == 'custom' ? 'checked="checked"' : ''; ?>"/>
                        <label for="jquery-colorbox-width-custom-radio"><?php _e('Set width of the Colorbox', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                        <input type="text" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[widthValue]" id="jquery-colorbox-widthValue" value="<?php echo $this->colorboxSettings['widthValue'] ?>" size="3" maxlength="3"/>
                        <select name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[widthUnit]" id="jquery-colorbox-width-unit" class="postform" style="margin:0">
                        <?php
                        foreach ($this->colorboxUnits as $unit => $name) {
                            echo '<option value="' . esc_attr($unit) . '"';
                            selected($this->colorboxSettings['widthUnit'], $unit);
                            echo '>' . htmlspecialchars($name) . "</option>\n";
                        }
                        ?>
                        </select>
                        <br/><?php _e('Set the maximum width of the Colorbox itself in relation to the browser window to a value between in percent or pixels. If the image is bigger than the colorbox, scrollbars are displayed. If width is not set, the Colorbox will be as wide as the picture in it', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-heightValue"><?php _e('Maximum height of the Colorbox', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[height]" id="jquery-colorbox-height-false-radio" value="false" <?php echo ($this->colorboxSettings['height']) == 'false' ? 'checked="checked"' : ''; ?>"/>
                        <label for="jquery-colorbox-height-false-radio"><?php _e('Do not set height', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                        <br/>
                        <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[height]" id="jquery-colorbox-height-custom-radio" value="custom" <?php echo ($this->colorboxSettings['height']) == 'custom' ? 'checked="checked"' : ''; ?>"/>
                        <label for="jquery-colorbox-height-custom-radio"><?php _e('Set height of the Colorbox', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                        <input type="text" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[heightValue]" id="jquery-colorbox-heightValue" value="<?php echo $this->colorboxSettings['heightValue'] ?>" size="3" maxlength="3"/>
                        <select name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[heightUnit]" id="jquery-colorbox-height-unit" class="postform" style="margin:0">
                        <?php
                        foreach ($this->colorboxUnits as $unit => $name) {
                            echo '<option value="' . esc_attr($unit) . '"';
                            selected($this->colorboxSettings['heightUnit'], $unit);
                            echo '>' . htmlspecialchars($name) . "</option>\n";
                        }
                        ?>
                        </select>
                        <br/><?php _e('Set the maximum height of the Colorbox itself in relation to the browser window to a value between in percent or pixels. If the image is bigger than the colorbox, scrollbars are displayed. If height is not set, the Colorbox will be as high as the picture in it', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-scalePhotos"><?php _e('Resize images', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[scalePhotos]" id="jquery-colorbox-scalePhotos" value="true" <?php echo ($this->colorboxSettings['scalePhotos']) ? 'checked="checked"' : '';?>/>
                        <br/><?php _e('If enabled and if maximum width of images, maximum height of images, width of the Colorbox, or height of the Colorbox have been defined, ColorBox will scale photos to fit within the those values', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-overlayClose"><?php _e('Close Colorbox on overlay click', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[overlayClose]" id="jquery-colorbox-overlayClose" value="true" <?php echo ($this->colorboxSettings['overlayClose']) ? 'checked="checked"' : '';?>/>
                        <br/><?php _e('If checked, enables closing ColorBox by clicking on the background overlay', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-preloading"><?php _e('Preload images', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[preloading]" id="jquery-colorbox-preloading" value="true" <?php echo ($this->colorboxSettings['preloading']) ? 'checked="checked"' : '';?>/>
                        <br/><?php _e('Allows for preloading of "next" and "previous" content in a group, after the current content has finished loading. Uncheck box to disable.', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-transition"><?php _e('Transition type', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <select name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[transition]" id="jquery-colorbox-transition" class="postform" style="margin:0">
                        <?php
                        foreach ($this->colorboxTransitions as $unit => $name) {
                            echo '<option value="' . esc_attr($unit) . '"';
                            selected($this->colorboxSettings['transition'], $unit);
                            echo '>' . htmlspecialchars($name) . "</option>\n";
                        }
                        ?>
                        </select>
                        <br/><?php _e('The transition type of the Colorbox. Can be set to "elastic", "fade", or "none"', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-speed"><?php _e('Transition speed', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="text" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[speed]" id="jquery-colorbox-speed" value="<?php echo $this->colorboxSettings['speed'] ?>" size="5" maxlength="5"/>ms
                        <br/><?php _e('Sets the speed of the "fade" and "elastic" transitions, in milliseconds', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-opacity"><?php _e('Opacity', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                    </th>
                    <td>
                        <input type="text" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[opacity]" id="jquery-colorbox-opacity" value="<?php echo $this->colorboxSettings['opacity'] ?>" size="4" maxlength="4"/>
                        <br/><?php _e('The overlay opacity level. Range: 0 to 1', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-autoHideFlash"><?php printf(__('Automate hiding of flash objects', JQUERYCOLORBOX_TEXTDOMAIN), JQUERYCOLORBOX_NAME); ?>:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoHideFlash]" id="jquery-colorbox-autoHideFlash" value="true" <?php echo ($this->colorboxSettings['autoHideFlash']) ? 'checked="checked"' : '';?>/>
                        <br/><?php _e('Automatically hide embeded flash objects behind the Colorbox layer. Done by setting the wmode to transparent.', JQUERYCOLORBOX_TEXTDOMAIN); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="jquery-colorbox-colorboxWarningOff"><?php printf(__('Disable warning', JQUERYCOLORBOX_TEXTDOMAIN), JQUERYCOLORBOX_NAME); ?>:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[colorboxWarningOff]" id="jquery-colorbox-colorboxWarningOff" value="true" <?php echo ($this->colorboxSettings['colorboxWarningOff']) ? 'checked="checked"' : '';?>/>
                        <br/><?php _e('Disables the warning that is displayed if the plugin is activated but the auto-colorbox feature for all images is turned off.', JQUERYCOLORBOX_TEXTDOMAIN); ?>
                    </td>
                </tr>
                </table>
                <p class="submit">
                    <input type="hidden" name="action" value="jQueryColorboxUpdateSettings"/>
                    <input type="submit" name="jQueryColorboxUpdateSettings" class="button-primary" value="<?php _e('Save Changes') ?>"/>
                </p>
                </form>
            </div>
        </div>
    </div>

    <div id="poststuff" class="ui-sortable meta-box-sortables">
        <div id="jquery-colorbox-delete_settings" class="postbox">
            <h3 id="delete_options"><?php _e('Delete Settings', JQUERYCOLORBOX_TEXTDOMAIN) ?></h3>

            <div class="inside">
                <p><?php _e('Check the box and click this button to delete settings of this plugin.', JQUERYCOLORBOX_TEXTDOMAIN); ?></p>

                <form name="delete_settings" method="post" action="admin-post.php">
                <?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('jquery-delete_settings-form'); ?>
                    <p id="submitbutton">
                    <input type="hidden" name="action" value="jQueryColorboxDeleteSettings"/>
                    <input type="submit" name="jQueryColorboxDeleteSettings" value="<?php _e('Delete Settings', JQUERYCOLORBOX_TEXTDOMAIN); ?> &raquo;" class="button-secondary"/>
                    <input type="checkbox" name="delete_settings-true"/>
                </p>
                </form>
            </div>
        </div>
    </div>

    <div id="poststuff" class="ui-sortable meta-box-sortables">
        <div id="jquery-colorbox-donate" class="postbox">
            <h3 id="donate"><?php _e('Donate', JQUERYCOLORBOX_TEXTDOMAIN) ?></h3>

            <div class="inside">
                <p>
                    <span style="float: left;">
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                            <input type="hidden" name="cmd" value="_s-xclick">
                            <input type="hidden" name="hosted_button_id" value="11235030">
                            <input type="image" src="https://www.paypal.com/en_GB/i/btn/btn_donate_SM.gif" name="submit" alt="PayPal - The safer, easier way to pay online.">
                            <img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
                        </form>
                    </span>
                </p>
                <p>
                <?php _e('If you would like to make a small (or large) contribution towards future development please consider making a donation.', JQUERYCOLORBOX_TEXTDOMAIN) ?>
                    <br/>&copy; Copyright 2009 - <?php echo date("Y"); ?> <a href="http://www.techotronic.de">Arne Franken</a>
                </p>
            </div>
        </div>
    </div>

    <div id="poststuff" class="ui-sortable meta-box-sortables">
        <div id="jquery-colorbox-translation" class="postbox">
            <h3 id="translation"><?php _e('Translation', JQUERYCOLORBOX_TEXTDOMAIN) ?></h3>

            <div class="inside">
                <p><?php _e('The english translation was done by <a href="http://www.techotronic.de">Arne Franken</a>.', JQUERYCOLORBOX_TEXTDOMAIN); ?></p>
            </div>
        </div>
    </div>
</div>