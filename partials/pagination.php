<?php
$older_link = false;
$newer_link = false;

if (is_single()) {
  $prev_post = get_previous_post();
  $next_post = get_next_post();

  if (!empty($prev_post)) {
    $older_link = get_permalink($prev_post);
  }
  if (!empty($next_post)) {
    $newer_link = get_permalink($next_post);
  }
} else {
  if (get_previous_posts_link()) {
    $newer_link = get_previous_posts_page_link();
  }

  if (get_next_posts_link()) {
    $older_link = get_next_posts_page_link();
  }
}

if ($newer_link || $older_link) {
?>
<section id="pagination" class="padding-top-basic">
  <div class="container">
    <nav class="grid-row">
<?php
  if ($newer_link) {
?>
      <div class="grid-item flex-grow font-size-mid font-bold">
        <?php echo url_get_contents(get_template_directory_uri() . '/dist/img/arrow-left.svg'); ?> <a class="link-underline" href="<?php echo $newer_link; ?>">Newer</a>
      </div>
<?php
  }

  if ($older_link) {
?>
      <div class="grid-item flex-grow text-align-right font-size-mid font-bold">
        <a class="link-underline" href="<?php echo $older_link; ?>">Older</a> <?php echo url_get_contents(get_template_directory_uri() . '/dist/img/arrow-right.svg'); ?>
      </div>
<?php
  }
?>
    </nav>
  </div>
</section>
<?php
}
?>
