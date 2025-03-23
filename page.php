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

<div class="section">
	<div class="container max-w-[865px] mx-auto">
		<?php lp_breadcrumbs() ?>

		<h1 class="heading-1">
			<?php the_title(); ?>
		</h1>

		<?php while (have_posts()) : the_post(); ?>
			<?php if (get_the_content()) : ?>
				<div class="editor-area">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		<?php endwhile; ?>

	</div>
</div>


<?php get_footer(); ?>