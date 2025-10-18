<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * body classes
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_body_class')) {
	function lp_body_class($classes)
	{

		$classes[]  = '';

		if (get_field('preloader', 'option')) {
			$classes[]       = 'is-loading';
		}

		$include = array(
			'is-iphone'            => $GLOBALS['is_iphone'],
			'is-chrome'            => $GLOBALS['is_chrome'],
			'is-safari'            => $GLOBALS['is_safari'],
			'is-ns4'               => $GLOBALS['is_NS4'],
			'is-opera'             => $GLOBALS['is_opera'],
			'is-mac-ie'            => $GLOBALS['is_macIE'],
			'is-win-ie'            => $GLOBALS['is_winIE'],
			'is-gecko'             => $GLOBALS['is_gecko'],
			'is-lynx'              => $GLOBALS['is_lynx'],
			'is-ie'                => $GLOBALS['is_IE'],
			'is-edge'              => $GLOBALS['is_edge'],
			'is-mobile'            => wp_is_mobile(),
			'is-desktop'           => !wp_is_mobile(),
		);
		foreach ($include as $class => $do_include) {
			if ($do_include) $classes[$class] = $class;
		}

		$allowed_classes = [
			'singular',
			'single',
			'archive',
			'home',
			'blog',
			'error404',
			'header-sticky',
			'is-loading',
			'logged-in',
			'is-iphone',
			'is-chrome',
			'is-safari',
			'is-ns4',
			'is-opera',
			'is-mac-ie',
			'is-win-ie',
			'is-gecko',
			'is-lynx',
			'is-ie',
			'is-edge',
			'is-mobile',
			'is-desktop',
		];

		return array_intersect($classes, $allowed_classes);
	}
	add_filter('body_class', 'lp_body_class');
}

/**
 * ------------------------------------------------------------------------------------------------
 * clean menu classes (Deletes all CSS classes and id's, except for those listed in the array below)
 * ------------------------------------------------------------------------------------------------
 */

function custom_wp_nav_menu($var)
{
	return is_array($var) ? array_intersect(
		$var,
		array(
			'current_page_parent',
			'current-menu-item',
			'current_page_ancestor',
			'menu-item-has-children',
			'first',
			'last',
			'vertical',
			'new',
			'hot',
			'btn',
		)
	) : '';
}
add_filter('nav_menu_css_class', 'custom_wp_nav_menu');
add_filter('nav_menu_item_id', 'custom_wp_nav_menu');
add_filter('page_css_class', 'custom_wp_nav_menu');

// menu description field
function lp_nav_description($item_output, $item, $depth, $args)
{
	if (!empty($item->description)) {
		$item_output = str_replace($args->link_after . '</a>', '<span class="menu-item-description">' . $item->description . '</span>' . $args->link_after . '</a>', $item_output);
	}
	return $item_output;
}
add_filter('walker_nav_menu_start_el', 'lp_nav_description', 10, 4);


/**
 * ------------------------------------------------------------------------------------------------
 * analytics area insert
 * ------------------------------------------------------------------------------------------------
 */

// before </head>
if (! function_exists('closing_head_code')) {
	function closing_head_code()
	{ ?>
		<link rel="dns-prefetch" href="//googletagmanager.com/">
		<link rel="dns-prefetch" href="//analytics.google.com/">
	<?php the_field('closing_head_code', 'option');
	}
	if (get_field('closing_head_code', 'option')) {
		add_action('wp_head', 'closing_head_code', 10);
	}
}

// after <body>
if (! function_exists('opening_body_code')) {
	function opening_body_code()
	{
		the_field('opening_body_code', 'option');
	}
	if (get_field('opening_body_code', 'option')) {
		add_action('wp_body_open', 'opening_body_code', 10);
	}
}

// before </body>
if (! function_exists('closing_body_code')) {
	function closing_body_code()
	{
		the_field('closing_body_code', 'option');
	}
	if (get_field('closing_body_code', 'option')) {
		add_action('wp_footer', 'closing_body_code', 10);
	}
}

// Allow ICO file uploads
function allow_custom_mime_types($mimes)
{
	$mimes['ico'] = array('image/x-icon', 'image/vnd.microsoft.icon');
	return $mimes;
}
add_filter('upload_mimes', 'allow_custom_mime_types', 1, 1);

