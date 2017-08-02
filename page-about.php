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
?>

        <article <?php post_class('grid-row'); ?> id="post-<?php the_ID(); ?>">

          <h1 class="u-hidden">About In Search of the Truth</h1>

<?php
    if (!empty($headline)) {
?>

          <section class="grid-row margin-top-mid margin-bottom-mid">
            <div class="grid-item item-s-12 item-m-8 offset-m-2 item-l-6 offset-l-3 text-align-center">
              <?php echo apply_filters('the_content', $headline); ?>
            </div>
          </section>

<?php
    }
?>

          <div class="grid-item item-s-12">
            <?php the_content(); ?>
          </div>

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
