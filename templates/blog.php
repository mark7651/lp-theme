<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');
/**
 * Template Name: Blog
 *
 * @package LPtheme
*/
get_header();

$post_per_page = get_field('blog-posts-per-page');

if (get_query_var('paged')) {
  $paged = get_query_var('paged');
} elseif (get_query_var('page')) { 
  $paged = get_query_var('page');
} else {
  $paged = 1;
}

if (!$post_per_page) {
  $post_per_page = get_option('posts_per_page');
}

$post_args = array(
  'posts_per_page' => $post_per_page,
  'orderby'        => 'date',
  'paged'          => $paged,
  'order'          => 'DESC',
  'post_type'      => 'post',
  'post_status'    => 'publish'
);

$query = new WP_Query( $post_args );
if(is_page()) {
  $max_num_pages = $query -> max_num_pages;
} else {
  global $wp_query;
  $query = $wp_query;
  $max_num_pages = false;
}
?>

<style>
.lp-pagination {
  display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 3rem;
}

.lp-pagination .page-numbers{
  padding: 1.2rem 1.8rem;
  border-radius: 10px;
  font-weight: 700;
}

.lp-pagination .current {
  background-color: #34ddff;
  color: #18152e;
}

.lp-pagination .page-numbers:hover:not(.dots){
  background-color: #34ddff;
  color: #18152e;
}

</style>

<main class="section blog-section" role="main">
  <div class="container">

    <div class="page-title__wrap section-title">
      <?php echo lp_page_title();?>
    </div>

    <div class="four-columns posts-grid">
      <?php if($query -> have_posts()): while ($query -> have_posts()) : $query -> the_post(); ?>
      <?php get_template_part( 'template-parts/post-card' ); ?>
      <?php endwhile; wp_reset_postdata(); else:
          get_template_part('templates/content', 'none');
        endif; ?>
    </div>

    <?php lp_paging_nav($max_num_pages); ?>

  </div>
</main>

<?php get_footer();
