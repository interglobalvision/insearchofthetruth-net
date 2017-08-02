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
