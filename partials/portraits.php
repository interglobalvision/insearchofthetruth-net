<?php
$portraits = new WP_Query( array(
  'post_type' => array( 'portrait' ),
  'nopaging' => true,
  'post_status' => array( 'publish' ),
));


if ( $portraits->have_posts() ) {
?>
<section id="portraits">
  <div id="portraits-player"></div>
  <div class="container">
    <div id="portraits-grid" class="grid grid-row">
<?php
  while ( $portraits->have_posts() ) {
    $portraits->the_post();
?>
      <article <?php post_class('portrait grid-item item-s-6 item-m-4 item-l-2 margin-bottom-small u-pointer'); ?> id="post-<?php the_ID(); ?>">
        <?php the_post_thumbnail('item-l-4'); ?>
      </article>
<?php
  }
?>

    </div>
  </div>
</div>
<?php
}

/*
if ( $portraits->have_posts() ) {
?>
<section id="portraits">
  <div id="portraits-player"></div>
  <div class="container">
    <div id="portraits-grid" class="grid grid-row">

<?php
  $portraits->while( $portraits->have_posts() ) {
}
    /*
      $portraits->the_post();

      if (has_category()) {
        $category = get_the_category();
        $cat_name = $category[0]->cat_name;
        $cat_id = get_cat_ID( $name );
        $cat_link = get_category_link( $cat_id );
      }
?>

        <article <?php post_class('portrait'); ?> id="post-<?php the_ID(); ?>">

          <div class="grid-item item-s-6 item-m-4 item-l-2 margin-bottom-small">
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
 */
