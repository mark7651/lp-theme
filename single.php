<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package Catch_Starter
 */

get_header(); ?>

<main id="main" class="section site-main" role="main">
	<div class="container">

	<div class="page-title__wrap section-title">
      <?php echo lp_page_title();?>
    </div>

		<?php while ( have_posts() ) : the_post();?>

		<?php	get_template_part( 'template-parts/content/content', 'single' );?>

		<?php	//get_template_part( 'template-parts/content/content', 'comment' );

			  // if ( is_singular( 'post' ) ) {
				// 	// Previous/next post navigation.
				// 	lptheme_paging_nav();
				// }
				// End of the loop.
			endwhile; ?>
	</div>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
