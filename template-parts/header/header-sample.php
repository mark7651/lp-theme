<?php if (! defined('ABSPATH')) {
  die();
}
/**
 * header simple
 * css in critical
 * @package lptheme
 */
$phone_1 = get_field('phone_1', 'option') ?: '';
$phone_2 = get_field('phone_2', 'option') ?: '';

$phone_link_1 = preg_replace('![^0-9+]!', '', $phone_1);
$phone_link_2 = preg_replace('![^0-9+]!', '', $phone_2);
?>

<div class="container">
  <div class="grid">

    <div class="col header-logo"><?php lp_logo(); ?></div>
    <div class="col header-navbar"><?php lp_header_main_nav(); ?></div>

    <div class="col header-contacts">
      <div class="header-contacts_info">

        <?php if ($phone_1) : ?>
          <a href="tel:<?php echo esc_attr($phone_link_1); ?>" rel="noopener">
            <?php echo esc_html($phone_1); ?></a>
        <?php endif; ?>

        <?php if ($phone_2) : ?>
          <a href="tel:<?php echo esc_attr($phone_link_2); ?>" rel="noopener">
            <?php echo esc_html($phone_2); ?></a>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>