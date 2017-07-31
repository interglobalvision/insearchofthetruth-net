<?php

if( function_exists( 'add_theme_support' ) ) {
  add_theme_support( 'post-thumbnails' );
}

if( function_exists( 'add_image_size' ) ) {
  add_image_size( 'admin-thumb', 150, 150, false );
  add_image_size( 'opengraph', 1200, 630, true );

  add_image_size( 'single-big', 1800, 1013, false ); // 16:9
  add_image_size( 'item-l-4', 600, 800, false );
  add_image_size( 'gallery', 1200, 9999, false );
}
