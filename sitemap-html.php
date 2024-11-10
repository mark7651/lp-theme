<?php 
/**
 * The template for displaying the sitemap page
 * Template Name: Sitemap HTML
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 */ 
get_header(); 

$postsArgs = array(
  'post_type' => 'post',
  'posts_per_page'=>'-1',
  // 'post__not_in' => array(), 
);

$tags = get_tags(array( 
  //  'exclude'    => array(),
  )
);

$customPostArgs = array(
  'post_type' => 'our-work',
  'posts_per_page'=>'-1',
  //'post__not_in' => array(), 
);
?>

<main class="page-section section" role="main">
  <div class="container">
    
    <div class="page-title__wrap section-title">
      <?php echo lp_page_title();?>
    </div>

      <div class="entry-content">

        <h2 id="sitemap-pages">Pages</h2>

        <ul>
         <?php 
          wp_list_pages( array( 
            'exclude' => '1387',
              'title_li' => '',
            )
          );
          ?>
        </ul>

        <h2 id="sitemap-posts">Posts</h2>

        <ul>
          <?php $postsLoop = new WP_Query( $postsArgs );
          while ( $postsLoop->have_posts() ) {
          $postsLoop->the_post();
          ?>
          <li <?php post_class(); ?>><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          <?php } wp_reset_query(); ?>
        </ul>

        <h2 id="sitemap-posts-categories">Post Categories</h2>

        <ul>
         <?php wp_list_categories( array(
            'title_li' => '',
            'show_count' => false,
        //  'exclude'    => array(),
        ) ); ?>
        </ul>

          <?php if ($tags) { ?>
          <h2 id="sitemap-posts-tags">Post Tags</h2>
          <ul>
            <?php foreach ($tags as $tag) { ?>
            <li class="tag-id-<?php echo $tag->term_id ?>"><a
                href="<?php echo get_tag_link( $tag->term_id );?>"><?php echo $tag->name ?></a></li>
            <?php } ?>
          </ul>
        <? } ?>

        <h2 id="sitemap-our-work">Our Work</h2>

        <ul>
          <?php $customPostsLoop = new WP_Query( $customPostArgs );
          while ( $customPostsLoop->have_posts() ) {
            $customPostsLoop->the_post();
          ?>
          <li <?php post_class(); ?>><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          <?php } wp_reset_query(); ?>
        </ul>

      </div>


  </div>
</main>

<?php get_footer(); ?>
