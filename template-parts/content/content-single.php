<?php
/**
 * The template part for displaying single posts
 *
 * @package Catch_Starter
 */
?>
<style>
	.single article, .page-title__wrap {
    max-width: 768px;
		margin: 0 auto;
}
.single article img{
    border-radius: 10px;
}
.entry-content {
    margin-top: 3rem;
}
.entry-meta-list {
    padding: 0;
		margin: 0;
    list-style: none;
}
.post-actions {
    display: flex;
    gap: 3rem;
}
.post-mask {
    display: inline-grid;
    gap: 3rem;
    grid-auto-flow: column;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    margin-top: 2rem;
}
</style>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<header class="entry-header">
		<figure>
				<?php the_post_thumbnail( 'full' ); ?>
			<!-- <?php // if ( get_the_category_list( ', ' ) ): ?>
			<div class="meta-post-categories"><?php //echo get_the_category_list( ', ' ); ?></div>
			<?php //endif ?> -->

			<?php //lp_post_date(); ?>
		</figure>

		<div class="post-mask">
				<div class="entry-meta lp-entry-meta">
					<?php lp_post_meta(array(
							'date' => 0,
							'labels' => 1,
						)); ?>
				</div>
				<div class="post-actions">
				<button class="like" data-id="<?php the_ID(); ?>"><?php get_template_part( 'images/like.svg' ); ?><span
						class="like-count"><?php display_post_likes( get_the_ID() ); ?></span></button>

				<?php echo lp_share_links();?>

				</div>

			</div>

	</header>

	<div class="entry-content">
		<?php the_content();?>
	</div>

</article>
