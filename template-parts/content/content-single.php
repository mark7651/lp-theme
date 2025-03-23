<?php

/**
 * The template part for displaying single posts
 *
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="flex items-center gap-30 mt-10 mb-30 lg:mt-30 lg:mb-60">
			<div class="text-tag text-gray"> <?php lp_post_date(); ?></div>
			<?php if (get_the_category_list(', ')): ?>
				<div class="meta-post-categories link-btn"><?php echo get_the_category_list(', '); ?></div>
			<?php endif ?>
		</div>
		<?php if (get_the_post_thumbnail()): ?>
			<figure class="my-20 lg:my-40 rounded-[20px] overflow-hidden">
				<?php the_post_thumbnail('full'); ?>
			</figure>
		<?php endif ?>
	</header>

	<div class="editor-area">
		<?php the_content(); ?>
	</div>

	<div class="article-bottom flex gap-30 justify-between items-center p-20 xl:p-30 bg-white rounded-[20px] mt-60 xl:mt-90">
		<button id="goBackBtn" onclick="history.back()" class="btn-small border border-gray-3 gap-10 rounded-[12px] text-center">
			<?php icon('arrow-l', ''); ?><span><?php _e('Go back', 'lptheme'); ?></span>
		</button>
		<button id="shareBtn" class="btn-small border border-gray-3 gap-10 rounded-[12px] text-center" data-title="<?php the_title(); ?>" data-url="<?php echo esc_url(get_permalink()); ?>">
			<?php icon('share', ''); ?><span><?php _e('Share', 'lptheme'); ?></span>
		</button>
	</div>

</article>