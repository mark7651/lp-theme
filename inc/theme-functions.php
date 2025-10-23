<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 *  global query processing
 * ------------------------------------------------------------------------------------------------
 */

function my_main_query_filter($query)
{

	if (is_admin()) {
		return;
	}

	// Handle only main query *or* specific flagged custom queries
	$is_target_query = $query->is_main_query() || $query->get('lptheme_filter');

	if (! $is_target_query) {
		return;
	}

	// Example: limit category queries
	if ($query->is_category()) {
		$query->set('include_children', false);
	}

	// Default to published posts only
	$query->set('post_status', 'publish');

	// Performance tweaks for non-paginated queries (e.g. widgets)
	if (! $query->get('paged')) {
		$query->set('no_found_rows', true);
	}

	// Ignore sticky posts for simplicity
	$query->set('ignore_sticky_posts', true);

	// Prevent unnecessary meta and term cache population
	$query->set('update_post_meta_cache', false);
	$query->set('update_post_term_cache', false);
}
add_action('pre_get_posts', 'my_main_query_filter');


/**
 * ------------------------------------------------------------------------------------------------
 * body classes
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_body_class')) {
	function lp_body_class($classes)
	{
		static $preloader_enabled = null;
		if ($preloader_enabled === null) {
			$preloader_enabled = get_field('preloader', 'option');
		}

		if ($preloader_enabled) {
			$classes[] = 'is-loading';
		}

		$classes[] = wp_is_mobile() ? 'is-mobile' : 'is-desktop';

		return $classes;
	}
	add_filter('body_class', 'lp_body_class');
}


// menu description field
function lp_nav_description($item_output, $item, $depth, $args)
{
	if (!empty($item->description)) {
		$item_output .= '<span class="menu-item-description">' . esc_html($item->description) . '</span>';
	}
	return $item_output;
}
add_filter('walker_nav_menu_start_el', 'lp_nav_description', 10, 4);


/**
 * ------------------------------------------------------------------------------------------------
 * analytics area insert
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('get_cached_acf_option')) {
	function get_cached_acf_option($field_name)
	{
		$cache_key = 'acf_option_' . $field_name;
		$value = wp_cache_get($cache_key, 'theme_options');

		if (false === $value) {
			$value = get_field($field_name, 'option');
			wp_cache_set($cache_key, $value ?: '', 'theme_options', 3600);
		}

		return $value ?: false;
	}
}

// Before </head>
if (!function_exists('closing_head_code')) {
	function closing_head_code()
	{
		if ($code = get_cached_acf_option('closing_head_code')) {
			echo $code;
		}
	}
	add_action('wp_head', 'closing_head_code', 10);
}

// After <body>
if (!function_exists('opening_body_code')) {
	function opening_body_code()
	{
		if ($code = get_cached_acf_option('opening_body_code')) {
			echo $code;
		}
	}
	add_action('wp_body_open', 'opening_body_code', 10);
}

// Before </body>
if (!function_exists('closing_body_code')) {
	function closing_body_code()
	{
		if ($code = get_cached_acf_option('closing_body_code')) {
			echo $code;
		}
	}
	add_action('wp_footer', 'closing_body_code', 10);
}

add_action('acf/save_post', function ($post_id) {
	if ($post_id === 'options') {
		wp_cache_delete('acf_option_closing_head_code', 'theme_options');
		wp_cache_delete('acf_option_opening_body_code', 'theme_options');
		wp_cache_delete('acf_option_closing_body_code', 'theme_options');
	}
});

/**
 * ------------------------------------------------------------------------------------------------
 * preloader
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_preloader')) {
	function lp_preloader()
	{
		if (!get_field('preloader', 'option')) {
			return;
		}
?>
		<div class="loader">
			<div class="loader__spinner"></div>
			<div class="loader__logo"></div>
		</div>
	<?php
	}
	add_action('wp_body_open', 'lp_preloader');
}


/**
 * ------------------------------------------------------------------------------------------------
 * favicon
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_favicon')) {
	function lp_favicon()
	{
		if (function_exists('has_site_icon') && has_site_icon()) {
			return;
		}

		$favicon_ico = get_field('favicon_ico', 'option') ?: LP_IMAGES . '/favicon.ico';
		$favicon_svg = get_field('favicon_svg', 'option') ?: LP_IMAGES . '/favicon.svg';
		$icon_touch = get_field('apple_touch_180', 'option') ?: LP_IMAGES . '/apple-touch-icon.png';

		if (is_array($favicon_ico)) {
			$favicon_ico = $favicon_ico['url'] ?? LP_IMAGES . '/favicon.ico';
		}
		if (is_array($favicon_svg)) {
			$favicon_svg = $favicon_svg['url'] ?? LP_IMAGES . '/favicon.svg';
		}
		if (is_array($icon_touch)) {
			$icon_touch = $icon_touch['url'] ?? LP_IMAGES . '/apple-touch-icon.png';
		}
	?>
		<link rel="icon" href="<?php echo esc_url($favicon_ico); ?>" sizes="any">
		<link rel="icon" href="<?php echo esc_url($favicon_svg); ?>" type="image/svg+xml">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url($icon_touch); ?>">
	<?php
	}
	add_action('wp_head', 'lp_favicon', 2);
	add_action('admin_head', 'lp_favicon', 2);
}

/**
 * ------------------------------------------------------------------------------------------------
 * logo image
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_logo')) {
	function lp_logo()
	{
		$logo_rastr = get_field('header_logo', 'option');
		$logo_svg = get_field('header_logo_svg', 'option');

		$is_home = is_home() || is_front_page();
		$link_url = $is_home ? '#top' : esc_url(home_url('/'));
		$link_attrs = $is_home ? '' : ' rel="home"';

		if ($logo_svg) {
			$logo_html = wp_get_attachment_image($logo_svg, 'thumbnail', false, ['loading' => 'eager']);
		} elseif ($logo_rastr) {
			$logo_html = sprintf(
				'<img src="%s" width="%s" height="%s" alt="%s" loading="eager">',
				esc_url($logo_rastr['url']),
				esc_attr($logo_rastr['width']),
				esc_attr($logo_rastr['height']),
				esc_attr(get_bloginfo('name'))
			);
		} else {
			$logo_html = sprintf(
				'<img src="%s" alt="%s" loading="eager">',
				esc_url(LP_IMAGES . '/logo.png'),
				esc_attr(get_bloginfo('name'))
			);
		}

		printf(
			'<a href="%s"%s aria-label="%s">%s</a>',
			$link_url,
			$link_attrs,
			esc_attr(get_bloginfo('name')),
			$logo_html
		);
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Header main navigation
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_header_main_nav')) {
	function lp_header_main_nav()
	{
		$location = 'main-menu';

		if (!has_nav_menu($location)) {
			if (current_user_can('edit_theme_options')) {
				$menu_link = admin_url('nav-menus.php');
				printf(
					'<nav class="main-nav"><p>%s</p></nav>',
					sprintf(
						__('Create your first <a href="%s"><strong>navigation menu</strong></a>', 'lptheme'),
						esc_url($menu_link)
					)
				);
			}
			return;
		}
	?>
		<nav class="main-nav">
			<?php
			wp_nav_menu(array(
				'theme_location' => $location,
				'container'      => false,
				'menu_id'        => false,
				'fallback_cb'    => false,
				'items_wrap'     => '<ul>%3$s</ul>',
			));
			?>
		</nav>
	<?php
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get page data it's template name
 * ------------------------------------------------------------------------------------------------
 */

