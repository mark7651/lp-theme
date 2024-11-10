<?php if (! defined('ABSPATH')) {
	die();
} ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="MobileOptimized" content="280">
	<meta name="HandheldFriendly" content="True">
	<meta name="format-detection" content="telephone=no">
	<meta name="author" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php if (function_exists('wp_body_open')) : ?>
		<?php wp_body_open(); ?>
	<?php endif; ?>

	<header id="header" class="header">
		<?php get_template_part('template-parts/header/header'); ?>
	</header>