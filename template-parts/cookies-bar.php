<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------------------------------------
 * Cookies Bar
 * ------------------------------------------------------------------------------------------------
 */
?>

<style>
  .cookies {
    position: fixed;
    z-index: 111;
    bottom: 2rem;
    right: 2rem;
}
.cookies__content{
  display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    flex-wrap: nowrap;
    background: rgba(17, 15, 38, 0.34);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid #2B2665;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgb(65 72 86 / 6%);
    transition: all 400ms ease-in-out;
}
.cookies__accept {
    height: 30px;
    padding: 0 10px;
    line-height: 30px;
    border: none;
    border-radius: 10px;
    color: #18152e;
    font-size: .9rem;
    text-align: center;
    background: #04d4ff;
    cursor: pointer;
    outline: none;
    user-select: none;
    transition: background 250ms ease-in-out;
}
.cookies__message {
    font-size: 1rem;
    margin: 0;
}
@media(max-width:568px){
  .cookies {
    bottom: 1rem;
    right: 0;
    left: 0;
    width: calc(100% - 2rem);
    margin: auto;
}
}
</style>

<div id="cookiesNotice" class="cookies">
  <div class="cookies__content">
    <p class="cookies__message">
    <?php _e( 'We use cookies to ensure better User Experience.', 'lptheme' ); ?></p>
    <button class="cookies__accept"><?php _e( 'Ok', 'lptheme' ); ?></button>
  </div>
</div>