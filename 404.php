<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');
/**
 * The template for displaying 404 pages (Not Found)
 */
get_header() ?>

<div class="min-h-[80vh] grid place-content-center h-full text-center">

	<h1 class="font-bold text-8xl">404</h1>
	<div class="mt-4">
		<h2 class="text-h3"><?php _e('Nothing Found', 'lptheme'); ?></h2>
	</div>

</div>

<?php get_footer() ?>