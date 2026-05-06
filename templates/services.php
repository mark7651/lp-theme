<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Template Name: Services
 * ------------------------------------------------------------------------------------------------
 */

get_header();
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

      </div>


    </div>
  </div>
</section>


<?php
get_footer();
