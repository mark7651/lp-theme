<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');

// blog options

if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_235be41428b',
		'title' => 'Blog',
		'fields' => array(
			array(
				'key' => 'field_dfhd636574345',
				'label' => esc_html__('Blog Settings', 'lptheme'),
				'name' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
				),
				'message' => '',
				'new_lines' => '',
				'esc_html' => 0,
		),
			array(
				'key' => 'field_357dfh449e',
				'label' => esc_html__('Posts per page', 'lptheme'),
				'name' => 'blog-posts-per-page',
				'type' => 'number',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '20',
					'class' => '',
					'id' => '',
				),
				'default_value' => '8',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			
		),
		'location' => array(
			array(
				array(
				'param' => 'page_template',
				'operator' => '==',
				'value' => 'templates/blog.php',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));
	
	endif;