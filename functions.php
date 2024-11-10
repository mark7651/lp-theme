<?php if (!defined('ABSPATH')) {
	die();
}

/**
 * ------------------------------------------------------------------------------------------------
 * Define constants
 * ------------------------------------------------------------------------------------------------
 */

define('LP_THEME_DIR', get_template_directory_uri());
define('LP_THEMEROOT', get_template_directory());
define('LP_FRAMEWORK', LP_THEMEROOT . '/inc');
define('LP_ACF_PATH', LP_THEMEROOT . '/inc/acf/');
define('LP_ACF_URL', LP_THEME_DIR . '/inc/acf/');
define('LP_SCRIPTS', LP_THEME_DIR . '/js');
define('LP_STYLES', LP_THEME_DIR . '/css');
define('LP_FONTS', LP_THEME_DIR . '/fonts');
define('LP_IMAGES', LP_THEME_DIR . '/images');

/**
 * ------------------------------------------------------------------------------------------------
 *  require framework files
 * ------------------------------------------------------------------------------------------------
 */

include_once(LP_ACF_PATH . 'acf.php');
if (! function_exists('lp_acf_settings_url')) {
	add_filter('acf/settings/url', 'lp_acf_settings_url');
	function lp_acf_settings_url($url)
	{
		return LP_ACF_URL;
	}
}

require_once LP_FRAMEWORK . '/theme-options.php';
require_once LP_FRAMEWORK . '/theme-setup.php';

add_action('acf/init', 'global_acf_init');
function global_acf_init()
{
	require_once LP_FRAMEWORK . '/theme-functions.php';
	require_once LP_FRAMEWORK . '/theme-post-types.php';
	require_once LP_FRAMEWORK . '/theme-admin.php';
	require_once LP_FRAMEWORK . '/theme-seo.php';
	require_once LP_FRAMEWORK . '/theme-forms.php';
	require_once LP_FRAMEWORK . '/theme-testimonials.php';
	require_once LP_FRAMEWORK . '/theme-subscriptions.php';
	require_once LP_FRAMEWORK . '/theme-custom.php';
}
if (class_exists('woocommerce')) {
	require_once LP_FRAMEWORK . '/shop/woocommerce.php';
}
require_once LP_FRAMEWORK . '/class-tgm-plugin-activation.php';


/**
 * ------------------------------------------------------------------------------------------------
 *  Fonts preload
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_fonts_preload')) {
	function lp_fonts_preload()
	{
		$fonts = get_field('fonts_preload_list', 'option');
		if ($fonts) {
			foreach ($fonts as $font) {
				$font_name = $font['font_name'];
				echo wpautop('<link rel="preload" as="font" href="' . esc_url(LP_FONTS) . '/' . $font_name . '.woff2" type="font/woff2" crossorigin="anonymous">');
			}
		}
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 *  Enqueue styles
 * ------------------------------------------------------------------------------------------------
 */

// files vertion
if (! function_exists('lp_version')) {
	function lp_version()
	{
		if (!get_field('enable_cache', 'option')) return;
		global $version;
		$version = '?ver=' . mt_rand();
		return $version;
	}
}

if (! function_exists('lp_critical_css')) {
	function lp_critical_css()
	{

		$critical_css = get_style(LP_STYLES . '/critical.css');

		if ($critical_css != "") {
			echo '<style>' . $critical_css . '</style>';
		}
	}
	add_action('wp_head', 'lp_critical_css', 2);
}

if (! function_exists('lp_head_functions')) {
	function lp_head_functions()
	{
		if (get_field('fonts_preload', 'option')) {
			lp_fonts_preload();
		}

		if (! get_field('enable_min_css', 'option')): ?>
			<link rel="stylesheet" href="<?php echo esc_url(get_stylesheet_uri() . lp_version()); ?>">
		<?php else: ?>
			<link rel="stylesheet" href="<?php echo esc_url(LP_THEME_DIR . '/style.min.css' . lp_version()); ?>">
<?php endif;
	}
	add_action('wp_head', 'lp_head_functions', 3);
}

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue scripts
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_enqueue_scripts')) {
	add_action('wp_enqueue_scripts', 'lp_enqueue_scripts', 1);

	function lp_enqueue_scripts()
	{

		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		if (get_field('enable_jquery', 'option')) {
			wp_enqueue_script('jquery');
		}

		wp_enqueue_script('lp-script', LP_SCRIPTS . '/app.js', array(), lp_version(), true);

		wp_localize_script('lp-script', 'wp_ajax', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('lp-nonce'),
		));

		if (is_page_template('templates/testimonials.php') && get_field('enable_testimonials', 'option')) {
			wp_enqueue_script('testimonials-js', LP_SCRIPTS . '/testimonials.js');
		}

		if (get_field('enable_slider', 'option')) {
			wp_enqueue_script('slider-js', LP_SCRIPTS . '/splide.js');
		}
		if (get_field('lightbox_enable', 'option')) {
			wp_enqueue_script('fslightbox-js', LP_SCRIPTS . '/fslightbox.js');
		}
		if (get_field('smooth_scrollbar', 'option')) {
			if (!wp_is_mobile()) {
				wp_enqueue_script('smooth-scrollbar-js', LP_SCRIPTS . '/smooth-scrollbar.js');
			}
		}
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue footer scripts/styles
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('add_footer_components')) {
	function add_footer_components()
	{

		if (get_field('enable_slider', 'option')) {
			wp_enqueue_style('splide', LP_STYLES . '/splide-slider.css');
		}
	}
	add_action('get_footer', 'add_footer_components');
}

// defer load scripts
if (! function_exists('lp_defer_scripts')) {
	function lp_defer_scripts($tag, $handle, $src)
	{
		$defer = array(
			'fslightbox-js',
			'slider-js',
			'smooth-scrollbar-js'
		);
		if (in_array($handle, $defer)) {
			return '<script src="' . $src . '" defer="defer"></script>' . "\n";
		}
		return $tag;
	}
	add_filter('script_loader_tag', 'lp_defer_scripts', 10, 3);
}


/**
 * ------------------------------------------------------------------------------------------------
 * translation
 * ------------------------------------------------------------------------------------------------
 */
if (function_exists('pll_current_language')) {
	function translate_pll($ru_text, $ua_text)
	{
		$current_language = pll_current_language();

		if ($current_language == "ru") {
			return pll__($ru_text);
		} else {
			return pll__($ua_text);
		}
	}
}
