<form id="form-quick-purchase" method="POST" action="<?php echo esc_url( get_permalink() ); ?>" class="lp-form"
  novalidate="novalidate" enctype="multipart/form-data">

  <div class="input-group">
    <input class="your-name" type="text" id="Name" name="name" value="" autocomplete="off"
      placeholder="<?php _e( 'Your name', 'lptheme' ); ?>" required="required">
  </div>

  <div class="input-group">
      <input class="your-phone mask" type="tel" id="Phone" name="phone" autocomplete="off" 
    placeholder="+38 (___) ___ __ __" data-mask="+38 (___) ___ __ __"
      required="required" minlength="13">
  </div>

  <div class="input-group">
    <input class="your-product" type="text" id="Product" name="product" autocomplete="off" 
      required="required" readonly>
  </div>

  <button class="btn btn-primary btn-quick-purchase submit-btn" name="submit" type="submit">
    <?php _e( 'Buy', 'lptheme' ); ?>
  </button>

  <input type="hidden" name="quick-purchase-form" value="1">
  <input type="hidden" name="form_name" value="<?php _e( 'Quick purchase', 'lptheme' ); ?>">
  <input type="hidden" name="form_page" value="<?php the_title();?>">

  <div class="notification-box"></div>

</form>
