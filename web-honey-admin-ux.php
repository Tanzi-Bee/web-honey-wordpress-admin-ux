<?php
/**
 * Plugin Name: Web Honey Admin UX
 * Plugin URI: https://webhoney.digital
 * Description: A clean, simplified WordPress admin experience. Designed for clients, built by Web Honey Digital.
 * Version: 1.2
 * Author: Web Honey Digital
 * Author URI: https://webhoney.digital
 * License: GPL2
 * Text Domain: web-honey-admin-ux
 */

// Load only in admin area
if (is_admin()) {

    // 1. Clean Dashboard Widgets
    add_action('wp_dashboard_setup', function () {
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    });

    // 2. Custom Welcome Widget
    add_action('wp_dashboard_setup', function () {
        wp_add_dashboard_widget('wh_adminux_help_widget', 'Welcome', function () {
            echo '<p>Welcome to your site dashboard. Need help? Contact Web Honey Digital.</p>';
        });
    });

    // 3. Enqueue Custom Admin CSS
    add_action('admin_enqueue_scripts', function () {
        wp_enqueue_style('wh-adminux-style', plugin_dir_url(__FILE__) . 'admin-style.css');
    });

    // 4. Group Plugin Menus Under "Plugins"
    add_action('admin_menu', function () {
        global $menu, $submenu;

        $plugin_menus = [
            'wpseo_dashboard',
            'gf_edit_forms',
            'wpcf7',
            'elementor',
            'edit.php?post_type=wpforms',
            'rank-math',
            'redux-about',
            'duplicator'
        ];

        foreach ($plugin_menus as $slug) {
            foreach ($menu as $index => $item) {
                if (isset($item[2]) && $item[2] === $slug) {
                    $submenu['plugins.php'][] = [$item[0], $item[1], $item[2]];
                    unset($menu[$index]);
                }
            }
        }
    }, 999);

    // 5. Force Spectra & Menu Image into Plugins submenu
    add_action('admin_menu', function () {
        remove_menu_page('spectra');
        add_submenu_page('plugins.php', 'Spectra', 'Spectra', 'manage_options', 'spectra');

        remove_menu_page('menu-image');
        add_submenu_page('plugins.php', 'Menu Image', 'Menu Image', 'manage_options', 'menu-image');
    }, 999);

    // 6. Custom Menu Order
    add_filter('custom_menu_order', '__return_true');
    add_filter('menu_order', function ($menu_order) {
        if (!$menu_order) return true;

        return [
            'index.php',                // Dashboard
            'edit.php',                 // Posts
            'upload.php',               // Media
            'edit.php?post_type=page',  // Pages
            'themes.php',               // Appearance
            'customize.php',            // Customiser
            'tools.php',                // Tools
            'options-general.php',      // Settings
            'plugins.php',              // Plugins
        ];
    });
}
