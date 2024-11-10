<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Fully Disable Gutenberg editor
 * ------------------------------------------------------------------------------------------------
 */

add_filter('use_block_editor_for_post_type', '__return_false', 10);
add_filter( 'use_block_editor_for_post', '__return_false' );
add_filter( 'big_image_size_threshold', '__return_false' );

// remove admin staff
add_filter( 'show_admin_bar', '__return_false' );
add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );


/**
 * ------------------------------------------------------------------------------------------------
 * Custom color scheme (dark mode)
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'dark_mode_dashboard_add_styles' ) ) { 
  function dark_mode_dashboard_add_styles() {
    if(wp_get_current_user()->dark_mode_dashboard != 1) {
        $dark_mode_dashboard_style = apply_filters( 'dark_mode_dashboard_css', LP_THEME_DIR . '/inc/admin/css/dark-mode.css');
        wp_register_style( 'dark-mode-dashboard', $dark_mode_dashboard_style, array() );
        wp_enqueue_style( 'dark-mode-dashboard');
        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
        add_editor_style( LP_THEME_DIR . '/inc/admin/css/dark-editor-style.css');
    } else{
      add_editor_style( LP_THEME_DIR . '/inc/admin/css/editor-style.css');
    }
  }
  add_action( 'admin_enqueue_scripts', 'dark_mode_dashboard_add_styles' );
  }
  
 // Add field to user profile page
  add_action( 'show_user_profile', 'dark_mode_dashboard_user_profile_fields', 10, 1 );
  add_action( 'edit_user_profile', 'dark_mode_dashboard_user_profile_fields', 10, 1 );
  
  function dark_mode_dashboard_user_profile_fields( $user ) { ?>
    <h3><?php _e("Dark Mode for WP Dashboard", "blank"); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="darkmode"><?php _e( 'Disable darkmode?', 'lptheme' ); ?></label></th>
            <td>
                <input type="checkbox" name="dark_mode_dashboard" id="darkmode" value="1" <?php checked($user->dark_mode_dashboard, true, true); ?>>
            </td>
        </tr>
    </table>
  <?php }
  
  // Save data from user profile field to database
  add_action( 'personal_options_update', 'dark_mode_dashboard_save_user_profile_fields' );
  add_action( 'edit_user_profile_update', 'dark_mode_dashboard_save_user_profile_fields' );
  
  function dark_mode_dashboard_save_user_profile_fields( $user_id ) {
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    update_user_meta( $user_id, 'dark_mode_dashboard', $_POST['dark_mode_dashboard'] );
  }


/**
 * ------------------------------------------------------------------------------------------------
 * whitelabel admin
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'lp_admin_enqueue_scripts' ) ) {
function lp_admin_enqueue_scripts() {
  wp_enqueue_script( 'options', LP_THEME_DIR . '/inc/admin/js/options.js');
  wp_enqueue_style( 'options', LP_THEME_DIR . '/inc/admin/css/options.css');
}
add_action('admin_enqueue_scripts', 'lp_admin_enqueue_scripts');
}

// remove wp logo
function admin_bar_remove_logo() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'wp-logo' );
}
add_action( 'wp_before_admin_bar_render', 'admin_bar_remove_logo', 0 );

// custom footer copyrights
function layers_child_footer_admin () { 
	echo esc_html__( 'Developed by', 'lptheme' ) . ' <a href="//lpunity.com">LPunity</a>'; 
}

if( get_field( 'developer_dashboard' , 'option' ) ) {
  add_filter('admin_footer_text', 'layers_child_footer_admin');
}

// admin menu open website in new window
function shatel_view( $wp_admin_bar ) {
    $all_toolbar_nodes = $wp_admin_bar->get_nodes();
    foreach ( $all_toolbar_nodes as $node ) {
        if($node->id == 'site-name' || $node->id == 'view-site')
        {
        $args = $node;
        $args->meta = array('target' => '_blank');
        $wp_admin_bar->add_node( $args );
        }
    }
}
add_action( 'admin_bar_menu', 'shatel_view', 999 );

// decline wrap <p> 
function remove_img_ptags_func( $content ){
	return preg_replace('/<p>\s*((?:<a[^>]+>)?\s*<img[^>]+>\s*(?:<\/a>)?)\s*<\/p>/i', '\1', $content );
}
add_filter('the_content', 'remove_img_ptags_func');

// login page custom css
if( ! function_exists( 'lp_login_style' ) ) {
  function lp_login_style() {
    wp_enqueue_style( 'custom-login', LP_THEME_DIR . '/inc/admin/css/login.css' );
  }
  add_action( 'login_enqueue_scripts', 'lp_login_style' );
}

// custom login logo

if (get_field('header_logo_svg', 'option')) {
  add_action('login_head', 'lp_login_logo');
  function lp_login_logo() {
    $login_logo = get_field('header_logo_svg', 'option');
    echo '<style> h1 a { background: url('. esc_url($login_logo) .') no-repeat 50% 50%/contain!important; height: 150px!important;width: auto!important;}
    </style>';
  }
} else{
  add_action('login_head', 'lp_login_logo');
  function lp_login_logo() {
    $login_logo = get_field('header_logo', 'option');
    echo '<style> h1 a { background: url('. esc_url($login_logo['url']) .') no-repeat 50% 50%/contain!important; height: 150px!important;width: auto!important;}
    </style>';
  }
}

// changing the logo link from wordpress.org to your site
function mb_login_url() {  return home_url(); }
add_filter( 'login_headerurl', 'mb_login_url' );
function mb_login_title() { return get_option( 'blogname' ); }
add_filter( 'login_headertext', 'mb_login_title' );

/**
 * ------------------------------------------------------------------------------------------------
 * disable revisions
 * ------------------------------------------------------------------------------------------------
 */

