<?php if (!defined('LP_THEME_DIR')) exit('No direct script access allowed');

//  pll_the_languages(array(
//   'show_names' => 1,
//   'hide_current' => 1,
// ));
?>


<header id="header" class="header sticky w-full bg-white backdrop-blur-[30px] top-0 z-10 flex items-center py-16 xl:py-20">

  <div class="container">
    <div class="grid items-center justify-between gap-30 grid-cols-[1fr_auto_auto] lg:grid-cols-[1fr_auto_1fr]">

      <div class="relative transition-all duration-300 size-full header-logo hover:opacity-70">
        <?php lp_logo(); ?>
      </div>

      <div class="items-center hidden h-full lg:flex gap-30">
        <?php echo lp_header_main_nav() ?>
      </div>

      <div class="flex items-center w-full lg:justify-end">
        <button class="btn-secondary btn-small panel-trigger" data-panel="contactPanel"><?php _e('Book a Call', 'lptheme'); ?></button>
      </div>

      <button class="flex justify-end order-3 panel-trigger lg:hidden"
        data-panel="mobilePanel"
        aria-label="menu"
        data-toggle="mobilePanel">
        <?php icon('menu'); ?>
      </button>

    </div>
  </div>
</header>