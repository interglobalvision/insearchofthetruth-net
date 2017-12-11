<?php

// Enqueue

function scripts_and_styles_method() {
  $templateuri = get_template_directory_uri();

  if (WP_DEBUG) {
    $javascriptLibrary = $templateuri . '/dist/js/library.js';
    $javascriptMain = $templateuri . '/src/js/main.js';
  } else {
    $javascriptLibrary = $templateuri . '/dist/js/library.min.js';
    $javascriptMain = $templateuri . '/dist/js/main.min.js';
  }

  $is_admin = current_user_can('administrator') ? true : false;

  $javascriptVars = array(
    'siteUrl' => home_url(),
    'themeUrl' => get_template_directory_uri(),
    'isAdmin' => $is_admin,
    'wp_debug' => WP_DEBUG,
  );

  wp_enqueue_script('javascript-library', $javascriptLibrary, '', '', true);

  if (is_front_page()) {
    // Get Locations taxonomy
    $locations = array_merge(array(), get_locations_data());

    // To be used in `WP`
    $javascriptVars['locations'] = $locations;
  }

  wp_register_script('javascript-main', $javascriptMain);
  wp_localize_script('javascript-main', 'WP', $javascriptVars);
  wp_enqueue_script('javascript-main', $javascriptMain, '', '', true);

  if (is_front_page()) {
    // Enqueue youtube api
    wp_enqueue_script('yt-player-api', 'http://www.youtube.com/player_api', array(), false, true);

    // Get google api id from site options
    $google_api_key = IGV_get_option('_igv_site_options', '_igv_google_api_key');

    if (!empty($google_api_key)) {
      // Enqueue google maps api
      wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . $google_api_key . '&async&callback=Site.Map.init', array(), false, true);

    }
  }

  wp_enqueue_style( 'style-site', get_stylesheet_directory_uri() . '/dist/css/site.min.css' );

  // dashicons for admin
  if (is_admin()) {
    wp_enqueue_style( 'dashicons' );
  }
}
add_action('wp_enqueue_scripts', 'scripts_and_styles_method');

// Declare thumbnail sizes

get_template_part( 'lib/thumbnail-sizes' );

// Register Nav Menus
/*
register_nav_menus( array(
  'menu_location' => 'Location Name',
) );
*/

// Add third party PHP libs

function cmb_initialize_cmb_meta_boxes() {
  if (!class_exists( 'cmb2_bootstrap_202' ) ) {
    require_once 'vendor/webdevstudios/cmb2/init.php';
  }
}
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 11 );

function composer_autoload() {
  require_once( 'vendor/autoload.php' );
}
add_action( 'init', 'composer_autoload', 10 );

// Add libs

get_template_part( 'lib/custom-gallery' );
get_template_part( 'lib/post-types' );
get_template_part( 'lib/meta-boxes' );
get_template_part( 'lib/theme-options/theme-options' );

// Add custom functions

get_template_part( 'lib/functions-misc' );
get_template_part( 'lib/functions-custom' );
get_template_part( 'lib/functions-filters' );
get_template_part( 'lib/functions-hooks' );
get_template_part( 'lib/functions-utility' );

?>
