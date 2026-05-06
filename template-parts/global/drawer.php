<?php
$lp_drawers = [
	['id' => 'contactPanel', 'title' => lp_t('Submit a request'), 'form' => 'application'],
	['id' => 'invitePanel',  'title' => lp_t('Request an invitation'), 'form' => 'invitation'],
];
foreach ($lp_drawers as $d): ?>
	<aside id="<?php echo esc_attr($d['id']); ?>" class="aside-drawer md:hidden">
		<div class="drawer__scroller">
			<div class="drawer__slide">
				<div class="drawer__anchors">
					<div class="drawer__anchor"></div>
					<div class="drawer__anchor"></div>
				</div>
				<button class="drawer__curtain" aria-label="Close drawer" tabindex="-1"></button>
				<div class="drawer__content">
					<button class="drawer__drag" aria-label="Close drawer">
						<span></span>
					</button>
					<button class="drawer__close" aria-label="Close drawer"></button>
					<div class="drawer__inner">
						<noindex>
							<div class="text-center heading-2 mb-30">
								<?php echo esc_html($d['title']); ?>
							</div>
							<?php get_template_part('template-parts/forms/form', $d['form']); ?>
						</noindex>
					</div>
				</div>
			</div>
		</div>
	</aside>
<?php endforeach; ?>

<aside id="mobilePanel" class="aside-drawer md:hidden">
	<div class="drawer__scroller">
		<div class="drawer__slide">
			<div class="drawer__anchors">
				<div class="drawer__anchor"></div>
				<div class="drawer__anchor"></div>
			</div>
			<button class="drawer__curtain" aria-label="Close drawer" tabindex="-1"></button>
			<div class="drawer__content">
				<button class="drawer__drag" aria-label="Close drawer">
					<span></span>
				</button>
				<button class="drawer__close" aria-label="Close drawer"></button>
				<div class="drawer__inner">
					<noindex>
						<div class="flex flex-col items-center justify-center mobile-menu gap-30 mb-30">

							<div class="mobile-menu">
								<?php echo get_mobile_accordion_menu(); ?>
							</div>

							<div class="flex flex-col gap-30 items-center w-full">
								<div class="flex flex-col gap-10 w-full">
									<button class="w-full btn-outline btn btn-small panel-trigger" data-panel="invitePanel"><?php echo esc_html(lp_t('Get an invitation')); ?></button>

									<button class="w-full btn-white btn btn-small panel-trigger" data-panel="contactPanel"><?php echo esc_html(lp_t('Submit a request')); ?></button>
								</div>
								<?php if (function_exists('pll_the_languages')): ?>
									<?php foreach ((array) pll_the_languages(['raw' => 1, 'hide_current' => 1]) as $lang): ?>
										<a href="<?php echo esc_url($lang['url']); ?>"
											class="lang-switch btn btn-small btn-ghost text-[16px]"
											hreflang="<?php echo esc_attr($lang['slug']); ?>"
											aria-label="<?php echo esc_attr(sprintf(lp_t('Switch language to %s'), $lang['name'])); ?>">
											<?php icon('globe'); ?>
											<span><?php echo esc_html(strtoupper($lang['slug'])); ?></span>
										</a>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>

						</div>
					</noindex>
				</div>
			</div>
		</div>
	</div>
</aside>