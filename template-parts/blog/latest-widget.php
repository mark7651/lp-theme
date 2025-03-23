<?php

/**
 * Template Part: Latest Articles
 *
 * @package LPTheme
 */

$args = array(
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'orderby'        => 'date',
	'order'          => 'DESC',
);

$latest_articles = new WP_Query($args);

if ($latest_articles->have_posts()) : ?>

	<div class="flex flex-col gap-20 xl:gap-40">
		<h3 class="title pb-20 border-b border-gray-3"><?php _e('Latest Articles', 'lptheme'); ?></h3>
		<div class="flex flex-col gap-10 md:gap-16 xl:gap-30">
			<?php while ($latest_articles->have_posts()) : $latest_articles->the_post(); ?>
				<div class="flex items-center gap-10 md:gap-16">

					<div class="size-60 lg:size-110 aspect-square rounded-[12px] overflow-hidden">
						<?php if (has_post_thumbnail()) : ?>
							<a href="<?php the_permalink(); ?>" class="hover:opacity-70 transition-opacity duration-300">
								<?php the_post_thumbnail('thumbnail', array('loading' => 'lazy', 'class' => 'w-full h-full object-cover')); ?>
							</a>
						<?php else : ?>
							<div class="size-full bg-gray"></div>
						<?php endif; ?>
					</div>

					<div class="flex-1 flex flex-col gap-10 justify-between h-full">
						<h4 class="text-[14px] md:text-[18px] font-medium text-gray">
							<?php the_title(); ?>
						</h4>
						<a href="<?php the_permalink(); ?>" class="link-btn">
							<?php _e('Read more', 'lptheme'); ?>
						</a>
					</div>
				</div>
			<?php endwhile; ?>
		</div>

	</div>
	<?php wp_reset_postdata(); ?>
<?php else : ?>
	<p class="text-gray"><?php _e('No articles found', 'lptheme'); ?></p>
<?php endif; ?>