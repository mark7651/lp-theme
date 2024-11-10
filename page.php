<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that other
 * 'pages' on your WordPress site will use a different template.
 *
 */

get_header(); ?>

<main class="page-content section">
	<div class="container">
		
		<div class="page-title">
      <?php echo lp_page_title();?>
    </div>
	
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		<?php endwhile; ?>
	</div>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
