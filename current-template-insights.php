<?php
/**
 * Plugin Name: Current Template Insights
 * Description: Displays the current template file and key page info in the front end admin bar. No configuration required.
 * Version: 1.0.2
 * Plugin URI: https://wordpress.org/plugins/current-template-insights/
 * Author: Mark Colling
 * Author URI: https://mark-colling.com/
 * Requires at least: 5.5
 * Tested up to: 6.8.2
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: current-template-insights
 * Domain Path: /languages
 */

/**
 * Main plugin file for Current Template Insights plugin with includes, constants ...
 *
 * @package CurrentTemplateInsights
 */
defined( 'ABSPATH' ) || exit;

// Constant for plugin version
if ( ! defined( 'CURRTEMPINSIGHTS_VERSION' ) ) {
    $currtempinsights_plugin_data = get_file_data( __FILE__, array( 'Version' => 'Version' ) );
    define( 'CURRTEMPINSIGHTS_VERSION', $currtempinsights_plugin_data['Version'] );
}

// Constant for plugin path
if ( ! defined( 'CURRTEMPINSIGHTS_PATH' ) ) {
    define( 'CURRTEMPINSIGHTS_PATH', plugin_dir_path( __FILE__ ) );
}

// Constant for plugin url
if ( ! defined( 'CURRTEMPINSIGHTS_URL' ) ) {
    define( 'CURRTEMPINSIGHTS_URL', plugin_dir_url( __FILE__ ) );
}

// Check if required files exist before including
if ( ! file_exists( CURRTEMPINSIGHTS_PATH . 'inc/current-template-insights-data.php' ) || 
     ! file_exists( CURRTEMPINSIGHTS_PATH . 'inc/current-template-insights-render.php' ) ||
     ! file_exists( CURRTEMPINSIGHTS_PATH . 'inc/current-template-insights-helpers.php' ) ) {
    return; 
}

// Include data gathering functions
require_once CURRTEMPINSIGHTS_PATH . 'inc/current-template-insights-data.php';

// Include rendering functions
require_once CURRTEMPINSIGHTS_PATH . 'inc/current-template-insights-render.php';

// Include helper functions
require_once CURRTEMPINSIGHTS_PATH . 'inc/current-template-insights-helpers.php';

/**
 * Enqueues the plugin’s custom CSS for the front-end admin bar.
 *
 * @return void
 */
function currtempinsights_enqueue_styles() {
    if ( ! is_admin_bar_showing() || ! current_user_can( 'manage_options' ) ) {
        return;
    }
    wp_enqueue_style(
        'current-template-insights-adminbar-css',
        CURRTEMPINSIGHTS_URL . 'assets/current-template-insights.css',
        array( 'admin-bar' ), // ensure it loads after the admin bar styles
        CURRTEMPINSIGHTS_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'currtempinsights_enqueue_styles', 100 );

/**
 * Adds the Current Template insights menu item to the WordPress admin bar.
 *
 * @param WP_Admin_Bar $wp_admin_bar The admin bar object.
 * @return void
 */
function currtempinsights_add_admin_bar( $wp_admin_bar ) {
    if ( ! is_admin_bar_showing() || ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Get all data
    $data = currtempinsights_get_debug_data();

    // Check if we have valid data
    if ( empty( $data['template_info'] ) || empty( $data['details'] ) ) {
        return;
    }

    // Render the admin bar
    currtempinsights_render_admin_bar( $wp_admin_bar, $data['details'], $data['template_info'] );
}
add_action( 'admin_bar_menu', 'currtempinsights_add_admin_bar', 200 );

/**
 * Displays an admin notice if the admin bar is disabled on the front-end.
 * @return void
 */
function currtempinsights_admin_bar_notice() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $user_id = get_current_user_id();
    $user_pref = get_user_meta( $user_id, 'show_admin_bar_front', true );
    $is_enabled = apply_filters( 'show_admin_bar', true );

    /* translators: 1: opening <strong> tag, 2: closing </strong> tag. */
    $notice = sprintf(
        __( '%1$sCurrent Template Insights%2$s requires the WordPress admin bar to be enabled on the front end in order to display template details.', 'current-template-insights' ),
        '<strong>',
        '</strong>'
    );
    $allowed = array(
        'strong' => array(),
    );

    if ( $user_pref === 'false' || ! $is_enabled ) {
        echo '<div class="notice notice-warning"><p>' . wp_kses( $notice, $allowed ) . '</p></div>';
    }
}
add_action( 'admin_notices', 'currtempinsights_admin_bar_notice' );