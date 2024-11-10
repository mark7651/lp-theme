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

		if (get_field('header_sticky', 'option')) {
			$classes[]       = 'header-sticky';
		}
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
 * analytics area isert
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
													?><?php printf(wp_kses(__('Создайте Ваше первое <a href="%s"><strong>меню навигации</strong></a>', 'lptheme'), 'default'), $menu_link) ?><?php
																																																																																	}
																																																																																		?></nav>
		<button class="burger" aria-label="menu"><span></span><span></span><span></span></button>
	<?php
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * mobile menu
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_mobile_menu')) {
	add_action('wp_footer', 'lp_mobile_menu', 10);

	function lp_mobile_menu()
	{
		if (!wp_is_mobile()) return;
	?>
		<?php if (has_nav_menu('mobile-menu')): ?>

			<div class="sidebar-menu scrollbar">
				<div class="widget-heading">
					<div class="widget-title"><?php esc_html_e('Menu', 'lptheme'); ?></div>
					<button class="widget-close"><?php esc_html_e('close', 'lptheme'); ?></button>
				</div>
				<?php wp_nav_menu(array('theme_location' => 'mobile-menu')); ?>
			</div>

		<?php endif; ?>
	<?php }
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
			'meta_value' => $tpl
		));
		foreach ($pages as $page) {
			return $page->ID;
		}
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * Maintenance mode
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_maintenance_mode')) {
	function lp_maintenance_mode()
	{
		if (!get_field('enable_maintenance', 'option')) return;
		if (!is_user_logged_in() && !is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php'))) {

			$period = 24 * HOUR_IN_SECONDS; // 24 hours

			header('Retry-After: ' . $period);

			include_once(LP_THEMEROOT . '/maintenance.php');
			exit;
		}
	}
	add_action('init', 'lp_maintenance_mode');
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

