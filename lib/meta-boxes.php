<?php

/* Get post objects for select field options */
function get_post_objects( $query_args ) {
$args = wp_parse_args( $query_args, array(
    'post_type' => 'post',
) );
$posts = get_posts( $args );
$post_options = array();
if ( $posts ) {
    foreach ( $posts as $post ) {
        $post_options [ $post->ID ] = $post->post_title;
    }
}
return $post_options;
}


/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Hook in and add metaboxes. Can only happen on the 'cmb2_init' hook.
 */
add_action( 'cmb2_init', 'igv_cmb_metaboxes' );
function igv_cmb_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_igv_';

	/**
	 * Metaboxes declarations here
   * Reference: https://github.com/WebDevStudios/CMB2/blob/master/example-functions.php
	 */

  // PORTRAIT
  $cmb_portrait = new_cmb2_box( array(
    'id'            => $prefix . 'portrait_metabox',
    'title'         => esc_html__( 'Options', 'cmb2' ),
    'object_types'  => array( 'portrait', ), // Post type
  ));

  $cmb_portrait->add_field( array(
  	'name'       => esc_html__( 'Youtube ID', 'cmb2' ),
  	'desc'       => esc_html__( 'From the video URL. 11 character string after watch?v=', 'cmb2' ),
  	'id'         => $prefix . 'youtube_id',
  	'type'       => 'text',
  ) );

  //PRODUCT
  $cmb_product = new_cmb2_box( array(
    'id'            => $prefix . 'product_metabox',
    'title'         => esc_html__( 'Options', 'cmb2' ),
    'object_types'  => array( 'product', ), // Post type
  ));

  $cmb_product->add_field( array(
    'name' => esc_html__( 'Price USD', 'cmb2' ),
  'desc' => esc_html__( '$', 'cmb2' ),
  'id'   => $prefix . 'product_price',
  'type' => 'text_small',
) );

  $cmb_product->add_field( array(
    'name' => esc_html__( 'PayPal Embed Code', 'cmb2' ),
    'desc' => esc_html__( '
    [1] https://www.paypal.com/webapps/mpp/merchant-services-hub
    [2] Click on "Create payment buttons for your website"
    [3] Choose a button type: Buy Now
    [4] Enter Item name, Price, Shipping (optional), Tax (optional)
    [5] If your item has options (eg. Size): Add a drop-down menu
    [6] Click "Create Button"
    [7] Copy form code and paste here
', 'cmb2' ),
    'id'   => $prefix . 'paypal_embed',
    'type' => 'textarea_code',
  ) );

  // HOME PAGE METABOXES
  $home_page = get_page_by_path('home');

  if (!empty($home_page) ) {

    $cmb_home = new_cmb2_box( array(
      'id'            => $prefix . 'home_metabox',
      'title'         => esc_html__( 'Splash Images', 'cmb2' ),
      'object_types'  => array( 'page', ), // Post type
      'show_on'      => array( 'key' => 'id', 'value' => array($home_page->ID) ),
    ));

    $cmb_home->add_field( array(
    	'name'       => esc_html__( 'Splash Images', 'cmb2' ),
      'desc'       => esc_html__( 'Images slection fror home page splash', 'cmb2' ),
    	'id'         => $prefix . 'home_splash_images',
    	'type'       => 'file_list',
    ) );
  }

  // SUPPORT
  $support_page = get_page_by_path('support');

  if (!empty($support_page) ) {

    $cmb_support = new_cmb2_box( array(
      'id'            => $prefix . 'support_metabox',
      'title'         => esc_html__( 'Options', 'cmb2' ),
      'object_types'  => array( 'page', ), // Post type
      'show_on'      => array( 'key' => 'id', 'value' => array($support_page->ID) ),
    ));

    $cmb_support->add_field( array(
    	'name'       => esc_html__( 'Headline text', 'cmb2' ),
      'desc'       => esc_html__( 'Appears before image', 'cmb2' ),
    	'id'         => $prefix . 'support_headline',
    	'type'       => 'textarea_small',
    ) );

    $cmb_support->add_field( array(
    	'name'       => esc_html__( 'Email form options', 'cmb2' ),
      'desc'       => esc_html__( 'Text completes phrase: "I want to..."', 'cmb2' ),
    	'id'         => $prefix . 'support_form_options',
    	'type'       => 'text',
      'repeatable' => true,
    ) );

    $cmb_support->add_field( array(
    	'name'       => esc_html__( 'Sponsor logos', 'cmb2' ),
    	'id'         => $prefix . 'sponsor_logos',
    	'type'       => 'file_list',
      'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
    ) );

  }


  // ABOUT
  $about_page = get_page_by_path('about');

  if (!empty($about_page) ) {

    $cmb_about = new_cmb2_box( array(
      'id'            => $prefix . 'about_metabox',
      'title'         => esc_html__( 'Options', 'cmb2' ),
      'object_types'  => array( 'page', ), // Post type
      'show_on'       => array( 'key' => 'id', 'value' => array($about_page->ID) ),
    ));

    $cmb_about->add_field( array(
      'name'       => esc_html__( 'Headline text', 'cmb2' ),
      'desc'       => esc_html__( 'Appears before image', 'cmb2' ),
    	'id'         => $prefix . 'about_headline',
    	'type'       => 'textarea_small',
    ));

    $cmb_about->add_field( array(
      'name'       => esc_html__( 'Long text', 'cmb2' ),
      'desc'       => esc_html__( 'Appears below gallery', 'cmb2' ),
    	'id'         => $prefix . 'about_long',
    	'type'       => 'wysiwyg',
      'options' => array(
        'textarea_rows' => 20,
        'media_buttons' => false,
      ),
    ));

    $cmb_about->add_field( array(
    	'name'       => esc_html__( 'Image gallery', 'cmb2' ),
    	'id'         => $prefix . 'about_gallery',
    	'type'       => 'file_list',
      'preview_size' => array( 150, 150 ), // Default: array( 50, 50 )
    ) );

    // PRESS
    $press_group = $cmb_about->add_field( array(
  		'id'          => $prefix . 'about_press',
  		'type'        => 'group',
  		'options'     => array(
  			'group_title'   => esc_html__( 'Quote {#}', 'cmb2' ), // {#} gets replaced by row number
  			'add_button'    => esc_html__( 'Add Another Quote', 'cmb2' ),
  			'remove_button' => esc_html__( 'Remove Quote', 'cmb2' ),
  			'sortable'      => true, // beta
  			// 'closed'     => true, // true to have the groups closed by default
  		),
  	) );

    $cmb_about->add_group_field( $press_group, array(
  		'name' => esc_html__( 'Quote', 'cmb2' ),
  		'id'   => 'quote',
  		'type' => 'textarea_small',
  	) );

    $cmb_about->add_group_field( $press_group, array(
  		'name' => esc_html__( 'Publication / Author', 'cmb2' ),
  		'id'   => 'author',
  		'type' => 'text',
  	) );

    $cmb_about->add_group_field( $press_group, array(
  		'name' => esc_html__( 'Link', 'cmb2' ),
  		'id'   => 'link',
  		'type' => 'text_url',
  	) );

    $cmb_about->add_field( array(
    	'name'       => esc_html__( 'Press PDF', 'cmb2' ),
    	'id'         => $prefix . 'press_pdf',
    	'type'       => 'file',
    ) );

    // BIOS
    $bios_group = $cmb_about->add_field( array(
  		'id'          => $prefix . 'about_bios',
  		'type'        => 'group',
  		'options'     => array(
  			'group_title'   => esc_html__( 'Artist Bio {#}', 'cmb2' ), // {#} gets replaced by row number
  			'add_button'    => esc_html__( 'Add Another Artist', 'cmb2' ),
  			'remove_button' => esc_html__( 'Remove Artist', 'cmb2' ),
  			'sortable'      => true, // beta
  			// 'closed'     => true, // true to have the groups closed by default
  		),
  	) );

    $cmb_about->add_group_field( $bios_group, array(
  		'name' => esc_html__( 'Name', 'cmb2' ),
  		'id'   => 'name',
  		'type' => 'text',
  	) );

    $cmb_about->add_group_field( $bios_group, array(
  		'name' => esc_html__( 'Image', 'cmb2' ),
  		'id'   => 'image',
  		'type' => 'file',
  	) );

    $cmb_about->add_group_field( $bios_group, array(
  		'name' => esc_html__( 'Bio', 'cmb2' ),
  		'id'   => 'bio',
  		'type' => 'textarea',
  	) );

  }

}