// Additional filter for mime type checking
function fix_mime_type_ico($data, $file, $filename, $mimes)
{
	$ext = pathinfo($filename, PATHINFO_EXTENSION);

	if ($ext === 'ico') {
		$data['ext'] = 'ico';
		$data['type'] = 'image/x-icon';
	}

	return $data;
}
add_filter('wp_check_filetype_and_ext', 'fix_mime_type_ico', 10, 4);

function custom_upload_validation($file)
{
	if ($file['type'] == 'image/x-icon' || $file['type'] == 'image/vnd.microsoft.icon') {
		$file['ext'] = 'ico';
		$file['type'] = 'image/x-icon';
	}
	return $file;
}
add_filter('wp_handle_upload_prefilter', 'custom_upload_validation');


/**
 * ------------------------------------------------------------------------------------------------
 * preloader
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_preloader')) {
	function lp_preloader()
	{ ?>
		<div class="loader">
			<div class="loader__spinner"></div>
			<div class="loader__logo"></div>
		</div>
	<?php	}
	if (get_field('preloader', 'option')) {
		add_action('wp_body_open', 'lp_preloader');
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * favicon
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_favicon')) {
	function lp_favicon()
	{
		if (function_exists('has_site_icon') && has_site_icon()) return '';

		$favicon_ico = get_field('favicon_ico', 'option');
		$favicon_svg = get_field('favicon_svg', 'option');
		$icon_touch = get_field('apple_touch_180', 'option');

		if ($favicon_ico) {
			$favicon_ico;
		} else {
			$favicon_ico = LP_IMAGES . '/favicon.ico';
		}
		if ($favicon_svg) {
			$favicon_svg;
		} else {
			$favicon_svg = LP_IMAGES . '/favicon.svg';;
		}
		if ($icon_touch) {
			$icon_touch;
		} else {
			$icon_touch = LP_IMAGES . '/apple-touch-icon.png';
		}

	?>
		<link rel="icon" href="<?php echo esc_attr($favicon_ico); ?>" sizes="any">
		<link rel="icon" href="<?php echo esc_attr($favicon_svg); ?>" type="image/svg+xml">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_attr($icon_touch); ?>">
		<?php }
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

		if ($logo_rastr) {
			$logo_url = $logo_rastr['url'];
		} else {
			$logo_url = LP_IMAGES . '/logo.png';
		}

		if (!is_home() && !is_front_page()): ?>

			<a href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
				<?php if ($logo_svg): ?>
					<?php echo wp_get_attachment_image($logo_svg,  'thumbnail', false, ['loading' => 'eager']); ?>
				<?php elseif ($logo_rastr): ?>
					<img src="<?php echo esc_url($logo_url); ?>"
						width="<?php echo esc_attr($logo_rastr['width']); ?>"
						height="<?php echo esc_attr($logo_rastr['height']); ?>"
						alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
				<?php else: ?>
					<img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
				<?php endif; ?>
			</a>

		<?php else: ?>

			<a href="#top">
				<?php if ($logo_svg): ?>
					<?php echo wp_get_attachment_image($logo_svg,  'thumbnail', false, ['loading' => 'eager']); ?>
				<?php elseif ($logo_rastr): ?>
					<img src="<?php echo esc_url($logo_url); ?>"
						width="<?php echo esc_attr($logo_rastr['width']); ?>"
						height="<?php echo esc_attr($logo_rastr['height']); ?>"
						alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
				<?php else: ?>
					<img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
				<?php endif; ?>

			</a>

		<?php endif; ?>
	<?php
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Header main navigation
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_header_main_nav')) {
	function lp_header_main_nav()
	{
		$location = apply_filters('lp_main_menu_location', 'main-menu'); ?>
		<nav class="main-nav"><?php if (has_nav_menu($location)) {
														wp_nav_menu(array(
															'theme_location' => $location,
															'container' => false,
															'menu_id' => false,
															'fallback_cb' => 'wp_page_menu',
															'items_wrap' => '<ul>%3$s</ul>',
														));
													} else {
														$menu_link = get_admin_url(null, 'nav-menus.php');
													?><?php printf(wp_kses(__('Create <a href="%s"><strong>menu</strong></a>', 'lptheme'), 'default'), $menu_link) ?><?php
																																																																					}
																																																																						?></nav>

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

if (! function_exists('template_markup')) {
	function template_markup()
	{
		$template_part_name = $_POST['template_part_name'];
		ob_start();
		get_template_part('template-parts/' . $template_part_name);
		$html = ob_get_clean();
		echo $html;
		exit();
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
 * Snippet from http://dimox.net/wordpress-breadcrumbs-without-a-plugin/
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
			'cache_duration' => HOUR_IN_SECONDS,
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
			'cpage' => esc_html__('Comments page %s', 'lptheme'),
			'year' => esc_html__('Year: %s', 'lptheme'),
			'month' => esc_html__('Month: %s', 'lptheme'),
			'day' => esc_html__('Day: %s', 'lptheme'),
		), $args['custom_labels']);

		$wrap_before = '<nav class="breadcrumbs md:inline-flex hidden gap-20 mb-40 font-medium" itemscope itemtype="https://schema.org/BreadcrumbList" aria-label="' . esc_attr__('Breadcrumb Navigation', 'lptheme') . '">';
		$wrap_after = '</nav>';
		$sep = '<span class="breadcrumbs__separator" aria-hidden="true"> / </span>';
		$before = '<span class="breadcrumbs__current" aria-current="page">';
		$after = '</span>';

		global $post, $wp_query;
		$home_url = home_url('/');
		$position = 0;
		$breadcrumbs = array();

		$link_template = '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">' .
			'<a class="breadcrumbs__link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>' .
			'<meta itemprop="position" content="%3$s" />' .
			'</span>';

		function add_breadcrumb(&$breadcrumbs, $url, $title, &$position)
		{
			$position++;
			$breadcrumbs[] = array(
				'url' => esc_url($url),
				'title' => esc_html($title),
				'position' => $position
			);
		}

		function get_post_type_archive_breadcrumb($post_type_name, &$breadcrumbs, &$position)
		{
			$post_type = get_post_type_object($post_type_name);

			if (!$post_type || !$post_type->public || !$post_type->has_archive) {
				return;
			}

			$archive_link = get_post_type_archive_link($post_type_name);
			if ($archive_link) {
				add_breadcrumb($breadcrumbs, $archive_link, $post_type->labels->name, $position);
			}
		}

		function get_taxonomy_hierarchy($term_id, $taxonomy, &$breadcrumbs, &$position, $max_depth = 10)
		{
			if ($max_depth <= 0) return;

			$parents = get_ancestors($term_id, $taxonomy);
			$parents = array_reverse($parents);

			foreach ($parents as $parent_id) {
				$parent_term = get_term($parent_id, $taxonomy);
				if ($parent_term && !is_wp_error($parent_term)) {
					add_breadcrumb($breadcrumbs, get_term_link($parent_term), $parent_term->name, $position);
				}
			}
		}

		function get_page_hierarchy($page_id, &$breadcrumbs, &$position, $max_depth = 10)
		{
			if ($max_depth <= 0) return;

			$parents = get_post_ancestors($page_id);
			$parents = array_reverse($parents);

			foreach ($parents as $parent_id) {
				$parent_title = get_the_title($parent_id);
				if ($parent_title) {
					add_breadcrumb($breadcrumbs, get_permalink($parent_id), $parent_title, $position);
				}
			}
		}

		function get_custom_post_hierarchy($post_id, $post_type, &$breadcrumbs, &$position)
		{
			// Add post type archive
			get_post_type_archive_breadcrumb($post_type, $breadcrumbs, $position);

			// Handle hierarchical custom post types
			$post_type_obj = get_post_type_object($post_type);
			if ($post_type_obj && $post_type_obj->hierarchical) {
				get_page_hierarchy($post_id, $breadcrumbs, $position);
				return;
			}

			// Handle non-hierarchical custom post types with primary taxonomy
			$taxonomies = get_object_taxonomies($post_type, 'objects');

			foreach ($taxonomies as $taxonomy) {
				if (!$taxonomy->public || !$taxonomy->show_ui) continue;

				$terms = get_the_terms($post_id, $taxonomy->name);
				if ($terms && !is_wp_error($terms)) {
					// Get the primary term or first term
					$primary_term = $terms[0];

					// Check for Yoast primary term
					if (class_exists('WPSEO_Primary_Term')) {
						$primary_term_obj = new WPSEO_Primary_Term($taxonomy->name, $post_id);
						$primary_term_id = $primary_term_obj->get_primary_term();
						if ($primary_term_id) {
							$primary_term = get_term($primary_term_id);
						}
					}

					if ($primary_term && !is_wp_error($primary_term)) {
						get_taxonomy_hierarchy($primary_term->term_id, $taxonomy->name, $breadcrumbs, $position);
						add_breadcrumb($breadcrumbs, get_term_link($primary_term), $primary_term->name, $position);
						break; // Only show one taxonomy
					}
				}
			}
		}

		if ($args['show_home_link']) {
			add_breadcrumb($breadcrumbs, $home_url, $text['home'], $position);
		}

		if (is_home() || is_front_page()) {
			if (!$args['show_on_home']) {
				return;
			}
		} elseif (is_category()) {
			get_taxonomy_hierarchy(get_query_var('cat'), 'category', $breadcrumbs, $position, $args['max_depth']);

			if (get_query_var('paged')) {
				add_breadcrumb($breadcrumbs, get_category_link(get_query_var('cat')), single_cat_title('', false), $position);
				$current_title = sprintf($text['page'], get_query_var('paged'));
			} else {
				$current_title = $args['show_current'] ? single_cat_title('', false) : '';
			}
		} elseif (is_single() && !is_attachment()) {
			$post_type = get_post_type();

			if ($post_type === 'post') {
				// Regular blog post
				$categories = get_the_category();
				if ($categories) {
					$category = $categories[0];
					get_taxonomy_hierarchy($category->term_id, 'category', $breadcrumbs, $position, $args['max_depth']);
					add_breadcrumb($breadcrumbs, get_category_link($category), $category->name, $position);
				}
			} else {
				// Custom post type
				get_custom_post_hierarchy($post->ID, $post_type, $breadcrumbs, $position);
			}

			$current_title = $args['show_current'] ? get_the_title() : '';
		} elseif (is_page()) {
			if ($post->post_parent) {
				get_page_hierarchy($post->ID, $breadcrumbs, $position, $args['max_depth']);
			}
			$current_title = $args['show_current'] ? get_the_title() : '';
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
				// Add post type archive if applicable
				$post_types = get_taxonomy($term->taxonomy)->object_type;
				if (!empty($post_types)) {
					get_post_type_archive_breadcrumb($post_types[0], $breadcrumbs, $position);
				}

				get_taxonomy_hierarchy($term->term_id, $term->taxonomy, $breadcrumbs, $position, $args['max_depth']);
				$current_title = $args['show_current'] ? $term->name : '';
			}
		} elseif (is_tag()) {
			$current_title = $args['show_current'] ? sprintf($text['tag'], single_tag_title('', false)) : '';
		} elseif (is_author()) {
			$author = get_userdata(get_query_var('author'));
			$current_title = $args['show_current'] && $author ? sprintf($text['author'], $author->display_name) : '';
		} elseif (is_search()) {
			$current_title = $args['show_current'] ? sprintf($text['search'], get_search_query()) : '';
		} elseif (is_404()) {
			$current_title = $args['show_current'] ? $text['404'] : '';
		} elseif (is_year()) {
			$current_title = $args['show_current'] ? sprintf($text['year'], get_the_time('Y')) : '';
		} elseif (is_month()) {
			add_breadcrumb($breadcrumbs, get_year_link(get_the_time('Y')), get_the_time('Y'), $position);
			$current_title = $args['show_current'] ? sprintf($text['month'], get_the_time('F')) : '';
		} elseif (is_day()) {
			add_breadcrumb($breadcrumbs, get_year_link(get_the_time('Y')), get_the_time('Y'), $position);
			add_breadcrumb($breadcrumbs, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'), $position);
			$current_title = $args['show_current'] ? sprintf($text['day'], get_the_time('d')) : '';
		}

		$breadcrumbs = apply_filters('lp_breadcrumbs_items', $breadcrumbs, $args);
		$current_title = apply_filters('lp_breadcrumbs_current_title', $current_title ?? '', $args);

		if (empty($breadcrumbs) && empty($current_title)) {
			return;
		}

		echo $wrap_before;

		foreach ($breadcrumbs as $index => $crumb) {
			if ($index > 0) {
				echo $sep;
			}
			printf($link_template, $crumb['url'], $crumb['title'], $crumb['position']);
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
		extract(shortcode_atts(array(
			'author'     => 0,
			'author_ava' => 0,
			'date'     => 0,
			'cats'     => 0,
			'tags'     => 0,
			'labels'   => 0,
			'short_labels' => false,
			'edit'     => 0,
			'comments' => 0,
			'limit_cats' => 0
		), $atts));
	?>
		<ul class="entry-meta-list">
			<?php if (get_post_type() === 'post'): ?>

				<?php // Is sticky 
				?>
				<li class="modified-date"><time class="updated" datetime="<?php echo get_the_modified_date('c'); ?>"><?php echo get_the_modified_date(); ?></time></li>

				<?php if (is_sticky()): ?>
					<li class="meta-featured-post"><?php esc_html_e('Featured', 'lptheme') ?></li>
				<?php endif; ?>

				<?php // Author 
				?>
				<?php if ($author == 1): ?>
					<li class="meta-author">
						<?php if ($labels == 1 && ! $short_labels): ?>
							<?php esc_html_e('Posted by', 'lptheme'); ?>
						<?php elseif ($labels == 1 && $short_labels): ?>
							<?php esc_html_e('By', 'lptheme'); ?>
						<?php endif; ?>
						<?php if ($author_ava == 1): ?>
							<?php echo get_avatar(get_the_author_meta('ID'), 32, '', 'author-avatar'); ?>
						<?php endif; ?>
						<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author">
							<span class="vcard author author_name">
								<span class="fn"><?php echo get_the_author(); ?></span>
							</span>
						</a>
					</li>
				<?php endif ?>
				<?php // Date 
				?>
				<?php if ($date == 1): ?><li class="meta-date"><?php the_date(); ?></li><?php endif ?>
				<?php // Categories 
				?>
				<?php if (get_the_category_list(', ') && $cats == 1): ?>
					<li class="meta-categories"><?php echo get_the_category_list(', '); ?></li>
				<?php endif; ?>
				<?php // Tags 
				?>
				<?php if (get_the_tag_list('', ', ') && $tags == 1): ?>
					<li class="meta-tags"><?php echo get_the_tag_list('', ', '); ?></li>
				<?php endif; ?>
				<?php // Comments 
				?>
				<?php if ($comments && comments_open()): ?>
					<li><span class="meta-reply">
							<?php comments_popup_link(esc_html__('Leave a comment', 'lptheme'), esc_html__('1 comment', 'lptheme'), esc_html__('% comments', 'lptheme')); ?>
						</span></li>
				<?php endif; ?>
				<?php // Edit link 
				?>
				<?php if (is_user_logged_in() && $edit == 1): ?>
					<!--li><?php edit_post_link(esc_html__('Edit', 'lptheme'), '<span class="edit-link">', '</span>'); ?></li-->
				<?php endif; ?>
			<?php endif; ?>
		</ul>
	<?php
	}
}

if (!function_exists('lp_post_date')) {
	function lp_post_date()
	{
		$has_title = get_the_title() != '';
		$attr = '';
		if (! $has_title && ! is_single()) {
			$url = get_the_permalink();
			$attr = 'window.location=\'' . $url . '\';';
		}
	?>
		<div class="post-date" onclick="<?php echo esc_attr($attr); ?>">
			<span class="post-date-day">
				<?php echo date('d.m.Y') ?>
			</span>
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

		$current_page = max(1, get_query_var('paged'));

		if ($max_num_pages > 1) : ?>
			<div class="mt-60 flex items-center gap-8">
				<?php
				// Previous button
				if ($current_page > 1) : ?>
					<a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>"
						class="btn-outline aspect-square scale-x-[1]">
						<svg class="w-4 h-4 rotate-180" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</a>
				<?php endif;

				// Page numbers
				for ($i = 1; $i <= min(5, $max_num_pages); $i++) : ?>
					<a href="<?php echo esc_url(get_pagenum_link($i)); ?>"
						class="btn-outline aspect-square <?php echo $current_page === $i ? 'bg-primary/10 text-primary' : 'text-slate-900 hover:bg-slate-100'; ?>">
						<?php echo $i; ?>
					</a>
				<?php endfor;

				// Next button
				if ($current_page < $max_num_pages) : ?>
					<a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>"
						class="btn-outline aspect-square">
						<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</a>
				<?php endif; ?>
			</div>
<?php endif;
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * icons parts
 * ------------------------------------------------------------------------------------------------
 */

function icon($name, $selector = null)
{
	ob_start();
	get_template_part('assets/icons/' . $name . '.svg');
	$svg = ob_get_clean();

	$svg = preg_replace('/^<svg /', '<svg class="' . $selector . '" ', $svg);

	echo $svg;
}


/**
 * ------------------------------------------------------------------------------------------------
 * translation
 * ------------------------------------------------------------------------------------------------
 */

if (function_exists('pll_current_language')) {
	function translate_pll($uk_text, $en_text)
	{
		$current_language = pll_current_language();

		if ($current_language == "uk") {
			return pll__($uk_text);
		} else {
			return pll__($en_text);
		}
	}
}
