<?php
/**
 * @package Techotronic
 * @subpackage jQuery Colorbox
 *
 * Plugin Name: jQuery Colorbox
 * Plugin URI: http://www.techotronic.de/index.php/plugins/jquery-colorbox/
 * Description: Used to overlay images on the current page. Images in one post are grouped automatically.
 * Version: 1.3.3
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
class jQueryColorbox {
    var $colorboxThemes = array();

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
        load_plugin_textdomain( 'jquery-colorbox', false, '/jquery-colorbox/localization/' );

        add_action( 'wp_head',         array(&$this, 'buildWordpressHeader') );
        add_action( 'admin_init',      array(&$this, 'registerSettings') );
        add_action('admin_post_jQueryDeleteSettings', array(&$this, 'jQueryDeleteSettings') );
        add_action('admin_post_jQueryUpdateSettings', array(&$this, 'jQueryUpdateSettings') );
            // add options page
        add_action( 'admin_menu', array(&$this, 'registerAdminMenu') );
            //register plugin for uninstall
        if ( function_exists('register_uninstall_hook') ){
            register_uninstall_hook(__FILE__, 'uninstallJqueryColorbox');
        }

        if ( !is_admin() ) {
            wp_enqueue_script( 'colorbox', plugins_url( 'js/jquery.colorbox-min.js', __FILE__ ), array( 'jquery' ), '1.3.6' );

            wp_register_style( 'colorbox-theme1', plugins_url( 'themes/theme1/colorbox.css', __FILE__ ), array(), '1.3.6', 'screen' );
            wp_register_style( 'colorbox-theme2', plugins_url( 'themes/theme2/colorbox.css', __FILE__ ), array(), '1.3.6', 'screen' );
            wp_register_style( 'colorbox-theme3', plugins_url( 'themes/theme3/colorbox.css', __FILE__ ), array(), '1.3.6', 'screen' );
            wp_register_style( 'colorbox-theme4', plugins_url( 'themes/theme4/colorbox.css', __FILE__ ), array(), '1.3.6', 'screen' );
            wp_register_style( 'colorbox-theme5', plugins_url( 'themes/theme5/colorbox.css', __FILE__ ), array(), '1.3.6', 'screen' );
        }

            // Create list of themes and their human readable names
        $this->colorboxThemes = array(
            'theme1' => __( 'Theme #1', 'jquery-colorbox' ),
            'theme2' => __( 'Theme #2', 'jquery-colorbox' ),
            'theme3' => __( 'Theme #3', 'jquery-colorbox' ),
            'theme4' => __( 'Theme #4', 'jquery-colorbox' ),
            'theme5' => __( 'Theme #5', 'jquery-colorbox' ),
        );

            // Create array of default settings
        $this->colorboxDefaultSettings = array(
            'colorboxTheme' => 'theme1',
            'maxWidth' => 'false',
            'maxHeight' => 'false',
            'height' => 'false',
            'width' => 'false'
        );

            // Create the settings array by merging the user's settings and the defaults
        $usersettings = (array) get_option('jquery-colorbox_settings');
        $this->colorboxSettings = wp_parse_args( $usersettings, $this->colorboxDefaultSettings );

            // Enqueue the theme in wordpress
        if ( empty($this->colorboxThemes[$this->colorboxSettings['colorboxTheme']]) )
            $this->colorboxSettings['colorboxTheme'] = $this->colorboxDefaultSettings['colorboxTheme'];
        wp_enqueue_style( 'colorbox-' . $this->colorboxSettings['colorboxTheme'] );
    }

    //jQueryColorbox()

    /**
     * Register the settings page in wordpress
     *
     * @since 1.0
     * @access private
     * @author Arne Franken
     */
    function registerSettingsPage() {
        static $plugin_basename;
        if ( current_user_can('manage_options') ) {
            $plugin_basename = plugin_basename(__FILE__);
            add_filter( 'plugin_action_links_' . $plugin_basename, array(&$this, 'addPluginActionLinks') );
            add_options_page( __('jQuery Colorbox', 'jquery-colorbox'), __('jQuery Colorbox', 'jquery-colorbox'), 'manage_options', $plugin_basename, array(&$this, 'renderSettingsPage') );
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
        static $plugin_basename;
        if ( !$plugin_basename ) $plugin_basename = plugin_basename(__FILE__);
        $settings_link = '<a href="options-general.php?page='.$plugin_basename.'">' . __('Settings', 'jquery-colorbox') . '</a>';
        array_unshift( $action_links, $settings_link );

        return $action_links;
    }

    //addPluginActionLinks()

    /**
     * Register the plugins settings
     *
     * @since 1.0
     * @access private
     * @author Arne Franken
     */
    function registerSettings() {
        register_setting( 'jquery-colorbox_settings', 'jquery-colorbox_settings', array(&$this, 'validateSettings') );
    }

    //registerSettings()

    /**
     * Insert JavaScript for Colorbox into WP Header
     *
     * @since 1.0
     * @access private
     * @author Arne Franken
     *
     * @return rewritten content or excerpt
     */
    function buildWordpressHeader() {
        ?>
        <!-- jQuery Colorbox 1.3.3 | by Arne Franken, http://www.techotronic.de/ -->
        <script type="text/javascript">
            // <![CDATA[
            jQuery(document).ready(function($) {
                //gets all "a" elements that have a nested "img"
                $("a:has(img)").each(function(index, obj) {
                    //in this context, the first child is always an image if fundamental Wordpress functions are used
                    var $nestedElement = $(obj).children(0);
                    if ($nestedElement.is("img")) {
                        var $groupId = $nestedElement.attr("class").match('colorbox-[0-9]+');
                        //only call colorbox if there is a groupId for the image.
                        if ($groupId && !$nestedElement.attr("class").match('colorbox-off')) {
                            //and calls colorbox function on each img.
                            //elements with the same groupId in the class attribute are grouped
                            //the title of the img is used as the title for the colorbox.
                            $(obj).colorbox({rel:$groupId.toString(), <?php echo('maxWidth:' . '"' . $this->colorboxSettings['maxWidth'] . '"' . ',');
        echo('maxHeight:' . '"' . $this->colorboxSettings['maxHeight'] . '"' . ',');
        echo('height:' . '"' . $this->colorboxSettings['height'] . '"' . ',');
        echo('width:' . '"' . $this->colorboxSettings['width'] . '"' . ',') ?> title:$nestedElement.attr("title")});
                        }
                    }
                });
            });
            // ]]>
        </script>
        <!-- jQuery Colorbox | by Arne Franken, http://www.techotronic.de/ -->
        <?php
                //write "colorbox-postID" to "img"-tags class attribute.
            //Priority = 100, hopefully the preg_replace is then executed after other plugins messed with the_content
        add_filter('the_content', 'addColorboxGroupIdToImages', 100);
        add_filter('the_excerpt', 'addColorboxGroupIdToImages', 100);
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

        <div class="wrap">
        <?php screen_icon(); ?>
        <h2><?php _e( 'jQuery Colorbox Settings', 'jquery-colorbox' ); ?></h2>
            <br class="clear"/>

        <?php settings_fields('jquery-colorbox_settings'); ?>

            <div id="poststuff" class="ui-sortable meta-box-sortables">
                <div id="jquery-colorbox-settings" class="postbox">
                    <h3 id="settings"><?php _e( 'Settings', 'jquery-colorbox' ); ?></h3>

                    <div class="inside">
                        <form name="jquery-colorbox-settings-update" method="post" action="admin-post.php">
                        <?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('jquery-colorbox-settings-form'); ?>

                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="jquery-colorbox-theme"><?php _e('Theme', 'jquery-colorbox'); ?></label>
                                    </th>
                                    <td>
                                        <select name="jquery-colorbox_settings[colorboxTheme]" id="jquery-colorbox-theme" class="postform" style="margin:0">
                                        <?php
                                                                                foreach ( $this->colorboxThemes as $theme => $name ) {
                                            echo '<option value="' . esc_attr($theme) . '"';
                                            selected( $this->colorboxSettings['colorboxTheme'], $theme );
                                            echo '>' . htmlspecialchars($name) . "</option>\n";
                                        }
        ?>
                                            </select>
                                        <br/><?php _e( 'Select the theme you want to use on your blog.', 'jquery-colorbox' ); ?>
                                </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="jquery-colorbox-maxWidth"><?php _e('Maximum width of an image', 'jquery-colorbox'); ?>:</label>
                                    </th>
                                    <td>
                                        <input type="text" name="jquery-colorbox_settings[maxWidth]" id="jquery-colorbox-maxWidth" value="<?php echo $this->colorboxSettings['maxWidth'] ?>"/>
                                        <br/><?php _e('Set the maximum width of the picture in the Colorbox in relation to the browser window. The picture is resized to the appropriate size. Set to either "false" (no maximum width for the picture, picture is as wide as the Colorbox) or a percent value, e.g. "95%"', 'jquery-colorbox'); ?>
                                </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="jquery-colorbox-maxHeight"><?php _e('Maximum height of an image', 'jquery-colorbox'); ?>:</label>
                                    </th>
                                    <td>
                                        <input type="text" name="jquery-colorbox_settings[maxHeight]" id="jquery-colorbox-maxHeight" value="<?php echo $this->colorboxSettings['maxHeight'] ?>"/>
                                        <br/><?php _e('Set the maximum height of the picture in the Colorbox in relation to the browser window. The picture is resized to the appropriate size. Set to either "false" (no maximum height for the picture, picture is as high as the Colorbox) or a percent value, e.g. "95%"', 'jquery-colorbox'); ?>
                                </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="jquery-colorbox-width"><?php _e('Maximum width of the Colorbox', 'jquery-colorbox'); ?>:</label>
                                    </th>
                                    <td>
                                        <input type="text" name="jquery-colorbox_settings[width]" id="jquery-colorbox-width" value="<?php echo $this->colorboxSettings['width'] ?>"/>
                                        <br/><?php _e('Set the maximum width of the Colorbox itself in relation to the browser window. The picture is NOT resized, if bigger than the colorbox, scrollbars are displayed. Set to either "false" (no maximum width for Colorbox, Colorbox is as big as the picture in it) or a percent value, e.g. "95%"', 'jquery-colorbox'); ?>
                                </td>
                                </tr>
                                <tr>
                                    <th scope="row">
                                        <label for="jquery-colorbox-height"><?php _e('Maximum height of the Colorbox', 'jquery-colorbox'); ?>:</label>
                                    </th>
                                    <td>
                                        <input type="text" name="jquery-colorbox_settings[height]" id="jquery-colorbox-height" value="<?php echo $this->colorboxSettings['height'] ?>"/>
                                        <br/><?php _e('Set the maximum height of the Colorbox itself in relation to the browser window. The picture is NOT resized, if bigger than the colorbox, scrollbars are displayed. Set to either "false" (no maximum height for Colorbox, Colorbox is as big as the picture in it) or a percent value, e.g. "95%"', 'jquery-colorbox'); ?>
                                </td>
                                </tr>
                            </table>
                            <p class="submit">
                                <input type="hidden" name="action" value="jQueryUpdateSettings"/>
                                <input type="submit" name="jquery-colorbox-submit" class="button-primary" value="<?php _e('Save Changes') ?>"/>
                            </p>
                        </form>
                    </div>
                </div>
            </div>

            <div id="poststuff" class="ui-sortable meta-box-sortables">
                <div id="jquery-colorbox-delete_settings" class="postbox">
                    <h3 id="delete_options"><?php _e('Delete Settings','jquery-colorbox') ?></h3>

                    <div class="inside">
                        <p><?php _e('Check the box and click this button to delete settings of this plugin.','jquery-colorbox'); ?></p>

                        <form name="delete_settings" method="post" action="admin-post.php">
                        <?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('jquery-delete_settings-form'); ?>
                        <p id="submitbutton">
                            <input type="hidden" name="action" value="jQueryDeleteSettings"/>
                            <input type="submit" value="<?php _e('Delete Settings','jquery-colorbox'); ?> &raquo;" class="button-secondary"/>
                            <input type="checkbox" name="delete_settings-true"/>
                        </p>
                        </form>
                    </div>
                </div>
            </div>

            <div id="poststuff" class="ui-sortable meta-box-sortables">
                <div id="jquery-colorbox-donate" class="postbox">
                    <h3 id="donate"><?php _e('Donate','jquery-colorbox') ?></h3>

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
                        <?php _e('If you would like to make a small (or large) contribution towards future development please consider making a donation.', 'jquery-colorbox') ?>
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

            if ( !isset($_GET['update']) )
                $_GET['update'] = 'false';

            if ( !isset($_GET['uninstall']) )
                $_GET['uninstall'] = 'false';

                // update, uninstall message
            if ( strpos($_SERVER['REQUEST_URI'], 'jquery-colorbox.php') && $_GET['update'] == 'true' ) {
                $return_message = __('Sucessfully updated jQuery Colorbox Settings.', 'jquery-colorbox');
            } elseif ( $_GET['uninstall'] == 'true' ) {
                $return_message = __('jQuery Colorbox settings were successfully deleted. Please deactivate the plugin now.', 'jquery-colorbox');
            } elseif (isset($_GET['delete_settings-true'])) {
                $return_message = __('jQuery Colorbox settings were successfully deleted. Please deactivate the plugin now.', 'jquery-colorbox');
            } else {
                $return_message = '';
            }
        }
        $message = '<div class="updated fade"><p>' . $return_message . '</p></div>';

        if ( $return_message !== '' ) {
            add_action('admin_notices', create_function( '', "echo '$message';" ) );
        }

        $this->registerSettingsPage();
    }


    // registerAdminMenu()

    /**
     * Validate the settings sent from the settings page
     *
     * @since 1.0
     * @access private
     * @author Arne Franken
     *
     * @param  $colorboxSettings settings to be validated
     * @return valid settings
     */
    function validateSettings( $colorboxSettings ) {
        if ( empty($colorboxSettings['colorboxTheme']) || empty($this->colorboxThemes[$colorboxSettings['colorboxTheme']]) )
            $colorboxSettings['colorboxTheme'] = $this->colorboxDefaultSettings['colorboxTheme'];

        return $colorboxSettings;
    }

    // validateSettings()

    /**
     * Update jQuery Colorbox settings
     *
     * handles checks and redirect
     *
     * @since 1.3.3
     * @access private
     * @author Arne Franken
     */
    function jQueryUpdateSettings() {

        if ( !current_user_can('manage_options') )
            wp_die( __('Did not update options, you do not have the necessary rights.', 'jquery-colorbox') );

            //cross check the given referer for nonce set in settings form
        check_admin_referer('jquery-colorbox-settings-form');

        $this->updateSettingsInDatabase();

        $referer = str_replace('&update=true&update=true', '', $_POST['_wp_http_referer'] );
        wp_redirect($referer . '&update=true' );
    }

    // jQueryUpdateSettings()

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
        $this->colorboxSettings = $_POST['jquery-colorbox_settings'];
        update_option('jquery-colorbox_settings', $this->colorboxSettings);
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
    function jQueryDeleteSettings() {

        if ( current_user_can('manage_options') && isset($_POST['delete_settings-true']) ){
            //cross check the given referer for nonce set in delete settings form
            check_admin_referer('jquery-delete_settings-form');
            $this->deleteSettingsFromDatabase();
        } else {
            wp_die( __('Did not delete jQuery Colorbox settings. Either you dont have the nececssary rights or you didnt check the checkbox.', 'jquery-colorbox') );
        }
        wp_redirect( 'plugins.php' );
    }

    // jQueryDeleteSettings()

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
        delete_option('jquery-colorbox_settings');
    }

    // deleteSettings()
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
    global
    $post;
    $pattern = "/<img(.*?)class=('|\")([A-Za-z0-9 \/_\.\~\:-]*?)('|\")([^\>]*?)>/i";
    $replacement = '<img$1class=$2$3 colorbox-'.$post->ID.'$4$5>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}

//addColorboxGroupIdToImages()
?>