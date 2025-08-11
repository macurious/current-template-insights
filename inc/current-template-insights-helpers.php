<?php 
/**
 * Helper functions for Current Template Insights plugin.
 *
 * @package CurrentTemplateInsights
 */
defined( 'ABSPATH' ) || exit;

/**
 * Gets the post type for the current query or a given post ID.
 *
 * @param int|null $post_id Optional. Post ID for singular views.
 * @return string Post type slug or '---' if not available.
 */
function currtempinsights_get_current_post_type( $post_id = null ) {
    if ( is_singular() && $post_id ) {
        $post_type = get_post_type( $post_id );
    } else {
        $post_type = get_post_type();
        if ( empty( $post_type ) ) {
            $post_type = get_query_var( 'post_type' );
            if ( is_array( $post_type ) ) {
                $post_type = implode( ', ', $post_type );
            }
        }
    }
    return $post_type ? $post_type : '---';
}

/**
 * Builds and returns the template hierarchy for the current request.
 *
 * @return string Human-readable template hierarchy.
 */
function currtempinsights_get_template_hierarchy() {
  $template_hierarchy = array();
  if ( function_exists( 'get_body_class' ) ) {
      // Use WordPress template hierarchy logic to determine what templates were checked
      if ( is_404() ) {
          $template_hierarchy = array( '404.php', 'index.php' );
      } elseif ( is_search() ) {
          $template_hierarchy = array( 'search.php', 'index.php' );
      } elseif ( is_front_page() ) {
          $template_hierarchy = array( 'front-page.php', 'home.php', 'index.php' );
      } elseif ( is_home() ) {
          $template_hierarchy = array( 'home.php', 'index.php' );
      } elseif ( is_singular() ) {
          $post_type = get_post_type();
          $post_slug = get_post_field( 'post_name', get_queried_object_id() );
          $template_hierarchy = array(
              "single-{$post_type}-{$post_slug}.php",
              "single-{$post_type}.php",
              'single.php',
              'singular.php',
              'index.php'
          );
      } elseif ( is_category() ) {
          $cat_slug = get_queried_object()->slug;
          $cat_id = get_queried_object_id();
          $template_hierarchy = array(
              "category-{$cat_slug}.php",
              "category-{$cat_id}.php",
              'category.php',
              'archive.php',
              'index.php'
          );
      } elseif ( is_archive() ) {
          $template_hierarchy = array( 'archive.php', 'index.php' );
      } else {
          $template_hierarchy = array( 'index.php' );
      }
  }
  return implode( ' → ', $template_hierarchy );
}

/**
 * Returns a comma-separated list of true conditional tags for the current request.
 *
 * @return string List of active conditionals or 'none'.
 */
function currtempinsights_get_conditionals() {
  $conds = array();
  // Check each conditional tag and add to the list if true
  foreach ( [ 'home', 'front_page', 'singular', 'archive', 'search', 'author', 'date', 'category', 'tag', 'page', 'post_type_archive', 'attachment', '404', 'privacy_policy' ] as $cond ) {
      $func = 'is_' . $cond;
      if ( function_exists( $func ) && call_user_func( $func ) ) {
          $conds[] = $cond;
      }
  }
  return $conds ? implode( ', ', $conds ) : __( 'none', 'current-template-insights' );
}

/**
 * Returns a comma-separated list of active main query variables.
 *
 * @return string Query variables or 'none'.
 */
function currtempinsights_get_active_query_vars() {
    global $wp_query;
    $query_vars = array();
    // Check if query vars are set and not empty
    if ( ! empty( $wp_query->query_vars ) ) {
        foreach ( $wp_query->query_vars as $key => $value ) {
            if ( ! empty( $value ) && ! is_array( $value ) ) {
                $query_vars[] = "{$key}={$value}";
            }
        }
    }
    return $query_vars ? implode( ', ', $query_vars ) : __( 'none', 'current-template-insights' );
}

/**
 * Gets the appropriate slug for the current context (post, term, or archive).
 *
 * @return string The slug or '---' if not available.
 */
function currtempinsights_get_slug() {
  if ( is_singular() ) {
      // Single post/page/custom post type
      $post_slug = get_post_field( 'post_name', get_queried_object_id() );
  } elseif ( is_category() || is_tag() || is_tax() ) {
      // Taxonomy archive
      $term = get_queried_object();
      $post_slug = isset( $term->slug ) ? $term->slug : '---';
  } elseif ( is_post_type_archive() ) {
      $post_slug = get_query_var( 'post_type' );
      if ( is_array( $post_slug ) ) {
          $post_slug = implode( ', ', $post_slug );
      }
  } else {
      $post_slug = '---';
  }

  return $post_slug;
}