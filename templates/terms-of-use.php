<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');
//Template Name: Terms of use
get_header(); ?>

<main class="site-content section" role="main">
	<div class="container">
	<div class="page-title__wrap section-title">
      <?php echo lp_page_title();?>
    </div>

		<?php while ( have_posts() ) : the_post(); ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		<?php endwhile; ?>
	</div>
</main>

<?php get_footer(); ?>