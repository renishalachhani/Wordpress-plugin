<?php
/*
Plugin Name: Custom Login Page
Description: A simple plugin to customize the WordPress login page.
Version: 1.4
Author: Renisha Lachhani
*/

// Hook to enqueue custom styles
add_action('login_enqueue_scripts', 'custom_login_styles');

function custom_login_styles() {
    $background_color = get_option('custom_login_background_color', '#f1f1f1');
    $logo_url = get_option('custom_login_logo_url', plugin_dir_url(__FILE__) . 'images/custom-logo.png');
    $button_color = get_option('custom_login_button_color', '#0073aa');
    $button_hover_color = get_option('custom_login_button_hover_color', '#005177');
    $text_color = get_option('custom_login_text_color', '#333');
    $link_color = get_option('custom_login_link_color', '#0073aa');
    $link_hover_color = get_option('custom_login_link_hover_color', '#005177');
    $custom_css = get_option('custom_login_custom_css', '');
    $custom_message = get_option('custom_login_custom_message', '');
    $font_family = get_option('custom_login_font_family', 'Arial, sans-serif');
    $font_size = get_option('custom_login_font_size', '14px');
    $padding = get_option('custom_login_padding', '20px');
    $margin = get_option('custom_login_margin', '10px');
    $border_radius = get_option('custom_login_border_radius', '5px');
    $box_shadow = get_option('custom_login_box_shadow', '0 1px 3px rgba(0,0,0,.13)');
    ?>
    <style type="text/css">
        body.login {
            background-color: <?php echo esc_attr($background_color); ?>;
            font-family: <?php echo esc_attr($font_family); ?>;
            font-size: <?php echo esc_attr($font_size); ?>;
        }
        body.login div#login {
            padding: <?php echo esc_attr($padding); ?>;
            margin: <?php echo esc_attr($margin); ?>;
        }
        body.login div#login h1 a {
            background-image: url('<?php echo esc_url($logo_url); ?>');
            background-size: contain;
            width: 100%;
            height: 80px;
        }
        .login form {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: <?php echo esc_attr($border_radius); ?>;
            box-shadow: <?php echo esc_attr($box_shadow); ?>;
        }
        .login form .input, .login form input[type="checkbox"], .login form input[type="text"], .login form input[type="password"], .login form input[type="email"] {
            color: <?php echo esc_attr($text_color); ?>;
        }
        .login form input[type="submit"] {
            background: <?php echo esc_attr($button_color); ?>;
            border-color: <?php echo esc_attr($button_color); ?>;
            color: #fff;
        }
        .login form input[type="submit"]:hover {
            background: <?php echo esc_attr($button_hover_color); ?>;
            border-color: <?php echo esc_attr($button_hover_color); ?>;
        }
        .login #backtoblog a, .login #nav a {
            color: <?php echo esc_attr($link_color); ?>;
        }
        .login #backtoblog a:hover, .login #nav a:hover {
            color: <?php echo esc_attr($link_hover_color); ?>;
        }
        <?php echo esc_html($custom_css); ?>
    </style>
    <?php
    if ($custom_message) {
        echo '<div class="custom-message">' . wp_kses_post($custom_message) . '</div>';
    }

    // Integrate with reCAPTCHA if enabled
    if (get_option('custom_login_enable_recaptcha')) {
        ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <div class="g-recaptcha" data-sitekey="<?php echo esc_attr(get_option('custom_login_recaptcha_site_key')); ?>"></div>
        <?php
    }
}

// Hook to change the logo URL
add_filter('login_headerurl', 'custom_login_logo_url');

function custom_login_logo_url() {
    return home_url();
}

// Hook to change the logo title
add_filter('login_headertext', 'custom_login_logo_url_title');

function custom_login_logo_url_title() {
    return get_bloginfo('name');
}

// Add a settings page to the admin menu
add_action('admin_menu', 'custom_login_settings_page');

function custom_login_settings_page() {
    add_options_page(
        'Custom Login Page Settings',
        'Custom Login',
        'manage_options',
        'custom-login-settings',
        'custom_login_settings_page_html'
    );
}

// Register settings
add_action('admin_init', 'custom_login_register_settings');

