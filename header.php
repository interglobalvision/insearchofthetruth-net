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
<body <?php body_class(); ?>>
<!--[if lt IE 9]><p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p><![endif]-->

<section id="main-container">

  <header id="header" class="margin-bottom-large">
    <div class="container">
      <div class="grid-row align-items-center">
        <div id="logo-holder" class="grid-item text-align-center">
          <h1 class="u-visuallyhidden">In Search of the Truth</h1>
          <a href="<?php echo home_url(); ?>"><img id="logo" src="<?php bloginfo('stylesheet_directory'); ?>/dist/img/truth-logo.png"></a>
        </div>
        <nav id="main-nav" class="grid-item flex-grow no-gutter">
          <ul class="u-inline-list font-bold grid-row">
            <li class="grid-item"><a href="<?php echo home_url(); ?>">Portraits</a></li>
            <li class="grid-item"><a href="<?php echo home_url('support'); ?>">Support</a></li>
            <li class="grid-item"><a href="<?php echo home_url('about'); ?>">About</a></li>
            <li class="grid-item"><a href="<?php echo home_url('blog'); ?>">Blog</a></li>
          </ul>
        </nav>
      </div>
    </div>
  </header>
