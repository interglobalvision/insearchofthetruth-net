<?php
if( get_next_posts_link() || get_previous_posts_link() ) {
  $previous = get_previous_posts_link('Newer');
  $next = get_next_posts_link('Older');
?>
<section id="pagination">
  <div class="container">
    <nav class="grid-row">
<?php
  if ($previous) {
?>
      <div class="grid-item flex-grow font-size-mid font-bold">
        <?php echo url_get_contents(get_template_directory_uri() . '/dist/img/arrow-left.svg'); ?> <a class="link-underline" href="<?php echo get_previous_posts_page_link(); ?>">Newer</a>
      </div>
<?php
  }

  if ($next) {
?>
      <div class="grid-item flex-grow text-align-right font-size-mid font-bold">
        <a class="link-underline" href="<?php echo get_next_posts_page_link(); ?>">Older</a> <?php echo url_get_contents(get_template_directory_uri() . '/dist/img/arrow-right.svg'); ?>
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