function get_page_url($template_name)
{
	$pages = get_posts([
		'post_type' => 'page',
		'post_status' => 'publish',
		'meta_query' => [[
			'key' => '_wp_page_template',
			'value' => 'templates/' . $template_name . '.php',
			'compare' => '='
		]]
	]);
	if (!empty($pages)) {
		foreach ($pages as $pages__value) {
			return get_permalink($pages__value->ID);
		}
	}
	return get_bloginfo('url');
}

if (! function_exists('lp_tpl2id')) {
	function lp_tpl2id($tpl = '')
	{
		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => 'templates/' . $tpl . '.php',
		));
		foreach ($pages as $page) {
			return $page->ID;
		}
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * lazy template parts
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('template_markup')) {
	function template_markup()
	{
		check_ajax_referer('lp_template_markup_nonce', 'nonce');

		$template_slug = isset($_POST['template_slug'])
			? sanitize_key($_POST['template_slug'])
			: '';

		// Map slugs to actual template files
		$template_map = array(
			'post-card'    => 'template-parts/post-card',
			'hero-banner'  => 'template-parts/hero-banner',
			'cta-section'  => 'template-parts/cta-section',
		);

		if (!isset($template_map[$template_slug])) {
			wp_send_json_error('Invalid template', 400);
		}

		$template_path = $template_map[$template_slug];

		if (!locate_template($template_path . '.php')) {
			wp_send_json_error('Template not found', 404);
		}

		ob_start();
		get_template_part($template_path);
		$html = ob_get_clean();

		wp_send_json_success(array('html' => $html));
	}

	add_action('wp_ajax_lp_template_markup', 'template_markup');
	add_action('wp_ajax_nopriv_lp_template_markup', 'template_markup');
}

