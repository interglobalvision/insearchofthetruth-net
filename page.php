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
?>

        <article <?php post_class('grid-row'); ?> id="post-<?php the_ID(); ?>">

          <div class="grid-item item-s-12">
            <h1><?php the_title(); ?><h1>
          </div>

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
