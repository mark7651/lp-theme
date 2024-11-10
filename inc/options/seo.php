<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');

// seo options

if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_60747be41428b',
		'title' => 'SEO',
		'fields' => array(
			array(
				'key' => 'field_adsvc5d634634345',
				'label' => 'SEO',
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
				'key' => 'field_6074a2bed789e',
				'label' => esc_html__('Title', 'lptheme'),
				'name' => 'lp_meta_title',
				'type' => 'text',
				'instructions' => '<span id="char_count_title" class="char-counter">0</span>'. esc_html__('symbols', 'lptheme').'. '. esc_html__('Recomended length 60 symbols', 'lptheme'),
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'meta-title',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => 160,
			),
			array(
				'key' => 'field_607479a0021eb',
				'label' => esc_html__('Description', 'lptheme'),
				'name' => 'lp_meta_description',
				'type' => 'textarea',
				'instructions' => '<span id="char_count_description" class="char-counter">0</span>'. esc_html__('symbols', 'lptheme').'. '. esc_html__('Recomended length 160 symbols', 'lptheme'),
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => 'meta-description',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => 260,
				'rows' => 4,
				'new_lines' => '',
			),
			array(
				'key' => 'field_607606978b9da',
				'label' => 'Robots',
				'name' => 'lp_robots',
				'type' => 'button_group',
				'instructions' => esc_html__('Page visibility for robots', 'lptheme'),
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'index, follow' => 'index, follow',
					'noindex' => 'noindex',
					'nofollow' => 'nofollow',
					'noindex, nofollow' => 'noindex, nofollow',
				),
				'allow_null' => 0,
				'default_value' => 'index, follow',
				'layout' => 'horizontal',
				'return_format' => 'value',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
				),
			),
			array(
				array(
					'param' => 'page',
					'operator' => '!=',
					'value' => '3',
				),
				array(
					'param' => 'post_type',
					'operator' => '!=',
					'value' => 'shop_order',
				),
				array(
					'param' => 'post_type',
					'operator' => '!=',
					'value' => 'testimonials',
				),
				array(
					'param' => 'post_type',
					'operator' => '!=',
					'value' => 'subscriptions',
				),
			),
		),
		'menu_order' => -1,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));
	
	endif;
