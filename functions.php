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
define('LP_SCRIPTS', LP_THEME_DIR . '/assets/js');
define('LP_STYLES', LP_THEME_DIR . '/assets/css');
define('LP_FONTS', LP_THEME_DIR . '/assets/fonts');
define('LP_IMAGES', LP_THEME_DIR . '/assets/img');
define('LP_ICONS', LP_THEME_DIR . '/assets/icons');

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

require_once LP_FRAMEWORK . '/theme-setup.php';

add_action('acf/init', 'global_acf_init');
function global_acf_init()
{
	require_once LP_FRAMEWORK . '/options/options.php';
	require_once LP_FRAMEWORK . '/theme-functions.php';
	require_once LP_FRAMEWORK . '/theme-post-types.php';
	require_once LP_FRAMEWORK . '/theme-admin.php';
	require_once LP_FRAMEWORK . '/seo/seo.php';
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
if (!function_exists('lp_version')) {
	function lp_version()
	{
		if (!get_field('enable_cache', 'option')) {
			return '';
		}

		$version = defined('WP_DEBUG') && WP_DEBUG
			? (string) mt_rand()
			: wp_get_theme()->get('Version');

		return '?ver=' . $version;
	}
}

if (! function_exists('lp_head_functions')) {
	function lp_head_functions()
	{
		if (get_field('fonts_preload', 'option')) {
			lp_fonts_preload();
		}

		$stylesheet_url = get_field('enable_min_css', 'option')
			? LP_THEME_DIR . '/style.min.css'
			: get_stylesheet_uri();

		$version = lp_version();
		if ($version) {
			$stylesheet_url .= $version;
		}
?>
		<link rel="stylesheet" href="<?php echo esc_url($stylesheet_url); ?>">
<?php
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

		wp_enqueue_script_module('app-script', LP_SCRIPTS . '/app.js', array(), true);

		$scripts = [
			'testimonials-js' => [
				'condition' => is_page_template('templates/testimonials.php') && get_field('enable_testimonials', 'option'),
				'src'       => LP_SCRIPTS . '/testimonials.js'
			],
			'slider-js' => [
				'condition' => get_field('enable_slider', 'option'),
				'src'       => LP_SCRIPTS . '/splide.js'
			],
			'fslightbox-js' => [
				'condition' => get_field('lightbox_enable', 'option'),
				'src'       => LP_SCRIPTS . '/fslightbox.js'
			],
			'smooth-scrollbar-js' => [
				'condition' => get_field('smooth_scrollbar', 'option'),
				'src'       => LP_SCRIPTS . '/smooth-scrollbar.js'
			],
			'gsap-js' => [
				'condition' => get_field('gsap_enable', 'option'),
				'src'       => LP_SCRIPTS . '/gsap.js'
			],
		];

		foreach ($scripts as $handle => $data) {
			if ($data['condition']) {
				wp_enqueue_script($handle, $data['src'], array(), null, true);
			}
		}
	}
}

add_action('wp_print_footer_scripts', function () {
	$admin_url = esc_url(admin_url('admin-ajax.php'));
	$nonce = wp_create_nonce('lp-nonce');

	echo '<script type="module">
			 window.wp_ajax = {
					ajax_url: "' . $admin_url . '",
					nonce: "' . $nonce . '"
			};
</script>';
});

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue footer scripts/styles
 * ------------------------------------------------------------------------------------------------
 */

// defer load scripts
if (! function_exists('lp_defer_scripts')) {
	function lp_defer_scripts($tag, $handle, $src)
	{
		$defer = array(
			'app-script',
			'fslightbox-js',
			'slider-js',
			'smooth-scrollbar-js',
			'gsap-js'
		);
		if (in_array($handle, $defer)) {
			return '<script src="' . $src . '" defer="defer"></script>' . "\n";
		}
		return $tag;
	}
	add_filter('script_loader_tag', 'lp_defer_scripts', 10, 3);
}
