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
				<div class="text-[15px] font-semibold text-center md:text-left">Меню</div>
				<?php echo lp_header_main_nav() ?>
			</div>

			<div class="flex flex-col md:flex-row gap-30 w-full sm:w-fit">
				<div class="flex flex-col gap-12 lg:gap-16 items-center md:items-start">
					<div class="text-[15px] font-semibold text-center md:text-left">Звʼязок з нами</div>
					<div class="flex items-center gap-28">
						<div class="flex flex-col gap-10">
							<?php if ($phone_1): ?>
								<a href="tel:+38<?php echo $phone_1_link; ?>" rel="noopener" class="flex gap-4 lg:gap-8 items-center font-medium text-black font-second transition-opacity text-[15px] xl:text-[20px] whitespace-nowrap hover:opacity-60">
									<?php icon('phone-2', 'size-19'); ?>
									<span><?php echo esc_html($phone_1); ?></span>
								</a>
							<?php endif; ?>
							<?php if ($phone_2): ?>
								<a href="tel:+38<?php echo $phone_2_link; ?>" rel="noopener" class="flex gap-4 lg:gap-8 items-center font-medium text-black font-second transition-opacity text-[15px] xl:text-[20px] whitespace-nowrap hover:opacity-60">
									<?php icon('phone-2', 'size-19'); ?>
									<span> <?php echo esc_html($phone_2); ?></span>
								</a>
							<?php endif; ?>
						</div>

						<div class="flex flex-col">
							<?php if ($instagram): ?>
								<a
									href="<?php echo $instagram ?>"
									class="bg-gray-3 rounded-[16px] aspect-square h-46 px-6 py-12 flex items-center justify-center transition-opacity hover:opacity-60"
									target="_blank"
									rel="noopener"
									aria-label="Instagram">
									<?php icon('insta'); ?>
								</a>
							<?php endif; ?>
							<?php if ($facebook): ?>
								<a
									href="<?php echo $facebook ?>"
									class="bg-gray-3 rounded-[16px] aspect-square h-46 px-6  py-12 flex items-center justify-center transition-opacity hover:opacity-60"
									target="_blank"
									rel="noopener"
									aria-label="Facebook">
									<?php icon('fb'); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="flex flex-col gap-12 lg:gap-16">
					<div class="text-[15px] font-semibold text-center md:text-left">Локації</div>
					<div class="flex md:flex-col">
						<button class="btn btn-primary h-46 pointer-events-none justify-start w-full">
							<?php icon('pin', 'size-12 lg:size-18'); ?>
							<span class="current-city text-[12px] md:text-[15px] relative bottom-1"> Чернівці</span>
						</button>

						<a href="/" rel="noopener" class="btn bg-gray-3 justify-start w-full h-46">
							<?php icon('pin', 'size-12 lg:size-18 !text-black'); ?>
							<span class="text-[12px] md:text-[15px] relative bottom-1"> Рівне</span>
						</a>
					</div>
				</div>
			</div>

		</div>
	</div>
</footer>