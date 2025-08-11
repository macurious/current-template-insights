<?php 
/**
 * Rendering functions for Current Template Insights plugin.
 *
 * @package CurrentTemplateInsights
 */
defined( 'ABSPATH' ) || exit;

/**
 * Renders the debug insights dropdown in the WordPress admin bar.
 *
 * @param WP_Admin_Bar $wp_admin_bar The WordPress admin bar object.
 * @param array        $details      Array of debug details to display.
 * @param array        $template_info Template file info.
 * @return void
 */
function currtempinsights_render_admin_bar( $wp_admin_bar, $details, $template_info ) {

    // Add top-level menu item showing template filename
    $wp_admin_bar->add_node( array(
        'id'    => 'current-template-insights',
        'title' => '📄 Template: <span>' . esc_html( $template_info['file'] ) . '</span>',
        'meta'  => array(
            'title' => __( 'Current Template File', 'current-template-insights' ),
            'class' => 'current-template-insights-bar',
        ),
        'after'  => 'edit'
    ) );

    // Create mapping for consistent English IDs
    $currtempinsights_id_mapping = array(
        __( 'Full Path', 'current-template-insights' )          => 'full-path',
        __( 'Template Hierarchy', 'current-template-insights' ) => 'template-hierarchy',
        __( 'Request URI', 'current-template-insights' )        => 'request-uri',
        __( 'Main Query', 'current-template-insights' )         => 'main-query',
        __( 'Post Type', 'current-template-insights' )          => 'post-type',
        __( 'Post ID', 'current-template-insights' )            => 'post-id',
        __( 'Slug', 'current-template-insights' )               => 'slug',
        __( 'Query Vars', 'current-template-insights' )         => 'query-vars',
        __( 'Conditionals', 'current-template-insights' )       => 'conditionals',
        __( 'Body Classes', 'current-template-insights' )       => 'body-classes',
        __( 'Database Queries', 'current-template-insights' )   => 'database-queries',
        __( 'Query Time', 'current-template-insights' )         => 'query-time',
        __( 'Memory Usage', 'current-template-insights' )       => 'memory-usage',
        __( 'Theme', 'current-template-insights' )              => 'theme',
        __( 'Theme Version', 'current-template-insights' )      => 'theme-version',
        __( 'WP Version', 'current-template-insights' )         => 'wp-version',
        __( 'PHP Version', 'current-template-insights' )        => 'php-version',
        __( 'Locale', 'current-template-insights' )             => 'locale',
        __( 'Permalink Structure', 'current-template-insights' ) => 'permalink-structure',
    );

    // Add each detail as sub-item in admin bar
    foreach ( $details as $label => $value ) {
        $id_key = isset( $currtempinsights_id_mapping[$label] ) ? $currtempinsights_id_mapping[$label] : sanitize_title( $label );
        $wp_admin_bar->add_node( array(
            'id' => 'current-template-insights-' . $id_key,
            'parent' => 'current-template-insights',
            'title' => esc_html( "{$label}: {$value}" ),
        ) );
    }
}