function deactivate_revisions( $count ) {return 0;}
add_filter( 'wp_revisions_to_keep', 'deactivate_revisions' );


/**
 * ------------------------------------------------------------------------------------------------
 * remove admin menu items
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'lp_remove_menus' ) ) {
  function lp_remove_menus(){
    global $menu;
    global $submenu;
    $menu[5][0] = esc_html__( 'Blog', 'lptheme' ); 
    unset($submenu['themes.php'][6]);
    unset($submenu['themes.php'][5]);
    unset($submenu['themes.php'][20]);
    unset($submenu['themes.php'][22]);
    if(get_field('news_menu_item','option')){ remove_menu_page( 'edit.php' ); } // Записи
    if(get_field('comments_menu_item','option')){ remove_menu_page( 'edit-comments.php' ); } // Комментарии
    if(get_field('users_menu_item','option')){ remove_menu_page( 'users.php' ); } // Пользователи
    if(get_field('plugins_menu_item','option')){ remove_menu_page( 'plugins.php' ); } // Плагины
    if(get_field('tools_menu_item','option')){ remove_menu_page( 'tools.php' ); } // Инструменты
    if(get_field('options_general_menu_item','option')){ remove_menu_page('options-general.php'); } // Настройки
    remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
    remove_submenu_page( 'index.php', 'update-core.php' );
    if(!defined('LP_THEME')) {
      remove_menu_page('developers-settings');
      remove_menu_page('edit.php?post_type=acf-field-group');
    } 
  }
  add_action( 'admin_menu', 'lp_remove_menus' , 999);
}

function lp_custom_menu_order( $menu_ord ) {
  if ( !$menu_ord ) return true;
  return array(
      'index.php', // Dashboard
      'edit.php?post_type=page', // Pages
      'edit.php', // Posts
  );
}
add_filter( 'custom_menu_order', 'lp_custom_menu_order', 10, 1 );
add_filter( 'menu_order', 'lp_custom_menu_order', 10, 1 );

/**
 * ------------------------------------------------------------------------------------------------
 * disable Default Dashboard Widgets
 * ------------------------------------------------------------------------------------------------
 */

