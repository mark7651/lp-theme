<?php
$homepage_id = lp_tpl2id('front-page');
$cta_heading = get_field('sup_heading', $homepage_id);
$cta_subheading = get_field('sup_subheading', $homepage_id);
?>

<section class="relative text-white py-60 lg:py-90 bg-primary">
	<div class="container">
		<div class="flex flex-wrap justify-center text-center gap-30 lg:gap-50 lg:items-center lg:justify-evenly md:text-left">
			<div class="flex flex-col gap-3">
				<h2 class="title-2">
					<?php echo $cta_heading ?>
				</h2>
				<p class="m-0"><?php echo $cta_subheading ?></p>
			</div>

		</div>
	</div>
</section>