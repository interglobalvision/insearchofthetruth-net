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
  <div id="portraits-player"></div>
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

    $age = get_taxonomy_value($post,'age');
    $subject = get_taxonomy_value($post,'subject');
    $location = get_taxonomy_value($post,'location');
    $gender = get_taxonomy_value($post,'gender');

?>
  <article
    <?php post_class('portrait grid-item item-s-6 item-m-4 item-l-2 margin-bottom-small u-pointer'); ?>
    id="post-<?php the_ID(); ?>"
    data-age="<?php echo $age; ?>"
    data-subject="<?php echo $subject; ?>"
    data-location="<?php echo $location; ?>"
    data-gender="<?php echo $gender; ?>"
    >
        <?php the_post_thumbnail('item-l-4'); ?>
      </article>
<?php
  }
?>

    </div>
  </div>
</div>
<?php
}
?>