if( ! function_exists( 'lp_remove_dashboard_meta' ) ) {
function lp_remove_dashboard_meta() {
	remove_action('welcome_panel', 'wp_welcome_panel');
	remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); //Removes the 'incoming links' widget
	remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); //Removes the 'plugins' widget
	remove_meta_box('dashboard_primary', 'dashboard', 'normal'); //Removes the 'WordPress News' widget
	remove_meta_box('dashboard_secondary', 'dashboard', 'normal'); //Removes the secondary widget
	remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); //Removes the 'Quick Draft' widget
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); //Removes the 'Recent Drafts' widget
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); //Removes the 'Activity' widget
	remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //Removes the 'At a Glance' widget
	remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Removes the 'Activity' widget (since 3.8)
  remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal'); //Removes the 'Activity' widget (since 3.8)
  remove_meta_box('semperplugins-rss-feed', 'dashboard', 'normal');
	remove_action('admin_notices', 'update_nag');
}
add_action('admin_init', 'lp_remove_dashboard_meta');
}

/**
 * ------------------------------------------------------------------------------------------------
 *  dashboard status
 * ------------------------------------------------------------------------------------------------
 */

function status_dashboard_widgets() {
  wp_add_dashboard_widget('status_dashboard_widget', 'Website status info', 'status_theme_info');
}

