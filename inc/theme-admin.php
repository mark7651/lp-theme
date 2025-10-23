<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Fully Disable Gutenberg editor
 * ------------------------------------------------------------------------------------------------
 */

add_filter('use_block_editor_for_post_type', '__return_false', 10);
add_filter('use_block_editor_for_post', '__return_false');
add_filter('big_image_size_threshold', '__return_false');

// remove admin staff
add_filter('show_admin_bar', '__return_false');
add_filter('wp_fatal_error_handler_enabled', '__return_false');

/**
 * ------------------------------------------------------------------------------------------------
 * Custom color scheme (dark mode)
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_dark_mode_dashboard_styles')) {
  function lp_dark_mode_dashboard_styles()
  {
    $current_user = wp_get_current_user();
    $dark_mode_disabled = get_user_meta($current_user->ID, 'dark_mode_dashboard', true);

    if ($dark_mode_disabled != '1') {
      $dark_mode_css = apply_filters('lp_dark_mode_dashboard_css', get_template_directory_uri() . '/inc/admin/css/dark-mode.css');
      wp_enqueue_style(
        'lp-dark-mode-dashboard',
        $dark_mode_css,
        array(),
        wp_get_theme()->get('Version')
      );

      remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');

      add_editor_style(get_template_directory_uri() . '/inc/admin/css/dark-editor-style.css');
    } else {
      add_editor_style(get_template_directory_uri() . '/inc/admin/css/editor-style.css');
    }
  }
  add_action('admin_enqueue_scripts', 'lp_dark_mode_dashboard_styles');
}


function lp_dark_mode_user_profile_fields($user)
{
?>
  <h2><?php esc_html_e('Dark Mode Settings', 'lptheme'); ?></h2>
  <table class="form-table" role="presentation">
    <tr>
      <th scope="row">
        <label for="lp_darkmode"><?php esc_html_e('Dashboard Appearance', 'lptheme'); ?></label>
      </th>
      <td>
        <fieldset>
          <label for="lp_darkmode">
            <input
              type="checkbox"
              name="dark_mode_dashboard"
              id="lp_darkmode"
              value="1"
              <?php checked(get_user_meta($user->ID, 'dark_mode_dashboard', true), '1'); ?>>
            <?php esc_html_e('Disable dark mode (use WordPress default colors)', 'lptheme'); ?>
          </label>
          <p class="description">
            <?php esc_html_e('By default, dark mode is enabled. Check this box to use the standard WordPress admin colors.', 'lptheme'); ?>
          </p>
        </fieldset>
      </td>
    </tr>
  </table>
<?php
}
add_action('show_user_profile', 'lp_dark_mode_user_profile_fields', 10, 1);
add_action('edit_user_profile', 'lp_dark_mode_user_profile_fields', 10, 1);


function lp_dark_mode_save_user_profile_fields($user_id)
{
  if (empty($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'update-user_' . $user_id)) {
    return;
  }

  if (!current_user_can('edit_user', $user_id)) {
    return;
  }

  if (isset($_POST['dark_mode_dashboard']) && $_POST['dark_mode_dashboard'] === '1') {
    update_user_meta($user_id, 'dark_mode_dashboard', '1');
  } else {
    delete_user_meta($user_id, 'dark_mode_dashboard');
  }
}
add_action('personal_options_update', 'lp_dark_mode_save_user_profile_fields');
add_action('edit_user_profile_update', 'lp_dark_mode_save_user_profile_fields');


/**
 * ------------------------------------------------------------------------------------------------
 * whitelabel admin
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_admin_enqueue_scripts')) {
  function lp_admin_enqueue_scripts()
  {
    wp_enqueue_script('options', LP_THEME_DIR . '/inc/admin/js/options.js');
    wp_enqueue_style('options', LP_THEME_DIR . '/inc/admin/css/options.css');
  }
  add_action('admin_enqueue_scripts', 'lp_admin_enqueue_scripts');
}

// remove wp logo
function admin_bar_remove_logo()
{
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'admin_bar_remove_logo', 0);

// custom footer copyrights
function layers_child_footer_admin()
{
  echo esc_html__('Developed by', 'lptheme') . ' <a href="//lpunity.com">LPunity</a>';
}

if (get_field('developer_dashboard', 'option')) {
  add_filter('admin_footer_text', 'layers_child_footer_admin');
}

// admin menu open website in new window
function shatel_view($wp_admin_bar)
{
  $all_toolbar_nodes = $wp_admin_bar->get_nodes();
  foreach ($all_toolbar_nodes as $node) {
    if ($node->id == 'site-name' || $node->id == 'view-site') {
      $args = $node;
      $args->meta = array('target' => '_blank');
      $wp_admin_bar->add_node($args);
    }
  }
}
add_action('admin_bar_menu', 'shatel_view', 999);

// decline wrap <p> 
function lp_remove_img_ptags($content)
{
  if (!has_shortcode($content, 'gallery') && strpos($content, '<img') === false) {
    return $content;
  }

  $content = preg_replace('/<p>\s*(<a.*?<img.*?<\/a>)\s*<\/p>/is', '$1', $content);
  $content = preg_replace('/<p>\s*(<img.*?>)\s*<\/p>/is', '$1', $content);

  return $content;
}
add_filter('the_content', 'lp_remove_img_ptags');

// login page custom css
if (! function_exists('lp_login_style')) {
  function lp_login_style()
  {
    wp_enqueue_style('custom-login', LP_THEME_DIR . '/inc/admin/css/login.css');
  }
  add_action('login_enqueue_scripts', 'lp_login_style');
}

// custom login logo
function lp_login_logo()
{
  $login_logo = get_field('header_logo_svg', 'option') ?: get_field('header_logo', 'option');

  if (!$login_logo) {
    return;
  }

  $logo_url = esc_url(wp_get_attachment_url($login_logo));
?>
  <style>
    #login h1 a,
    .login h1 a {
      background-image: url(<?php echo $logo_url; ?>);
      background-repeat: no-repeat;
      background-position: center center;
      background-size: contain;
      width: 320px;
      height: 150px;
      margin: 0 auto 25px;
    }
  </style>
<?php
}
add_action('login_enqueue_scripts', 'lp_login_logo');

function mb_login_url()
{
  return esc_url(home_url('/'));
}
add_filter('login_headerurl', 'mb_login_url');

function mb_login_title()
{
  return esc_attr(get_bloginfo('name'));
}
add_filter('login_headertext', 'mb_login_title');


/**
 * ------------------------------------------------------------------------------------------------
 * remove admin menu items
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_remove_menus')) {
  function lp_remove_menus()
  {
    global $menu;
    global $submenu;
    $menu[5][0] = esc_html__('Blog', 'lptheme');
    unset($submenu['themes.php'][6]);
    unset($submenu['themes.php'][5]);
    unset($submenu['themes.php'][20]);
    unset($submenu['themes.php'][22]);
    if (get_field('news_menu_item', 'option')) {
      remove_menu_page('edit.php');
    } // Записи
    if (get_field('comments_menu_item', 'option')) {
      remove_menu_page('edit-comments.php');
    } // Комментарии
    if (get_field('users_menu_item', 'option')) {
      remove_menu_page('users.php');
    } // Пользователи
    if (get_field('plugins_menu_item', 'option')) {
      remove_menu_page('plugins.php');
    } // Плагины
    if (get_field('tools_menu_item', 'option')) {
      remove_menu_page('tools.php');
    } // Инструменты
    if (get_field('options_general_menu_item', 'option')) {
      remove_menu_page('options-general.php');
    } // Настройки
    remove_submenu_page('plugins.php', 'plugin-editor.php');
    remove_submenu_page('index.php', 'update-core.php');
    if (!defined('LP_THEME')) {
      remove_menu_page('developers-settings');
      remove_menu_page('edit.php?post_type=acf-field-group');
    }
  }
  add_action('admin_menu', 'lp_remove_menus', 999);
}

function lp_custom_menu_order($menu_ord)
{
  if (!$menu_ord) return true;
  return array(
    'index.php', // Dashboard
    'edit.php?post_type=page', // Pages
    'edit.php', // Posts
  );
}
add_filter('custom_menu_order', 'lp_custom_menu_order', 10, 1);
add_filter('menu_order', 'lp_custom_menu_order', 10, 1);

/**
 * ------------------------------------------------------------------------------------------------
 * disable Default Dashboard Widgets
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_remove_dashboard_meta')) {
  function lp_remove_dashboard_meta()
  {
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

function status_dashboard_widgets()
{
  wp_add_dashboard_widget(
    'status_dashboard_widget',
    __('Website Status Info', 'lptheme'),
    'status_theme_info'
  );
}

function status_theme_info()
{ ?>
  <ul class="lptheme-status-list">

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('Site Title'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php if (is_network_admin()) { ?>
          <a href="<?php echo esc_url(admin_url('network/settings.php')); ?>">
            <?php echo esc_html(get_site_option('site_name')); ?>
          </a>
        <?php } else { ?>
          <a href="<?php echo esc_url(admin_url('options-general.php')); ?>">
            <?php echo esc_html(get_bloginfo('name')); ?>
          </a>
        <?php } ?>
      </span>
    </li>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('Sitemap', 'lptheme'); ?>
      </span>
      <span class="lptheme-status-value">
        <a href="<?php echo esc_url(home_url('/wp-sitemap.xml')); ?>" target="_blank">
          <?php esc_html_e('View Sitemap', 'lptheme'); ?> →
        </a>
      </span>
    </li>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('Administration Email Address'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php if (is_network_admin()) { ?>
          <a href="mailto:<?php echo esc_attr(get_site_option('admin_email')); ?>">
            <?php echo esc_html(get_site_option('admin_email')); ?>
          </a>
        <?php } else { ?>
          <a href="mailto:<?php echo esc_attr(get_bloginfo('admin_email')); ?>">
            <?php echo esc_html(get_bloginfo('admin_email')); ?>
          </a>
        <?php } ?>
      </span>
    </li>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('Debug mode', 'lptheme'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php
        echo (defined('WP_DEBUG') && WP_DEBUG)
          ? esc_html_x('On', 'Debug mode status')
          : esc_html_x('Off', 'Debug mode status');
        ?>
      </span>
    </li>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('Post revisions', 'lptheme'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php
        if (defined('WP_POST_REVISIONS') && !WP_POST_REVISIONS) {
          echo esc_html_x('Off', 'Post revisions status');
        } elseif (WP_POST_REVISIONS === true) {
          echo esc_html_x('On', 'Post revisions status');
        } else {
          echo esc_html(WP_POST_REVISIONS);
        }
        ?>
      </span>
    </li>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('File editor', 'lptheme'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php
        echo (defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT)
          ? esc_html_x('Off', 'File editor status')
          : esc_html_x('On', 'File editor status');
        ?>
      </span>
    </li>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('WP-Cron'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php
        echo (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON)
          ? esc_html_x('Off', 'WP-Cron status')
          : esc_html_x('On', 'WP-Cron status');
        ?>
      </span>
    </li>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('Media folder writable', 'lptheme'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php
        $upload_dir = wp_upload_dir();
        if (!file_exists($upload_dir['basedir'])) {
          echo esc_html__('Not found', 'lptheme');
        } else {
          echo is_writable($upload_dir['basedir'])
            ? esc_html_x('Yes', 'Media folder writable')
            : esc_html_x('No', 'Media folder writable');
        }
        ?>
      </span>
    </li>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('Max upload size', 'lptheme'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php echo esc_html(size_format(wp_max_upload_size())); ?>
      </span>
    </li>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('Max execution time', 'lptheme'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php echo esc_html(ini_get('max_execution_time')); ?>s
      </span>
    </li>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('Memory limit', 'lptheme'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php echo esc_html(ini_get('memory_limit')); ?>
      </span>
    </li>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('PHP version'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php echo esc_html(phpversion()); ?>
      </span>
    </li>

    <?php if (!is_network_admin()) { ?>
      <li>
        <span class="lptheme-status-key">
          <?php esc_html_e('Posts'); ?>
        </span>
        <span class="lptheme-status-value">
          <?php echo esc_html(wp_count_posts('post')->publish); ?>
        </span>
      </li>

      <li>
        <span class="lptheme-status-key">
          <?php esc_html_e('Pages'); ?>
        </span>
        <span class="lptheme-status-value">
          <?php echo esc_html(wp_count_posts('page')->publish); ?>
        </span>
      </li>
    <?php } ?>

    <li>
      <span class="lptheme-status-key">
        <?php esc_html_e('Users'); ?>
      </span>
      <span class="lptheme-status-value">
        <?php
        if (function_exists('get_user_count')) {
          echo esc_html(get_user_count());
        } else {
          $user_count = count_users();
          echo esc_html($user_count['total_users']);
        }
        ?>
      </span>
    </li>

  </ul>

<?php }

if (get_field('status_dashboard', 'option')) {
  add_action('wp_dashboard_setup', 'status_dashboard_widgets');
}

/**
 * ------------------------------------------------------------------------------------------------
 * Dashboard widget LPunity 
 * ------------------------------------------------------------------------------------------------
 */

