<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * SEO meta tags, Open Graph, Twitter Cards, and robots meta for WordPress.
 * ------------------------------------------------------------------------------------------------
 */

class LP_SEO_Tags
{
	static function init()
	{
		// force WP document_title function to run
		add_filter('pre_get_document_title', [__CLASS__, 'lp_meta_title'], 1);
		add_action('wp_head', [__CLASS__, 'lp_meta_description'], 1);
		add_action('wp_head', [__CLASS__, 'lp_og_meta'], 1); // Open Graph, twitter данные

		// WP 5.7+
		add_filter('wp_robots', [__CLASS__, 'wp_robots_callback'], 11);
	}

	/**
	 * Enhanced meta title generation with pattern support
	 */
	static function lp_meta_title($title = '')
	{
		global $post;

		// support for `pre_get_document_title` hook.
		if ($title)
			return $title;

		static $cache;
		if ($cache) return $cache;

		// Check for custom pattern first
		$pattern_title = self::get_pattern_title();
		if ($pattern_title) {
			return $cache = $pattern_title;
		}

		// Original logic fallback
		$l10n = apply_filters('LP_meta_title_l10n', [
			'404'     => 'Ошибка 404: такой страницы не существует',
			'search'  => 'Результаты поиска по запросу: %s',
			'compage' => 'Комментарии %s',
			'author'  => 'Статьи автора: %s',
			'archive' => 'Архив за',
			'paged'   => 'Страница %d',
		]);

		$parts = [
			'prev'  => '',
			'title' => '',
			'page'  => '',
			'after' => '',
		];

		// 404
		if (is_404()) {
			$parts['title'] = $l10n['404'];
		}
		// search
		elseif (is_search()) {
			$parts['title'] = sprintf($l10n['search'], get_query_var('s'));
		}
		// front_page
		elseif (is_front_page()) {
			if (is_page() && $parts['title'] = get_post_meta($post->ID, 'lp_meta_title', 1)) {
				// $parts['title'] defined
			} else {
				$parts['title'] = get_bloginfo('name', 'display');
				$parts['after'] = '{{description}}';
			}
		}
		// singular
		elseif (is_singular() || (is_home() && ! is_front_page()) || (is_page() && ! is_front_page())) {
			$parts['title'] = get_post_meta($post->ID, 'lp_meta_title', 1);
			if (! $parts['title']) {
				$parts['title'] = apply_filters('LP_meta_title_singular', '', $post);
			}

			if (! $parts['title']) {
				$parts['title'] = single_post_title('', 0);
			}

			if ($cpage = get_query_var('cpage')) {
				$parts['prev'] = sprintf($l10n['compage'], $cpage);
			}
		}
		// post_type_archive
		elseif (is_post_type_archive()) {
			$parts['title'] = post_type_archive_title('', 0);
			$parts['after'] = '{{blog_name}}';
		}
		// taxonomy
		elseif (is_category() || is_tag() || is_tax()) {
			$term = get_queried_object();
			$parts['title'] = $term ? get_term_meta($term->term_id, 'title', 1) : '';

			if (! $parts['title']) {
				$parts['title'] = single_term_title('', 0);
				if (is_tax()) {
					$parts['prev'] = get_taxonomy($term->taxonomy)->labels->name;
				}
			}
			$parts['after'] = '{{blog_name}}';
		}
		// author posts archive
		elseif (is_author()) {
			$parts['title'] = sprintf($l10n['author'], get_queried_object()->display_name);
			$parts['after'] = '{{blog_name}}';
		}
		// date archive
		elseif ((get_locale() === 'ru_RU') && (is_day() || is_month() || is_year())) {
			$rus_month = ['', 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];
			$rus_month2 = ['', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
			$year = get_query_var('year');
			$monthnum = get_query_var('monthnum');
			$day = get_query_var('day');

			if (is_year()) $dat = "$year год";
			elseif (is_month()) $dat = "{$rus_month[$monthnum]} $year года";
			elseif (is_day()) $dat = "$day {$rus_month2[$monthnum]} $year года";

			$parts['title'] = sprintf($l10n['archive'], $dat);
			$parts['after'] = '{{blog_name}}';
		}
		// other archives
		else {
			$parts['title'] = get_the_archive_title();
			$parts['after'] = '{{blog_name}}';
		}

		// pagination
		$pagenum = get_query_var('paged') ?: get_query_var('page');
		if ($pagenum && ! is_404()) {
			$parts['page'] = sprintf($l10n['paged'], $pagenum);
		}

		$parts = apply_filters('LP_meta_title_parts', $parts, $l10n);
		$parts = apply_filters('document_title_parts', $parts);

		// handle placeholders
		if ('{{blog_name}}' === $parts['after']) {
			$parts['after'] = get_bloginfo('name', 'display');
		} elseif ('{{description}}' === $parts['after']) {
			$parts['after'] = get_bloginfo('description', 'display');
		}

		$sep = apply_filters('document_title_separator', ' – ');
		$title = implode(' ' . trim($sep) . ' ', array_filter($parts));
		$title = esc_html($title);
		$title = capital_P_dangit($title);

		return $cache = $title;
	}

	/**
	 * Enhanced meta description with pattern support
	 */
	static function lp_meta_description()
	{
		global $post;

		$echo_result = (func_num_args() === 1);
		static $cache = null;

		if (isset($cache)) {
			if ($echo_result) echo $cache;
			return $cache;
		}

		// Check for custom pattern first
		$pattern_description = self::get_pattern_description();
		if ($pattern_description) {
			$cache = sprintf("<meta name=\"description\" content=\"%s\">\n", esc_attr(trim($pattern_description)));
			if ($echo_result) echo $cache;
			return $cache;
		}

		// Original logic fallback
		$desc = '';
		$need_cut = true;

		// front
		if (is_front_page()) {
			if (is_page()) {
				$desc = get_post_meta($post->ID, 'lp_meta_description', true);
				$need_cut = false;
			}
			if (! $desc) {
				$desc = apply_filters('home_meta_description', get_bloginfo('description', 'display'));
			}
		}
		// any post
		elseif (is_singular()) {
			if ($desc = get_post_meta($post->ID, 'lp_meta_description', true)) {
				$need_cut = false;
			}
			if (! $desc) {
				$desc = $post->post_excerpt ?: $post->post_content;
			}
			$desc = trim(strip_tags($desc));
		}
		// any term (taxonomy element)
		elseif (($term = get_queried_object()) && ! empty($term->term_id)) {
			$desc = get_term_meta($term->term_id, 'lp_meta_description', true);
			if (! $desc)
				$desc = get_term_meta($term->term_id, 'description', true);
			$need_cut = false;
			if (! $desc && $term->description) {
				$desc = strip_tags($term->description);
				$need_cut = true;
			}
		}

		$desc = str_replace(["\n", "\r"], ' ', $desc);
		$desc = preg_replace('~\[[^\]]+\](?!\()~', '', $desc);
		$desc = apply_filters('LP_meta_description', $desc);
		$need_cut = apply_filters('LP_meta_description__need_cut', $need_cut);

		if ($need_cut) {
			$maxchar = apply_filters('LP_meta_description__maxchar', 160);
			$char = mb_strlen($desc);
			if ($char > $maxchar) {
				$desc = mb_substr($desc, 0, $maxchar);
				$words = explode(' ', $desc);
				$maxwords = count($words) - 1;
				$desc = implode(' ', array_slice($words, 0, $maxwords)) . ' ...';
			}
		}

		$desc = preg_replace('/\s+/s', ' ', $desc);
		$cache = $desc ? sprintf("<meta name=\"description\" content=\"%s\">\n", esc_attr(trim($desc))) : '';
		if ($echo_result) echo $cache;

		return $cache;
	}

	/**
	 * Get pattern-based title
	 */
	private static function get_pattern_title()
	{
		$pattern = self::get_matching_pattern('title');
		if (!$pattern || empty($pattern['title_pattern'])) {
			return false;
		}

		return self::process_pattern($pattern['title_pattern']);
	}

	/**
	 * Get pattern-based description
	 */
	private static function get_pattern_description()
	{
		$pattern = self::get_matching_pattern('description');
		if (!$pattern || empty($pattern['description_pattern'])) {
			return false;
		}

		return self::process_pattern($pattern['description_pattern']);
	}

	/**
	 * Find matching pattern for current context (enhanced with template support)
	 */
	private static function get_matching_pattern($type = 'title')
	{
		$patterns = get_option('lp_seo_patterns', []);
		if (empty($patterns)) {
			return false;
		}

		$current_post_type = get_post_type();
		$current_locale = self::get_current_locale();
		$current_context = self::get_current_context();
		$current_template = self::get_current_template();

		// Sort patterns by specificity - templates first, then post types
		$template_patterns = [];
		$post_type_patterns = [];

		foreach ($patterns as $pattern) {
			if (!isset($pattern['enabled']) || !$pattern['enabled']) {
				continue;
			}

			$pattern_type = isset($pattern['pattern_type']) ? $pattern['pattern_type'] : 'post_type';

			if ($pattern_type === 'template') {
				$template_patterns[] = $pattern;
			} else {
				$post_type_patterns[] = $pattern;
			}
		}

		// Check template patterns first (more specific)
		foreach ($template_patterns as $pattern) {
			$template_match = (isset($pattern['template']) && ($pattern['template'] === 'all' || $pattern['template'] === $current_template));
			$locale_match = (!isset($pattern['locale']) || $pattern['locale'] === 'all' || $pattern['locale'] === $current_locale);

			if ($template_match && $locale_match) {
				return $pattern;
			}
		}

		// Then check post type patterns
		foreach ($post_type_patterns as $pattern) {
			$post_type_match = (!isset($pattern['post_type']) || $pattern['post_type'] === 'all' || $pattern['post_type'] === $current_post_type);
			$context_match = (!isset($pattern['context']) || $pattern['context'] === $current_context);
			$locale_match = (!isset($pattern['locale']) || $pattern['locale'] === 'all' || $pattern['locale'] === $current_locale);

			if ($post_type_match && $context_match && $locale_match) {
				return $pattern;
			}
		}

		return false;
	}

	/**
	 * Get current template name
	 */
	private static function get_current_template()
	{
		global $template;

		if ($template) {
			$template_name = basename($template, '.php');
			return $template_name;
		}

		// Fallback - determine template based on context
		if (is_front_page()) {
			return 'front-page';
		} elseif (is_home()) {
			return 'home';
		} elseif (is_404()) {
			return '404';
		} elseif (is_search()) {
			return 'search';
		} elseif (is_page()) {
			global $post;
			if ($post) {
				$page_template = get_page_template_slug($post->ID);
				if ($page_template) {
					return basename($page_template, '.php');
				}
			}
			return 'page';
		} elseif (is_single()) {
			$post_type = get_post_type();
			return 'single-' . $post_type;
		} elseif (is_category()) {
			$category = get_queried_object();
			return 'category-' . ($category ? $category->slug : '');
		} elseif (is_tag()) {
			$tag = get_queried_object();
			return 'tag-' . ($tag ? $tag->slug : '');
		} elseif (is_tax()) {
			$term = get_queried_object();
			return 'taxonomy-' . ($term ? $term->taxonomy : '');
		} elseif (is_author()) {
			return 'author';
		} elseif (is_archive()) {
			if (is_post_type_archive()) {
				$post_type = get_post_type();
				return 'archive-' . $post_type;
			}
			return 'archive';
		}

		return 'index';
	}

	/**
	 * Get current context (single or archive)
	 */
	private static function get_current_context()
	{
		if (is_singular()) {
			return 'single';
		} elseif (is_archive() || is_home()) {
			return 'archive';
		}
		return 'single'; // default
	}

	/**
	 * Get current locale
	 */
	private static function get_current_locale()
	{
		// Polylang support
		if (function_exists('pll_current_language')) {
			return pll_current_language();
		}

		// WPML support
		if (function_exists('icl_get_current_language')) {
			return icl_get_current_language();
		}

		// Default WordPress locale
		return get_locale();
	}

	/**
	 * Process pattern and replace placeholders
	 */
	private static function process_pattern($pattern)
	{
		global $post;

		// Basic placeholders
		$replacements = [
			'[site_name]' => get_bloginfo('name'),
			'[site_description]' => get_bloginfo('description'),
			'[date]' => get_the_date(),
			'[year]' => get_the_date('Y'),
			'[month]' => get_the_date('F'),
			'[day]' => get_the_date('j'),
			'[template_name]' => self::get_current_template(),
		];

		// Context-specific placeholders
		if (is_singular()) {
			$replacements['[post_title]'] = get_the_title();
			$replacements['[post_excerpt]'] = get_the_excerpt();
			$replacements['[author_name]'] = get_the_author_meta('display_name');

			// Primary term
			$primary_term = self::get_primary_term();
			$replacements['[term_name]'] = $primary_term ? $primary_term->name : '';

			// Custom fields support - [custom_field:field_name]
			$pattern = preg_replace_callback('/\[custom_field:([^\]]+)\]/', function ($matches) {
				$field_name = $matches[1];
				return get_post_meta(get_the_ID(), $field_name, true) ?: '';
			}, $pattern);

			// ACF fields support - [acf field="field_name"]
			$pattern = preg_replace_callback('/\[acf field="([^"]+)"\]/', function ($matches) {
				$field_name = $matches[1];

				// Try ACF function first if available
				if (function_exists('get_field')) {
					$value = get_field($field_name, get_the_ID());
					if (is_array($value)) {
						// Handle array values (like select fields with multiple values)
						return implode(', ', array_filter($value));
					}
					return $value ?: '';
				}

				// Fallback to get_post_meta if ACF not available
				return get_post_meta(get_the_ID(), $field_name, true) ?: '';
			}, $pattern);
		} elseif (is_archive()) {
			$replacements['[archive_title]'] = get_the_archive_title();

			if (is_category() || is_tag() || is_tax()) {
				$term = get_queried_object();
				$replacements['[term_name]'] = $term ? $term->name : '';
				$replacements['[term_description]'] = $term ? $term->description : '';

				// ACF fields for terms - [acf field="field_name"]
				$pattern = preg_replace_callback('/\[acf field="([^"]+)"\]/', function ($matches) use ($term) {
					$field_name = $matches[1];

					if (function_exists('get_field') && $term) {
						$value = get_field($field_name, $term);
						if (is_array($value)) {
							return implode(', ', array_filter($value));
						}
						return $value ?: '';
					}

					// Fallback to get_term_meta
					return $term ? get_term_meta($term->term_id, $field_name, true) ?: '' : '';
				}, $pattern);
			}

			if (is_author()) {
				$author = get_queried_object();
				$replacements['[author_name]'] = $author ? $author->display_name : '';

				// ACF fields for users - [acf field="field_name"]
				$pattern = preg_replace_callback('/\[acf field="([^"]+)"\]/', function ($matches) use ($author) {
					$field_name = $matches[1];

					if (function_exists('get_field') && $author) {
						$value = get_field($field_name, 'user_' . $author->ID);
						if (is_array($value)) {
							return implode(', ', array_filter($value));
						}
						return $value ?: '';
					}

					// Fallback to get_user_meta
					return $author ? get_user_meta($author->ID, $field_name, true) ?: '' : '';
				}, $pattern);
			}

			if (is_post_type_archive()) {
				$post_type_obj = get_queried_object();
				$replacements['[post_type_name]'] = $post_type_obj ? $post_type_obj->label : '';
			}
		}

		// Replace placeholders
		foreach ($replacements as $placeholder => $value) {
			$pattern = str_replace($placeholder, $value, $pattern);
		}

		// Clean up any remaining unreplaced placeholders
		$pattern = preg_replace('/\[[^\]]+\]/', '', $pattern);

		// Clean up extra spaces
		$pattern = preg_replace('/\s+/', ' ', $pattern);
		$pattern = trim($pattern);

		return $pattern;
	}

	/**
	 * Get primary term for the post
	 */
	private static function get_primary_term()
	{
		global $post;

		if (!$post) {
			return null;
		}

		$taxonomies = get_object_taxonomies($post->post_type);

		foreach ($taxonomies as $taxonomy) {
			$terms = get_the_terms($post, $taxonomy);
			if ($terms && !is_wp_error($terms)) {
				return array_shift($terms);
			}
		}

		return null;
	}

	/**
	 * Open Graph, twitter data in `<head>`.
	 */
	static function lp_og_meta()
	{
		$obj = get_queried_object();
		if (isset($obj->post_type))   $post = $obj;
		elseif (isset($obj->term_id)) $term = $obj;
		$is_post = isset($post);
		$is_term = isset($term);
		$title = self::lp_meta_title();
		$desc = preg_replace('/^.+content="([^"]*)".*$/s', '$1', self::lp_meta_description());

		// Open Graph
		$els = [];
		$els['og:locale']      = get_locale();
		$els['og:site_name']   = get_bloginfo('name');
		$els['og:title']       = $title;
		$els['og:description'] = $desc;
		$els['og:type']        = is_singular() ? 'article' : 'object';

		// og:url
		if ('url') {
			if ($is_post) $url = get_permalink($post);
			if ($is_term) $url = get_term_link($term);
			if (! empty($url)) {
				$els['og:url'] = $url;
				// relative (not allowed)
				if ('/' === $url[0]) {
					// without protocol only: //domain.com/path
					if (substr($url, 0, 2) === '//') {
						$els['og:url'] = set_url_scheme($url);
					}
					// without domain
					else {
						$parts = wp_parse_url($url);
						$els['og:url'] = home_url($parts['path']) . (isset($parts['query']) ? "?{$parts['query']}" : '');
					}
				}
			}
		}

		if (apply_filters('LP_og_meta_show_article_section', true) && is_singular()) {
			$post_taxname = get_object_taxonomies($post->post_type);
			if ($post_taxname) {
				$post_terms = get_the_terms($post, reset($post_taxname));
				if ($post_terms && $post_term = array_shift($post_terms)) {
					$els['article:section'] = $post_term->name;
				}
			}
		}

		// og:image
		if ('image') {
			$image = apply_filters('pre_LP_og_meta_image', null);

			if (!$image) {
				$attach_id_from_text__fn = static function ($text) {
					if (
						preg_match('/<img +src *= *[\'"]([^\'"]+)[\'"]/', $text, $mm)
						&&
						('/' === $mm[1][0] || strpos($mm[1], $_SERVER['HTTP_HOST']))
					) {
						$name = basename($mm[1]);
						$name = preg_replace('~-[0-9]+x[0-9]+(?=\..{2,6})~', '', $name); // удалим размер (-80x80)
						$name = preg_replace('~\.[^.]+$~', '', $name);                   // удалим расширение
						$name = sanitize_title(sanitize_file_name($name));

						global $wpdb;
						$attach_id = $wpdb->get_var($wpdb->prepare(
							"SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = 'attachment'",
							$name
						));

						return (int) $attach_id;
					}
					return 0;
				};

				if ($is_post) {
					$image = get_post_thumbnail_id($post);

					if (!$image) {
						if (apply_filters('LP_og_meta_thumb_id_find_in_content', true)) {
							$image = $attach_id_from_text__fn($post->post_content);

							// первое вложение поста
							if (!$image) {
								$attach = get_children([
									'numberposts'    => 1,
									'post_mime_type' => 'image',
									'post_type'      => 'attachment',
									'post_parent'    => $post->ID,
								]);

								if ($attach && $attach = array_shift($attach)) {
									$image = $attach->ID;
								}
							}
						}
					}
				} elseif ($is_term) {
					$image = get_term_meta($term->term_id, '_thumbnail_id', 1);
					if (! $image)
						$image = $attach_id_from_text__fn($term->description);
				}

				$image = apply_filters('LP_og_meta_image', $image);
				$image = apply_filters('LP_og_meta_thumb_id', $image); // backcompat
			}

			if ($image) {
				if (
					$image instanceof WP_Post
					||
					(is_numeric($image) && $image = get_post($image))
				) {
					// full size
					[
						$els['og:image[1]'],
						$els['og:image[1]:width'],
						$els['og:image[1]:height'],
						$els['og:image[1]:alt'],
						$els['og:image[1]:type']
					] = array_merge(
						array_slice(image_downsize($image->ID, 'full'), 0, 3),
						[$image->post_excerpt, $image->post_mime_type]
					);

					if (!$els['og:image[1]:alt']) {
						unset($els['og:image[1]:alt']);
					}
				} elseif (is_array($image)) {
					[
						$els['og:image[1]'],
						$els['og:image[1]:width'],
						$els['og:image[1]:height']
					] = $image;
				} else {
					$els['og:image[1]'] = $image;
				}
			}
		}

		// twitter
		$els['twitter:card'] = 'summary';
		$els['twitter:title'] = $els['og:title'];
		$els['twitter:description'] = $els['og:description'];
		if (! empty($els['og:image[1]'])) {
			$els['twitter:image'] = $els['og:image[1]'];
		}

		$els = apply_filters('LP_og_meta_elements_values', $els);
		$els = array_filter($els);
		ksort($els);

		// make <meta> tags
		$metas = [];
		foreach ($els as $key => $val) {
			// og:image[1] > og:image  ||  og:image[1]:width > og:image:width
			$fixed_key = preg_replace('/\[\d\]/', '', $key);

			if (0 === strpos($key, 'twitter:'))
				$metas[] = '<meta name="' . $fixed_key . '" content="' . esc_attr($val) . '">';
			else
				$metas[] = '<meta property="' . $fixed_key . '" content="' . esc_attr($val) . '">';
		}

		$metas = apply_filters('LP_og_meta_elements', $metas, $els);
		echo "\n\n" . implode("\n", $metas) . "\n\n";
	}

	/**
	 * Wrapper for WP Robots API introduced in WP 5.7+.
	 */
	static function wp_robots_callback($robots)
	{
		if (is_singular()) {
			$robots_str = get_post_meta(get_queried_object_id(), 'lp_robots', true);
		} elseif (is_tax() || is_category() || is_tag()) {
			$robots_str = get_term_meta(get_queried_object_id(), 'lp_robots', true);
		}

		if (! empty($robots_str)) {
			// split by spece or comma
			$robots_parts = preg_split('/(?<!:)[\s,]+/', $robots_str, -1, PREG_SPLIT_NO_EMPTY);

			foreach ($robots_parts as $directive) {
				// for max-snippet:2
				if (strpos($directive, ':')) {
					[$key, $value] = explode(':', $directive);
					$robots[$key] = $value;
				} else {
					$robots[$directive] = true;
				}
			}
		}

		if (! empty($robots['none']) || ! empty($robots['noindex'])) {
			unset($robots['max-image-preview']);
		}

		// close
		if (
			is_attachment() ||
			is_paged() ||
			is_post_type_archive()
		) {
			$robots['noindex'] = true;
			$robots['follow'] = true;
		}

		// close draft
		if (is_preview()) {
			$robots['none'] = true;
		}

		return $robots;
	}
}

LP_SEO_Tags::init();
