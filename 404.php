<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');
/**
 * The template for displaying 404 pages (Not Found)
 */

get_header(); ?>

<main class="page-content" role="main">
	<div class="container">
		<div class="page-title__wrap section-title center">
			<div class="section-title center"><?php echo lp_page_title();?></div>
		</div>
		<div class="entry-content center">
			<h2><?php esc_html_e( 'Nothing Found', 'lptheme' ); ?></h2>
		</div>
	</div>
</main>

<?php get_footer(); ?>