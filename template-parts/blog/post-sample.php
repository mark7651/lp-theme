<?php if ( ! defined( 'ABSPATH' ) ) { die(); } 
/**
 * ------------------------------------------------------------------------------------------------
 * Post Card
 * ------------------------------------------------------------------------------------------------
 */
?>

<article class="post-card">
  <div class="post-card__content">

    <a href="<?php esc_url(the_permalink()); ?>">
      <?php the_post_thumbnail(); ?>
    </a>

    <h2><a href="<?php esc_url(the_permalink()); ?>"><?php the_title(); ?></a></h2>
    <div class="post-card__meta">
      <ul class="post-card__categories">
        <li>
          <?php echo get_the_category_list( __( ' ', 'lptheme' ) );?>
        </li>
      </ul>
      <time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('m.d.Y'); ?></time>
    </div>

    <p><?php echo lp_post_excerpt(30); ?></p>
    <a href="<?php esc_url(the_permalink()); ?>"
      class="read-more__link"><?php esc_html_e('Read More', 'lptheme'); ?></a>
  </div>
</article>