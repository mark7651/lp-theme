<?php if ( ! defined( 'ABSPATH' ) ) { die(); } ?>

<div class="footer-columns">
  <div class="footer-column">
    <?php if ( is_active_sidebar( 'footer-1' ) ) { ?><?php dynamic_sidebar('footer-1'); ?><?php } ?>
  </div>
  <div class="footer-column">
    <?php if ( is_active_sidebar( 'footer-2' ) ) { ?><?php dynamic_sidebar('footer-2'); ?><?php } ?>
  </div>
  <div class="footer-column">
    <?php if ( is_active_sidebar( 'footer-3' ) ) { ?><?php dynamic_sidebar('footer-3'); ?><?php } ?>
  </div>
  <div class="footer-column">
    <div class="widget widget-contacts">
      <div class="widget-title"><?php _e( 'Contact Us', 'lptheme' ); ?></div>

      <div class="contact-list">

      </div>

    </div>
  </div>
</div>