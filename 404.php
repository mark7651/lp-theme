<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');
/**
 * The template for displaying 404 pages (Not Found)
 */
get_header() ?>

<div class="min-h-[80vh] grid place-content-center h-full text-center">
	<h1 class="heading-1">404</h1>
	<div class="mt-4">
		<p class="heading-4"><?php _e('Nothing Found', 'lptheme'); ?></p>
	</div>
</div>

<?php get_footer() ?>