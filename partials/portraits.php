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
      <div id="player-iframe"></div>
    </div>
  </div>
  <div id="map-portraits-wrapper">
    <div id="map-portraits-container">
      <div class="container">
        <div id="portraits-filters-container" class="padding-top-small padding-bottom-small grid-row">
          <form id="portraits-filters" class="flex-grow grid-row grid-item no-gutter">

  <?php
    $item_classes = 'grid-item';

    if (!empty($ages)) {
      render_filter_select($ages, 'age', $item_classes);
    }

    if (!empty($subjects)) {
      render_filter_select($subjects, 'subject', $item_classes);
    }

    if (!empty($locations)) {
      render_filter_select($locations, 'location', $item_classes);
    }

    if (!empty($genders)) {
      render_filter_select($genders, 'gender', $item_classes);
    }

  ?>
        </form>
          <a href="#" class="toggle-map grid-item font-bold">Map <?php echo url_get_contents(get_template_directory_uri() . '/dist/img/arrow-right.svg'); ?></a>
        </div>

        <div id="portraits-grid">

  <?php
    while ( $portraits->have_posts() ) {
      $portraits->the_post();

      $youtube_id = get_post_meta($post->ID, '_video_id_value', true);

      if (has_post_thumbnail() && !empty($youtube_id)) {

        $filters_data = get_post_filters_data($post);
  ?>
          <article <?php post_class('portrait margin-bottom-small u-pointer'); ?> id="post-<?php the_ID(); ?>" data-groups='<?php echo json_encode($filters_data); ?>' data-youtube-id="<?php echo $youtube_id; ?>">
          <a href="#!/portrait/<?php echo $youtube_id; ?>"><?php the_post_thumbnail('item-l-4'); ?></a>
          </article>
  <?php
      }
    }
  ?>
          <div id="portrait-sizer"></div>
        </div>

      </div>

      <div id="portraits-map">
        <div class="container">
          <div class="grid-row padding-bottom-small padding-top-small">
            <div class="grid-item item-s-3">
              <a href="#" class="toggle-map font-bold"><?php echo url_get_contents(get_template_directory_uri() . '/dist/img/arrow-left.svg'); ?> Grid</a>
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