// **********************************************************************// 
// ! Blog/Post functions
// **********************************************************************// 

/**
 * ------------------------------------------------------------------------------------------------
 * Excerpt Read More
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_post_excerpt')) {
	function lp_post_excerpt($limit = '')
	{
		$limit   = (empty($limit)) ? 20 : $limit;
		$content = get_the_excerpt();
		$content = strip_shortcodes($content);
		$content = str_replace(']]>', ']]&gt;', $content);
		$content = strip_tags($content);
		$words   = explode(' ', $content, $limit + 1);
		if (count($words) > $limit) {
			array_pop($words);
			$content  = implode(' ', $words);
			$content .= ' &hellip;';
		}
		return $content;
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Is blog archive page
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_is_blog_archive')) {
	function lp_is_blog_archive()
	{
		return (is_home() || is_search() || is_tag() || is_category() || is_date() || is_author());
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Breacdrumbs function
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_breadcrumbs')) {
	function lp_breadcrumbs($args = array())
	{
		if (!get_field('breadcrumbs', 'option')) {
			return;
		}

		$defaults = array(
			'show_on_home' => false,
			'show_home_link' => true,
			'show_current' => true,
			'show_last_sep' => true,
			'max_depth' => 5,
			'custom_labels' => array()
		);
		$args = wp_parse_args($args, $defaults);

		$text = array_merge(array(
			'home' => esc_html__('Home', 'lptheme'),
			'category' => '%s',
			'search' => esc_html__('Search results for: "%s"', 'lptheme'),
			'tag' => esc_html__('Posts tagged "%s"', 'lptheme'),
			'author' => esc_html__('Author: %s', 'lptheme'),
			'404' => esc_html__('Error 404', 'lptheme'),
			'page' => esc_html__('Page %s', 'lptheme'),
			'year' => esc_html__('Year: %s', 'lptheme'),
			'month' => esc_html__('Month: %s', 'lptheme'),
			'day' => esc_html__('Day: %s', 'lptheme'),
		), $args['custom_labels']);

		$wrap_before = '<nav class="breadcrumbs md:inline-flex hidden gap-20 mb-40 font-medium" itemscope itemtype="https://schema.org/BreadcrumbList" aria-label="' . esc_attr__('Breadcrumb Navigation', 'lptheme') . '">';
		$wrap_after = '</nav>';
		$sep = '<span class="breadcrumbs__separator" aria-hidden="true"> / </span>';
		$before = '<span class="breadcrumbs__current" aria-current="page">';
		$after = '</span>';

		$home_url = home_url('/');
		$position = 0;
		$breadcrumbs = array();
		$current_title = '';

		$link_template = '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">' .
			'<a class="breadcrumbs__link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>' .
			'<meta itemprop="position" content="%3$s" />' .
			'</span>';

		// Add home breadcrumb
		if ($args['show_home_link']) {
			$position++;
			$breadcrumbs[] = array(
				'url' => esc_url($home_url),
				'title' => esc_html($text['home']),
				'position' => $position
			);
		}

		// Build breadcrumbs based on page type
		if (is_home() || is_front_page()) {
			if (!$args['show_on_home']) {
				return;
			}
		} elseif (is_category()) {
			$category = get_queried_object();

			// Add parent categories
			if ($category->parent) {
				$ancestors = get_ancestors($category->term_id, 'category');
				$ancestors = array_reverse($ancestors);
				$depth = 0;

				foreach ($ancestors as $ancestor_id) {
					if ($depth >= $args['max_depth']) break;
					$ancestor = get_term($ancestor_id, 'category');
					if ($ancestor && !is_wp_error($ancestor)) {
						$position++;
						$breadcrumbs[] = array(
							'url' => esc_url(get_term_link($ancestor)),
							'title' => esc_html($ancestor->name),
							'position' => $position
						);
						$depth++;
					}
				}
			}

			if (get_query_var('paged')) {
				$position++;
				$breadcrumbs[] = array(
					'url' => esc_url(get_category_link($category->term_id)),
					'title' => esc_html($category->name),
					'position' => $position
				);
				$current_title = sprintf($text['page'], get_query_var('paged'));
			} else {
				$current_title = $args['show_current'] ? $category->name : '';
			}
		} elseif (is_single() && !is_attachment()) {
			$post = get_queried_object();
			$post_type = get_post_type();

			if ($post_type === 'post') {
				// Regular blog post - add category hierarchy
				$categories = get_the_category($post->ID);
				if ($categories) {
					$category = $categories[0];

					// Add parent categories
					if ($category->parent) {
						$ancestors = get_ancestors($category->term_id, 'category');
						$ancestors = array_reverse($ancestors);
						$depth = 0;

						foreach ($ancestors as $ancestor_id) {
							if ($depth >= $args['max_depth']) break;
							$ancestor = get_term($ancestor_id, 'category');
							if ($ancestor && !is_wp_error($ancestor)) {
								$position++;
								$breadcrumbs[] = array(
									'url' => esc_url(get_term_link($ancestor)),
									'title' => esc_html($ancestor->name),
									'position' => $position
								);
								$depth++;
							}
						}
					}

					$position++;
					$breadcrumbs[] = array(
						'url' => esc_url(get_category_link($category)),
						'title' => esc_html($category->name),
						'position' => $position
					);
				}
			} else {
				// Custom post type
				$post_type_obj = get_post_type_object($post_type);

				// Add post type archive
				if ($post_type_obj && $post_type_obj->has_archive) {
					$position++;
					$breadcrumbs[] = array(
						'url' => esc_url(get_post_type_archive_link($post_type)),
						'title' => esc_html($post_type_obj->labels->name),
						'position' => $position
					);
				}

				// If hierarchical, add parent posts
				if ($post_type_obj && $post_type_obj->hierarchical && $post->post_parent) {
					$ancestors = get_post_ancestors($post->ID);
					$ancestors = array_reverse($ancestors);
					$depth = 0;

					foreach ($ancestors as $ancestor_id) {
						if ($depth >= $args['max_depth']) break;
						$position++;
						$breadcrumbs[] = array(
							'url' => esc_url(get_permalink($ancestor_id)),
							'title' => esc_html(get_the_title($ancestor_id)),
							'position' => $position
						);
						$depth++;
					}
				}
				// If non-hierarchical, add primary taxonomy
				elseif ($post_type_obj && !$post_type_obj->hierarchical) {
					$taxonomies = get_object_taxonomies($post_type, 'objects');

					foreach ($taxonomies as $taxonomy) {
						if (!$taxonomy->public || !$taxonomy->show_ui) continue;

						$terms = get_the_terms($post->ID, $taxonomy->name);
						if ($terms && !is_wp_error($terms)) {
							$primary_term = $terms[0];

							if ($primary_term && !is_wp_error($primary_term)) {
								// Add parent terms
								if ($primary_term->parent) {
									$ancestors = get_ancestors($primary_term->term_id, $taxonomy->name);
									$ancestors = array_reverse($ancestors);
									$depth = 0;

									foreach ($ancestors as $ancestor_id) {
										if ($depth >= $args['max_depth']) break;
										$ancestor = get_term($ancestor_id, $taxonomy->name);
										if ($ancestor && !is_wp_error($ancestor)) {
											$position++;
											$breadcrumbs[] = array(
												'url' => esc_url(get_term_link($ancestor)),
												'title' => esc_html($ancestor->name),
												'position' => $position
											);
											$depth++;
										}
									}
								}

								$position++;
								$breadcrumbs[] = array(
									'url' => esc_url(get_term_link($primary_term)),
									'title' => esc_html($primary_term->name),
									'position' => $position
								);
								break;
							}
						}
					}
				}
			}

			$current_title = $args['show_current'] ? get_the_title($post->ID) : '';
		} elseif (is_page()) {
			$page = get_queried_object();

			// Add parent pages (hierarchical)
			if ($page->post_parent) {
				$ancestors = get_post_ancestors($page->ID);
				$ancestors = array_reverse($ancestors);
				$depth = 0;

				foreach ($ancestors as $ancestor_id) {
					if ($depth >= $args['max_depth']) break;
					$position++;
					$breadcrumbs[] = array(
						'url' => esc_url(get_permalink($ancestor_id)),
						'title' => esc_html(get_the_title($ancestor_id)),
						'position' => $position
					);
					$depth++;
				}
			}

			$current_title = $args['show_current'] ? get_the_title($page->ID) : '';
		} elseif (is_post_type_archive()) {
			$post_type = get_query_var('post_type');
			if (is_array($post_type)) {
				$post_type = reset($post_type);
			}
			$post_type_obj = get_post_type_object($post_type);
			$current_title = $args['show_current'] && $post_type_obj ? $post_type_obj->labels->name : '';
		} elseif (is_tax()) {
			$term = get_queried_object();

			if ($term && !is_wp_error($term)) {
				// Add post type archive
				$taxonomy = get_taxonomy($term->taxonomy);
				if ($taxonomy && !empty($taxonomy->object_type)) {
					$post_type_obj = get_post_type_object($taxonomy->object_type[0]);
					if ($post_type_obj && $post_type_obj->has_archive) {
						$position++;
						$breadcrumbs[] = array(
							'url' => esc_url(get_post_type_archive_link($taxonomy->object_type[0])),
							'title' => esc_html($post_type_obj->labels->name),
							'position' => $position
						);
					}
				}

				// Add parent terms
				if ($term->parent) {
					$ancestors = get_ancestors($term->term_id, $term->taxonomy);
					$ancestors = array_reverse($ancestors);
					$depth = 0;

					foreach ($ancestors as $ancestor_id) {
						if ($depth >= $args['max_depth']) break;
						$ancestor = get_term($ancestor_id, $term->taxonomy);
						if ($ancestor && !is_wp_error($ancestor)) {
							$position++;
							$breadcrumbs[] = array(
								'url' => esc_url(get_term_link($ancestor)),
								'title' => esc_html($ancestor->name),
								'position' => $position
							);
							$depth++;
						}
					}
				}

				$current_title = $args['show_current'] ? $term->name : '';
			}
		} elseif (is_tag()) {
			$tag = get_queried_object();
			$current_title = $args['show_current'] ? sprintf($text['tag'], $tag->name) : '';
		} elseif (is_author()) {
			$author = get_queried_object();
			$current_title = $args['show_current'] ? sprintf($text['author'], $author->display_name) : '';
		} elseif (is_search()) {
			$current_title = $args['show_current'] ? sprintf($text['search'], get_search_query()) : '';
		} elseif (is_404()) {
			$current_title = $args['show_current'] ? $text['404'] : '';
		} elseif (is_year()) {
			$current_title = $args['show_current'] ? sprintf($text['year'], get_the_time('Y')) : '';
		} elseif (is_month()) {
			$position++;
			$breadcrumbs[] = array(
				'url' => esc_url(get_year_link(get_the_time('Y'))),
				'title' => esc_html(get_the_time('Y')),
				'position' => $position
			);
			$current_title = $args['show_current'] ? sprintf($text['month'], get_the_time('F')) : '';
		} elseif (is_day()) {
			$position++;
			$breadcrumbs[] = array(
				'url' => esc_url(get_year_link(get_the_time('Y'))),
				'title' => esc_html(get_the_time('Y')),
				'position' => $position
			);
			$position++;
			$breadcrumbs[] = array(
				'url' => esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))),
				'title' => esc_html(get_the_time('F')),
				'position' => $position
			);
			$current_title = $args['show_current'] ? sprintf($text['day'], get_the_time('d')) : '';
		}

		// Allow filtering
		$breadcrumbs = apply_filters('lp_breadcrumbs_items', $breadcrumbs, $args);
		$current_title = apply_filters('lp_breadcrumbs_current_title', $current_title, $args);

		// Don't output if nothing to show
		if (empty($breadcrumbs) && empty($current_title)) {
			return;
		}

		// Output breadcrumbs
		echo $wrap_before;

		foreach ($breadcrumbs as $index => $crumb) {
			if ($index > 0) {
				echo $sep;
			}
			printf(
				$link_template,
				$crumb['url'],
				$crumb['title'],
				$crumb['position']
			);
		}

		if (!empty($current_title)) {
			if (!empty($breadcrumbs)) {
				echo $sep;
			}
			echo $before . esc_html($current_title) . $after;
		} elseif ($args['show_last_sep'] && !empty($breadcrumbs)) {
			echo $sep;
		}

		echo $wrap_after;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Page title function
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_page_title')) {
	function lp_page_title()
	{

		$breadcrumbs = get_field('breadcrumbs', 'option');
		$title = '';

		if (is_home() && !is_singular('page')) :
			$title = esc_html__('Blog', 'lptheme');

		// Singular
		elseif (is_singular()) :
			$title = get_the_title();

		// Search
		elseif (is_search()) :
			global $wp_query;
			$total_results = $wp_query->found_posts;
			$prefix = '';

			if ($total_results == 1) {
				$prefix = esc_html__('1 search result for', 'lptheme');
			} else if ($total_results > 1) {
				$prefix = $total_results . ' ' . esc_html__('search results for', '');
			} else {
				$prefix = esc_html__('Search results for', 'lptheme');
			}
			//$title = $prefix . ': ' . get_search_query();
			$title = get_search_query();

		// Category and other Taxonomies
		elseif (is_category()) :
			$title = single_cat_title('', false);

		elseif (is_tag()) :
			$title = single_tag_title('', false);

		elseif (is_author()) :
			$title = wp_kses_post(sprintf(__('Author: %s', 'lptheme'), '<span class="vcard">' . get_the_author() . '</span>'));

		elseif (is_day()) :
			$title = wp_kses_post(sprintf(__('Day: %s', 'lptheme'), '<span>' . get_the_date() . '</span>'));

		elseif (is_month()) :
			$title = wp_kses_post(sprintf(__('Month: %s', 'lptheme'), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', 'lptheme')) . '</span>'));

		elseif (is_year()) :
			$title = wp_kses_post(sprintf(__('Year: %s', 'lptheme'), '<span>' . get_the_date(_x('Y', 'yearly archives date format', 'lptheme')) . '</span>'));

		elseif (is_tax()) :
			$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
			$title = $term->name;

		elseif (is_tax('post_format', 'post-format-aside')) :
			$title = esc_html__('Asides', 'lptheme');

		elseif (is_tax('post_format', 'post-format-gallery')) :
			$title = esc_html__('Galleries', 'lptheme');

		elseif (is_tax('post_format', 'post-format-image')) :
			$title = esc_html__('Images', 'lptheme');

		elseif (is_tax('post_format', 'post-format-video')) :
			$title = esc_html__('Videos', 'lptheme');

		elseif (is_tax('post_format', 'post-format-quote')) :
			$title = esc_html__('Quotes', 'lptheme');

		elseif (is_tax('post_format', 'post-format-link')) :
			$title = esc_html__('Links', 'lptheme');

		elseif (is_tax('post_format', 'post-format-status')) :
			$title = esc_html__('Statuses', 'lptheme');

		elseif (is_tax('post_format', 'post-format-audio')) :
			$title = esc_html__('Audios', 'lptheme');

		elseif (is_tax('post_format', 'post-format-chat')) :
			$title = esc_html__('Chats', 'lptheme');

		elseif (is_404()) :
			$title = esc_html__('404', 'lptheme');

		elseif (is_archive()) :
			$title = post_type_archive_title('', false);

		else :
			$title = esc_html__('Archives', 'lptheme');
		endif; ?>

		<div class="page-title">
			<h1><?php echo $title; ?></h1>
			<?php if ($breadcrumbs) lp_breadcrumbs(); ?>
		</div>
	<?php return;
	}
}

if (!function_exists('lp_back_btn')) {
	function lp_back_btn()
	{
	?>
		<a href="javascript:history.go(-1)" class="back-btn"><span><?php esc_html_e('Back', 'lptheme') ?></span></a>
	<?php
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Display meta information for a specific post
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_post_meta')) {
	function lp_post_meta($atts = array())
	{
		// Don't use extract() - it's bad practice
		$args = shortcode_atts(array(
			'author'       => 0,
			'author_ava'   => 0,
			'date'         => 0,
			'cats'         => 0,
			'tags'         => 0,
			'labels'       => 0,
			'short_labels' => false,
			'edit'         => 0,
			'comments'     => 0,
			'limit_cats'   => 0,
		), $atts);

		if (get_post_type() !== 'post') {
			return;
		}
	?>
		<ul class="entry-meta-list">
			<?php // Sticky post indicator 
			?>
			<?php if (is_sticky()): ?>
				<li class="meta-featured-post"><?php esc_html_e('Featured', 'lptheme'); ?></li>
			<?php endif; ?>

			<?php // Author 
			?>
			<?php if ($args['author'] === 1): ?>
				<li class="meta-author">
					<?php if ($args['labels'] === 1): ?>
						<?php echo $args['short_labels']
							? esc_html__('By', 'lptheme')
							: esc_html__('Posted by', 'lptheme'); ?>
					<?php endif; ?>

					<?php if ($args['author_ava'] === 1): ?>
						<?php echo get_avatar(get_the_author_meta('ID'), 32, '', esc_attr__('Author avatar', 'lptheme')); ?>
					<?php endif; ?>

					<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author">
						<?php echo esc_html(get_the_author()); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php // Publish date 
			?>
			<?php if ($args['date'] === 1): ?>
				<li class="meta-date">
					<time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
						<?php echo esc_html(get_the_date()); ?>
					</time>
				</li>
			<?php endif; ?>

			<?php // Modified date (only show if different from publish date) 
			?>
			<?php if (get_the_modified_date('c') !== get_the_date('c')): ?>
				<li class="modified-date">
					<time class="updated" datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>">
						<?php printf(
							esc_html__('Updated: %s', 'lptheme'),
							esc_html(get_the_modified_date())
						); ?>
					</time>
				</li>
			<?php endif; ?>

			<?php // Categories 
			?>
			<?php if ($args['cats'] === 1 && has_category()): ?>
				<li class="meta-categories">
					<?php
					$categories = get_the_category_list(', ');
					if ($args['limit_cats'] > 0) {
						$cats_array = get_the_category();
						$cats_array = array_slice($cats_array, 0, $args['limit_cats']);
						$categories = implode(', ', array_map(function ($cat) {
							return sprintf(
								'<a href="%s">%s</a>',
								esc_url(get_category_link($cat->term_id)),
								esc_html($cat->name)
							);
						}, $cats_array));
					}
					echo $categories;
					?>
				</li>
			<?php endif; ?>

			<?php // Tags 
			?>
			<?php if ($args['tags'] === 1 && has_tag()): ?>
				<li class="meta-tags">
					<?php echo get_the_tag_list('', ', '); ?>
				</li>
			<?php endif; ?>

			<?php // Comments 
			?>
			<?php if ($args['comments'] === 1 && comments_open()): ?>
				<li class="meta-comments">
					<?php comments_popup_link(
						esc_html__('Leave a comment', 'lptheme'),
						esc_html__('1 comment', 'lptheme'),
						esc_html__('% comments', 'lptheme')
					); ?>
				</li>
			<?php endif; ?>

			<?php // Edit link (only for editors) 
			?>
			<?php if ($args['edit'] === 1 && current_user_can('edit_post', get_the_ID())): ?>
				<li class="meta-edit">
					<?php edit_post_link(esc_html__('Edit', 'lptheme')); ?>
				</li>
			<?php endif; ?>
		</ul>
	<?php
	}
}

if (!function_exists('lp_post_date')) {
	function lp_post_date()
	{
		$post_date = get_the_date('d.m.Y'); // Get POST date, not today's date!
		$datetime = get_the_date('c'); // ISO 8601 format for datetime attribute
	?>

		<div class="post-date">
			<time datetime="<?php echo esc_attr($datetime); ?>" class="post-date-day">
				<?php echo esc_html($post_date); ?>
			</time>
		</div>
	<?php
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Display pagination of posts.
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_paging_nav')) {
	function lp_paging_nav($max_num_pages = false)
	{
		if ($max_num_pages === false) {
			global $wp_query;
			$max_num_pages = $wp_query->max_num_pages;
		}

		if ($max_num_pages <= 1) {
			return;
		}

		$current_page = max(1, get_query_var('paged') ?: 1);

		// Calculate which pages to show (5 pages centered around current)
		$pages_to_show = 5;
		$start_page = max(1, $current_page - 2);
		$end_page = min($max_num_pages, $start_page + $pages_to_show - 1);

		// Adjust start if we're near the end
		if ($end_page - $start_page < $pages_to_show - 1) {
			$start_page = max(1, $end_page - $pages_to_show + 1);
		}
	?>
		<nav class="flex items-center gap-10 mt-60 justify-center mx-auto"
			aria-label="<?php esc_attr_e('Pagination', 'lptheme'); ?>">
			<?php
			// Previous button
			if ($current_page > 1) : ?>
				<a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>"
					class="btn-round text-black bg-white"
					aria-label="<?php esc_attr_e('Previous page', 'lptheme'); ?>">
					<svg class="size-[1em] rotate-180" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</a>
			<?php else : ?>
				<span class="btn-round text-black bg-white opacity-50 cursor-not-allowed"
					aria-disabled="true"
					aria-label="<?php esc_attr_e('Previous page (disabled)', 'lptheme'); ?>">
					<svg class="size-[1em] rotate-180" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</span>
				<?php endif;

			// Page numbers
			for ($i = $start_page; $i <= $end_page; $i++) :
				if ($i === $current_page) : ?>
					<span class="btn-round bg-primary text-white"
						aria-current="page"
						aria-label="<?php printf(esc_attr__('Page %s', 'lptheme'), $i); ?>">
						<?php echo $i; ?>
					</span>
				<?php else : ?>
					<a href="<?php echo esc_url(get_pagenum_link($i)); ?>"
						class="btn-round text-black bg-white hover:bg-gray-bg"
						aria-label="<?php printf(esc_attr__('Go to page %s', 'lptheme'), $i); ?>">
						<?php echo $i; ?>
					</a>
				<?php endif;
			endfor;

			// Next button
			if ($current_page < $max_num_pages) : ?>
				<a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>"
					class="btn-round text-black bg-white"
					aria-label="<?php esc_attr_e('Next page', 'lptheme'); ?>">
					<svg class="size-[1em]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</a>
			<?php else : ?>
				<span class="btn-round text-black bg-white opacity-50 cursor-not-allowed"
					aria-disabled="true"
					aria-label="<?php esc_attr_e('Next page (disabled)', 'lptheme'); ?>">
					<svg class="size-[1em]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</span>
			<?php endif; ?>
		</nav>
<?php
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * icons parts
 * ------------------------------------------------------------------------------------------------
 */

