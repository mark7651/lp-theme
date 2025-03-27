<?php

/**
 * Template for displaying the HTML sitemap page
 * Template Name: Sitemap HTML
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */
get_header();
?>

<section class="page-section section">
  <div class="container">
    <div class="heading-2">
      <?php echo esc_html(lp_page_title()); ?>
    </div>

    <div class="entry-content">
      <?php
      // Pages
      $pages = wp_list_pages(array(
        'exclude'     => '1387',
        'title_li'    => '',
        'sort_column' => 'menu_order, post_title',
        'echo'        => 0,
      ));
      if (! empty($pages)) : ?>
        <h2 id="sitemap-pages">Pages</h2>
        <ul><?php echo wp_kses_post($pages); ?></ul>
      <?php endif; ?>

      <?php
      // Posts
      $posts_args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
      );
      $posts_query = new WP_Query($posts_args);
      if ($posts_query->have_posts()) : ?>
        <h2 id="sitemap-posts">Posts</h2>
        <ul>
          <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
            <li <?php post_class(); ?>><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          <?php endwhile;
          wp_reset_postdata(); ?>
        </ul>
      <?php endif; ?>

      <?php
      // Post Categories
      $categories = wp_list_categories(array(
        'title_li'   => '',
        'show_count' => false,
        'hide_empty' => true,
        'echo'       => 0,
      ));
      if (! empty($categories)) : ?>
        <h2 id="sitemap-posts-categories">Post Categories</h2>
        <ul><?php echo wp_kses_post($categories); ?></ul>
      <?php endif; ?>

      <?php
      // Post Tags
      $tags = get_tags(array(
        'hide_empty' => true,
      ));
      if (! empty($tags) && ! is_wp_error($tags)) : ?>
        <h2 id="sitemap-posts-tags">Post Tags</h2>
        <ul>
          <?php foreach ($tags as $tag) : ?>
            <li class="tag-id-<?php echo esc_attr($tag->term_id); ?>">
              <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>">
                <?php echo esc_html($tag->name); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <?php
      // Custom Post Type: Our Work
      $custom_args = array(
        'post_type'      => 'our-work',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
      );
      $custom_query = new WP_Query($custom_args);
      if ($custom_query->have_posts()) : ?>
        <h2 id="sitemap-our-work">Our Work</h2>
        <ul>
          <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
            <li <?php post_class(); ?>><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          <?php endwhile;
          wp_reset_postdata(); ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>