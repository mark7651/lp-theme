<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');
/**
 * Template for displaying the HTML sitemap page
 * Template Name: Sitemap HTML
 *
 */
get_header();

$sitemap_page_id = lp_tpl2id('sitemap-html');
// Pages
$pages = get_pages([
  'sort_column' => 'menu_order',
  'exclude' => $sitemap_page_id,
]);

// Posts
$posts = get_posts([
  'post_type'      => 'post',
  'post_status'    => 'publish',
  'posts_per_page' => -1,
  'orderby'        => 'date',
  'order'          => 'DESC',
]);

// Services (custom post type)
$services = get_posts([
  'post_type'      => 'services',
  'post_status'    => 'publish',
  'posts_per_page' => -1,
  'orderby'        => 'title',
  'order'          => 'ASC',
]);
?>

<section class="section-sm sitemap">

  <div class="container">
    <div class="page-header">
      <?php lp_breadcrumbs() ?>

      <h1 class="heading-2">
        <?php the_title(); ?>
      </h1>
    </div>

    <div class="grid gap-40 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">

      <div class="flex-col flex">
        <h2 class="heading-4 mb-30">Сторінки</h2>
        <ul class="mb-60 grid gap-10">
          <?php foreach ($pages as $page): ?>
            <li>
              <a href="<?php echo get_permalink($page); ?>">
                <?php echo esc_html($page->post_title); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="flex-col flex">
        <h2 class="heading-4 mb-30">Блог</h2>
        <ul class="mb-60 grid gap-10">
          <?php foreach ($posts as $post): ?>
            <li>
              <a href="<?php echo get_permalink($post); ?>">
                <?php echo esc_html(get_the_title($post)); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="flex-col flex">
        <h2 class="heading-4 mb-30">Послуги</h2>
        <ul class="grid gap-10">
          <?php foreach ($services as $service): ?>
            <li>
              <a href="<?php echo get_permalink($service); ?>">
                <?php echo esc_html(get_the_title($service)); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

  </div>
</section>
<?php get_footer(); ?>