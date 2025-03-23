<?php if (! defined('ABSPATH')) {
  die();
}
/**
 * ------------------------------------------------------------------------------------------------
 * Post Card
 * ------------------------------------------------------------------------------------------------
 */
?>

<article class="relative flex flex-col border-t border-gray gap-30 group pt-20 lg:pt-40">
  <?php if (has_category()) : ?>
    <div class="flex items-center justify-between gap-30">

      <time datetime="<?php the_time('Y-m-d'); ?>"
        class="subline text-gray text-[0.9rem]">
        <?php the_time('d.m.Y'); ?>
      </time>
    </div>
  <?php endif; ?>

  <div class="flex flex-col mb-10 gap-30">
    <h3 class="heading-3">
      <a href="<?php echo esc_url(get_permalink()); ?>"
        class="transition-opacity hover:opacity-70">
        <?php the_title(); ?>
      </a>
    </h3>

    <a href="<?php echo esc_url(get_permalink()); ?>"
      class="link-btn">
      <?php _e('Read more', 'lptheme'); ?>
    </a>
  </div>
</article>