<?php
get_header();
?>

<main id="main-content">
  <section id="posts">
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

        <article <?php post_class('grid-row'); ?> id="post-<?php the_ID(); ?>">

          <div class="grid-item item-s-12 item-m-4">
            <h1><a class="link-underline" href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
          </div>

          <div class="grid-item item-s-12 item-m-4">
            <time class="u-block" datetime="<?php the_time('Y-m-d'); ?>"><?php echo get_the_date(); ?></time>

            <?php if (has_category() && $cat_name !== 'Uncategorized') { ?>
            <a href="<?php echo esc_url($cat_link); ?>"><?php echo $cat_name; ?></a>
            <?php } ?>
          </div>

          <div class="grid-item item-s-12 item-m-4">
            <?php the_post_thumbnail('item-l-4'); ?>
          </div>

        </article>

<?php
  }
} else {
?>
        <article class="u-alert grid-item item-s-12"><?php _e('Sorry, no posts matched your criteria :{'); ?></article>
<?php
} ?>

    </div>
  </section>

  <?php get_template_part('partials/pagination'); ?>

</main>

<?php
get_footer();
?>
