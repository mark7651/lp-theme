<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package lptheme
 */

get_header(); ?>

<section class="section site-main">
	<div class="container">

		<div class="page-title__wrap section-title">
			<?php lp_page_title(); ?>
		</div>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/content/content', 'single' ); ?>
		<?php endwhile; ?>

	</div>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
