<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns#">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php wp_title('|',true,'right'); bloginfo('name'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php
    get_template_part('partials/globie');
    get_template_part('partials/seo');
  ?>

  <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
  <link rel="icon" href="<?php bloginfo('stylesheet_directory'); ?>/dist/img/favicon.png">
  <link rel="shortcut" href="<?php bloginfo('stylesheet_directory'); ?>/dist/img/favicon.ico">
  <link rel="apple-touch-icon" href="<?php bloginfo('stylesheet_directory'); ?>/dist/img/favicon-touch.png">
  <link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('stylesheet_directory'); ?>/dist/img/favicon.png">

  <?php if (is_singular() && pings_open(get_queried_object())) { ?>
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
  <?php } ?>
  <?php wp_head(); ?>
</head>

<?php
    $loading_class = is_front_page() ? 'loading' : '';
?>
<body <?php body_class($loading_class); ?>>
<!--[if lt IE 9]><p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p><![endif]-->

<section id="main-container">

  <header id="header" class="padding-top-small">
    <div class="container">
      <div class="grid-row align-items-center justify-between">
        <div id="logo-holder" class="grid-item">
          <h1 class="u-visuallyhidden">In Search of the Truth</h1>
          <a href="<?php echo home_url(); ?>"><?php echo url_get_contents(get_template_directory_uri() . '/dist/img/truth-logo.svg'); ?></a>
        </div>
        <div id="nav-toggle-holder" class="grid-item">
          <div><?php echo url_get_contents(get_template_directory_uri() . '/dist/img/menu-open.svg'); ?></div>
        </div>
        <nav id="main-nav" class="grid-item flex-grow no-gutter">
          <ul class="grid-row font-bold">
            <li class="grid-item"><a href="<?php echo home_url(); ?>">Portraits</a></li>
            <li class="grid-item"><a href="<?php echo home_url('support'); ?>">Support</a></li>
            <li class="grid-item"><a href="<?php echo home_url('about'); ?>">About</a></li>
            <li class="grid-item"><a href="<?php echo home_url('blog'); ?>">Blog</a></li>
          </ul>
        </nav>
      </div>
    </div>

    <div id="mobile-menu">
      <div class="container margin-top-basic padding-bottom-basic">
        <div class="grid-row justify-between">
          <div class="grid-item item-s-12 text-align-right">
            <?php echo url_get_contents(get_template_directory_uri() . '/dist/img/menu-close.svg'); ?>
          </div>
          <nav id="mobile-nav" class="grid-item flex-grow">

            <ul class="grid-column font-bold font-size-mid justify-center align-items-center">
              <li class="grid-item"><a href="<?php echo home_url(); ?>">Portraits</a></li>
              <li class="grid-item"><a href="<?php echo home_url('support'); ?>">Support</a></li>
              <li class="grid-item"><a href="<?php echo home_url('about'); ?>">About</a></li>
              <li class="grid-item"><a href="<?php echo home_url('blog'); ?>">Blog</a></li>
            </ul>
          </nav>

        </div>
      </div>
    </div>
<?php
    // Render loading cover in frontpage
    if (is_front_page()) {
      render_front_splash($post->ID);
    }
?>
  </header>
