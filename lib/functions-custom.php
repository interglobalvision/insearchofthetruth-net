<?php

// Custom functions (like special queries, etc)

function render_gallery_slider($images) {
?>

<section class="grid-row justify-center">
  <div class="swiper-container item-s-10 grid-row">
    <div class="swiper-wrapper align-items-center margin-bottom-mid">
  <?php
    foreach($images as $image_id => $image) {
  ?>
            <div class="swiper-slide text-align-center">
              <?php echo wp_get_attachment_image($image_id, 'gallery'); ?>
            </div>
  <?php
    }
  ?>
    </div>

    <div class="swiper-pagination"></div>

  </div>
</section>

<?php

}

function render_filter_select($options, $name, $classes) {
  if (empty($options) || empty($name)) {
   return;
  }
    ?>
      <div class="<?php echo $classes; ?>">
      <select id="filter-<?php echo $name; ?>" name="filter-<?php echo $name; ?>" class="filter-select" data-filter="<?php echo $name; ?>">
  <option value=""><?php echo $name; ?></option>
<?php
  foreach($options as $option) {
?>
    <option value="<?php echo $option->slug; ?>"><?php echo $option->name; ?></option>
<?php
  }
?>
  </select>
</div>
<?php
}

// Return taxonomy values in a single array
function get_taxonomy_values($post,$taxonomy) {
  $terms = get_the_terms($post,$taxonomy);

  if(empty($terms)) {
    return null;
  }

  $values = array();

  foreach($terms as $term) {
    array_push($values, $term->slug);
  }

  return $values;
}

// Return a string constructed from the filters values
// eg. 'age-20 gender-male'
//     'age-16 subject-racism subject-free-speach gender-female'
function get_post_filters_data($post) {
  $filters = array(
    'age' => get_taxonomy_values($post,'age'),
    'subject' => get_taxonomy_values($post,'subject'),
    'location' => get_taxonomy_values($post,'location'),
    'gender' => get_taxonomy_values($post,'gender'),
  );

  $filters_data = '';

  foreach($filters as $filter_slug => $filter_data) {
    if(!empty($filter_data)) {
      foreach($filter_data as $filter_value) {
        $filters_data .= $filter_slug . "-" . $filter_value . " ";
      }
    }
  }

  return $filters_data;
}

function get_locations_data() {
  // Get Locations taxonomy
  $locations = get_terms( array(
    'taxonomy' => 'location',
  ), true);

  if (!empty($locations)) {

    $data = array();

    foreach($locations as $location) {
      // Get coordinates
      $lat = get_term_meta($location->term_id, '_igv_location_lat', true);
      $lon = get_term_meta($location->term_id, '_igv_location_lon', true);

      if (!empty($lat) && !empty($lat)) {
        array_push($data, array(
          'name' => $location->name,
          'slug' => $location->slug,
          'lat' => $lat,
          'lng' => $lon,
        ));
      }
    }

    return $data;
  }
}
