<?php
$args = array(
  'post_type' => 'portrait',
  'orderby'   => 'rand',
  'posts_per_page'  => 6,
);

$query = new WP_Query( $args );

if ($query->have_posts() && !is_front_page()) {
?>
  <footer id="footer" class="container">
    <div class="grid-row justify-center align-items-end">
<?php
  while ($query->have_posts()) {
    $query->the_post();

    $youtube_id = get_post_meta($post->ID, '_video_id_value', true);

    if (has_post_thumbnail() && !empty($youtube_id)) {
?>
      <div class="item-s-2">
        <a href="<?php echo home_url(); ?>/#!/portrait/<?php echo $youtube_id; ?>">
          <?php the_post_thumbnail('portrait-16-9', 'class=u-block'); ?>
        </a>
      </div>
<?php
    }
  }
?>
    </div>
  </footer>
<?php
}
?>

</section>

<?php
  get_template_part('partials/scripts');
  get_template_part('partials/schema-org');
?>

</body>
</html>
