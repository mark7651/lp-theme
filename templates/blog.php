<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');
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

if (is_category()) {
  $post_args['cat'] = get_query_var('cat');
  $category = get_category(get_query_var('cat'));
  $page_title = $category->name;
} else {
  $page_title = get_the_title();
}

$query = new WP_Query($post_args);
$max_num_pages = $query->max_num_pages;

$blog_id = lp_tpl2id('blog');
$thumbnail_size = wp_is_mobile() ? 'medium' : 'large';
$hero_cover = get_field('blog_cover', $blog_id);
?>

<section id="top" class="relative py-60 lg:py-90 section-hero parallax overflow-clip bg-gray">
  <div class="container z-1">
    <h1 class="text-white heading-1 animated-heading">
      <?php echo esc_html($page_title); ?>
    </h1>
  </div>
  <?php if ($hero_cover) : ?>
    <div class="absolute inset-0 z-0 size-fulll">
      <?php echo wp_get_attachment_image($hero_cover, $thumbnail_size, false, [
        'class' => 'size-full bg-gray object-cover'
      ]); ?>
    </div>
  <?php endif; ?>
</section>

<div class="relative py-60 lg:py-90">
  <div class="container">

    <div id="articles-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-30 gap-y-60 xl:gap-y-100">
      <?php if ($query->have_posts()): while ($query->have_posts()) : $query->the_post(); ?>
          <?php get_template_part('template-parts/blog/post-card-alt'); ?>
      <?php endwhile;
        wp_reset_postdata();
      endif; ?>
    </div>

    <?php lp_paging_nav($max_num_pages); ?>

  </div>
</div>

<?php get_footer();
