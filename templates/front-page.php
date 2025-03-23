<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Template Name: Home
 * ------------------------------------------------------------------------------------------------
 */

get_header();
$thumbnail_size = wp_is_mobile() ? 'medium' : 'large';
$hero_heading = get_field('hero_heading');
$hero_subheading = get_field('hero_subheading');
$hero_subheading_2 = get_field('hero_subheading_2');
$hero_video = get_field('hero_video');
$hero_cover = get_field('hero_cover');
?>

<section id="top" class="section section-hero overflow-x-clip bg-gray">
	<div class="container z-1">
		<h1>Test</h1>
		<div class="flex flex-col items-center justify-center text-center text-white">
			<span class="mb-20 heading-4 hero-subheading">
				<?php echo $hero_subheading; ?>
			</span>
			<h1 class="heading-1 mb-60 hero-heading">
				<?php echo $hero_heading; ?>
			</h1>
			<div class="subline max-w-350 mx-auto mb-30 hero-subheading-2">
				<?php echo $hero_subheading_2; ?>
			</div>
		</div>
	</div>

	<div class="absolute inset-0 w-full h-full z-0 after:content-[''] after:absolute after:bg-[#597386]/60 after:inset-0 after:mix-blend-multiply
				">
		<video
			class="object-cover object-left w-full h-full lazy md:object-center"
			cover="<?php echo $hero_cover ?>"
			autoplay
			muted
			loop
			playsinline
			webkit-playsinline>
			<source data-src="<?php echo $hero_video ?>" type="video/mp4" />
		</video>
	</div>
</section>


<?php //get_template_part('template-parts/global/cta') 
?>

<?php
get_footer();
