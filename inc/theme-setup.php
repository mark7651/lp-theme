<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

if (! isset($content_width)) {
  $content_width = 1280; /* pixels */
}

add_theme_support('content-width', $content_width);

if (! function_exists('lp_setup')) {
  function lp_setup()
  {
    load_theme_textdomain('lptheme', LP_THEMEROOT . '/languages');
    register_nav_menus(array(
      'main-menu' => esc_html__('Primary', 'lptheme'),
      'footer-menu' => esc_html__('Footer', 'lptheme'),
      'mobile-menu' => esc_html__('Mobile', 'lptheme'),
    ));
    add_theme_support('woocommerce');
    add_theme_support('editor-style');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    //add_theme_support( 'automatic-feed-links' );
    remove_theme_support('widgets-block-editor');
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for post formats
    add_theme_support(
      'post-formats',
      array(
        // 'video', 
        // 'audio', 
        // 'quote', 
        // 'image', 
        // 'gallery', 
        // 'link'
      )
    );

    add_theme_support(
      'html5',
      array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
      )
    );
    //add_image_size('lpcard', 700, 525, true);
  }

  add_action('after_setup_theme', 'lp_setup');
}

add_filter('widget_text', 'do_shortcode');



/**
 * ------------------------------------------------------------------------------------------------
 * css helper functions
 * ------------------------------------------------------------------------------------------------
 */

//  get file content
if (!function_exists('get_style')) {
  function get_style($url)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
  }
}

// compress css 
if (!function_exists('compressCSS')) {
  function compressCSS($css)
  {
    ob_start();

    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    preg_match_all('/(\'[^\']*?\'|"[^"]*?")/ims', $css, $hit, PREG_PATTERN_ORDER);
    for ($i = 0; $i < count($hit[1]); $i++) {
      $css = str_replace($hit[1][$i], '##########' . $i . '##########', $css);
    }

    $css = preg_replace('/;[\s\r\n\t]*?}[\s\r\n\t]*/ims', "}\r\n", $css);
    $css = preg_replace('/;[\s\r\n\t]*?([\r\n]?[^\s\r\n\t])/ims', ';$1', $css);
    $css = preg_replace('/[\s\r\n\t]*:[\s\r\n\t]*?([^\s\r\n\t])/ims', ':$1', $css);
    $css = preg_replace('/[\s\r\n\t]*,[\s\r\n\t]*?([^\s\r\n\t])/ims', ',$1', $css);
    $css = preg_replace('/[\s\r\n\t]*{[\s\r\n\t]*?([^\s\r\n\t])/ims', '{$1', $css);
    $css = preg_replace('/([\d\.]+)[\s\r\n\t]+(px|em|pt|%)/ims', '$1$2', $css);
    $css = preg_replace('/([^\d\.]0)(px|em|pt|%)/ims', '$1', $css);
    $css = preg_replace('/\p{Zs}+/ims', ' ', $css);
    $css = str_replace(array("\r\n", "\r", "\n"), '', $css);
    $css = str_replace("\t", " ", $css);
    for ($i = 0; $i < count($hit[1]); $i++) {
      $css = str_replace('##########' . $i . '##########', $hit[1][$i], $css);
    }
    return $css;
    $css = ob_get_contents();
  }
}


