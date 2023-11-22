<?php

/**
 * Plugin Name: Simple Image Popup
 * Description: Display a simple image in a lightbox on page load
 * Author: Mr Digital
 * Author URI: https://www.mrdigital.com.au
 * Text Domain: simple-image-popup
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Version: 2.4.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('SimpleImagePopup')) :

    class SimpleImagePopup
    {

        public function __construct()
        {

            add_action('wp_enqueue_scripts', array($this, 'assets'));
            add_action('admin_enqueue_scripts', array($this, 'admin_assets'));
            add_action('admin_init', array($this, 'plugin_options'));
            add_action('admin_menu', array($this, 'plugin_menu'));
            add_action('wp_footer', array($this, 'popup'), 100);
        }

        public function assets()
        {

            wp_enqueue_script('jquery');
            wp_register_style('simple-image-popup', plugin_dir_url(__FILE__) . '/css/simple-image-popup.css', array(), false, 'all');
            wp_enqueue_style('simple-image-popup');
        }

        public function admin_assets()
        {
            wp_enqueue_media();
            wp_register_script('media-uploader', plugins_url('js/media-uploader.js', __FILE__), array('jquery'));
            wp_enqueue_script('media-uploader');
        }

        public function plugin_options()
        {

            if (false == get_option('sip_plugin_options')) {
                add_option('sip_plugin_options');
            }

            add_settings_section(
                'sip_image_options', // ID used to identify this section and with which to register options
                '', // Title to be displayed on the administration page
                '', // Callback used to render the description of the section
                'sip_plugin_options' // Page on which to add this section of options
            );

            add_settings_field(
                'sip_image_status', // ID used to identify the field throughout the theme
                'Active', // The label to the left of the option interface element
                array($this, 'sip_image_status_callback'), // The name of the function responsible for rendering the option interface
                'sip_plugin_options', // The page on which this option will be displayed
                'sip_image_options', // The name of the section to which this field belongs
                null
            );

            add_settings_field(
                'sip_image_url', // ID used to identify the field throughout the theme
                'Image URL', // The label to the left of the option interface element
                array($this, 'sip_image_url_callback'), // The name of the function responsible for rendering the option interface
                'sip_plugin_options', // The page on which this option will be displayed
                'sip_image_options', // The name of the section to which this field belongs
                null
            );

            add_settings_field(
                'sip_max_width', // ID used to identify the field throughout the theme
                'Image Max Width (px)', // The label to the left of the option interface element
                array($this, 'sip_max_width_callback'), // The name of the function responsible for rendering the option interface
                'sip_plugin_options', // The page on which this option will be displayed
                'sip_image_options', // The name of the section to which this field belongs
                null
            );

            add_settings_field(
                'sip_link', // ID used to identify the field throughout the theme
                'Link URL', // The label to the left of the option interface element
                array($this, 'sip_link_callback'), // The name of the function responsible for rendering the option interface
                'sip_plugin_options', // The page on which this option will be displayed
                'sip_image_options', // The name of the section to which this field belongs
                null
            );

            add_settings_field(
                'sip_click_to_close', // ID used to identify the field throughout the theme
                'Click to close', // The label to the left of the option interface element
                array($this, 'sip_click_to_close_callback'), // The name of the function responsible for rendering the option interface
                'sip_plugin_options', // The page on which this option will be displayed
                'sip_image_options', // The name of the section to which this field belongs
                null
            );

            add_settings_field(
                'sip_cookie_name', // ID used to identify the field throughout the theme
                'Popup ID', // The label to the left of the option interface element
                array($this, 'sip_cookie_callback'), // The name of the function responsible for rendering the option interface
                'sip_plugin_options', // The page on which this option will be displayed
                'sip_image_options', // The name of the section to which this field belongs
                null
            );

            add_settings_field(
                'sip_popup_expiry', // ID used to identify the field throughout the theme
                'Popup expiry (minutes)', // The label to the left of the option interface element
                array($this, 'sip_popup_expiry_callback'), // The name of the function responsible for rendering the option interface
                'sip_plugin_options', // The page on which this option will be displayed
                'sip_image_options', // The name of the section to which this field belongs
                null
            );


            add_settings_field(
                'sip_popup_before_show', // ID used to identify the field throughout the theme
                'Show popup after (seconds)', // The label to the left of the option interface element
                array($this, 'sip_popup_before_show_callback'), // The name of the function responsible for rendering the option interface
                'sip_plugin_options', // The page on which this option will be displayed
                'sip_image_options', // The name of the section to which this field belongs
                null
            );

            // Finally, we register the fields with WordPress
            register_setting(
                'sip_plugin_options',
                'sip_plugin_options'
            );


            add_action('admin_notices', function () {



                $options = get_option('sip_plugin_options');
                $active = isset($options['sip_plugin_status']) ? true : false;


                if (!$active) {
                    return;
                }

                $current_screen = get_current_screen()->base;

                if (
                    $current_screen == 'dashboard'
                ) {
                    echo admin_message('Image popup is enabled', 'success', 'null', 'Edit popup settings', admin_url('admin.php?page=simple_image_plugin'));
                }
            });

            function admin_message(string $message, string $type = 'success', $classes = null, $link_title = null, $link_url = null): string
            {

                $message = __($message, 'simple-image-popup');

                switch ($type) {
                    case 'success':
                        $class = 'notice notice-success';
                        break;

                    case 'error':
                        $class = 'notice notice-error';
                        break;

                    default:

                        $class = 'notice notice-warning';
                }

                return sprintf('
                  <div class="%1$s" style="display:flex; align-items:center; justify-content:space-between"><p>%2$s</p>
                  <p><a href="' . $link_url . '">' . $link_title . '</a></p>
                  </div>', esc_attr($class), esc_html($message));
            }
        }

        public function plugin_menu()
        {
            add_menu_page(
                'Simple Image Popup Options', // The title to be displayed on the corresponding page for this menu
                'Image Popup', // The text to be displayed for this actual menu item
                'administrator', // Which type of users can see this menu
                'simple_image_plugin', // The unique ID - that is, the slug - for this menu item
                array($this, 'sip_plugin_page'), // The name of the function to call when rendering the menu for this page
                ''
            );
        }

        public function sip_plugin_page()
        {
?>
            <div class="wrap">
                <h2>Simple Image Popup Options</h2>


                <?php settings_errors(); ?>


                <form method="post" action="options.php">
                    <?php settings_fields('sip_plugin_options'); ?>
                    <?php do_settings_sections('sip_plugin_options'); ?>
                    <?php submit_button(); ?>
                </form>


            </div>

        <?php
        } // end sandbox_menu_page_display

        public function sip_image_url_callback($args)
        {
            // Field for the image URL

            $options = get_option('sip_plugin_options');

            $image = isset($options['sip_image_url']) ? esc_url_raw($options['sip_image_url']) : null;

            // Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
            $html = '
									                                            <input type="text" id="sip_image_url" name="sip_plugin_options[sip_image_url]" class="regular-text" value="' . $image . '"/>
									                                            <input id="upload_image_button" type="button" class="button-primary" value="Insert Image" />
									                                ';

            echo $html;
        }

        public function sip_link_callback($args)
        {
            // Field for the image URL

            $options = get_option('sip_plugin_options');

            $link = isset($options['sip_link']) ? esc_url_raw($options['sip_link']) : null;

            // Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
            $html = '
									                                            <input type="text" id="sip_link" name="sip_plugin_options[sip_link]" class="regular-text" value="' . $link . '" placeholder="Page to link to eg. https://www.google.com"/>

									                                ';

            echo $html;
        }

        public function sip_cookie_callback($args)
        {
            // Field for the image URL

            $options = get_option('sip_plugin_options');

            $cookie = isset($options['sip_cookie_name']) ? esc_html($options['sip_cookie_name']) : uniqid();

            // Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
            $html = '
                            <input type="text" id="sip_link" name="sip_plugin_options[sip_cookie_name]" class="regular-text" value="' . $cookie . '" placeholder="Cookie name"/>
            <small style="display:block; margin-top:5px">Changing the Popup ID will reset the popup view on browsers so that the popup can be seen again. You can type anything you want as your ID. The popup ID is stored in the browser to track whether the popup has already opened so it does not open on every page load.</small>
                ';

            echo $html;
        }

        public function sip_max_width_callback($args)
        {
            // Field for the image URL

            $options = get_option('sip_plugin_options');

            $width = isset($options['sip_max_width']) ? $options['sip_max_width'] : 700;

            if (empty($options['sip_max_width'])) {
                $width = 700;
            }

            // Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
            $html = '
                        <input type="number" id="sip_link" name="sip_plugin_options[sip_max_width]" class="regular-text" value="' . $width . '" placeholder="Max width (eg. 500)"/>
                        <small style="display:block; margin-top:5px">Max width in pixels (how large is the max width of the popup (image will fit into this). Default 700px.</small>
            ';

            echo $html;
        }

        public function sip_click_to_close_callback($args)
        {
            // Field for checkbox for click to close

            $options = get_option('sip_plugin_options');

            $status = isset($options['sip_click_to_close']) ? true : false;

            // Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
            $html = '<input type="checkbox" id="sip_click_to_close" name="sip_plugin_options[sip_click_to_close]" value="1" ' . checked(1, $status, false) . '/>
            <small style="display:block; margin-top:5px">Check if you want the popup to close when the image is clicked.</small>
		           ';

            echo $html;
        }

        public function sip_image_status_callback($args)
        {

            // Field for checkbox status of popup

            $options = get_option('sip_plugin_options');

            $status = isset($options['sip_plugin_status']) ? $options['sip_plugin_status'] : false;

            // Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
            $html = '<input type="checkbox" id="sip_plugin_status" name="sip_plugin_options[sip_plugin_status]" value="1" ' . checked(1, $status, false) . '/>
            ';

            echo $html;
        }

        public function sip_popup_expiry_callback($args)
        {

            // Field for expiry of popup cookie

            $options = get_option('sip_plugin_options');

            $default_minutes = 30;

            $expiry = isset($options['sip_popup_expiry']) ? $options['sip_popup_expiry'] : $default_minutes;

            // Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
            $html = '
						                    <input type="number" min="0" id="sip_popup_expiry" name="sip_plugin_options[sip_popup_expiry]" value="' . $expiry . '" placeholder="0 to disable"/>
		                                    <small style="display:block; margin-top:5px">Set 0 to disable. This means the popup will display every page load.</small>
		                                    ';

            echo $html;
        }


        public function sip_popup_before_show_callback($args)
        {

            // Field for expiry of popup cookie

            $options = get_option('sip_plugin_options');

            $default_seconds = 1;

            $expiry = isset($options['sip_popup_before_show']) ? $options['sip_popup_before_show'] : $default_seconds;

            // Note the ID and the name attribute of the element should match that of the ID in the call to add_settings_field
            $html = '
						                    <input type="number" min="0" id="sip_popup_before_show" name="sip_plugin_options[sip_popup_before_show]" value="' . $expiry . '" placeholder="0 to disable"/>
		                                    <small style="display:block; margin-top:5px">Set 0 to show immediately on page load.</small>
		                                    ';

            echo $html;
        }





        public function popup()
        {

            $options = get_option('sip_plugin_options');
            $image_url = isset($options['sip_image_url']) ? esc_url($options['sip_image_url']) : null;
            $link = isset($options['sip_link']) ? esc_html($options['sip_link']) : null;
            $active = isset($options['sip_plugin_status']) ? true : false;
            $expiry = isset($options['sip_popup_expiry']) ? $options['sip_popup_expiry'] : 0;
            $seconds_before_show = isset($options['sip_popup_before_show']) ? $options['sip_popup_before_show'] : 0;
            $seconds_before_show = $seconds_before_show * 1000;
            $clicktoclose = isset($options['sip_click_to_close']) ? true : false;
            $cookie_name = isset($options['sip_cookie_name']) ? esc_html($options['sip_cookie_name']) : null;
            $max_width = isset($options['sip_max_width']) ? $options['sip_max_width'] : null;

        ?>


            <?php if ($active && $image_url) : ?>
                <div id="simple-image-popup" class="simple-image-popup-plugin" style="display:none">

                    <div class="simple-image-popup-plugin__inner" style="width:<?php echo $max_width; ?>px; max-width:90%; margin:0 auto;">

                        <button id="simple-image-popup-plugin__close">

                            <svg class="simple-image-popup-plugin__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                <path d="M315.3 411.3c-6.253 6.253-16.37 6.253-22.63 0L160 278.6l-132.7 132.7c-6.253 6.253-16.37 6.253-22.63 0c-6.253-6.253-6.253-16.37 0-22.63L137.4 256L4.69 123.3c-6.253-6.253-6.253-16.37 0-22.63c6.253-6.253 16.37-6.253 22.63 0L160 233.4l132.7-132.7c6.253-6.253 16.37-6.253 22.63 0c6.253 6.253 6.253 16.37 0 22.63L182.6 256l132.7 132.7C321.6 394.9 321.6 405.1 315.3 411.3z" />
                            </svg>


                        </button>

                        <?php if ($link && !$clicktoclose) : ?>
                            <a href="<?php echo $link; ?>">
                            <?php endif; ?>

                            <img src="<?php echo $options['sip_image_url']; ?>" <?php if ($clicktoclose) : ?> id="closeimage" style="cursor:pointer" <?php endif; ?> class="simple-image-popup-plugin__image">

                            <?php if ($link && !$clicktoclose) : ?>
                            </a>
                        <?php endif; ?>

                    </div>
                </div>


                <script>
                    var $open = false;
                    var $popup = localStorage.getItem('<?php echo $cookie_name; ?>');

                    if (!$popup) {

                        var $time = new Date();

                        <?php if ($expiry) : ?>
                            $time.setMinutes($time.getMinutes() + <?php echo $expiry; ?>);
                        <?php endif; ?>

                        localStorage.setItem('<?php echo $cookie_name; ?>', $time);

                        $open = true;

                    } else {

                        var $time_now = new Date();
                        var $last_opened = new Date($popup);



                        if ($time_now >= $last_opened) {

                            $open = true;

                            localStorage.removeItem('<?php echo $cookie_name; ?>');

                            var $time = new Date();

                            <?php if ($expiry) : ?>
                                $time.setMinutes($time.getMinutes() + <?php echo $expiry; ?>);
                            <?php endif; ?>

                            localStorage.setItem('<?php echo $cookie_name; ?>', $time);
                        } else {
                            $open = false;
                        }

                    }




                    if ($open) {


                        jQuery(document).ready(function($) {

                            setTimeout(function() {
                                $('#simple-image-popup').fadeIn(300);
                            }, <?php echo $seconds_before_show; ?>);


                            $('#simple-image-popup-plugin__close').on('click', function() {

                                $('#simple-image-popup').fadeOut(300);

                            });


                            <?php if ($clicktoclose) : ?>


                                $('#simple-image-popup').on('click', function() {

                                    $('#simple-image-popup').fadeOut(300);

                                });


                            <?php endif; ?>

                        });


                    }
                </script>

            <?php endif; ?>


<?php

        }
    }
    $simpleImagePopup = new SimpleImagePopup();

endif;