function custom_login_register_settings() {
    register_setting('custom_login_settings', 'custom_login_background_color', 'sanitize_hex_color');
    register_setting('custom_login_settings', 'custom_login_logo_url', 'esc_url_raw');
    register_setting('custom_login_settings', 'custom_login_button_color', 'sanitize_hex_color');
    register_setting('custom_login_settings', 'custom_login_button_hover_color', 'sanitize_hex_color');
    register_setting('custom_login_settings', 'custom_login_text_color', 'sanitize_hex_color');
    register_setting('custom_login_settings', 'custom_login_link_color', 'sanitize_hex_color');
    register_setting('custom_login_settings', 'custom_login_link_hover_color', 'sanitize_hex_color');
    register_setting('custom_login_settings', 'custom_login_custom_css', 'wp_strip_all_tags');
    register_setting('custom_login_settings', 'custom_login_custom_message', 'wp_kses_post');
    register_setting('custom_login_settings', 'custom_login_font_family', 'sanitize_text_field');
    register_setting('custom_login_settings', 'custom_login_font_size', 'sanitize_text_field');
    register_setting('custom_login_settings', 'custom_login_padding', 'sanitize_text_field');
    register_setting('custom_login_settings', 'custom_login_margin', 'sanitize_text_field');
    register_setting('custom_login_settings', 'custom_login_border_radius', 'sanitize_text_field');
    register_setting('custom_login_settings', 'custom_login_box_shadow', 'sanitize_text_field');
    register_setting('custom_login_settings', 'custom_login_enable_recaptcha', 'sanitize_text_field');
    register_setting('custom_login_settings', 'custom_login_recaptcha_site_key', 'sanitize_text_field');
    register_setting('custom_login_settings', 'custom_login_recaptcha_secret_key', 'sanitize_text_field');
}

// Settings page HTML
function custom_login_settings_page_html() {
    ?>
    <div class="wrap">
        <h1>Custom Login Page Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('custom_login_settings');
            do_settings_sections('custom_login_settings');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Background Color</th>
                    <td><input type="text" name="custom_login_background_color" value="<?php echo esc_attr(get_option('custom_login_background_color', '#f1f1f1')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Logo URL</th>
                    <td><input type="text" name="custom_login_logo_url" value="<?php echo esc_url(get_option('custom_login_logo_url', plugin_dir_url(__FILE__) . 'images/custom-logo.png')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Button Color</th>
                    <td><input type="text" name="custom_login_button_color" value="<?php echo esc_attr(get_option('custom_login_button_color', '#0073aa')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Button Hover Color</th>
                    <td><input type="text" name="custom_login_button_hover_color" value="<?php echo esc_attr(get_option('custom_login_button_hover_color', '#005177')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Text Color</th>
                    <td><input type="text" name="custom_login_text_color" value="<?php echo esc_attr(get_option('custom_login_text_color', '#333')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Link Color</th>
                    <td><input type="text" name="custom_login_link_color" value="<?php echo esc_attr(get_option('custom_login_link_color', '#0073aa')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Link Hover Color</th>
                    <td><input type="text" name="custom_login_link_hover_color" value="<?php echo esc_attr(get_option('custom_login_link_hover_color', '#005177')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Font Family</th>
                    <td><input type="text" name="custom_login_font_family" value="<?php echo esc_attr(get_option('custom_login_font_family', 'Arial, sans-serif')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Font Size</th>
                    <td><input type="text" name="custom_login_font_size" value="<?php echo esc_attr(get_option('custom_login_font_size', '14px')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Padding</th>
                    <td><input type="text" name="custom_login_padding" value="<?php echo esc_attr(get_option('custom_login_padding', '20px')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Margin</th>
                    <td><input type="text" name="custom_login_margin" value="<?php echo esc_attr(get_option('custom_login_margin', '10px')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Border Radius</th>
                    <td><input type="text" name="custom_login_border_radius" value="<?php echo esc_attr(get_option('custom_login_border_radius', '5px')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Box Shadow</th>
                    <td><input type="text" name="custom_login_box_shadow" value="<?php echo esc_attr(get_option('custom_login_box_shadow', '0 1px 3px rgba(0,0,0,.13)')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Custom CSS</th>
                    <td><textarea name="custom_login_custom_css" rows="5" cols="50"><?php echo esc_html(get_option('custom_login_custom_css', '')); ?></textarea></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Custom Message</th>
                    <td><textarea name="custom_login_custom_message" rows="5" cols="50"><?php echo esc_html(get_option('custom_login_custom_message', '')); ?></textarea></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Enable reCAPTCHA</th>
                    <td><input type="checkbox" name="custom_login_enable_recaptcha" value="1" <?php checked(1, get_option('custom_login_enable_recaptcha', 0)); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">reCAPTCHA Site Key</th>
                    <td><input type="text" name="custom_login_recaptcha_site_key" value="<?php echo esc_attr(get_option('custom_login_recaptcha_site_key', '')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">reCAPTCHA Secret Key</th>
                    <td><input type="text" name="custom_login_recaptcha_secret_key" value="<?php echo esc_attr(get_option('custom_login_recaptcha_secret_key', '')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <form method="post">
            <input type="hidden" name="custom_login_reset" value="1"/>
            <?php submit_button('Reset to Defaults', 'delete'); ?>
        </form>
    </div>
    <?php
}

