<?php
get_header();
?>

<main id="main-content" class="margin-bottom-large">
  <section id="post">
    <div class="container">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();

    if (has_category()) {
      $category = get_the_category();
      $cat_name = $category[0]->cat_name;
      $cat_id = get_cat_ID( $name );
      $cat_link = get_category_link( $cat_id );
    }
?>

        <article <?php post_class('grid-row margin-top-large'); ?> id="post-<?php the_ID(); ?>">

          <div class="grid-item item-s-12 margin-bottom-basic">
            <h1 class="font-size-large font-medium"><?php the_title(); ?></h1>
          </div>

          <div class="grid-item item-s-12 item-m-4 item-l-3 font-size-basic margin-bottom-basic">
            <time datetime="<?php the_time('Y-m-d'); ?>"><?php echo get_the_date(); ?></time><br>

            <?php if (has_category() && $cat_name !== 'Uncategorized') { ?>
            <a href="<?php echo esc_url($cat_link); ?>"><?php echo $cat_name; ?></a>
            <?php } ?>
          </div>

<?php
    if (has_post_thumbnail()) {
?>
          <div class="grid-item item-s-12 item-m-8 item-l-6 margin-bottom-basic text-align-center">
            <?php the_post_thumbnail('full-width'); ?>
          </div>
<?php
    }
?>

          <div class="grid-item item-s-12 item-m-8 item-l-6 <?php echo has_post_thumbnail() ? 'offset-m-2 offset-l-3' : ''; ?>">
            <div class="single-content-container">
              <?php the_content(); ?>
            </div>
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

  <?php get_template_part('partials/pagination'); ?>

</main>

<?php
get_footer();
?>