function lp_info_dashboard_widgets()
{
  wp_add_dashboard_widget('lpinfo_dashboard_widget', 'Development and Service', 'lp_theme_info');
}

function lp_theme_info()
{
  echo '<ul>
    <li><strong>' . esc_html__('Company', 'lptheme') . ':</strong> LPunity Studio</li>
    <li><strong>' . esc_html__('Website', 'lptheme') . ':</strong> <a href="//lpunity.com" target="_blank" rel="nofollow">lpunity.com</a></li>
    <li><strong>E-mail:</strong> <a href="mailto:info@lpunity.com">info@lpunity.com</a></li>
    <li><strong>Telegram:</strong> <a href="//t.me/lpunity/">@lpunity</a></li>
  </ul>';
  echo '<p>По вопросам поддержки или доработки вашего сайта свяжитесь с нами удобным для вас способом!</p>';
}

if (get_field('developer_dashboard', 'option')) {
  add_action('wp_dashboard_setup', 'lp_info_dashboard_widgets');
}


/**
 * ------------------------------------------------------------------------------------------------
 * Custom welcome panel 
 * ------------------------------------------------------------------------------------------------
 */

function lp_custom_welcome_panel()
{
  $screen = get_current_screen();
  $user = wp_get_current_user();
?>
  <div class="welcome-panel__wrap">
    <h2><?php printf(__('%s'), esc_html(get_bloginfo('name'))); ?></h2>
    <hr>

    <div class="welcome-panel-container">

      <!-- Column 1: Content -->
      <div class="flex flex-col">
        <a class="button button-primary button-hero" href="<?php echo admin_url('post-new.php'); ?>">
          <?php _e('Add Post'); ?>
        </a>
        <a class="button button-hero" href="<?php echo admin_url('post-new.php?post_type=page'); ?>">
          <?php _e('Add Page'); ?>
        </a>
      </div>

      <!-- Column 2: Customize -->
      <div class="flex flex-col gap-10">
        <ul class="columns-2">
          <li>
            <a href="<?php echo admin_url('admin.php?page=customers-settings'); ?>" class="welcome-icon welcome-learn-more">
              <?php _e('Settings'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo admin_url('users.php'); ?>" class="welcome-icon welcome-add-page">
              <?php _e('Users'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo admin_url('nav-menus.php'); ?>" class="welcome-icon welcome-widgets-menus">
              <?php _e('Menus'); ?>
            </a>
          </li>

          <li>
            <a href="<?php echo home_url('/'); ?>" target="_blank" class="welcome-icon welcome-view-site">
              <?php _e('View Site'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo admin_url('upload.php'); ?>" class="welcome-icon welcome-add-page">
              <?php _e('Add media'); ?>
            </a>
          </li>

        </ul>
      </div>


    </div>
  </div>
<?php
}

remove_action('welcome_panel', 'wp_welcome_panel');
add_action('welcome_panel', 'lp_custom_welcome_panel');
