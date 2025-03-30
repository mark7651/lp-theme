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
	<style>
		.loader {
			position: fixed;
			inset: 0;
			z-index: 1000;
			overflow: hidden;
		}

		.loader-backdrop {
			position: absolute;
			inset: 0;
			opacity: 0;
			background: rgba(255, 255, 0255, 0.5);
		}

		.loader-fill {
			position: absolute;
			inset: 0;
			background-color: #f1f1f1;
		}

		@supports (height: 100lvh) {
			.loader {
				height: 100lvh;
			}
		}
	</style>
</head>

<body id="top" <?php body_class(); ?> data-barba="wrapper">
	<?php if (function_exists('wp_body_open')) : ?>
		<?php wp_body_open(); ?>
	<?php endif; ?>

	<?php get_template_part('template-parts/header/header'); ?>

	<div class="loader">
		<div class="loader-backdrop"></div>
		<div class="loader-fill"></div>
	</div>

	<main id="app" data-barba="container" class="layout">