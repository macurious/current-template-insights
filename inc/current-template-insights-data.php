<?php
/**
 * Data gathering functions for Current Template Insights plugin.
 *
 * @package CurrentTemplateInsights
 */
defined( 'ABSPATH' ) || exit;

/**
 * Gathers and returns debug details for the admin bar.
 *
 * @return array Associative array of detail labels and values.
 */
function currtempinsights_get_debug_data() {

    // Get template info
    global $template;
    $currtempinsights_template = $template;
    if ( empty( $currtempinsights_template ) ) {
        return array();
    }
    $currtempinsights_template_file = basename( $currtempinsights_template );
    $currtempinsights_template_path = str_replace( ABSPATH, '', $currtempinsights_template );

    // Get post ID
    $currtempinsights_queried_object = get_queried_object();
    $currtempinsights_post_id = ( $currtempinsights_queried_object && isset( $currtempinsights_queried_object->ID ) ) ? $currtempinsights_queried_object->ID : '---';

    // Get post type
    $currtempinsights_post_type = currtempinsights_get_current_post_type( $currtempinsights_post_id );

    // Get slug
    $currtempinsights_post_slug = currtempinsights_get_slug();

    // Get request URI
    $currtempinsights_request_uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) );

    // Get template hierarchy
    $currtempinsights_hierarchy_display = currtempinsights_get_template_hierarchy();

    // Get conditionals that are true
    $currtempinsights_conditionals = currtempinsights_get_conditionals();

    // Get all active query vars
    $currtempinsights_query_vars_display = currtempinsights_get_active_query_vars();

    // Get body classes
    $currtempinsights_body_classes = get_body_class();
    $currtempinsights_body_classes = is_array( $currtempinsights_body_classes ) ? $currtempinsights_body_classes : array();

    // Get database queries
    global $wpdb;
    $currtempinsights_db_queries = $wpdb->num_queries;

    // Get query time
    $currtempinsights_query_time = timer_stop( 0, 3 ) . 's';

    // Get memory usage
    $currtempinsights_memory_usage = round( memory_get_peak_usage() / 1024 / 1024, 2 ) . ' MB';

    // Get active theme
    $currtempinsights_active_theme = wp_get_theme()->get( 'Name' ) ?: '---';

    // Get active theme version
    $currtempinsights_active_theme_version = wp_get_theme()->get( 'Version' ) ?: '---';

    // Get WP version
    $currtempinsights_wp_version = get_bloginfo( 'version' ) ?: '---';

    // Get PHP version
    $currtempinsights_php_version = phpversion() ?: '---';

    // Get locale
    $currtempinsights_locale = get_locale() ?: '---';

    // Get permalink structure
    $currtempinsights_permalink_structure = get_option( 'permalink_structure' ) ?: __( 'default', 'current-template-insights' );

    // Get main query status
    global $wp_query;
    $currtempinsights_main_query = $wp_query->is_main_query() ? __( 'Yes', 'current-template-insights' ) : __( 'No', 'current-template-insights' );

    $currtempinsights_details = array(
        // --- Template & Request Context ---
        __( 'Full Path', 'current-template-insights' )          => $currtempinsights_template_path,
        __( 'Template Hierarchy', 'current-template-insights' ) => $currtempinsights_hierarchy_display,
        __( 'Request URI', 'current-template-insights' )        => $currtempinsights_request_uri,

        // --- Query State ---
        __( 'Main Query', 'current-template-insights' )         => $currtempinsights_main_query, 
        __( 'Post Type', 'current-template-insights' )          => $currtempinsights_post_type,
        __( 'Post ID', 'current-template-insights' )            => $currtempinsights_post_id,
        __( 'Slug', 'current-template-insights' )               => $currtempinsights_post_slug,
        __( 'Query Vars', 'current-template-insights' )         => $currtempinsights_query_vars_display,

        // --- Conditionals & Classes ---
        __( 'Conditionals', 'current-template-insights' )       => $currtempinsights_conditionals,
        __( 'Body Classes', 'current-template-insights' )       => implode( ' ', $currtempinsights_body_classes ),

        // --- Performance ---
        __( 'Database Queries', 'current-template-insights' )   => $currtempinsights_db_queries,
        __( 'Query Time', 'current-template-insights' )         => $currtempinsights_query_time,
        __( 'Memory Usage', 'current-template-insights' )       => $currtempinsights_memory_usage,

        // --- Environment ---
        __( 'Theme', 'current-template-insights' )              => $currtempinsights_active_theme,
        __( 'Theme Version', 'current-template-insights' )      => $currtempinsights_active_theme_version,
        __( 'WP Version', 'current-template-insights' )         => $currtempinsights_wp_version,
        __( 'PHP Version', 'current-template-insights' )        => $currtempinsights_php_version,
        __( 'Locale', 'current-template-insights' )             => $currtempinsights_locale,
        __( 'Permalink Structure', 'current-template-insights' ) => $currtempinsights_permalink_structure,
    );

    // Allow other plugins/themes to modify the details
    $currtempinsights_details = apply_filters( 'current_template_insights_details', $currtempinsights_details );

    // Return both template info and debug details
    return array(
        'template_info' => array(
            'file' => $currtempinsights_template_file,
            'path' => $currtempinsights_template_path
        ),
        'details' => $currtempinsights_details
    );
}