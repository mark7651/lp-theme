<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Template Name: Contacts
 * ------------------------------------------------------------------------------------------------
 */

get_header();

$email = get_field('email', 'option');
$address = get_field('address', 'option');
?>

<section class="section bg-gray-2">
  <div class="container">
    <div class="grid grid-cols-1 gap-60 xl:gap-100 lg:grid-cols-2 gap-y-60">

      <div class="flex flex-col justify-between gap-60 lg:gap-100 bg-white rounded-[20px] p-20 lg:p-40">
        <div class="flex flex-col gap-40">
          <h1 class="heading-1 animated-heading">
            <?php echo the_title(); ?>
          </h1>

        </div>

        <div class="flex flex-col gap-30">
          <div class="flex flex-col gap-10 fade-in">
            <div class="subline w-fit px-12 py-7 rounded-[12px] bg-primary text-white">
              <?php _e('Agency address', 'lptheme'); ?>
            </div>
            <?php if ($address) : ?>
              <div class="heading-4">
                <?php echo esc_html($address); ?>
              </div>
            <?php endif; ?>
          </div>

          <?php if ($email) : ?>
            <a href="mailto:<?php echo esc_attr($email); ?>" class="underline transition-opacity duration-300 fade-in heading-3 prevent hover:opacity-70" rel="noopener">
              <?php echo esc_html($email); ?>
            </a>
          <?php endif; ?>
        </div>
      </div>

      <div class="flex flex-col gap-40">
        <div class="flex flex-col gap-12 fade-in">
          <div class="title">Get in Touch</div>
        </div>
        <?php echo do_shortcode('[form-contact]'); ?>
      </div>

    </div>
  </div>
</section>


<?php
get_footer();
