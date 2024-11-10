<?php $post_id = get_the_ID();
$testimonial_data = get_post_meta( $post_id, '_testimonial', true );
$client_name = ( empty( $testimonial_data['client_name'] ) ) ? '' : $testimonial_data['client_name'];
$client_message = ( empty( $testimonial_data['client_message'] ) ) ? '' : $testimonial_data['client_message'];
$date = get_the_date('F j, Y');
$user_image_id = get_post_thumbnail_id($post_id);
$user_image_url = wp_get_attachment_image_url($user_image_id, 'thumbnail'); 
 // Display admin comments
 $comments_args = array(
  'post_id' => $post_id,
  'status' => 'approve',
);
$comments = get_comments($comments_args);
?>

<div itemscope itemtype="http://schema.org/Review" class="testimonials__item box">
  <meta itemprop="datePublished" content="<?php echo $date;?>">

  <div class="testimonials__item-header">
    <div class="testimonials__item-image">
    <?php if ($user_image_url):?>
      <img src="<?php echo esc_url($user_image_url)?>" alt="<?php echo esc_attr($client_name)?>"> 
      <?php else: ?>
      <?php get_template_part('images/user.svg'); ?>
      <?php endif; ?>
    </div>
    <div class="testimonials__item-title">
      <div itemprop="author" itemscope itemtype="http://schema.org/Person"
        class="testimonials__item-name second-subheading">
        <span itemprop="name"><?php echo $client_name;?></span>
      </div>
      <time itemprop="datePublished" class="testimonials__item-date" datetime="<?php echo $date;?>"><?php echo $date;?></time>
    </div>
  </div>
  <blockquote itemprop="reviewBody" class="testimonials__item-text">
    <?php echo $client_message;?>
  </blockquote>

  <?php if ($comments): ?>
    <div class="testimonials__item-response">
      <div class="second-subheading"><?php echo translate_pll('Ответ менеджера', 'Відповідь менеджера'); ?></div>
        <ul class="testimonials__item-comment">
         <?php foreach ($comments as $comment):?>
           <li><?php echo esc_html($comment->comment_content); ?></li>
          <?php endforeach; ?>
        </ul>
    </div>
  <?php endif; ?>

  <div itemprop="itemReviewed" itemscope itemtype="https://schema.org/Organization">
    <meta itemprop="name" content="Отзыв о компании <?php bloginfo('name'); ?>">
    <meta itemprop="telephone" content="<?php the_field('phone_1', 'option');?>">
    <link itemprop="url" href="<?php bloginfo('url'); ?>">
    <meta itemprop="email" content="<?php the_field('email', 'option');?>">
    <p itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
      <meta itemprop="addressLocality" content="Одесса">
      <meta itemprop="streetAddress" content="<?php the_field('address', 'option');?>">
    </p>
  </div>

</div>