/**
 * ------------------------------------------------------------------------------------------------
 * register widget area
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_sidebar_init')) {
  function lp_sidebar_init()
  {
    register_sidebar(
      array(
        'name'          => esc_html__('Main Sidebar', 'lptheme'),
        'id'            => 'main-sidebar',
        'description'   => esc_html__('Add widgets here.', 'lptheme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="widget-title">',
        'after_title'   => '</div>',
      )
    );

    for ($i = 1; $i < 5; $i++) {
      register_sidebar(array(
        'name'          => 'Footer Sidebar ' . $i,
        'id'            => 'footer-' . $i,
        'description'   => esc_html__('Add widgets here.', 'lptheme'),
        'before_widget' => '<div id="%1$s" class="widget footer-widget-collapse %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="widget-title">',
        'after_title'   => '</div>',
      ));
    }
  }
  add_action('widgets_init', 'lp_sidebar_init');
}

/**
 * ------------------------------------------------------------------------------------------------
 * custom widgets
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_widgets_init')) {
  include_once LP_THEMEROOT . '/widgets/megamenu/megam-widget.php';
  function lp_widgets_init()
  {
    if (!is_blog_installed())
      return;
    register_widget('LP_WP_Nav_Menu_Widget');
  }
  add_action('widgets_init', 'lp_widgets_init');
}


/**
 * ------------------------------------------------------------------------------------------------
 *  required plugins
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lptheme_register_required_plugins')) {
  function lptheme_register_required_plugins()
  {
    $plugins = array(
      array(
        'name'               => 'WPS Hide Login',
        'slug'               => 'wps-hide-login',
        'required'           => true,
        'version'            => '',
        'force_activation'   => true,
        'force_deactivation' => false,
        'external_url'       => '',
      ),

      array(
        'name'               => 'Upload Converter for WebP',
        'slug'               => 'upload-converter-webp',
        'required'           => true,
        'version'            => '',
        'force_activation'   => true,
        'force_deactivation' => false,
        'external_url'       => '',
      ),

      array(
        'name'               => 'Cache Enabler',
        'slug'               => 'cache-enabler',
        'required'           => true,
        'version'            => '',
        'force_activation'   => true,
        'force_deactivation' => true,
        'external_url'       => '',
      ),
      array(
        'name'               => 'Cyr to Lat enhanced',
        'slug'               => 'cyr3lat',
        'required'           => true,
        'version'            => '',
        'force_activation'   => true,
        'force_deactivation' => false,
        'external_url'       => '',
      ),
      array(
        'name'               => 'Safe SVG',
        'slug'               => 'safe-svg',
        'required'           => true,
        'version'            => '',
        'force_activation'   => true,
        'force_deactivation' => false,
        'external_url'       => '',
      ),
    );

    $config = array(
      'default_path' => '',
      'menu'         => 'tgmpa-install-plugins',
      'has_notices'  => true,
      'dismissable'  => true,
      'dismiss_msg'  => '',
      'is_automatic' => true,
      'message'      => '',
      'strings'      => array(
        'page_title'                      => esc_html__('Install Required Plugins', 'lptheme'),
        'menu_title'                      => esc_html__('Install Plugins', 'lptheme'),
        'installing'                      => esc_html__('Installing Plugin: %s', 'lptheme'),
        'oops'                            => esc_html__('Something went wrong with the plugin API.', 'lptheme'),
        'notice_can_install_required'     => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'lptheme'),
        'notice_can_install_recommended'  => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'lptheme'),
        'notice_cannot_install'           => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin.', 'Sorry, but you do not have the correct permissions to install the %s plugins.', 'lptheme'),
        'notice_can_activate_required'    => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'lptheme'),
        'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'lptheme'),
        'notice_cannot_activate'          => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin.', 'Sorry, but you do not have the correct permissions to activate the %s plugins.', 'lptheme'),
        'notice_ask_to_update'            => _n_noop('The following plugin needs to be updated: %1$s.', 'The following plugins need to be updated: %1$s.', 'lptheme'),
        'notice_cannot_update'            => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin.', 'Sorry, but you do not have the correct permissions to update the %s plugins.', 'lptheme'),
        'install_link'                    => _n_noop('Begin installing plugin', 'Begin installing plugins', 'lptheme'),
        'activate_link'                   => _n_noop('Begin activating plugin', 'Begin activating plugins', 'lptheme'),
        'return'                          => esc_html__('Return to Required Plugins Installer', 'lptheme'),
        'plugin_activated'                => esc_html__('Plugin activated successfully.', 'lptheme'),
        'complete'                        => esc_html__('All plugins installed and activated successfully. %s', 'lptheme'),
        'nag_type'                        => 'updated',
      ),
    );

    tgmpa($plugins, $config);
  }

  add_action('tgmpa_register', 'lptheme_register_required_plugins');
}
