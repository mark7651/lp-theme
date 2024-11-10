<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Register Custom Post Type 1
 * ------------------------------------------------------------------------------------------------
 */

if( ! get_field( 'enable_post_types','option' ) ) return;

function services_init() {

	$labels = array(
		'name' => esc_html__( 'Услуги', 'lptheme' ),
		'singular_name' => esc_html__( 'Услуги', 'lptheme' ),
		'add_new' => esc_html__( 'Добавить', 'lptheme' ),
		'add_new_item' => esc_html__( 'Добавить', 'lptheme' ),
		'edit_item' => esc_html__( 'Редактировать', 'lptheme' ),
		'new_item' => esc_html__( 'Добавить новую', 'lptheme' ),
		'view_item' => esc_html__( 'Смотреть', 'lptheme' ),
		'search_items' => esc_html__( 'Поиск', 'lptheme' ),
		'not_found' => esc_html__( 'Услуги', 'lptheme' ),
		'not_found_in_trash' => esc_html__( 'Корзина пуста', 'lptheme' )
	);

	$args = array(
		'labels' => $labels,
		'public' => true,
		'supports' => array( 'title', 'editor', 'thumbnail', 'author', 'revisions' ),
		'capability_type' => 'post',
		'menu_position' => 4,
		'has_archive' => true,
		'hierarchical'  => true,
		'menu_icon' => 'dashicons-layout',
	);

	$args = apply_filters('lptheme_args', $args);
	register_post_type('uslugi', $args);
	flush_rewrite_rules();
}
add_action( 'init', 'services_init', 10 );

/**
 * ------------------------------------------------------------------------------------------------
 * register taxonomy
 * ------------------------------------------------------------------------------------------------
 */

$taxonomy_uslugi_labels = array(
	'name' => esc_html__( 'Категория', 'lptheme' ),
	'singular_name' => esc_html__( 'Категория', 'lptheme' ),
	'search_items' => esc_html__( 'Поиск по категориям', 'lptheme' ),
	'popular_items' => esc_html__( 'Популярные категории', 'lptheme' ),
	'all_items' => esc_html__( 'Все категории', 'lpthemes' ),
	'parent_item' => esc_html__( 'Parent Category', 'lptheme' ),
	'parent_item_colon' => esc_html__( 'Parent Category:', 'lptheme' ),
	'edit_item' => esc_html__( 'Редактировать категорию', 'lptheme' ),
	'update_item' => esc_html__( 'Обновить категорию', 'lptheme' ),
	'add_new_item' => esc_html__( 'Добавить категорию', 'lptheme' ),
	'new_item_name' => esc_html__( 'Категория', 'lptheme' ),
	'separate_items_with_commas' => esc_html__( 'Separate categories with commas', 'lptheme' ),
	'add_or_remove_items' => esc_html__( 'Add or remove categories', 'vlthemes' ),
	'choose_from_most_used' => esc_html__( 'Choose from the most used categories', 'lptheme' ),
	'menu_name' => esc_html__( 'Категории', 'lptheme' ),
);

$taxonomy_uslugi_args = array(
	'labels' => $taxonomy_uslugi_labels,
	'public' => true,
	'show_in_nav_menus' => true,
	'show_ui' => true,
	'show_admin_column' => true,
	'show_tagcloud' => true,
	'hierarchical' => true,
	//'query_var' => true,
	'rewrite' => false,
	
	// Disable single page view & archive page view !!!!!!!
		// 'exclude_from_search' => true,
		// 'show_in_admin_bar'   => false,
		// 'show_in_nav_menus'   => false,
		// 'publicly_queryable'  => false,
		// 'query_var'           => false,	
);

register_taxonomy('uslugi_category', array('uslugi'), $taxonomy_uslugi_args);
