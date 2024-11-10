<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package lptheme
 */

get_template_part('template-parts/global/page-before-content');?>

<div class="container">

<?php while ( have_posts() ) : the_post(); 

the_content();

  wp_link_pages( array(
    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'lptheme' ),
    'after'  => '</div>',
  ) );
endwhile;?>

</div>

<?php get_template_part('template-parts/global/page-after-content');

