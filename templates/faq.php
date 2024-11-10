<?php
/**
 * Template Name: FAQ
 *
 * @package lptheme
*/
get_header();
?>

<main>
  <?php get_template_part('template-parts/global/page-before-content');?>

  <div class="container">
    <?php get_template_part( 'template-parts/faq' ); ?>   
  </div>

  <?php get_template_part('template-parts/global/page-after-content');?>
</main>


<?php get_footer();