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

function get_taxonomy_value($post,$taxonomy) {
  $value = get_the_terms($post,$taxonomy);

  if(empty($value)) {
    return null;
  }

  $value = $value[0]->slug;

  return $value;
}
