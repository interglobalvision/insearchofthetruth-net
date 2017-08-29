<?php

// Get portraits
$portraits = new WP_Query( array(
  'post_type' => array( 'portrait' ),
  'nopaging' => true,
  'post_status' => array( 'publish' ),
));

/*
 * FILTERS
 */

// Age
$ages = get_terms( array(
  'taxonomy' => 'age',
) );

// Subject
$subjects = get_terms( array(
  'taxonomy' => 'subject',
) );

// Location
$locations = get_terms( array(
  'taxonomy' => 'location',
) );

// Gender
$genders = get_terms( array(
  'taxonomy' => 'gender',
) );

// Get splash image
$splash_images = get_post_meta($post->ID, '_igv_home_splash_images', true);

if ( $portraits->have_posts() ) {
?>
<section id="portraits">

<?php
  if (!empty($splash_images)) {
    $splash_image_id = array_rand($splash_images);
    $splash_image_srcset = wp_get_attachment_image_srcset($splash_image_id, 'full-width');
?>

  <section id="portraits-cover" class="grid-row">
    <div class="item-s-12 full-splash-image lazyload" data-bgset="<?php echo $splash_image_srcset; ?>" data-sizes="auto"></div>
  </section>

<?php
  }
?>

  <div class="">
    <div id="player-wrapper" class="container">
      <div id="player-container" class="">
        <div id="player-iframe"></div>
      </div>
    </div>
    <div class="container">
      <div id="portraits-filters-container" class="margin-bottom-basic">
        <form id="portraits-filters" class="grid-row">

  <?php
    if (!empty($ages)) {
      render_filter_select($ages, 'age', 'grid-item item-s-2');
    }

    if (!empty($subjects)) {
      render_filter_select($subjects, 'subject', 'grid-item item-s-2');
    }

    if (!empty($locations)) {
      render_filter_select($locations, 'location', 'grid-item item-s-2');
    }

    if (!empty($genders)) {
      render_filter_select($genders, 'gender', 'grid-item item-s-2');
    }

  ?>
        </form>
      </div>

      <div id="portraits-grid" class="grid-row">

  <?php
    while ( $portraits->have_posts() ) {
      $portraits->the_post();

      $youtube_id = get_post_meta($post->ID, '_video_id_value', true);

      $filters_data = get_post_filters_data($post);

  ?>
        <article <?php post_class('portrait grid-item item-s-6 item-m-4 item-l-2 margin-bottom-small u-pointer'); ?> id="post-<?php the_ID(); ?>" data-filters="<?php echo $filters_data; ?>" data-youtube-id="<?php echo $youtube_id; ?>">
          <?php the_post_thumbnail('item-l-4'); ?>
        </article>
  <?php
    }
  ?>

      </div>

      <div id="portraits-map">
      </div>
    </div>
  </div>

</div>
<?php
}
?>

