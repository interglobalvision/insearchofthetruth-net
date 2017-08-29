<?php

// Custom filters (like pre_get_posts etc)

// Page Slug Body Class
function add_slug_body_class( $classes ) {
  global $post;
  if (isset($post) && !is_home() && !is_archive()) {
    $classes[] = $post->post_type . '-' . $post->post_name;
  }
  return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

// Custom img attributes to be compatible with lazysize
function add_lazysize_on_srcset($attr) {

  if (!is_admin()) {

    // if image has data-no-lazysizes attribute dont add lazysizes classes
    if (isset($attr['data-no-lazysizes'])) {
      unset($attr['data-no-lazysizes']);
      return $attr;
    }

    // Add lazysize class
    $attr['class'] .= ' lazyload';

    if (isset($attr['srcset'])) {
      // Add lazysize data-srcset
      $attr['data-srcset'] = $attr['srcset'];
      // Remove default srcset
      unset($attr['srcset']);
    } else {
      // Add lazysize data-src
      $attr['data-src'] = $attr['src'];
    }

    // Set default to white blank
    $attr['src'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAABCAQAAABTNcdGAAAAC0lEQVR42mNkgAIAABIAAmXG3J8AAAAASUVORK5CYII=';

  }

  return $attr;

}
add_filter('wp_get_attachment_image_attributes', 'add_lazysize_on_srcset');

// This adds the height of the original image to the size in the srcset array that
// matches the original size.
//
// This is added to the srcset in order to make lazysizes work with background images
// via it's plugins bgset and parent-fit to work with background size cover.
// Ref. https://github.com/aFarkas/lazysizes/tree/gh-pages/plugins/bgset
//
// Eg.
// Array (
//  [1552] => Array (
//   [url] => http://localhost:8080/insearchofthetruth-net/wp-content/uploads/2017/08/e8b548419ee0ceaf94dbe9f8ce0a6f2f_original.jpg
//   [descriptor] => h
//   [value] => 1552w 873
//   )
// )
//
// NOTE: This is very hacky but works well and doesn't break anything
function add_height_size_to_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {

  // Get original image dimensions
  $width = $size_array[0];
  $height = $size_array[1];

  if (!empty($sources[$width])) {
    // Add the height value to the `value` in the original size
    // $sources is an array that uses the width of each size as the array index
    $sources[$width]['value'] = $width . 'w ' . $height;

    // Replace the descriptor for h
    $sources[$width]['descriptor'] = 'h ';
  }


  return $sources;
};
add_filter( 'wp_calculate_image_srcset', 'add_height_size_to_srcset', 10, 5 );
