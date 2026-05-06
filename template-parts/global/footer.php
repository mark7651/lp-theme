<?php
$phone_1 = get_field('phone_1', 'option');
$phone_1_link = $phone_1 ? preg_replace('/[^0-9\+]/', '', $phone_1) : null;
$phone_2 = get_field('phone_2', 'option');
$phone_2_link = $phone_2 ? preg_replace('/[^0-9\+]/', '', $phone_2) : null;
$instagram = get_field('instagram_link', 'option');
$facebook   = get_field('facebook_link', 'option');
?>

<footer id="footer" class="footer pb-20 md:pb-30 py-60">
	<div class="container">
		<div class="flex flex-col md:flex-row items-center md:items-start flex-wrap lg:flex-nowrap justify-between gap-30 3xl:gap-50">
			<div class="relative header-logo flex flex-col gap-4 w-full max-w-174">
				<?php lp_logo(); ?>
				<div class="text-black/40 leading-none">© 2021-<?php echo date("Y"); ?> - <?php bloginfo('name'); ?></div>
			</div>

			<div class="flex flex-col gap-12 lg:gap-16 max-w-500 3xl:max-w-none">
				<?php echo lp_header_main_nav() ?>
			</div>

		</div>
	</div>
</footer>