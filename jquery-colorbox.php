<?php
/**
 * @package Techotronic
 * @subpackage jQuery Colorbox
 *
 * Plugin Name: jQuery Colorbox
 * Plugin URI: http://www.techotronic.de/index.php/plugins/jquery-colorbox/
 * Description: Used to overlay images on the current page. Images in one post are grouped automatically.
 * Version: 2.0
 * Author: Arne Franken
 * Author URI: http://www.techotronic.de/
 * License: GPL
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
?>
<?php

/**
 * define vital constants
 */
define( 'JQUERYCOLORBOX_VERSION', '2.0' );

if ( ! defined( 'JQUERYCOLORBOX_PLUGIN_BASENAME' ) ) {
    define( 'JQUERYCOLORBOX_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'JQUERYCOLORBOX_PLUGIN_NAME' ) ) {
    define( 'JQUERYCOLORBOX_PLUGIN_NAME', trim( dirname( JQUERYCOLORBOX_PLUGIN_BASENAME ), '/' ) );
}
if ( ! defined( 'JQUERYCOLORBOX_NAME' ) ) {
    define( 'JQUERYCOLORBOX_NAME', 'jQuery Colorbox' );
}
if ( ! defined( 'JQUERYCOLORBOX_TEXTDOMAIN' ) ) {
    define( 'JQUERYCOLORBOX_TEXTDOMAIN', 'jquery-colorbox' );
}
if ( ! defined( 'JQUERYCOLORBOX_WP_PLUGIN_DIR' ) ) {
    define( 'JQUERYCOLORBOX_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . JQUERYCOLORBOX_PLUGIN_NAME );
}
if ( ! defined( 'JQUERYCOLORBOX_PLUGIN_DIR' ) ) {
    define( 'JQUERYCOLORBOX_PLUGIN_DIR', ABSPATH . '/' . PLUGINDIR . '/' . JQUERYCOLORBOX_PLUGIN_NAME );
}
if ( ! defined( 'JQUERYCOLORBOX_PLUGIN_URL' ) ) {
    define( 'JQUERYCOLORBOX_PLUGIN_URL', WP_PLUGIN_URL . '/' . JQUERYCOLORBOX_PLUGIN_NAME );
}
if ( ! defined( 'JQUERYCOLORBOX_PLUGIN_LOCALIZATION_DIR' ) ){
    define( 'JQUERYCOLORBOX_PLUGIN_LOCALIZATION_DIR', JQUERYCOLORBOX_PLUGIN_DIR . '/localization' );
}
if ( ! defined( 'JQUERYCOLORBOX_SETTINGSNAME' ) ) {
    define( 'JQUERYCOLORBOX_SETTINGSNAME', 'jquery-colorbox_settings' );
}

class jQueryColorbox {
    var $colorboxThemes = array();

    var $colorboxUnits = array();

    var $colorboxSettings = array();

    var $colorboxDefaultSettings = array();

    /**
     * Plugin initialization
     *
     * @since 1.0
     * @access private
     * @author Arne Franken
     */
    function jQueryColorbox() {
        if ( !function_exists('plugins_url') )
            return;
            // it seems that there is no way to find the plugin dir relative to the WP_PLUGIN_DIR through the Wordpress API...
        load_plugin_textdomain(JQUERYCOLORBOX_TEXTDOMAIN, false, '/jquery-colorbox/localization/' );

        add_action('wp_head', array(&$this, 'buildWordpressHeader') );
        add_action('admin_post_jQueryColorboxDeleteSettings', array(&$this, 'jQueryColorboxDeleteSettings') );
        add_action('admin_post_jQueryColorboxUpdateSettings', array(&$this, 'jQueryColorboxUpdateSettings') );
            // add options page
        add_action( 'admin_menu', array(&$this, 'registerAdminMenu') );
            //register method for uninstall
        if ( function_exists('register_uninstall_hook') ){
            register_uninstall_hook(__FILE__, array('jQueryColorbox', 'deleteSettingsFromDatabase' ) );
        }

            //write "colorbox-postID" to "img"-tags class attribute.
            //Priority = 100, hopefully the preg_replace is then executed after other plugins messed with the_content
        add_filter('the_content', array(&$this, 'addColorboxGroupIdToImages'), 100);
        add_filter('the_excerpt', array(&$this, 'addColorboxGroupIdToImages'), 100);
        add_filter('wp_get_attachment_image_attributes', array(&$this, 'wpPostThumbnailClassFilter') );


        if ( !is_admin() ) {
            wp_enqueue_script( 'colorbox', plugins_url( 'js/jquery.colorbox-min.js', __FILE__ ), array( 'jquery' ), '1.3.6' );
        }

            // Create list of themes and their human readable names
        $this->colorboxThemes = array(
            'theme1' => __( 'Theme #1', JQUERYCOLORBOX_TEXTDOMAIN ),
            'theme2' => __( 'Theme #2', JQUERYCOLORBOX_TEXTDOMAIN ),
            'theme3' => __( 'Theme #3', JQUERYCOLORBOX_TEXTDOMAIN ),
            'theme4' => __( 'Theme #4', JQUERYCOLORBOX_TEXTDOMAIN ),
            'theme5' => __( 'Theme #5', JQUERYCOLORBOX_TEXTDOMAIN ),
        );

            // create list of units
        $this->colorboxUnits = array (
            '%' => __( 'percent', JQUERYCOLORBOX_TEXTDOMAIN ),
            'px' => __( 'pixels', JQUERYCOLORBOX_TEXTDOMAIN )
        );

            // Create array of default settings
        $this->colorboxDefaultSettings = array(
            'jQueryColorboxVersion' => JQUERYCOLORBOX_VERSION,
            'colorboxTheme' => 'theme1',
            'maxWidth' => 'false',
            'maxWidthValue' => '',
            'maxWidthUnit' => '%',
            'maxHeight' => 'false',
            'maxHeightValue' => '',
            'maxHeightUnit' => '%',
            'height' => 'false',
            'heightValue' => '',
            'heightUnit' => '%',
            'width' => 'false',
            'widthValue' => '',
            'widthUnit' => '%',
            'autoColorbox' => false,
            'autoColorboxGalleries' => false,
            'slideshow' => false,
            'slideshowAuto' => false,
            'scalePhotos' => false,
            'slideshowSpeed' => '2500'
        );

            // Create the settings array by merging the user's settings and the defaults
        $usersettings = (array) get_option(JQUERYCOLORBOX_SETTINGSNAME);
        $this->colorboxSettings = wp_parse_args( $usersettings, jQueryColorbox::jQueryColorboxDefaultSettings() );

            // Enqueue the theme in wordpress
        if ( empty($this->colorboxThemes[$this->colorboxSettings['colorboxTheme']]) ) {
            $defaultArray = jQueryColorbox::jQueryColorboxDefaultSettings();
            $this->colorboxSettings['colorboxTheme'] = $defaultArray['colorboxTheme'];
        }
        if ( !is_admin() ) {
            wp_register_style('colorbox-' . $this->colorboxSettings['colorboxTheme'], plugins_url( 'themes/' . $this->colorboxSettings['colorboxTheme'] . '/colorbox.css', __FILE__ ), array(), '1.3.6', 'screen' );
            wp_enqueue_style('colorbox-' . $this->colorboxSettings['colorboxTheme'] );
        }
    }

    //jQueryColorbox()

    /**
     * ugly way to make the images Colorbox-ready by adding the necessary CSS class.
     *
     * function is called for every page or post rendering.
     *
     * unfortunately, Wordpress does not offer a convenient way to get certain elements from the_content,
     * so I had to do this by regexp replacement...
     *
     * @since 1.0
     * @access public
     * @author Arne Franken
     *
     * @param  the_content or the_excerpt
     * @return replaced content or excerpt
     */
    function addColorboxGroupIdToImages ($content) {
        $colorboxSettings = (array) get_option(JQUERYCOLORBOX_SETTINGSNAME);
        if(isset($colorboxSettings['autoColorbox']) && $colorboxSettings['autoColorbox']){
            global
            $post;
            $pattern = "/<img(.*?)class=('|\")([A-Za-z0-9 \/_\.\~\:-]*?)('|\")([^\>]*?)>/i";
            $replacement = '<img$1class=$2$3 colorbox-'.$post->ID.'$4$5>';
            $content = preg_replace($pattern, $replacement, $content);
        }
        return $content;
    }

    //addColorboxGroupIdToImages()

    /**
     * Add colorbox-CSS-Class to WP Galleries
     * If wp_get_attachment_image() is called, filters registered for the_content are not applied on the img-tag.
     * So we'll need to manipulate the class attribute separately.
     *
     * @since 2.0
     * @access public
     * @author Arne Franken
     *
     * @param  $attr class attribute of the attachment link
     * @return repaced attributes
     */
    function wpPostThumbnailClassFilter( $attr ) {
        $colorboxSettings = (array) get_option(JQUERYCOLORBOX_SETTINGSNAME);
        if(isset($colorboxSettings['autoColorboxGalleries']) && $colorboxSettings['autoColorboxGalleries']){
            global
            $post;
            $attr['class'] .= ' colorbox-'.$post->ID.' ';
        }
        return $attr;
    }

    // wpPostThumbnailClassFilter()

    /**
     * Register the settings page in wordpress
     *
     * @since 1.0
     * @access private
     * @author Arne Franken
     */
    function registerSettingsPage() {
        if ( current_user_can('manage_options') ) {
            add_filter( 'plugin_action_links_' . JQUERYCOLORBOX_PLUGIN_BASENAME, array(&$this, 'addPluginActionLinks') );
            add_options_page( JQUERYCOLORBOX_NAME, JQUERYCOLORBOX_NAME, 'manage_options', JQUERYCOLORBOX_PLUGIN_BASENAME, array(&$this, 'renderSettingsPage') );
        }
    }

    //registerSettingsPage()

    /**
     * Add settings link to plugin management page
     *
     * @since 1.0
     * @access private
     * @author Arne Franken
     *
     * @param  original action_links
     * @return action_links with link to settings page
     */
    function addPluginActionLinks($action_links) {
        $settings_link = '<a href="options-general.php?page='.JQUERYCOLORBOX_PLUGIN_BASENAME.'">' . __('Settings', JQUERYCOLORBOX_TEXTDOMAIN) . '</a>';
        array_unshift( $action_links, $settings_link );

        return $action_links;
    }

    //addPluginActionLinks()

    /**
     * Insert JavaScript for Colorbox into WP Header
     *
     * @since 1.0
     * @access private
     * @author Arne Franken
     * @author Fabian Wolf (http://usability-idealist.de/)
     *
     * @return rewritten content or excerpt
     */
    function buildWordpressHeader() {
        ?>
                <!-- <?php echo JQUERYCOLORBOX_NAME ?> <?php echo JQUERYCOLORBOX_VERSION ?> | by Arne Franken, http://www.techotronic.de/ -->
        <?php
            if($this->colorboxSettings['colorboxTheme']=='theme1'){
            ?>
            <!--[if IE]>
            <style type="text/css">
                /*
                    The following fixes png-transparency for IE6.
                    It is also necessary for png-transparency in IE7 & IE8 to avoid 'black halos' with the fade transition

                    Since this method does not support CSS background-positioning, it is incompatible with CSS sprites.
                    Colorbox preloads navigation hover classes to account for this.

                    !! Important Note: AlphaImageLoader src paths are relative to the HTML document,
                    while regular CSS background images are relative to the CSS document.
                */
                .cboxIE #cboxTopLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme1/images/internet_explorer/borderTopLeft.png, sizingMethod='scale');}
                .cboxIE #cboxTopCenter{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme1/images/internet_explorer/borderTopCenter.png, sizingMethod='scale');}
                .cboxIE #cboxTopRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme1/images/internet_explorer/borderTopRight.png, sizingMethod='scale');}
                .cboxIE #cboxBottomLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme1/images/internet_explorer/borderBottomLeft.png, sizingMethod='scale');}
                .cboxIE #cboxBottomCenter{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme1/images/internet_explorer/borderBottomCenter.png, sizingMethod='scale');}
                .cboxIE #cboxBottomRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme1/images/internet_explorer/borderBottomRight.png, sizingMethod='scale');}
                .cboxIE #cboxMiddleLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme1/images/internet_explorer/borderMiddleLeft.png, sizingMethod='scale');}
                .cboxIE #cboxMiddleRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme1/images/internet_explorer/borderMiddleRight.png, sizingMethod='scale');}
            </style>
            <![endif]-->
            <?php

        } elseif ($this->colorboxSettings['colorboxTheme']=='theme4'){
            ?>
            <!--[if IE]>
            <style type="text/css">
                /*
                    The following fixes png-transparency for IE6.
                    It is also necessary for png-transparency in IE7 & IE8 to avoid 'black halos' with the fade transition

                    Since this method does not support CSS background-positioning, it is incompatible with CSS sprites.
                    Colorbox preloads navigation hover classes to account for this.

                    !! Important Note: AlphaImageLoader src paths are relative to the HTML document,
                    while regular CSS background images are relative to the CSS document.
                */
                .cboxIE #cboxTopLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme4/images/internet_explorer/borderTopLeft.png, sizingMethod='scale');}
                .cboxIE #cboxTopCenter{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme4/images/internet_explorer/borderTopCenter.png, sizingMethod='scale');}
                .cboxIE #cboxTopRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme4/images/internet_explorer/borderTopRight.png, sizingMethod='scale');}
                .cboxIE #cboxBottomLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme4/images/internet_explorer/borderBottomLeft.png, sizingMethod='scale');}
                .cboxIE #cboxBottomCenter{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme4/images/internet_explorer/borderBottomCenter.png, sizingMethod='scale');}
                .cboxIE #cboxBottomRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme4/images/internet_explorer/borderBottomRight.png, sizingMethod='scale');}
                .cboxIE #cboxMiddleLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme4/images/internet_explorer/borderMiddleLeft.png, sizingMethod='scale');}
                .cboxIE #cboxMiddleRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=<?php echo JQUERYCOLORBOX_PLUGIN_URL ?>/themes/theme4/images/internet_explorer/borderMiddleRight.png, sizingMethod='scale');}
             </style>
             <![endif]-->
            <?php

        }
        ?>

        <script type="text/javascript">
            // <![CDATA[
            jQuery(document).ready(function($) {
                //gets all "a" elements that have a nested "img"
                $("a:has(img)").each(function(index, obj) {
                    //only go on if link points to an image
                    if ($(obj).attr("href").match('\.(?:jpe?g|gif|png|bmp)')) {
                        //in this context, the first child is always an image if fundamental Wordpress functions are used
                        var $nestedElement = $(obj).children(0);
                        if ($nestedElement.is("img")) {
                            var $nestedElementClassAttribute = $nestedElement.attr("class");
                            //either the groupId has to be the automatically created colorbox-123 or the manually added colorbox-manual
                            var $groupId = $nestedElementClassAttribute.match('colorbox-[0-9]+') || $nestedElementClassAttribute.match('colorbox-manual');
                            //only call Colorbox if there is a groupId for the image and the image is not excluded
                            if ($groupId && !$nestedElementClassAttribute.match('colorbox-off')) {
                                //convert groupId to string for easier use
                                $groupId = $groupId.toString();
                                //if groudId is colorbox-manual, set groupId to false so that images with that class are not grouped
                                if ($groupId == "colorbox-manual") {
                                    $groupId = false;
                                }
                                //call Colorbox function on each img. elements with the same groupId in the class attribute are grouped
                                //the title of the img is used as the title for the Colorbox.
                                $(obj).colorbox({
                                    rel:$groupId,
                                    title:$nestedElement.attr("title"),
                                    <?php echo $this->colorboxSettings['maxWidth']=="false"?'':'maxWidth:"'.$this->colorboxSettings['maxWidthValue'].$this->colorboxSettings['maxWidthUnit'].'",';
                                    echo $this->colorboxSettings['maxHeight']=="false"?'':'maxHeight:"'.$this->colorboxSettings['maxHeightValue'].$this->colorboxSettings['maxHeightUnit'].'",';
                                    echo $this->colorboxSettings['height']=="false"?'':'height:"'.$this->colorboxSettings['heightValue'].$this->colorboxSettings['heightUnit'].'",';
                                    echo $this->colorboxSettings['width']=="false"?'':'width:"'.$this->colorboxSettings['widthValue'].$this->colorboxSettings['widthUnit'].'",';
                                    echo !$this->colorboxSettings['slideshow']?'':'slideshow:true,';
                                    echo !$this->colorboxSettings['slideshowAuto']?'':'slideshowAuto:true,';
                                    echo $this->colorboxSettings['scalePhotos']?'':'scalePhotos:false,'; ?>
                                    slideshowSpeed:"<?php echo $this->colorboxSettings['slideshowSpeed']; ?>",
                                    close:"<?php _e( 'close', JQUERYCOLORBOX_TEXTDOMAIN ); ?>",
                                    next:"<?php _e( 'next', JQUERYCOLORBOX_TEXTDOMAIN ); ?>",
                                    previous:"<?php _e( 'previous', JQUERYCOLORBOX_TEXTDOMAIN ); ?>",
                                    slideshowStart:"<?php _e( 'start slideshow', JQUERYCOLORBOX_TEXTDOMAIN ); ?>",
                                    slideshowStop:"<?php _e( 'stop slideshow', JQUERYCOLORBOX_TEXTDOMAIN ); ?>",
                                    current:"<?php _e( '{current} of {total} images', JQUERYCOLORBOX_TEXTDOMAIN ); ?>"
                                });
                            }
                        }
                    }
                });
            });
            // ]]>
        </script>
        <!-- <?php echo JQUERYCOLORBOX_NAME ?> <?php echo JQUERYCOLORBOX_VERSION ?> | by Arne Franken, http://www.techotronic.de/ -->
        <?php

    }

    //buildWordpressHeader()

    /**
     * Render Settings page
     *
     * @since 1.0
     * @access private
     * @author Arne Franken
     */
    function renderSettingsPage() {
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
            });
            //]]>
        </script>
        <div class="wrap">
        <?php screen_icon(); ?>
        <h2><?php printf(__( '%1$s Settings', JQUERYCOLORBOX_TEXTDOMAIN ),JQUERYCOLORBOX_NAME); ?></h2>
        <br class="clear"/>

        <?php settings_fields(JQUERYCOLORBOX_SETTINGSNAME); ?>

        <div id="poststuff" class="ui-sortable meta-box-sortables">
            <div id="jquery-colorbox-settings" class="postbox">
                <h3 id="settings"><?php _e( 'Settings', JQUERYCOLORBOX_TEXTDOMAIN ); ?></h3>

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
                                                                            foreach ( $this->colorboxThemes as $theme => $name ) {
                                        echo '<option value="' . esc_attr($theme) . '"';
                                        selected( $this->colorboxSettings['colorboxTheme'], $theme );
                                        echo '>' . htmlspecialchars($name) . "</option>\n";
                                    }
?>
                                            </select>
                                    <br/><?php _e( 'Select the theme you want to use on your blog.', JQUERYCOLORBOX_TEXTDOMAIN ); ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="jquery-colorbox-autoColorbox"><?php printf(__('Automate %1$s for all images', JQUERYCOLORBOX_TEXTDOMAIN),JQUERYCOLORBOX_NAME); ?>:</label>
                                </th>
                                <td>
                                    <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorbox]" id="jquery-colorbox-autoColorbox" value="true" <?php echo ($this->colorboxSettings['autoColorbox'])?'checked="checked"':'';?>/>
                                    <br/><?php _e('Automatically add colorbox-class to images in posts and pages. Also adds colorbox-class to galleries. Images in one page or post are grouped automatically.', JQUERYCOLORBOX_TEXTDOMAIN); ?>
                                    </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="jquery-colorbox-autoColorboxGalleries"><?php printf(__('Automate %1$s for images in WordPress galleries', JQUERYCOLORBOX_TEXTDOMAIN),JQUERYCOLORBOX_NAME); ?>:</label>
                                </th>
                                <td>
                                    <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[autoColorboxGalleries]" id="jquery-colorbox-autoColorboxGalleries" value="true" <?php echo ($this->colorboxSettings['autoColorboxGalleries'])?'checked="checked"':'';?>/>
                                    <br/><?php _e('Automatically add colorbox-class to images in WordPress galleries, but nowhere else. Images in one page or post are grouped automatically.', JQUERYCOLORBOX_TEXTDOMAIN); ?>
                                    </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="jquery-colorbox-slideshow"><?php _e('Add Slideshow to groups', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                                </th>
                                <td>
                                    <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[slideshow]" id="jquery-colorbox-slideshow" value="true" <?php echo ($this->colorboxSettings['slideshow'])?'checked="checked"':'';?>/>
                                    <br/><?php printf(__('Add Slideshow functionality for %1$s Groups', JQUERYCOLORBOX_TEXTDOMAIN),JQUERYCOLORBOX_NAME); ?>
                                    </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="jquery-colorbox-slideshowAuto"><?php _e('Start Slideshow automatically', JQUERYCOLORBOX_TEXTDOMAIN); ?>:</label>
                                </th>
                                <td>
                                    <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[slideshowAuto]" id="jquery-colorbox-slideshowAuto" value="true" <?php echo ($this->colorboxSettings['slideshowAuto'])?'checked="checked"':'';?>/>
                                    <br/><?php printf(__('Start Slideshow automatically if slideshow functionality is added to %1$s Groups', JQUERYCOLORBOX_TEXTDOMAIN),JQUERYCOLORBOX_NAME); ?>
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
                                    <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxWidth]" id="jquery-colorbox-maxWidth-false-radio" value="false" <?php echo ($this->colorboxSettings['maxWidth'])=='false'?'checked="checked"':''; ?>"/>
                                    <label for="jquery-colorbox-maxWidth-false-radio"><?php _e('Do not set width', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                                    <br/>
                                    <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxWidth]" id="jquery-colorbox-maxWidth-custom-radio" value="custom" <?php echo ($this->colorboxSettings['maxWidth'])=='custom'?'checked="checked"':''; ?>"/>
                                    <label for="jquery-colorbox-maxWidth-custom-radio"><?php _e('Set maximum width of an image', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                                    <input type="text" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxWidthValue]" id="jquery-colorbox-maxWidthValue" value="<?php echo $this->colorboxSettings['maxWidthValue'] ?>" size="3" maxlength="3"/>
                                    <select name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxWidthUnit]" id="jquery-colorbox-maxWidth-unit" class="postform" style="margin:0">
                                    <?php
                                        foreach ( $this->colorboxUnits as $unit => $name ) {
                                            echo '<option value="' . esc_attr($unit) . '"';
                                            selected( $this->colorboxSettings['maxWidthUnit'], $unit );
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
                                    <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxHeight]" id="jquery-colorbox-maxHeight-false-radio" value="false" <?php echo ($this->colorboxSettings['maxHeight'])=='false'?'checked="checked"':''; ?>"/>
                                    <label for="jquery-colorbox-maxHeight-false-radio"><?php _e('Do not set height', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                                    <br/>
                                    <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxHeight]" id="jquery-colorbox-maxHeight-custom-radio" value="custom" <?php echo ($this->colorboxSettings['maxHeight'])=='custom'?'checked="checked"':''; ?>"/>
                                    <label for="jquery-colorbox-maxHeight-custom-radio"><?php _e('Set maximum height of an image', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                                    <input type="text" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxHeightValue]" id="jquery-colorbox-maxHeightValue" value="<?php echo $this->colorboxSettings['maxHeightValue'] ?>" size="3" maxlength="3"/>
                                    <select name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[maxHeightUnit]" id="jquery-colorbox-maxHeight-unit" class="postform" style="margin:0">
                                    <?php
                                        foreach ( $this->colorboxUnits as $unit => $name ) {
                                            echo '<option value="' . esc_attr($unit) . '"';
                                            selected( $this->colorboxSettings['maxHeightUnit'], $unit );
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
                                    <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[width]" id="jquery-colorbox-width-false-radio" value="false" <?php echo ($this->colorboxSettings['width'])=='false'?'checked="checked"':''; ?>"/>
                                    <label for="jquery-colorbox-width-false-radio"><?php _e('Do not set width', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                                    <br/>
                                    <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[width]" id="jquery-colorbox-width-custom-radio" value="custom" <?php echo ($this->colorboxSettings['width'])=='custom'?'checked="checked"':''; ?>"/>
                                    <label for="jquery-colorbox-width-custom-radio"><?php _e('Set width of the Colorbox', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                                    <input type="text" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[widthValue]" id="jquery-colorbox-widthValue" value="<?php echo $this->colorboxSettings['widthValue'] ?>" size="3" maxlength="3"/>
                                    <select name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[widthUnit]" id="jquery-colorbox-width-unit" class="postform" style="margin:0">
                                    <?php
                                        foreach ( $this->colorboxUnits as $unit => $name ) {
                                            echo '<option value="' . esc_attr($unit) . '"';
                                            selected( $this->colorboxSettings['widthUnit'], $unit );
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
                                    <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[height]" id="jquery-colorbox-height-false-radio" value="false" <?php echo ($this->colorboxSettings['height'])=='false'?'checked="checked"':''; ?>"/>
                                    <label for="jquery-colorbox-height-false-radio"><?php _e('Do not set height', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                                    <br/>
                                    <input type="radio" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[height]" id="jquery-colorbox-height-custom-radio" value="custom" <?php echo ($this->colorboxSettings['height'])=='custom'?'checked="checked"':''; ?>"/>
                                    <label for="jquery-colorbox-height-custom-radio"><?php _e('Set height of the Colorbox', JQUERYCOLORBOX_TEXTDOMAIN); ?>.</label>
                                    <input type="text" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[heightValue]" id="jquery-colorbox-heightValue" value="<?php echo $this->colorboxSettings['heightValue'] ?>" size="3" maxlength="3"/>
                                    <select name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[heightUnit]" id="jquery-colorbox-height-unit" class="postform" style="margin:0">
                                    <?php
                                        foreach ( $this->colorboxUnits as $unit => $name ) {
                                            echo '<option value="' . esc_attr($unit) . '"';
                                            selected( $this->colorboxSettings['heightUnit'], $unit );
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
                                    <input type="checkbox" name="<?php echo JQUERYCOLORBOX_SETTINGSNAME ?>[scalePhotos]" id="jquery-colorbox-scalePhotos" value="true" <?php echo ($this->colorboxSettings['scalePhotos'])?'checked="checked"':'';?>/>
                                    <br/><?php _e('If enabled and if maximum width of images, maximum height of images, width of the Colorbox, or height of the Colorbox have been defined, ColorBox will scale photos to fit within the those values', JQUERYCOLORBOX_TEXTDOMAIN); ?>.
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
                <h3 id="delete_options"><?php _e('Delete Settings',JQUERYCOLORBOX_TEXTDOMAIN) ?></h3>

                <div class="inside">
                    <p><?php _e('Check the box and click this button to delete settings of this plugin.',JQUERYCOLORBOX_TEXTDOMAIN); ?></p>

                    <form name="delete_settings" method="post" action="admin-post.php">
                    <?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('jquery-delete_settings-form'); ?>
                        <p id="submitbutton">
                        <input type="hidden" name="action" value="jQueryColorboxDeleteSettings"/>
                        <input type="submit" name="jQueryColorboxDeleteSettings" value="<?php _e('Delete Settings',JQUERYCOLORBOX_TEXTDOMAIN); ?> &raquo;" class="button-secondary"/>
                        <input type="checkbox" name="delete_settings-true"/>
                    </p>
                    </form>
                </div>
            </div>
        </div>

        <div id="poststuff" class="ui-sortable meta-box-sortables">
            <div id="jquery-colorbox-donate" class="postbox">
                <h3 id="donate"><?php _e('Donate',JQUERYCOLORBOX_TEXTDOMAIN) ?></h3>

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
        </div>
        <?php

    }

    //renderSettingsPage()

    /**
     * Registers the Settings Page in the Admin Menu
     *
     * @since 1.3.3
     * @access private
     * @author Arne Franken
     */
    function registerAdminMenu() {
        if ( function_exists('add_management_page') && current_user_can('manage_options') ) {

            // update, uninstall message
            if ( strpos($_SERVER['REQUEST_URI'], 'jquery-colorbox.php') && isset($_GET['jQueryColorboxUpdateSettings'])) {
                $return_message = sprintf(__('Successfully updated %1$s settings.', JQUERYCOLORBOX_TEXTDOMAIN),JQUERYCOLORBOX_NAME);
            } elseif (strpos($_SERVER['REQUEST_URI'], 'jquery-colorbox.php') && isset($_GET['jQueryColorboxDeleteSettings'])) {
                $return_message = sprintf(__('%1$s settings were successfully deleted.', JQUERYCOLORBOX_TEXTDOMAIN),JQUERYCOLORBOX_NAME);
            } else {
                $return_message = '';
            }
        }
        $this->registerAdminNotice($return_message);

        $this->registerSettingsPage();
    }

    // registerAdminMenu()

    /**
     * Registers Admin Notices
     *
     * @since 2.0
     * @access private
     * @author Arne Franken
     */
    function registerAdminNotice($notice){
        if ( $notice != '' ) {
            $message = '<div class="updated fade"><p>' . $notice . '</p></div>';
            add_action('admin_notices', create_function( '', "echo '$message';" ) );
        }
    }

    static function jQueryColorboxDefaultSettings(){

        // Create and return array of default settings
        return array(
            'jQueryColorboxVersion' => JQUERYCOLORBOX_VERSION,
            'colorboxTheme' => 'theme1',
            'maxWidth' => 'false',
            'maxWidthValue' => '',
            'maxWidthUnit' => '%',
            'maxHeight' => 'false',
            'maxHeightValue' => '',
            'maxHeightUnit' => '%',
            'height' => 'false',
            'heightValue' => '',
            'heightUnit' => '%',
            'width' => 'false',
            'widthValue' => '',
            'widthUnit' => '%',
            'autoColorbox' => false,
            'autoColorboxGalleries' => false,
            'slideshow' => false,
            'slideshowAuto' => false,
            'scalePhotos' => false,
            'slideshowSpeed' => '2500'
        );
    }

    /**
     * Update jQuery Colorbox settings
     *
     * handles checks and redirect
     *
     * @since 1.3.3
     * @access private
     * @author Arne Franken
     */
    function jQueryColorboxUpdateSettings() {

        if ( !current_user_can('manage_options') )
            wp_die( __('Did not update settings, you do not have the necessary rights.', JQUERYCOLORBOX_TEXTDOMAIN) );

            //cross check the given referer for nonce set in settings form
        check_admin_referer('jquery-colorbox-settings-form');
        $this->colorboxSettings = $_POST[JQUERYCOLORBOX_SETTINGSNAME];
        $this->updateSettingsInDatabase();
        $referrer = str_replace(array('&jQueryColorboxUpdateSettings','&jQueryColorboxDeleteSettings'), '', $_POST['_wp_http_referer'] );
        wp_redirect($referrer . '&jQueryColorboxUpdateSettings' );
    }

    // jQueryColorboxUpdateSettings()

    /**
     * Update jQuery Colorbox settings
     *
     * handles updating settings in the WordPress database
     *
     * @since 1.3.3
     * @access private
     * @author Arne Franken
     */
    function updateSettingsInDatabase() {
        update_option(JQUERYCOLORBOX_SETTINGSNAME, $this->colorboxSettings);
    }

    //updateSettings()

    /**
     * Delete jQuery Colorbox settings
     *
     * handles checks and redirect
     *
     * @since 1.3.3
     * @access private
     * @author Arne Franken
     */
    function jQueryColorboxDeleteSettings() {

        if ( current_user_can('manage_options') && isset($_POST['delete_settings-true']) ){
            //cross check the given referer for nonce set in delete settings form
            check_admin_referer('jquery-delete_settings-form');
            $this->deleteSettingsFromDatabase();
            $this->colorboxSettings = jQueryColorbox::jQueryColorboxDefaultSettings();
        } else {
            wp_die( sprintf(__('Did not delete %1$s settings. Either you dont have the nececssary rights or you didnt check the checkbox.', JQUERYCOLORBOX_TEXTDOMAIN),JQUERYCOLORBOX_NAME) );
        }
            //clean up referrer
        $referrer = str_replace(array('&jQueryColorboxUpdateSettings','&jQueryColorboxDeleteSettings'), '', $_POST['_wp_http_referer'] );
        wp_redirect($referrer . '&jQueryColorboxDeleteSettings' );
    }

    // jQueryColorboxDeleteSettings()

    /**
     * Delete jQuery Colorbox settings
     *
     * handles deletion from WordPress database
     *
     * @since 1.3.3
     * @access private
     * @author Arne Franken
     */
    function deleteSettingsFromDatabase() {
        delete_option(JQUERYCOLORBOX_SETTINGSNAME);
    }

    // deleteSettings()

    /**
     * execute during activation.
     *
     * @since 2.0
     * @access private
     * @author Arne Franken
     */
    function activateJqueryColorbox() {
        $jquery_colorbox_settings = get_option(JQUERYCOLORBOX_SETTINGSNAME);
        if($jquery_colorbox_settings){
            //if jQueryColorboxVersion does not exist, the plugin is a version prior to 2.0
            //settings are incompatible with 2.0, restore default settings.
            if(!array_key_exists('jQueryColorboxVersion',$jquery_colorbox_settings)){
                //in case future versions require resetting the settings
                //if($jquery_colorbox_settings['jQueryColorboxVersion'] < JQUERYCOLORBOX_VERSION)
                update_option(JQUERYCOLORBOX_SETTINGSNAME, jQueryColorbox::jQueryColorboxDefaultSettings());
            }
        }
    }

    // activateJqueryColorbox()
}

// class jQueryColorbox()
?><?php
/**
 * initialize plugin, call constructor
 *
 * @since 1.0
 * @access public
 * @author Arne Franken
 */
function jQueryColorbox() {
    global
    $jQueryColorbox;
    $jQueryColorbox = new jQueryColorbox();
}

//jQueryColorbox()

// add jQueryColorbox() to WordPress initialization
add_action( 'init', 'jQueryColorbox', 7 );

//register method for activation
register_activation_hook(__FILE__,array('jQueryColorbox', 'activateJqueryColorbox'));
?>