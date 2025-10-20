<aside id="contactPanel" class="aside-panel contact-panel fixed top-0 right-0 bottom-0 size-full max-w-700 translate-x-[120%] transition-all duration-500 ease">
	<button class="panel-close" aria-label="Close panel"></button>
	<noindex>
		<div class="h-full panel-scroll custom-scroll" data-lenis-prevent>
			<div class="panel-content">
				<div class="relative w-full panel-body md:mt-40">
					<div class="flex flex-col">

						<div class="text-center md:text-left heading-3">
							<?php _e('Free Strategy Call', 'lptheme'); ?>
						</div>

					</div>
				</div>
			</div>
		</div>
	</noindex>
</aside>

<aside id="mobilePanel" class="aside-panel fixed top-0 right-0 bottom-0 size-full max-w-700 translate-x-[120%] transition-all duration-500 ease">
	<button class="panel-close" aria-label="Close panel"></button>

	<noindex>
		<div class="h-full panel-scroll custom-scroll" data-lenis-prevent>
			<div class="panel-content">
				<div class="relative w-full panel-body">
					<div class="flex flex-col items-center justify-center h-full mobile-menu gap-60 mb-60">

						<div class="relative" data-tabs>
							<div class="tabs mb-10">
								<button class="tab w-full active"
									data-tab-target="#menu"><?php _e('Menu', 'lptheme'); ?></button>
								<button class="tab w-full"
									data-tab-target="#services"><?php _e('Services', 'lptheme'); ?></button>
							</div>
							<div class="tab-contents">
								<div id="menu" data-tab-content class="tab-content active">
									<div class="mobile-menu px-16 md:px-30 rounded-[10px] bg-white">
										<?php echo get_mobile_accordion_menu(); ?>
									</div>
								</div>
								<div id="services" data-tab-content class="tab-content">

								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</noindex>
</aside>

<div class="overlay ease-in-out"></div>
<div class="panel-fill ease"></div>