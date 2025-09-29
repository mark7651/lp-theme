<?php

/**
 * Template Part: Latest Articles
 *
 */

$args = array(
	'post_type'      => 'post',
	'posts_per_page' => 6,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post__not_in'   => array(get_the_ID()),
);

$latest_articles = new WP_Query($args);

if ($latest_articles->have_posts()) : ?>

	<div class="flex flex-col bg-white p-30 rounded-[20px]">
		<div class="title pb-20"><?php _e('Latest Articles', 'lptheme'); ?></div>
		<div class="flex flex-col gap-10">
			<?php while ($latest_articles->have_posts()) : $latest_articles->the_post(); ?>
				<div class="flex items-center gap-10 md:gap-16 p-10 bg-gray rounded-[20px]">
					<div class="size-60 lg:size-110 aspect-square rounded-[12px] overflow-hidden">
						<?php if (has_post_thumbnail()) : ?>
							<a href="<?php the_permalink(); ?>" class="hover:opacity-70 transition-opacity duration-300">
								<?php the_post_thumbnail('thumbnail', array('loading' => 'lazy', 'class' => 'size-full object-cover bg-gray-t')); ?>
							</a>
						<?php else : ?>
							<div class="size-full bg-gray-t"></div>
						<?php endif; ?>
					</div>

					<div class="flex-1 flex flex-col gap-10 justify-between">
						<div class="title-sm xl:text-[18px] line-clamp-2">
							<?php the_title(); ?>
						</div>
						<a href="<?php the_permalink(); ?>" class="link-btn">
							<span class="border-b"><?php _e('Read more', 'lptheme'); ?></span>
						</a>
					</div>
				</div>
			<?php endwhile; ?>
		</div>

	</div>
	<?php wp_reset_postdata(); ?>
<?php endif; ?>