// Handle reset settings
add_action('admin_post_custom_login_reset', 'custom_login_reset_settings');

function custom_login_reset_settings() {
    if (isset($_POST['custom_login_reset'])) {
        $options = [
            'custom_login_background_color',
            'custom_login_logo_url',
            'custom_login_button_color',
            'custom_login_button_hover_color',
            'custom_login_text_color',
            'custom_login_link_color',
            'custom_login_link_hover_color',
            'custom_login_custom_css',
            'custom_login_custom_message',
            'custom_login_font_family',
            'custom_login_font_size',
            'custom_login_padding',
            'custom_login_margin',
            'custom_login_border_radius',
            'custom_login_box_shadow',
            'custom_login_enable_recaptcha',
            'custom_login_recaptcha_site_key',
            'custom_login_recaptcha_secret_key',
        ];
        foreach ($options as $option) {
            delete_option($option);
        }
        wp_redirect(admin_url('options-general.php?page=custom-login-settings'));
        exit;
    }
}

// Add Customizer support for live preview
add_action('customize_register', 'custom_login_customize_register');

function custom_login_customize_register($wp_customize) {
    $wp_customize->add_section('custom_login_section', array(
        'title' => 'Custom Login Page',
        'priority' => 30,
    ));

    $wp_customize->add_setting('custom_login_background_color', array(
        'default' => '#f1f1f1',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'custom_login_background_color', array(
        'label' => 'Background Color',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_background_color',
    )));

    $wp_customize->add_setting('custom_login_logo_url', array(
        'default' => plugin_dir_url(__FILE__) . 'images/custom-logo.png',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_logo_url', array(
        'label' => 'Logo URL',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_logo_url',
        'type' => 'text',
    ));

    $wp_customize->add_setting('custom_login_font_family', array(
        'default' => 'Arial, sans-serif',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_font_family', array(
        'label' => 'Font Family',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_font_family',
        'type' => 'text',
    ));

    $wp_customize->add_setting('custom_login_font_size', array(
        'default' => '14px',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_font_size', array(
        'label' => 'Font Size',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_font_size',
        'type' => 'text',
    ));

    $wp_customize->add_setting('custom_login_padding', array(
        'default' => '20px',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_padding', array(
        'label' => 'Padding',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_padding',
        'type' => 'text',
    ));

    $wp_customize->add_setting('custom_login_margin', array(
        'default' => '10px',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_margin', array(
        'label' => 'Margin',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_margin',
        'type' => 'text',
    ));

    $wp_customize->add_setting('custom_login_border_radius', array(
        'default' => '5px',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_border_radius', array(
        'label' => 'Border Radius',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_border_radius',
        'type' => 'text',
    ));

    $wp_customize->add_setting('custom_login_box_shadow', array(
        'default' => '0 1px 3px rgba(0,0,0,.13)',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_box_shadow', array(
        'label' => 'Box Shadow',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_box_shadow',
        'type' => 'text',
    ));

    // Add more settings and controls for other options as needed

    $wp_customize->add_setting('custom_login_custom_css', array(
        'default' => '',
        'sanitize_callback' => 'wp_strip_all_tags',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_custom_css', array(
        'label' => 'Custom CSS',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_custom_css',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('custom_login_custom_message', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_custom_message', array(
        'label' => 'Custom Message',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_custom_message',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('custom_login_enable_recaptcha', array(
        'default' => 0,
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_enable_recaptcha', array(
        'label' => 'Enable reCAPTCHA',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_enable_recaptcha',
        'type' => 'checkbox',
    ));

    $wp_customize->add_setting('custom_login_recaptcha_site_key', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_recaptcha_site_key', array(
        'label' => 'reCAPTCHA Site Key',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_recaptcha_site_key',
        'type' => 'text',
    ));

    $wp_customize->add_setting('custom_login_recaptcha_secret_key', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('custom_login_recaptcha_secret_key', array(
        'label' => 'reCAPTCHA Secret Key',
        'section' => 'custom_login_section',
        'settings' => 'custom_login_recaptcha_secret_key',
        'type' => 'text',
    ));
}

// Enqueue customizer preview script
add_action('customize_preview_init', 'custom_login_customizer_preview');

function custom_login_customizer_preview() {
    wp_enqueue_script('custom-login-customizer-preview', plugin_dir_url(__FILE__) . 'customizer-preview.js', array('customize-preview'), null, true);
}

