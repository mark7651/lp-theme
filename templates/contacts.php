<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');
//Template Name: Contacts
get_header();
?>

<main class="page-content" role="main">
  <div class="container">
    <div class="page-title">
      <?php echo lp_page_title();?>
    </div>

    <?php while ( have_posts() ) : the_post(); ?>
      <div class="page-content">
        <?php the_content(); ?>
      </div>
    <?php endwhile; ?>
  </div>
</main>


<?php get_footer(); ?>
