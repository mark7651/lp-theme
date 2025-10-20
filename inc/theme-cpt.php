<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Register Custom Post Type 
 * ------------------------------------------------------------------------------------------------
 */

if (! get_field('enable_post_types', 'option')) return;

function exchange_init()
{
	$labels = array(
		'name' => esc_html__('Exchange', 'lptheme'),
		'singular_name' => esc_html__('Exchange', 'lptheme'),
		'add_new' => esc_html__('Add', 'lptheme'),
		'add_new_item' => esc_html__('Add', 'lptheme'),
		'edit_item' => esc_html__('Edit', 'lptheme'),
		'new_item' => esc_html__('Add new', 'lptheme'),
		'view_item' => esc_html__('Look', 'lptheme'),
		'search_items' => esc_html__('Search', 'lptheme'),
		'not_found' => esc_html__('Exchange', 'lptheme'),
		'not_found_in_trash' => esc_html__('Trash', 'lptheme')
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'supports' => array('title'),
		'capability_type' => 'post',
		'menu_position' => 7,
		'has_archive' => false,
		'hierarchical'  => true,
		'menu_icon' => 'dashicons-money-alt',
		// Disable single page view & archive page view !!!!!!!
		'exclude_from_search' => true,
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'publicly_queryable'  => false,
		'query_var'           => false,
	);

	$args = apply_filters('lptheme_args', $args);
	register_post_type('exchange', $args);
}
add_action('init', 'exchange_init', 10);

function register_exchange_taxonomies()
{
	function register_exchange_taxonomy($taxonomy_name, $singular_name, $plural_name)
	{
		$labels = array(
			'name' => esc_html__($plural_name, 'lptheme'),
			'singular_name' => esc_html__($singular_name, 'lptheme'),
			'search_items' => esc_html__("Search by {$plural_name}", 'lptheme'),
			'popular_items' => esc_html__("Popular {$plural_name}", 'lptheme'),
			'all_items' => esc_html__("All {$plural_name}", 'lptheme'),
			'parent_item' => esc_html__("Родительская {$singular_name}", 'lptheme'),
			'parent_item_colon' => esc_html__("Родительская {$singular_name}:", 'lptheme'),
			'edit_item' => esc_html__("Edit {$singular_name}", 'lptheme'),
			'update_item' => esc_html__("Update {$singular_name}", 'lptheme'),
			'add_new_item' => esc_html__("Add {$singular_name}", 'lptheme'),
			'new_item_name' => esc_html__($singular_name, 'lptheme'),
			'separate_items_with_commas' => esc_html__("Разделите {$plural_name} запятыми", 'lptheme'),
			'add_or_remove_items' => esc_html__("Add or remove {$plural_name}", 'lptheme'),
			'choose_from_most_used' => esc_html__("Add from {$plural_name}", 'lptheme'),
			'menu_name' => esc_html__($plural_name, 'lptheme'),
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_admin_column' => true,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'rewrite' => array('slug' => $taxonomy_name),
			'has_archive' => false,
		);

		register_taxonomy($taxonomy_name, array('exchange'), $args);
	}

	register_exchange_taxonomy('city', 'City', 'Cities');
	register_exchange_taxonomy('currency', 'Currency', 'Currencies');
	register_exchange_taxonomy('crypto', 'Crypto', 'Crypto');
}

add_action('init', 'register_exchange_taxonomies', 10);
