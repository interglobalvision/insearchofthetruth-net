<?php
get_header();
?>

<main id="main-content">
  <section id="post">
    <div class="container">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();

    $headline = get_post_meta($post->ID, '_igv_about_headline', true);
    $gallery = get_post_meta($post->ID, '_igv_about_gallery', true);
    $longtext = get_post_meta($post->ID, '_igv_about_long', true);
    $press = get_post_meta($post->ID, '_igv_about_press', true);
    $press_pdf = get_post_meta($post->ID, '_igv_press_pdf', true);
    $bios = get_post_meta($post->ID, '_igv_about_bios', true);
?>

        <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

          <h1 class="u-hidden">About In Search of the Truth</h1>

<?php
    if (!empty($headline)) {
?>

          <section class="grid-row margin-top-large margin-bottom-large">
            <div class="grid-item item-s-12 item-m-8 item-l-6 font-size-large font-medium">
              <?php echo apply_filters('the_content', $headline); ?>
            </div>
          </section>

<?php
    }

    if (has_post_thumbnail()) {
?>

          <section class="grid-row margin-top-large margin-bottom-large">
            <div class="item-s-12 item-m-8 offset-m-4">
              <?php the_post_thumbnail('full-width'); ?>
            </div>
          </section>

<?php
    }
?>

          <section class="grid-row margin-top-large margin-bottom-large">
            <div class="grid-item item-s-12 item-m-10 offset-m-1 font-size-large font-medium">
              <?php the_content(); ?>
            </div>
          </section>

<?php
    if (!empty($gallery)) {
      render_gallery_slider($gallery);
    }

    if (!empty($longtext)) {
?>

          <section class="grid-row margin-top-mid margin-bottom-mid">
            <div class="grid-item item-s-12 item-m-10 offset-m-1 item-l-8 offset-l-2 font-size-mid">
              <?php echo apply_filters('the_content', $longtext); ?>
            </div>
          </section>

<?php
    }

    if (!empty($press)) {
?>

          <section class="grid-row margin-top-large margin-bottom-large justify-center">

<?php
      $i = 0;

      foreach ($press as $quote) {
        if ($i < 3) {
          if (!empty($quote['link'])) {
            $quote['author'] = '<a class="link-underline" href="' . esc_url($quote['link']) . '">' . $quote['author'] . '</a>';
          }
?>
            <div class="grid-item item-s-12 item-m-4 font-size-large font-medium margin-bottom-mid">
              <div class="margin-bottom-small"><?php echo $quote['quote']; ?></div>
              <span>&mdash;&nbsp;<?php echo $quote['author']; ?></span>
            </div>
<?php
          $i++;
        }
      }

      if (!empty($press_pdf)) {
?>

            <div class="grid-item item-s-12 text-align-center font-size-mid margin-bottom-large">
              <a class="link-underline" href="<?php echo esc_url($press_pdf); ?>">Download our press archive</a>
            </div>

<?php
      }
?>
          </section>
<?php
    }

    if (!empty($bios)) {
?>

          <section class="margin-top-large margin-bottom-large">
            <div class="grid-row margin-bottom-small">
              <div class="grid-item item-s-12 item-m-3 offset-m-1">
                <h2 class="font-size-mid font-medium">Meet the artists:</h2>
              </div>
            </div>

<?php
      foreach ($bios as $bio) {

        if (!empty($bio['name'])) {
?>
            <div class="grid-row margin-bottom-small">
              <div class="grid-item item-s-12 item-m-6 offset-m-4">
                <h3 class="font-size-large font-medium"><?php echo $bio['name']; ?></h3>
              </div>
            </div>

<?php
        }
?>
            <div class="grid-row margin-bottom-basic">
              <div class="grid-item item-s-12 item-m-3 offset-m-1">
                <?php echo !empty($bio['image']) ? wp_get_attachment_image($bio['image_id'], 'item-l-3') : '&nbsp;'; ?>
              </div>

              <div class="grid-item item-s-12 item-m-6">
                <?php echo !empty($bio['bio']) ? apply_filters('the_content', $bio['bio']) : ''; ?>
              </div>
            </div>

<?php
      }
?>

          </section>

<?php
      }
?>

        </article>

<?php
  }
} else {
?>
        <article class="u-alert grid-item item-s-12"><?php _e('Sorry, no posts matched your criteria :{'); ?></article>
<?php
}
?>

    </div>
  </section>
</main>

<?php
get_footer();
?>
