<?php
get_header();

// Get splash image
$splash_images = get_post_meta($post->ID, '_igv_home_splash_images', true);
?>

<main id="main-content">

<?php
if (!empty($splash_images)) {
  $splash_image_id = array_rand($splash_images);
  $splash_image_srcset = wp_get_attachment_image_srcset($splash_image_id, 'full-width');
?>

  <section class="grid-row margin-top-large margin-bottom-large">
    <div class="item-s-12 full-splash-image lazyload" data-bgset="<?php echo $splash_image_srcset; ?>" data-sizes="auto"></div>
  </section>

<?php
}
?>

  <?php get_template_part('partials/portraits'); ?>

</main>

<?php
get_footer();
?>
