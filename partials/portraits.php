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

if ( $portraits->have_posts() ) {
?>
<section id="portraits">
  <div id="player-wrapper" class="container">
    <div id="player-container" class="">
      <div id="player-iframe" class="js-fit-height"></div>
    </div>
  </div>
  <div id="map-portraits-wrapper">
    <div id="map-portraits-container" class="js-fit-height">
      <div class="container">
        <div id="portraits-filters-container" class="padding-bottom-small grid-row">
          <form id="portraits-filters" class="flex-grow grid-row grid-item no-gutter">

  <?php
    if (!empty($ages)) {
      render_filter_select($ages, 'age', 'grid-item item-m-2');
    }

    if (!empty($subjects)) {
      render_filter_select($subjects, 'subject', 'grid-item item-m-2');
    }

    if (!empty($locations)) {
      render_filter_select($locations, 'location', 'grid-item item-m-2');
    }

    if (!empty($genders)) {
      render_filter_select($genders, 'gender', 'grid-item item-m-2');
    }

  ?>
        </form>
          <a href="#" class="js-toggle-map grid-item font-bold">Map ></a>
        </div>

        <div id="portraits-grid" class="grid-row">

  <?php
    while ( $portraits->have_posts() ) {
      $portraits->the_post();

      $youtube_id = get_post_meta($post->ID, '_video_id_value', true);

      $filters_data = get_post_filters_data($post);

  ?>
          <article <?php post_class('portrait grid-item item-s-6 item-m-4 item-l-2 margin-bottom-small u-pointer'); ?> id="post-<?php the_ID(); ?>" data-filters="<?php echo $filters_data; ?>" data-youtube-id="<?php echo $youtube_id; ?>">
          <a href="#!/portrait/<?php echo $youtube_id; ?>"><?php the_post_thumbnail('item-l-4'); ?></a>
          </article>
  <?php
    }
  ?>

        </div>
      </div>

      <div id="portraits-map">
        <div class="container">
          <div class="grid-row padding-bottom-small">
            <div class="grid-item item-s-3">
              <a href="#" class="js-toggle-map font-bold">< Grid</a>
            </div>
          </div>
          <div id="map-container"></div>
        </div>
      </div>
    </div>
  </div>

</div>
<?php
}
?>
