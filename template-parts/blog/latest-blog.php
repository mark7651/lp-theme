<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Latest Posts
 * ------------------------------------------------------------------------------------------------
 */

$post_args = array(
  'posts_per_page' => 4,
  'orderby'        => 'date',
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

<?php if($query -> have_posts()): while ($query -> have_posts()) : $query -> the_post(); ?>
  <div class="section grid latest-blog">
    <?php get_template_part( 'template-parts/blog/post-card' ); ?>
  </div>
<?php endwhile; wp_reset_postdata(); else: get_template_part('template-parts/content/content', 'none'); endif; ?>
