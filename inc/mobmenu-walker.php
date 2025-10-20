<?php

class Mobile_Accordion_Walker extends Walker_Nav_Menu
{

	private $menu_items = array();
	private $current_menu = null;

	public $tree_type = array('post_type', 'taxonomy', 'custom');

	public $db_fields = array(
		'parent' => 'menu_item_parent',
		'id' => 'db_id'
	);

	public function walk($elements, $max_depth, ...$args)
	{
		$menu_args = $args[0] ?? null;
		if ($menu_args && isset($menu_args->menu)) {
			$this->current_menu = $menu_args->menu;
			$this->menu_items = wp_get_nav_menu_items($this->current_menu);
		}

		return parent::walk($elements, $max_depth, ...$args);
	}

	public function start_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<div class=\"accordeon-item__description\">\n";
		$output .= "\n$indent<ul class=\"mt-20 flex flex-col\">\n";
	}

	public function end_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
		$output .= "$indent</div>\n";
	}

	public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
	{
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		if ($depth === 0) {
			$classes[] = 'py-16 lg:py-20 not-first:border-t border-border w-full px-0 rounded-[0px]';
		}

		$has_children = $this->item_has_children($item->ID);

		if ($has_children) {
			$classes[] = 'accordeon-item';
		}

		$wp_classes = array(
			'current-menu-item',
			'current-menu-parent',
			'current-menu-ancestor',
			'current_page_item',
			'current_page_parent',
			'current_page_ancestor'
		);

		foreach ($wp_classes as $wp_class) {
			if (in_array($wp_class, $classes)) {
				continue;
			}
		}

		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
		$id = $id ? ' id="' . esc_attr($id) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
		$attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) . '"' : '';
		$attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url) . '"' : '';

		$item_output = isset($args->before) ? $args->before : '';

		if ($has_children && $depth === 0) {
			$item_output .= '<div class="accordeon-item__header">';
			$item_output .= '<div class="accordeon-item__title w-full">';
		}

		$item_output .= '<a' . $attributes . '>';
		$item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
		$item_output .= '</a>';

		if ($has_children && $depth === 0) {
			$item_output .= '
				<span class="accordeon-item__icon aspect-square rounded-full bg-gray flex-center">
					<svg class="size-[.6em] text-primary" width="11" height="6" viewBox="0 0 11 6" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g clip-path="url(#clip0_106_95)">
							<path d="M1.64285 1L5.5 5L9.35714 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
						</g>
						<defs>
							<clipPath id="clip0_106_95">
								<rect width="11" height="6" fill="white"></rect>
							</clipPath>
						</defs>
					</svg>
				</span>
			';
			$item_output .= '</div></div>';
		}

		$item_output .= isset($args->after) ? $args->after : '';

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	public function end_el(&$output, $item, $depth = 0, $args = null)
	{
		$output .= "</li>\n";
	}

	private function item_has_children($item_id)
	{
		if (empty($this->menu_items)) {
			return false;
		}

		foreach ($this->menu_items as $menu_item) {
			if ($menu_item->menu_item_parent == $item_id) {
				return true;
			}
		}

		return false;
	}
}

function get_mobile_accordion_menu($args = array())
{
	$defaults = array(
		'theme_location' => 'main-menu',
		'container' => false,
		'menu_class' => 'accordeon gap-0 group',
		'walker' => new Mobile_Accordion_Walker(),
		'fallback_cb' => false,
		'echo' => false
	);

	$args = wp_parse_args($args, $defaults);

	return wp_nav_menu($args);
}
