<?php if (! defined('ABSPATH')) {
	die();
}
/**
 * ------------------------------------------------------------------------------------------------
 * Post Card
 * ------------------------------------------------------------------------------------------------
 */

$video = get_field('video');
$video_link = get_field('full_video_link');
?>

<article class="relative flex flex-col gap-30 group">
	<?php if (get_the_post_thumbnail()) : ?>
		<div class="media rounded-[20px] overflow-hidden bg-gray aspect-6/4 relative">
			<?php echo wp_get_attachment_image(get_post_thumbnail_id(), 'full', false, [
				'class' => 'w-full h-full object-cover',
				'loading' => 'lazy'
			]); ?>
			<?php if ($video) : ?>
				<button class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 size-70 lg:size-100 text-white hover:opacity-70 transition-opacity rounded-full bg-white/40 backdrop-blur-[30px] p-10 flex-center z-1 panel-trigger"
					data-panel="video-<?php echo the_ID() ?>panel">
					<svg class="relative left-2" width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g clip-path="url(#clip0_1874_1654)">
							<path d="M1.6875 1.19995L18.3125 12L1.6875 22.7999V1.19995Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
						</g>
						<defs>
							<clipPath id="clip0_1874_1654">
								<rect width="19" height="24" fill="white" transform="translate(0.5)" />
							</clipPath>
						</defs>
					</svg>
				</button>
			<?php endif; ?>
		</div>
	<?php else: ?>
		<div class="media rounded-[20px] overflow-hidden bg-gray aspect-6/4 flex-center">
		</div>
	<?php endif; ?>

	<?php if (has_category()) : ?>
		<div class="flex items-center justify-between gap-30">
			<time datetime="<?php the_time('Y-m-d'); ?>"
				class="subline text-gray text-[0.9rem]">
				<?php the_time('d.m.Y'); ?>
			</time>
		</div>
	<?php endif; ?>

	<div class="flex flex-col mb-10 gap-30">
		<h3 class="heading-3">
			<?php if ($video) : ?>
				<a href="<?php echo esc_url($video_link); ?>" target="_blank">
					<?php the_title(); ?>
				</a>
			<?php else: ?>
				<a href="<?php echo esc_url(get_permalink()); ?>"
					class="transition-opacity hover:opacity-70">
					<?php the_title(); ?>
				</a>
			<?php endif; ?>
		</h3>

		<?php if ($video_link) : ?>
			<a href="<?php echo esc_url($video_link); ?>" target="_blank"
				class="link-btn">
				<?php _e('Watch more', 'lptheme'); ?>
			</a>
		<?php else: ?>
			<a href="<?php echo esc_url(get_permalink()); ?>"
				class="link-btn">
				<?php _e('Read more', 'lptheme'); ?>
			</a>
		<?php endif; ?>
	</div>
</article>


<?php if ($video) : ?>
	<aside id="video-<?php echo the_ID() ?>panel" class="aside-panel video-panel fixed top-0 right-0 bottom-0 h-full w-full max-w-[50rem] translate-x-[120%] transition-all duration-500 ease">
		<button class="panel-close" aria-label="Close"></button>
		<noindex>
			<div class="h-full panel-scroll custom-scroll" data-lenis-prevent>
				<div class="panel-content">
					<div class="relative w-full panel-body md:mt-40">
						<div class="embed-container">
							<?php echo $video; ?>
						</div>
					</div>
				</div>
			</div>
		</noindex>
	</aside>
<?php endif; ?>