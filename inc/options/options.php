<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * register settings menus
 * ------------------------------------------------------------------------------------------------
 */

if (function_exists('acf_add_options_page')) {
	acf_add_options_page(array(
		'page_title' 	=> 'Настройки сайта',
		'menu_title'	=> 'Настройки сайта',
		'menu_slug' 	=> 'customers-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'icon_url' => '',
		'position' => '',
	));

	acf_add_options_page(array(
		'page_title' 	=> 'Настройки разработчика',
		'menu_title'	=> 'Разработка',
		'menu_slug' 	=> 'developers-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'icon_url' => 'dashicons-hammer',
		'position' => '',
	));
}

/**
 * ------------------------------------------------------------------------------------------------
 * Options init
 * ------------------------------------------------------------------------------------------------
 */

require_once LP_THEMEROOT . '/inc/options/developers-settings.php';
require_once LP_THEMEROOT . '/inc/options/clients-settings.php';
require_once LP_THEMEROOT . '/inc/options/seo.php';
require_once LP_THEMEROOT . '/inc/options/blog-settings.php';

/**
 * ------------------------------------------------------------------------------------------------
 * google maps acf
 * ------------------------------------------------------------------------------------------------
 */

add_action('acf/init', 'map_acf_init');
function map_acf_init()
{
	acf_update_setting('google_api_key', get_field('google_map_api_key', 'option'));
}

/**
 * ------------------------------------------------------------------------------------------------
 * Other ACF modifications
 * ------------------------------------------------------------------------------------------------
 */
