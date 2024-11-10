<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');
/**
 * The template for the sidebar containing the main widget area
 *
 * @package lptheme
 */
?>

<aside id="sidebar" class="sidebar-container" role="complementary">
  <div class="sidebar-inner lp-scroll">
    <div class="widget-area lp-sidebar-content">
      <?php dynamic_sidebar( 'main-sidebar' ); ?>
    </div>
  </div>
</aside>