/**
 * Hook in and add a metabox to add fields to location taxonomies
 */
add_action( 'cmb2_admin_init', 'igv_location_taxonomy_metabox' );

function igv_location_taxonomy_metabox() {
  $prefix = '_igv_';
  /**
   * Metabox to add fields to categories and tags
   */
  $cmb_location = new_cmb2_box(array(
    'id'               => $prefix . 'edit',
    'title'            => esc_html__('Location Metabox', 'cmb2'), // Doesn't output for term boxes
    'object_types'     => array('term'), // Tells CMB2 to use term_meta vs post_meta
    'taxonomies'       => array('location'), // Tells CMB2 which taxonomies should have these fields
  ));

  $cmb_location->add_field(array(
    'name'     => esc_html__('Coordinates', 'cmb2'),
    'id'       => $prefix . 'location_coordinates',
    'type'     => 'title',
    'on_front' => false,
  	'desc'             => esc_html__( 'Set the geolocation in lat long values for this location. http://www.latlong.net/ can be helpful', 'cmb2' ),
  ));

  $cmb_location->add_field(array(
    'name' => esc_html__('Latitude', 'cmb2'),
    'id'   => $prefix . 'location_lat',
    'type' => 'text',
  ));

  $cmb_location->add_field(array(
    'name' => esc_html__('Longitude', 'cmb2'),
    'id'   => $prefix . 'location_lng',
    'type' => 'text',
  ));

}
?>
