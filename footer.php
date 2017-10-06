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

    if (has_post_thumbnail()) {
?>
      <div class="item-s-3 item-m-2">
        <a href="<?php echo home_url(); ?>">
          <?php the_post_thumbnail('item-l-3', 'class=u-block'); ?>
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
