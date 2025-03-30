<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');
/**
 * The template for displaying the footer
 * 
 * Contains the closing of the #content div and all content after.
 *
 *
 */
?>

</main>

<?php get_template_part('template-parts/global/footer') ?>
<?php get_template_part('template-parts/global/panels') ?>
<?php wp_footer(); ?>

</body>

</html>