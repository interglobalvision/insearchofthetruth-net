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

    $headline = get_post_meta($post->ID, '_igv_support_headline', true);

    $form_options = get_post_meta($post->ID, '_igv_support_form_options', true);
    $form_recipient = get_post_meta($post->ID, '_igv_support_form_recipient', true);

    $products = get_posts(array(
      'posts_per_page'   => -1,
      'post_type'        => 'product',
      'post_status'      => 'publish',
      'orderby'          => 'menu_order',
    ));
    
    $sponsors = get_post_meta($post->ID, '_igv_sponsor_logos', true);
?>

        <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

          <h1 class="u-hidden">Support In Search of the Truth</h1>

<?php
    if (!empty($headline)) {
?>

          <section class="grid-row margin-bottom-large">
            <div class="grid-item item-s-12 item-m-8 offset-m-2 item-l-6 offset-l-3 text-align-center font-size-large font-medium">
              <?php echo apply_filters('the_content', $headline); ?>
            </div>
          </section>

<?php
    }

    if (has_post_thumbnail()) {
?>

          <section class="grid-row margin-bottom-large">
            <div class="item-s-12">
              <?php the_post_thumbnail('full-width'); ?>
            </div>
          </section>

<?php
    }
?>

          <section class="grid-row margin-bottom-large">
            <div class="grid-item item-s-12 item-m-10 offset-m-1 font-size-mid font-medium">
              <?php the_content(); ?>
            </div>
          </section>

<?php
    if (!empty($form_options) && !empty($form_recipient)) {
?>
          <section class="margin-bottom-large font-size-large font-medium">
            <div class="grid-row">
              <div class="grid-item item-s-11 offset-s-1">
                How can you support the truth booth?
              </div>
            </div>
            <?php render_support_form($form_options); ?>
          </section>
<?php
    }

    if (!empty($products)) {
?>

          <section class="grid-row margin-bottom-large justify-center align-items-end">
            <h2 class="grid-item item-s-12 text-align-center margin-bottom-basic font-size-large font-medium">Get Truth gear!</h2>
<?php
      foreach ($products as $product) {
        $paypal = get_post_meta($product->ID, '_igv_paypal_embed', true);
        $price = get_post_meta($product->ID, '_igv_product_price', true);

        if (!empty($paypal) && !empty($price)) {
?>
            <div class="grid-item item-s-6 item-m-4 item-l-3 no-gutter grid-row">
              <div class="grid-item item-s-12 margin-bottom-small">
                <?php echo get_the_post_thumbnail($product->ID, 'product-thumb'); ?>
              </div>

              <div class="grid-item item-s-12 font-size-mid font-medium no-gutter grid-row">
                <h3 class="grid-item item-s-12 margin-bottom-small"><?php echo get_the_title($product->ID) . '&mdash;$' . $price; ?><span class="font-size-basic">USD</span></h3>

                <div class="paypal-form-holder grid-item item-s-12 no-gutter u-hidden">
                  <?php echo $paypal ?>
                </div>
              </div>
            </div>
<?php
        }
      }
?>
          </section>

<?php
    }

    if (!empty($sponsors)) {

?>

        <section class="margin-bottom-large">
          <div class="grid-row margin-bottom-basic font-medium font-size-large">
            <div class="grid-item item-s-12 item-m-6 offset-m-1 item-l-5 item-xl-4">
              In Search of the Truth has been graciously sponsored by these organizations:
            </div>
          </div>

          <div class="grid-row align-items-center">
<?php
      foreach ($sponsors as $id => $url) {
?>
            <div class="grid-item item-s-6 item-m-3 margin-bottom-small text-align-center">
              <?php echo wp_get_attachment_image($id, 'item-l-3'); ?>
            </div>
<?php
      }
?>
          </div>
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