function status_theme_info() { ?>
<ul class="lptheme-status-list">

  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'Website title', 'Site / server status', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php if ( is_network_admin() ) { ?>
        <a href="<?php echo esc_url( admin_url( 'network/settings.php' ) ); ?>" title="<?php echo esc_attr( __( 'Change', 'lptheme' ) ); ?>">
          <?php echo get_site_option( 'site_name' ); ?>
        </a>
      <?php } else { ?>
        <a href="<?php echo esc_url( admin_url( 'options-general.php' ) ); ?>" title="<?php echo esc_attr( __( 'Change', 'lptheme' ) ); ?>">
          <?php echo get_bloginfo( 'name' ); ?>
        </a>
      <?php } ?>
    </span>
  </li>

  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'Sitemap XML', 'Site / server status', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <a href="<?php echo esc_url( home_url() ); ?>/wp-sitemap.xml" target="_blank" title="<?php _e( 'Visit', 'lptheme' ); ?>"><?php echo home_url(); ?>/wp-sitemap.xml</a>
    </span>
  </li>

  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'Admin Email', 'Site / server status', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php if ( is_network_admin() ) { ?>
        <?php echo get_site_option( 'admin_email' ); ?>
      <?php } else { ?>
        <?php echo get_bloginfo( 'admin_email' ); ?>
      <?php } ?>
    </span>
  </li>

  <?php if ( ! is_network_admin() ) { ?>
    <li>
      <span class="lptheme-status-key">
        <?php _e( 'Comments', 'lptheme' ); ?>
      </span>
      <span class="lptheme-status-value">
        <?php $comment_count = wp_count_comments(); ?>
        <?php echo $comment_count->total_comments; ?>
      </span>
    </li>
  <?php } ?>


  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'Debug mode', 'Site / server status', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php echo ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? __( 'On', 'lptheme' ) : __( 'Off', 'lptheme' ); ?>
    </span>
  </li>

  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'Post revisions', 'Site / server status', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php echo ( defined( 'WP_POST_REVISIONS' ) && ! WP_POST_REVISIONS ) ? __( 'Off', 'lptheme' ) : ( WP_POST_REVISIONS === true ? __( 'On', 'lptheme' ) : WP_POST_REVISIONS ); ?>
    </span>
  </li>

  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'Theme/plugin file editor', 'Site / server status', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php echo ( defined( 'DISALLOW_FILE_EDIT' ) ) ? __( 'Off', 'lptheme' ) : __( 'On', 'lptheme' ); ?>
    </span>
  </li>

  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'WP Cron', 'Site / server status', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php echo ( defined( 'DISABLE_WP_CRON' ) ) ? __( 'Off', 'lptheme' ) : __( 'On', 'lptheme' ); ?>
    </span>
  </li>

  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'Media folder writable', 'Site / server status', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php
      $upload_dir = wp_upload_dir();
      if ( ! file_exists( $upload_dir['basedir'] ) ) {
        echo __( 'Not found', 'lptheme' );
      }
      else {
        echo is_writable( $upload_dir['basedir'] ) ? __( 'Yes', 'lptheme' ) : __( 'No', 'lptheme' );
      }
      ?>
    </span>
  </li>

  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'Max upload size', 'Site / server status', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php echo strtolower( ini_get( 'upload_max_filesize' ) ); ?>
    </span>
  </li>

  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'Max execution time', 'Site / server status', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php echo ini_get( 'max_execution_time' ); ?>s
    </span>
  </li>

  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'PHP version', 'Site / server status', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php echo phpversion(); ?>
    </span>
  </li>

  <?php if ( is_network_admin() ) { ?>
    <?php $sitestats = get_sitestats(); ?>
    <?php if ( isset( $sitestats['blogs'] ) ) { ?>
      <li>
        <span class="lptheme-status-key">
          <?php _e( 'Sites', 'lptheme' ); ?>
        </span>
        <span class="lptheme-status-value">
          <?php echo $sitestats['blogs']; ?>
        </span>
      </li>
    <?php } ?>
  <?php } ?>

  <li>
    <span class="lptheme-status-key">
      <?php _ex( 'Users', 'User count', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php if ( function_exists( 'get_user_count' ) ) { ?>
        <?php echo get_user_count(); ?>
      <?php } else { ?>
        <?php $user_count = count_users(); ?>
        <?php echo $user_count['total_users']; ?>
      <?php } ?>
    </span>
  </li>

  <li>
    <span class="lptheme-status-key">
      <?php _e( 'Plugins', 'lptheme' ); ?>
    </span>
    <span class="lptheme-status-value">
      <?php
      $plugin_count = get_transient( 'plugin_slugs' );
      $plugin_count = $plugin_count ? $plugin_count : array_keys( get_plugins() );
      echo count( $plugin_count );
      ?>
    </span>
  </li>

  <?php if ( is_network_admin() ) { ?>
    <?php $theme_count = get_site_transient( 'update_themes' ); ?>
    <?php if ( $theme_count && isset( $theme_count->checked ) ) { ?>
      <li>
        <span class="lptheme-status-key">
          <?php _e( 'Themes', 'lptheme' ); ?>
        </span>
        <span class="lptheme-status-value">
          <?php echo count( $theme_count->checked ); ?>
        </span>
      </li>
    <?php } ?>
  <?php } ?>

</ul>
<?php }

if( get_field( 'status_dashboard' , 'option' ) ) {
 add_action('wp_dashboard_setup', 'status_dashboard_widgets' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Dashboard widget LPunity 
 * ------------------------------------------------------------------------------------------------
 */

function lp_info_dashboard_widgets() {
  wp_add_dashboard_widget('lpinfo_dashboard_widget', 'Development and Service', 'lp_theme_info');
}

function lp_theme_info() {
  echo '<ul>
    <li><strong>'. esc_html__( 'Company', 'lptheme' ).':</strong> LPunity Studio</li>
    <li><strong>'. esc_html__( 'Website', 'lptheme' ).':</strong> <a href="//lpunity.com" target="_blank" rel="nofollow">lpunity.com</a></li>
    <li><strong>E-mail:</strong> <a href="mailto:info@lpunity.com">info@lpunity.com</a></li>
    <li><strong>Telegram:</strong> <a href="//t.me/lpunity/">@lpunity</a></li>
  </ul>';
  echo '<p>По вопросам поддержки или доработки вашего сайта свяжитесь с нами удобным для вас способом!</p>';
}

if( get_field( 'developer_dashboard' , 'option' ) ) {
  add_action('wp_dashboard_setup', 'lp_info_dashboard_widgets' );
}
