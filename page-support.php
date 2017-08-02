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

          <section class="grid-row margin-top-mid margin-bottom-mid">
            <div class="grid-item item-s-12 item-m-8 offset-m-2 item-l-6 offset-l-3 text-align-center">
              <?php echo $headline; ?>
            </div>
          </section>

<?php
    }

    if (has_post_thumbnail()) {
?>

          <section class="grid-row margin-top-mid margin-bottom-mid">
            <div class="item-s-12">
              <?php the_post_thumbnail('full-width'); ?>
            </div>
          </section>

<?php
    }
?>

          <section class="grid-row margin-top-mid margin-bottom-mid">
            <div class="grid-item item-s-12 item-m-10 offset-m-1">
              <?php the_content(); ?>
            </div>
          </section>

<?php
    if (!empty($form_options)) {
?>
          <section class="margin-top-mid margin-bottom-mid">
            <div class="grid-row">
              <div class="grid-item item-s-11 offset-s-1 class="margin-bottom-basic" ">
                How can you support the truth booth?
              </div>
            </div>
            <form id="support-form" class="grid-row align-items-end">
              <div class="grid-item">
                I want to
              </div>
              <div class="grid-item flex-grow">
                <input id="support-form-email" class="form-element" type="email" placeholder="my email" class="margin-bottom-basic u-block" />

                <select id="support-form-select" class="form-element" class="u-block">
                  <?php
                    foreach ($form_options as $option) {
                      echo '<option>' . $option . '</option>';
                    }
                  ?>
                </select>
              </div>
              <div class="grid-item">
                <button class="js-submit-form"><?php echo url_get_contents(get_template_directory_uri() . '/dist/img/arrow-right.svg'); ?></button>
              </div>
            </form>
          </section>
<?php
    }

    if (!empty($products)) {
?>

          <section class="grid-row margin-top-mid margin-bottom-mid justify-center">
            <h2 class="grid-item item-s-12 text-align-center margin-bottom-basic">Get Truth gear!</h2>
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

              <div class="grid-item item-s-12">
                <h3 class="margin-bottom-small"><?php echo get_the_title($product->ID) . '&emsp;$' . $price; ?><span class="font-size-small">USD</span></h3>
                <?php echo $paypal ?>
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

        <section class="margin-top-mid margin-bottom-mid">
          <div class="grid-row margin-bottom-basic">
            <div class="grid-item item-s-12 item-m-8 offset-m-1 item-l-6">
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
