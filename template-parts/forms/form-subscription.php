<form id="form-subscription" method="POST" action="<?php echo esc_url( get_permalink() ); ?>" class="lp-form"
  novalidate="novalidate" enctype="multipart/form-data">

  <div class="flex">
    <div class="input-group">
      <input class="mask" type="email" id="SubscribeEmail" name="subscription-email" 
      autocomplete="off" 
      placeholder="<?php _e( 'Your email', 'lptheme' ); ?>"
      required="required">
    </div>

    <button class="btn btn-primary submit-btn" name="submit" type="submit">
      <?php _e( 'Subscribe', 'lptheme' ); ?>
    </button>
  </div>

  <input type="hidden" name="subscribe-form" value="1">
  <input type="hidden" name="form_name" value="<?php _e( 'Subscribe form', 'lptheme' ); ?>">
  <input type="hidden" name="form_page" value="<?php the_title();?>">

  <div class="notification-box"></div>

</form>