function icon($name, $selector = null)
{

	if (!preg_match('/^[a-z0-9]+(-[a-z0-9]+)*$/i', $name) || strlen($name) > 50) {
		return;
	}

	$file = get_template_directory() . '/assets/icons/' . $name . '.svg';

	if (!file_exists($file)) {
		return;
	}

	$svg = file_get_contents($file);

	if ($selector) {
		$svg = str_replace('<svg ', '<svg class="' . esc_attr($selector) . '" ', $svg);
	}

	echo $svg;
}

/**
 * ------------------------------------------------------------------------------------------------
 * simple translation
 * ------------------------------------------------------------------------------------------------
 */

function translate_pll($uk_text, $en_text = null)
{
	if (!function_exists('pll_current_language')) {
		return $uk_text;
	}

	$current_language = pll_current_language();

	if ($en_text === null) {
		$en_text = $uk_text;
	}

	return $current_language === 'en' ? $en_text : $uk_text;
}

/**
 * ------------------------------------------------------------------------------------------------
 *  Cache expensive template parts
 * ------------------------------------------------------------------------------------------------
 */

function cached_template_part($slug, $name = null, $args = array(), $expiration = 3600)
{

	$cache_key = 'tpl_' . $slug;
	if ($name) {
		$cache_key .= '_' . $name;
	}
	if (!empty($args)) {
		$cache_key .= '_' . md5(serialize($args));
	}

	if (function_exists('pll_current_language')) {
		$cache_key .= '_' . pll_current_language();
	}

	$cached = wp_cache_get($cache_key, 'template_parts');

	if (false !== $cached) {
		echo $cached;
		return;
	}

	ob_start();
	get_template_part($slug, $name, $args);
	$cached = ob_get_clean();

	wp_cache_set($cache_key, $cached, 'template_parts', $expiration);

	echo $cached;
}
