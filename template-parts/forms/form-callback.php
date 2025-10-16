<form id="form-callback" method="POST" action="<?php echo esc_url(get_permalink()); ?>" class="lp-form"
  novalidate="novalidate" enctype="multipart/form-data">

  <div class="input-group">
    <input type="text" id="NameCallback" name="name" autocomplete="off" placeholder="<?php _e('Your name', 'lptheme'); ?>"
      required="required">
  </div>

  <div class="input-group">
    <input class="mask" type="tel" id="PhoneCallback" name="phone" autocomplete="off"
      placeholder="+38 (___) ___ __ __" data-mask="+38 (___) ___ __ __"
      required="required" minlength="13">
  </div>

  <button class="btn btn-primary submit-btn" name="submit" type="submit">
    <?php _e('Send Message', 'lptheme'); ?>
  </button>

  <input type="hidden" name="callback-form" value="1">
  <input type="hidden" name="form_name" value="<?php _e('Callback form', 'lptheme'); ?>">
  <input type="hidden" name="form_page" value="<?php the_title(); ?>">

  <output class="notification-box"></output>

</form>