if (! function_exists('lp_breadcrumbs')) {
	function lp_breadcrumbs()
	{
		if (!get_field('breadcrumbs', 'option')) return;

		$text['home']     = translate_pll('Головна', 'Home');
		$text['category'] = '%s';
		$text['search']   = 'Результати пошуку: "%s"';
		$text['tag']      = 'Записи з тегом "%s"';
		$text['author']   = 'Автор: %s';
		$text['404']      = 'Помилка 404';
		$text['page']     = 'Сторінка %s';
		$text['cpage']    = 'Коментарі сторінка %s';

		$wrap_before    = '<nav class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">';
		$wrap_after     = '</nav>';
		$sep            = '<span class="breadcrumbs__separator"> / </span>';
		$before         = '<span class="breadcrumbs__current">';
		$after          = '</span>';

		$show_on_home   = 0;
		$show_home_link = 1;
		$show_current   = 1;
		$show_last_sep  = 1;

		global $post;
		$home_url = home_url('/');
		$link = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
		$link .= '<a class="breadcrumbs__link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>';
		$link .= '<meta itemprop="position" content="%3$s" />';
		$link .= '</span>';
		$position = 0;
		$home_link = sprintf($link, $home_url, $text['home'], 1);

		if (is_home() || is_front_page()) {
			if ($show_on_home) echo $wrap_before . $home_link . $wrap_after;
		} else {
			echo $wrap_before;
			if ($show_home_link) {
				$position += 1;
				echo $home_link;
			}

			if (is_category()) {
				// Category Breadcrumb
				$parents = get_ancestors(get_query_var('cat'), 'category');
				foreach (array_reverse($parents) as $cat) {
					$position += 1;
					if ($position > 1) echo $sep;
					echo sprintf($link, get_category_link($cat), get_cat_name($cat), $position);
				}
				if (get_query_var('paged')) {
					$position += 1;
					echo $sep . sprintf($link, get_category_link(get_query_var('cat')), single_cat_title('', false), $position);
					echo $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
				} else {
					if ($show_current) {
						if ($position >= 1) echo $sep;
						echo $before . single_cat_title('', false) . $after;
					} elseif ($show_last_sep) echo $sep;
				}
			} elseif (is_single() && !is_attachment()) {
				// Single Post Breadcrumb
				if (get_post_type() != 'post') {
					$post_type = get_post_type_object(get_post_type());
					$position += 1;
					if ($position > 1) echo $sep;
					echo sprintf($link, get_post_type_archive_link($post_type->name), $post_type->labels->name, $position);
					if ($show_current) echo $sep . $before . get_the_title() . $after;
				} else {
					$cat = get_the_category();
					if ($cat) {
						$cat = $cat[0];
						$parents = get_ancestors($cat->term_id, 'category');
						$parents = array_reverse($parents);
						foreach ($parents as $cat_id) {
							$position += 1;
							echo $sep . sprintf($link, get_category_link($cat_id), get_cat_name($cat_id), $position);
						}
					}
					if ($show_current) echo $sep . $before . get_the_title() . $after;
				}
			} elseif (is_page() && !$post->post_parent) {
				// Single Page Breadcrumb
				if ($show_current) echo $sep . $before . get_the_title() . $after;
			} elseif (is_page() && $post->post_parent) {
				// Page with Parent Breadcrumb
				$parents = get_post_ancestors($post->ID);
				foreach (array_reverse($parents) as $page_id) {
					$position += 1;
					if ($position > 1) echo $sep;
					echo sprintf($link, get_page_link($page_id), get_the_title($page_id), $position);
				}
				if ($show_current) echo $sep . $before . get_the_title() . $after;
			} elseif (is_tag()) {
				// Tag Archive Breadcrumb
				if ($show_current) echo $sep . $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
			} elseif (is_author()) {
				// Author Archive Breadcrumb
				$author = get_userdata(get_query_var('author'));
				if ($show_current) echo $sep . $before . sprintf($text['author'], $author->display_name) . $after;
			} elseif (is_404()) {
				// 404 Page Breadcrumb
				if ($show_current) echo $sep . $before . $text['404'] . $after;
			} elseif (is_search()) {
				// Search Results Breadcrumb
				if ($show_current) echo $sep . $before . sprintf($text['search'], get_search_query()) . $after;
			} elseif (is_year()) {
				// Year Archive Breadcrumb
				if ($show_current) echo $sep . $before . get_the_time('Y') . $after;
			} elseif (is_tax()) {
				$taxonomy = get_queried_object();
				$position += 1;

				// Get the taxonomy hierarchy if it's hierarchical
				if ($taxonomy->parent) {
					$parents = get_ancestors($taxonomy->term_id, $taxonomy->taxonomy);
					foreach (array_reverse($parents) as $term_id) {
						echo $sep . sprintf($link, get_term_link($term_id, $taxonomy->taxonomy), get_term($term_id)->name, ++$position);
					}
				}

				// Display current taxonomy term
				if ($show_current) {
					echo $sep . $before . $taxonomy->name . $after;
				} elseif ($show_last_sep) {
					echo $sep;
				}
			} elseif (is_month()) {
				// Month Archive Breadcrumb
				$position += 1;
				echo $sep . sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'), $position);
				if ($show_current) echo $sep . $before . get_the_time('F') . $after;
			} elseif (is_day()) {
				// Day Archive Breadcrumb
				$position += 1;
				echo $sep . sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'), $position);
				$position += 1;
				echo $sep . sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'), $position);
				if ($show_current) echo $sep . $before . get_the_time('d') . $after;
			}

			echo $wrap_after;
		}
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

		//woocoomerce page
		if (function_exists('is_woocoomerce') && is_woocommerce() || function_exists('is_shop') && is_shop()):
			if (apply_filters('woocommerce_show_page_title', true)):
				$title = woocommerce_page_title(false);
			endif;

		// Default Latest Posts page
		elseif (is_home() && !is_singular('page')) :
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
		<a href="javascript:history.go(-1)" class="lp-back-btn"><span><?php esc_html_e('Back', 'lptheme') ?></span></a>
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
 * Display entry meta
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('lp_entry_meta')) {
	function lp_entry_meta()
	{
		if (apply_filters('lp_entry_meta', false)) {
		?>
			<footer class="entry-meta">
				<?php if (is_user_logged_in()): ?>
					<p><?php edit_post_link(esc_html__('Edit', 'lptheme'), '<span class="edit-link">', '</span>'); ?></p>
				<?php endif; ?>
			</footer>
		<?php
		}
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Display pagination of posts.
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_paging_nav')) {
	function lp_paging_nav($max_num_pages = false)
	{

		$prev_icon = 'lp-pagination__arrow prev';
		$next_icon = 'lp-pagination__arrow next';

		if ($max_num_pages === false) {
			global $wp_query;
			$max_num_pages = $wp_query->max_num_pages;
		}

		$big = 999999999;
		$links = paginate_links(array(
			'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'format'    => '?paged=%#%',
			'current'   => max(1, get_query_var('paged')),
			'total'     => $max_num_pages,
			'prev_next' => true,
			'prev_text' => '<i class="' . $prev_icon . '">←</i>',
			'next_text' => '<i class="' . $next_icon . '">→</i>',
			'end_size'  => 1,
			'mid_size'  => 2,
			'type'      => 'default',
		));

		if (!empty($links)): ?>
			<div class="lp-pagination">
				<?php echo wp_kses_post($links); ?>
			</div>
	<?php endif;
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Modified dates
 * ------------------------------------------------------------------------------------------------
 */

function true_russian_date_forms($the_date = '')
{
	if (substr_count($the_date, '---') > 0) {
		return str_replace('---', '', $the_date);
	}
	$replacements = array(
		"Январь" => "января",
		"Февраль" => "февраля",
		"Март" => "марта",
		"Апрель" => "апреля",
		"Май" => "мая",
		"Июнь" => "июня",
		"Июль" => "июля",
		"Август" => "августа",
		"Сентябрь" => "сентября",
		"Октябрь" => "октября",
		"Ноябрь" => "ноября",
		"Декабрь" => "декабря"
	);
	return strtr($the_date, $replacements);
}
add_filter('the_time', 'true_russian_date_forms');
add_filter('get_the_time', 'true_russian_date_forms');
add_filter('the_date', 'true_russian_date_forms');
add_filter('get_the_date', 'true_russian_date_forms');
add_filter('the_modified_time', 'true_russian_date_forms');
add_filter('get_the_modified_date', 'true_russian_date_forms');
add_filter('get_post_time', 'true_russian_date_forms');
add_filter('get_comment_date', 'true_russian_date_forms');
add_filter('get_date', 'true_russian_date_forms');


/*-----------------------------------------------------------------------------------*/
/* Social Share Buttons */
/*-----------------------------------------------------------------------------------*/

// posts share links
if (! function_exists('lp_share_links')) {
	function lp_share_links()
	{

		$page_url = get_permalink();
		$page_title = get_the_title();

		$links = array(
			'facebook' => array(
				'url'  => 'https://www.facebook.com/sharer/sharer.php?u=' . $page_url . '&t=' . $page_title,
				'icon' => '<svg class="lp-svgsprite fb"><use xlink:href="' . LP_IMAGES . '/sprite.svg#facebook" /></svg>',
			),
			'twitter' => array(
				'url'  => 'https://twitter.com/intent/tweet?text=' . $page_title . '&url=' . $page_url,
				'icon' => '<svg class="lp-svgsprite twitter"><use xlink:href="' . LP_IMAGES . '/sprite.svg#twitter" /></svg>',
			),
			'linkedin' => array(
				'url'  => 'https://www.linkedin.com/shareArticle?url=' . $page_url,
				'icon' => '<svg class="lp-svgsprite linkedin"><use xlink:href="' . LP_IMAGES . '/sprite.svg#linkedin" /></svg>',
			),
			'vk' => array(
				'url'  => 'http://vk.com/share.php?url=' . $page_url,
				'icon' => '<svg class="lp-svgsprite vk"><use xlink:href="' . LP_IMAGES . '/sprite.svg#vk" /></svg>',
			),
			'telegram' => array(
				'url'  => 'https://t.me/share/url?url=' . $page_url . '&text=' . $page_title,
				'icon' => '<svg class="lp-svgsprite telegram"><use xlink:href="' . LP_IMAGES . '/sprite.svg#telegram" /></svg>',
			),
			'instagram' => array(
				'url'  => 'https://instagram.com',
				'icon' => '<svg class="lp-svgsprite instagram"><use xlink:href="' . LP_IMAGES . '/sprite.svg#instagram" /></svg>',
			),
			'email' => array(
				'url'  => 'mailto:?subject=I%20wanted%20to%20share%20this%20post%20with%20you&body=' . $page_title . ' - ' . $page_url,
				'icon' => '<svg class="lp-svgsprite email"><use xlink:href="' . LP_IMAGES . '/sprite.svg#email" /></svg>',
			),

			// 'whatsapp' => array(
			// 	'url'  => 'https://api.whatsapp.com/send?text='. $page_url,
			// 	'icon' => '<svg class="lp-svgsprite whatsapp"><use xlink:href="'. LP_IMAGES . '/sprite.svg#whatsapp" /></svg>',
			// ),
			// 'reddit' => array(
			// 	'url'  => 'http://reddit.com/submit?url='. $page_url,
			// 	'icon' => '<svg class="lp-svgsprite reddit"><use xlink:href="'. LP_IMAGES . '/sprite.svg#reddit" /></svg>',
			// ),

		);

		$html = '<div class="share-container">
	<button class="share-button">
		<span class="share-icon"></span>
		<span class="widget-close"></span>
	</button>
	<div class="share-container__icons"><ul class="social-sharing-links">';

		foreach ($links as $key => $link) {
			$html .= sprintf(
				'<li><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a></li>',
				esc_url($link['url']),
				$link['icon']
			);
		}

		$html .= '</ul></div></div>';
		return $html;
	}
}


// website share links
if (! function_exists('lp_website_share_links')) {
	function lp_website_share_links()
	{

		$page_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$page_title = get_the_title();

		$links = array(
			'facebook' => array(
				'url'  => 'https://www.facebook.com/sharer/sharer.php?u=' . $page_url . '&t=' . $page_title,
				'icon' => '<svg class="lp-svgsprite fb"><use xlink:href="' . LP_IMAGES . '/sprite.svg#facebook" /></svg>',
			),
			'twitter' => array(
				'url'  => 'https://twitter.com/intent/tweet?text=' . $page_title . '&url=' . $page_url,
				'icon' => '<svg class="lp-svgsprite twitter"><use xlink:href="' . LP_IMAGES . '/sprite.svg#twitter" /></svg>',
			),
			'linkedin' => array(
				'url'  => 'https://www.linkedin.com/shareArticle?url=' . $page_url,
				'icon' => '<svg class="lp-svgsprite linkedin"><use xlink:href="' . LP_IMAGES . '/sprite.svg#linkedin" /></svg>',
			),
			'vk' => array(
				'url'  => 'http://vk.com/share.php?url=' . $page_url,
				'icon' => '<svg class="lp-svgsprite vk"><use xlink:href="' . LP_IMAGES . '/sprite.svg#vk" /></svg>',
			),
			'telegram' => array(
				'url'  => 'https://t.me/share/url?url=' . $page_url . '&text=' . $page_title,
				'icon' => '<svg class="lp-svgsprite telegram"><use xlink:href="' . LP_IMAGES . '/sprite.svg#telegram" /></svg>',
			),
			'instagram' => array(
				'url'  => 'https://instagram.com',
				'icon' => '<svg class="lp-svgsprite instagram"><use xlink:href="' . LP_IMAGES . '/sprite.svg#instagram" /></svg>',
			),
			'email' => array(
				'url'  => 'mailto:?subject=I%20wanted%20to%20share%20this%20post%20with%20you&body=' . $page_title . ' - ' . $page_url,
				'icon' => '<svg class="lp-svgsprite email"><use xlink:href="' . LP_IMAGES . '/sprite.svg#email" /></svg>',
			),

			// 'whatsapp' => array(
			// 	'url'  => 'https://api.whatsapp.com/send?text='. $page_url,
			// 	'icon' => '<svg class="lp-svgsprite whatsapp"><use xlink:href="'. LP_IMAGES . '/sprite.svg#whatsapp" /></svg>',
			// ),
			// 'reddit' => array(
			// 	'url'  => 'http://reddit.com/submit?url='. $page_url,
			// 	'icon' => '<svg class="lp-svgsprite reddit"><use xlink:href="'. LP_IMAGES . '/sprite.svg#reddit" /></svg>',
			// ),

		);

		$html = '<div class="share-container">
	<button class="share-button">
		<span class="share-icon"></span>
		<span class="widget-close"></span>
	</button>
	<div class="share-container__icons"><ul class="social-sharing-links">';

		foreach ($links as $key => $link) {
			$html .= sprintf(
				'<li><a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a></li>',
				esc_url($link['url']),
				$link['icon']
			);
		}

		$html .= '</ul></div></div>';
		return $html;
	}
}


/*-----------------------------------------------------------------------------------*/
/* Post Likes */
/*-----------------------------------------------------------------------------------*/

// add_action('admin_head', 'wp_pageviews_display_pageviews_style');
// add_action('manage_posts_custom_column', 'wp_pageviews_display_pageviews_row', 10, 2);
// add_filter('manage_pages_columns', 'wp_pageviews_display_pageviews');
// add_filter('manage_posts_columns', 'wp_pageviews_display_pageviews');

function wp_pageviews_display_pageviews_style()
{ ?>
	<style type="text/css">
		.column-pv {
			width: 60px;
		}
	</style>
<?php
}

function wp_pageviews_display_pageviews($columns)
{
	$columns['pv'] = __('Like');

	return $columns;
}

function wp_pageviews_display_pageviews_row($column_name, $post_id)
{
	if ($column_name != 'pv') return;
	$pv = get_post_meta($post_id, 'my-post-likes', true);
	echo $pv ? $pv : 0;
}

function update_like_count()
{

	$post_id = intval($_POST['post_id']);

	if (filter_var($post_id, FILTER_VALIDATE_INT)) {

		$article = get_post($post_id);
		$output_count = 0;

		if (!is_null($article)) {
			$count = get_post_meta($post_id, 'my-post-likes', true);
			if ($count == '') {
				update_post_meta($post_id, 'my-post-likes', '1');
				$output_count = 1;
			} else {
				$n = intval($count);
				$n++;
				update_post_meta($post_id, 'my-post-likes', $n);
				$output_count = $n;
			}
		}
	}

	$output = $output_count;
	echo json_encode($output);
	exit();
}

add_action('wp_ajax_my_update_likes', 'update_like_count');
add_action('wp_ajax_nopriv_my_update_likes', 'update_like_count');

function remove_likes()
{
	$post_id = intval($_POST['post_id']);
	if (filter_var($post_id, FILTER_VALIDATE_INT)) {
		$article = get_post($post_id);
		if (!is_null($article)) {
			$count = get_post_meta($post_id, 'my-post-likes', true);
			$n = intval($count);
			$n--;
			update_post_meta($post_id, 'my-post-likes', $n);
			$output_count = $n;
		}
	}

	$output = $output_count;
	echo json_encode($output);
	exit();
}
add_action('wp_ajax_my_remove_likes', 'remove_likes');
add_action('wp_ajax_nopriv_my_remove_likes', 'remove_likes');

function display_post_likes($post_id = null)
{
	$value = '';
	if (is_null($post_id)) {
		global $post;
		$value = get_post_meta($post->ID, 'my-post-likes', true);
	} else {
		$value = get_post_meta($post_id, 'my-post-likes', true);
	}

	echo $value;
}
