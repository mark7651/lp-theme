<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * seo functions
 * ------------------------------------------------------------------------------------------------
 */

// jQuery to footer ==========================================================================
function remove_head_scripts()
{
	remove_action('wp_head', 'wp_print_scripts');
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'wp_enqueue_scripts', 1);
	add_action('wp_footer', 'wp_print_scripts', 5);
	add_action('wp_footer', 'wp_enqueue_scripts', 5);
	add_action('wp_footer', 'wp_print_head_scripts', 5);
}
add_action('wp_enqueue_scripts', 'remove_head_scripts');

if (! function_exists('disable_wp_polyfill_script')) {
	function disable_wp_polyfill_script()
	{
		if (!is_admin()) {
			wp_deregister_script('wp-polyfill-inert');
			wp_deregister_script('wp-polyfill');
			wp_deregister_script('regenerator-runtime');
			wp_deregister_script('jquery-migrate');
		}
	}
	add_action('wp_enqueue_scripts', 'disable_wp_polyfill_script', 99);
}

// Remove WP embed script ==========================================================================
if (! function_exists('lp_stop_loading_wp_embed')) {
	function lp_stop_loading_wp_embed()
	{
		if (!is_admin()) {
			wp_deregister_script('wp-embed');
			wp_dequeue_script('wp-a11y');
		}
	}
	add_action('wp_footer', 'lp_stop_loading_wp_embed');
}

// Remove script version  ==========================================================================
function remove_script_version($src)
{
	$parts = explode('?ver', $src);
	return $parts[0];
}
if (!get_field('enable_cache', 'option')) {
	add_filter('script_loader_src', 'remove_script_version', 15, 1);
	add_filter('style_loader_src', 'remove_script_version', 15, 1);
}

/**
 * ------------------------------------------------------------------------------------------------
 * Disable styles
 * ------------------------------------------------------------------------------------------------
 */

function custom_wp_remove_global_css()
{
	remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
	remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
}
add_action('init', 'custom_wp_remove_global_css');

// disable other styles ==========================================================================
if (! function_exists('lp_remove_styles')) {
	function lp_remove_styles()
	{
		wp_dequeue_style('wp-block-library');
		wp_dequeue_style('wp-block-library-theme');
		wp_dequeue_style('wc-blocks-style');
		wp_dequeue_style('wc-block-vendors-style');
		wp_dequeue_style('classic-theme-styles');
		wp_dequeue_style('global-styles');
		wp_dequeue_style('classic-theme-styles');
		wp_dequeue_style('global-styles-inline');
		wp_dequeue_style('wordfenceAJAXcss');
		wp_dequeue_style('aioseop-toolbar-menu');
	}
	add_action('wp_enqueue_scripts', 'lp_remove_styles', 1000);
}

/**
 * ------------------------------------------------------------------------------------------------
 * disable heartbeat api
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_stop_heartbeat')) {
	function lp_stop_heartbeat()
	{
		if (!get_field('enable_heartbeat_api', 'option')) return;
		wp_deregister_script('heartbeat');
	}
	add_action('init', 'lp_stop_heartbeat', 1);
}

/**
 * ------------------------------------------------------------------------------------------------
 * clean header stuff
 * ------------------------------------------------------------------------------------------------
 */

if (! get_field('enable_rest_api', 'option')) {
	// Отключаем сам REST API
	add_filter('rest_enabled', '__return_false');
	// Отключаем фильтры REST API
	remove_action('xmlrpc_rsd_apis',            'rest_output_rsd');
	remove_action('wp_head',                    'rest_output_link_wp_head', 10, 0);
	remove_action('template_redirect',          'rest_output_link_header', 11, 0);
	remove_action('auth_cookie_malformed',      'rest_cookie_collect_status');
	remove_action('auth_cookie_expired',        'rest_cookie_collect_status');
	remove_action('auth_cookie_bad_username',   'rest_cookie_collect_status');
	remove_action('auth_cookie_bad_hash',       'rest_cookie_collect_status');
	remove_action('auth_cookie_valid',          'rest_cookie_collect_status');
	remove_filter('rest_authentication_errors', 'rest_cookie_check_errors', 100);

	// Отключаем события REST API
	remove_action('init',          'rest_api_init');
	remove_action('rest_api_init', 'rest_api_default_filters', 10, 1);
	remove_action('parse_request', 'rest_api_loaded');

	// Отключаем Embeds связанные с REST API
	remove_action('rest_api_init',          'wp_oembed_register_route');
	remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);
	remove_action('wp_head', 'wp_oembed_add_discovery_links');
	remove_action('wp_head', 'wp_oembed_add_host_js');
	remove_filter('the_content', 'wptexturize'); /* убираем авотдобавление параграфов */
	remove_action('wp_head', 'wp_resource_hints', 2); /* удаляем dns-prefetch */
}

function fb_disable_feed()
{
	wp_redirect(get_option('siteurl'));
}

add_action('do_feed', 'fb_disable_feed', 1);
add_action('do_feed_rdf', 'fb_disable_feed', 1);
add_action('do_feed_rss', 'fb_disable_feed', 1);
add_action('do_feed_rss2', 'fb_disable_feed', 1);
add_action('do_feed_atom', 'fb_disable_feed', 1);
add_action('do_feed_rss2_comments', 'fb_disable_feed', 1);
add_action('do_feed_atom_comments', 'fb_disable_feed', 1);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);

remove_filter('pre_term_description', 'wp_filter_kses');
remove_filter('pre_term_description', 'wp_kses_data');

remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');


// remove users from wp-sitemap ==========================================================================
add_filter('wp_sitemaps_add_provider', function ($provider, $name) {
	return ($name == 'users') ? false : $provider;
}, 10, 2);


/**
 * ------------------------------------------------------------------------------------------------
 * MINIFY HTML OUTPUT
 * ------------------------------------------------------------------------------------------------
 */

class WP_HTML_Compression
{
	/**
	 * @var bool Whether to compress inline CSS.
	 */
	protected $compress_css = true;

	/**
	 * @var bool Whether to compress inline JS.
	 */
	protected $compress_js = true;

	/**
	 * @var bool Whether to add an info comment after minification.
	 */
	protected $info_comment = true;

	/**
	 * @var bool Whether to remove HTML comments.
	 */
	protected $remove_comments = true;

	/**
	 * @var string The HTML content to minify.
	 */
	protected $html;

	/**
	 * Constructor.
	 *
	 * @param string $html HTML content to minify.
	 */
	public function __construct($html)
	{
		if (! empty($html)) {
			$this->parse_html($html);
		}
	}

	/**
	 * Magic method to return the minified HTML as a string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->html;
	}

	/**
	 * Parse and minify the HTML content.
	 *
	 * @param string $html HTML content.
	 */
	protected function parse_html($html)
	{
		$this->html = $this->minify_html($html);

		if ($this->info_comment) {
			$this->html .= "\n<!-- HTML minified by WP_HTML_Compression -->";
		}
	}

	/**
	 * Minify the HTML content.
	 *
	 * @param string $html HTML content.
	 * @return string Minified HTML.
	 */
	protected function minify_html($html)
	{
		// Don't minify if user is logged in or in admin area
		if (is_admin() || is_user_logged_in()) {
			return $html;
		}

		// Define regex patterns
		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)/si';
		preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

		$output = '';
		$overriding = false;
		$raw_tag = false;

		foreach ($matches as $token) {
			$tag = isset($token['tag']) ? strtolower($token['tag']) : null;
			$content = $token[0];
			$strip = false;

			if (is_null($tag)) {
				if (! empty($token['script'])) {
					$strip = $this->compress_js;
					if ($strip) {
						$content = $this->minify_js($content);
					}
				} elseif (! empty($token['style'])) {
					$strip = $this->compress_css;
					if ($strip) {
						$content = $this->minify_css($content);
					}
				} elseif ($content === '<!--wp-html-compression no compression-->') {
					$overriding = ! $overriding;
					continue;
				} elseif ($this->remove_comments && ! $overriding && $raw_tag !== 'textarea') {
					// Skip conditional comments and important comments
					$content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
				}
			} else {
				if (in_array($tag, ['pre', 'textarea', 'script'], true)) {
					$raw_tag = $tag;
				} elseif (in_array($tag, ['/pre', '/textarea', '/script'], true)) {
					$raw_tag = false;
				} else {
					$strip = (! $raw_tag && ! $overriding);
					if ($strip) {
						// Remove unnecessary attributes and spaces
						$content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
						$content = str_replace(' />', '/>', $content);
					}
				}
			}

			if ($strip && ! $raw_tag) {
				$content = $this->remove_whitespace($content);
			}

			$output .= $content;
		}

		return $output;
	}

	/**
	 * Minify inline CSS.
	 *
	 * @param string $css CSS content.
	 * @return string Minified CSS.
	 */
	protected function minify_css($css)
	{
		// Remove comments
		$css = preg_replace('/\/\*.*?\*\//s', '', $css);
		// Remove whitespace
		$css = preg_replace('/\s+/', ' ', $css);
		// Remove spaces around selectors, properties
		$css = preg_replace('/\s*([{}:;,])\s*/', '$1', $css);
		return $css;
	}

	/**
	 * Minify inline JS.
	 *
	 * @param string $js JS content.
	 * @return string Minified JS.
	 */
	protected function minify_js($js)
	{
		// Remove comments
		$js = preg_replace('/\/\/.*$/m', '', $js);
		$js = preg_replace('/\/\*.*?\*\//s', '', $js);
		// Remove whitespace
		$js = preg_replace('/\s+/', ' ', $js);
		// Remove spaces around operators
		$js = preg_replace('/\s*([=+\-{}();:,\[\]])\s*/', '$1', $js);
		return $js;
	}

	/**
	 * Remove unnecessary whitespace from content.
	 *
	 * @param string $str Content to process.
	 * @return string Processed content.
	 */
	protected function remove_whitespace($str)
	{
		$str = str_replace(["\t", "\n", "\r"], ' ', $str);
		$str = preg_replace('/\s+/', ' ', $str);
		return trim($str);
	}

	/**
	 * Start output buffering to capture and minify HTML.
	 */
	public static function start()
	{
		if (is_admin() || defined('DOING_AJAX') || defined('DOING_CRON')) {
			return;
		}

		ob_start([__CLASS__, 'output_callback']);
	}

	/**
	 * Callback for output buffering.
	 *
	 * @param string $buffer Output buffer content.
	 * @return string Minified output.
	 */
	public static function output_callback($buffer)
	{
		if (empty($buffer) || strpos($buffer, '<html') === false) {
			return $buffer;
		}

		$minifier = new self($buffer);
		return (string) $minifier;
	}
}

if (get_field('minify_html', 'option')) {
	add_action('template_redirect', ['WP_HTML_Compression', 'start'], 1);
}

/**
 * ------------------------------------------------------------------------------------------------
 * Automatically set the image Title, Alt-Text, Caption & Description upon upload
 * ------------------------------------------------------------------------------------------------
 */


function lp_set_image_meta_on_upload($attachment_id)
{
	if (! wp_attachment_is_image($attachment_id)) {
		return;
	}

	$attachment = get_post($attachment_id);
	if (! $attachment) {
		return;
	}

	$image_title = sanitize_text_field($attachment->post_title);
	$image_title = preg_replace('/\s*[-_\s]+\s*/', ' ', $image_title);
	$image_title = ucwords(strtolower($image_title));

	$image_meta = [
		'ID'           => $attachment_id,
		'post_title'   => $image_title,
		'post_excerpt' => $image_title,
		'post_content' => $image_title,
	];

	$result = wp_update_post($image_meta, true);
	if (is_wp_error($result)) {
		return;
	}

	update_post_meta($attachment_id, '_wp_attachment_image_alt', $image_title);
}

add_action('add_attachment', 'lp_set_image_meta_on_upload');

/**
 * ------------------------------------------------------------------------------------------------
 * remove uneeded pages
 * ------------------------------------------------------------------------------------------------
 */

function disable_uneeded_archives()
{
	if (is_date() || is_author() || is_attachment()) {
		header("Status: 404 Not Found");
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
		nocache_headers();
	}
}
add_action('template_redirect', 'disable_uneeded_archives');


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
	 * Open Graph, twitter data in `<head>`.
	 *
	 * @See Documentation: http://ogp.me/
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

		/**
		 * Allow to disable `article:section` property.
		 *
		 * @param bool $is_on
		 */

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

			/**
			 * Allow to change `og:image` `og:image:width` `og:image:height` values.
			 *
			 * @param int|string|array|WP_Post  $image_data  WP attachment ID or Image URL or Array [ image_url, width, height ].
			 */

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
						/**
						 * Allows to turn off the image search in post content.
						 *
						 * @param bool $is_on
						 */

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

				/**
				 * Allow to set `og:image` `og:image:width` `og:image:height` values if it's not.
				 *
				 * @param int|string|array|WP_Post  $image  WP attachment ID or Image URL or [ image_url, width, height ] array.
				 */
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

					// thumbnail size
					// [
					// 	$els['og:image[2]'],
					// 	$els['og:image[2]:width'],
					// 	$els['og:image[2]:height']
					// ] = array_slice( image_downsize( $image->ID, 'thumbnail' ), 0, 3 );
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

		/**
		 * Allows change values of og / twitter meta properties.
		 *
		 * @param array  $els
		 */

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

		/**
		 * Filter resulting properties. Allows to add or remove any og/twitter properties.
		 *
		 * @param array  $els
		 */
		$metas = apply_filters('LP_og_meta_elements', $metas, $els);

		echo "\n\n" . implode("\n", $metas) . "\n\n";
	}

	/**
	 * Generate string to show as document title.
	 *
	 * For posts and taxonomies specific title can be specified as metadata with name `title`.	 *
	 *
	 * @param string $title `pre_get_document_title` passed value.
	 *
	 * @return string
	 */
	static function lp_meta_title($title = '')
	{
		global $post;

		// support for `pre_get_document_title` hook.
		if ($title)
			return $title;

		static $cache;
		if ($cache) return $cache;

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
				/**
				 * Allow to set meta title for singular type page, before the default title will be taken.
				 *
				 * @param string  $title
				 * @param WP_Post $post
				 */
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
			$rus_month = [
				'',
				'январь',
				'февраль',
				'март',
				'апрель',
				'май',
				'июнь',
				'июль',
				'август',
				'сентябрь',
				'октябрь',
				'ноябрь',
				'декабрь'
			];
			$rus_month2 = [
				'',
				'января',
				'февраля',
				'марта',
				'апреля',
				'мая',
				'июня',
				'июля',
				'августа',
				'сентября',
				'октября',
				'ноября',
				'декабря'
			];
			$year     = get_query_var('year');
			$monthnum = get_query_var('monthnum');
			$day      = get_query_var('day');

			if (is_year())      $dat = "$year год";
			elseif (is_month()) $dat = "{$rus_month[$monthnum]} $year года";
			elseif (is_day())   $dat = "$day {$rus_month2[$monthnum]} $year года";

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

		/**
		 * Allows to change parts of the document title.
		 *
		 * @param array $parts Title parts. It then will be joined.
		 * @param array $l10n  Localisation strings.
		 */
		$parts = apply_filters('LP_meta_title_parts', $parts, $l10n);

		/** This filter is documented in wp-includes/general-template.php */
		$parts = apply_filters('document_title_parts', $parts);

		// handle placeholders
		if ('{{blog_name}}' === $parts['after']) {
			$parts['after'] = get_bloginfo('name', 'display');
		} elseif ('{{description}}' === $parts['after']) {
			$parts['after'] = get_bloginfo('description', 'display');
		}

		/** This filter is documented in wp-includes/general-template.php */
		$sep = apply_filters('document_title_separator', ' – ');

		$title = implode(' ' . trim($sep) . ' ', array_filter($parts));

		//$title = wptexturize( $title );
		//$title = convert_chars( $title );
		$title = esc_html($title);
		$title = capital_P_dangit($title);

		return $cache = $title;
	}

	/**
	 * Display `description` metatag.
	 *
	 * Must be used on hook `wp_head`.
	 *
	 * Use `description` meta-field to set description for any posts.
	 * It also work for page setted as front page.
	 *
	 * Use `meta_description` meta-field to set description for any terms.
	 * Or use default `description` field of a term.
	 *
	 * @return string Description.
	 */
	static function lp_meta_description()
	{
		global $post;

		// called from `wp_head` hook
		$echo_result = (func_num_args() === 1);

		static $cache = null;
		if (isset($cache)) {

			if ($echo_result)
				echo $cache;

			return $cache;
		}

		$desc = '';
		$need_cut = true;

		// front
		if (is_front_page()) {

			// когда для главной установлена страница
			if (is_page()) {
				$desc = get_post_meta($post->ID, 'lp_meta_description', true);
				$need_cut = false;
			}

			if (! $desc) {

				/**
				 * Allow to change front_page meta description.
				 *
				 * @param string $home_description
				 */
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

		// remove shortcodes, but leave markdown [foo](URL)
		$desc = preg_replace('~\[[^\]]+\](?!\()~', '', $desc);

		/**
		 * Allow change or set the meta description.
		 *
		 * @param string $desc        Current description.
		 * @param string $origin_desc Description before cut.
		 * @param bool   $need_cut    Is need to cut?
		 * @param int    $maxchar     How many characters leave after cut.
		 */
		$desc = apply_filters('LP_meta_description', $desc);

		/**
		 * Allow to specify is the meta description need to be cutted.
		 *
		 * @param bool $need_cut
		 */
		$need_cut = apply_filters('LP_meta_description__need_cut', $need_cut);

		if ($need_cut) {
			/**
			 * Allow set max length of the meta description.
			 *
			 * @param int $maxchar
			 */
			$maxchar = apply_filters('LP_meta_description__maxchar', 160);

			$char = mb_strlen($desc);

			if ($char > $maxchar) {
				$desc = mb_substr($desc, 0, $maxchar);
				$words = explode(' ', $desc);
				$maxwords = count($words) - 1; // remove last word, it incomplete in 90% cases
				$desc = implode(' ', array_slice($words, 0, $maxwords)) . ' ...';
			}
		}

		// remove multi-space
		$desc = preg_replace('/\s+/s', ' ', $desc);

		$cache = $desc
			? sprintf("<meta name=\"description\" content=\"%s\">\n", esc_attr(trim($desc))) : '';
		if ($echo_result) echo $cache;

		return $cache;
	}

	/**
	 * Wrpper for WP Robots API introduced in WP 5.7+.
	 *
	 * Must be used on hook `wp_robots`.
	 *
	 * @param array $robots
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

		// close taxonomies

		// close draft
		if (is_preview()) {
			$robots['none'] = true;
		}

		return $robots;
	}
}

LP_SEO_Tags::init();

/**
 * ------------------------------------------------------------------------------------------------
 * remove trailing_slash from attachment_image
 * ------------------------------------------------------------------------------------------------
 */
function remove_trailing_slash_from_attachment_image($html)
{
	$pattern = '/\/>$/';
	$html = preg_replace($pattern, '>', $html);
	return $html;
}
add_filter('wp_get_attachment_image', 'remove_trailing_slash_from_attachment_image', 10, 4);


/**
 * ------------------------------------------------------------------------------------------------
 * Create html sitemap page
 * ------------------------------------------------------------------------------------------------
 */

function lp_html_sitemap()
{
	$page_slug = 'sitemap-html';
	$page_title = __('HTML Sitemap', 'lp-seo');
	$template_path = 'templates/sitemap-html.php';

	// Check if page already exists
	$existing_page = get_page_by_path($page_slug, OBJECT, 'page');

	if (! $existing_page) {
		$page_args = [
			'post_type'      => 'page',
			'post_title'     => $page_title,
			'post_content'   => '',
			'post_status'    => 'publish',
			'post_author'    => 1,
			'post_name'      => $page_slug,
			'meta_input'     => [
				'_wp_page_template' => $template_path,
			],
		];

		$page_id = wp_insert_post($page_args, true);

		if (is_wp_error($page_id)) {
			return;
		}
	}
}

if (function_exists('get_field') && get_field('html_sitemap', 'option')) {
	add_action('init', 'lp_html_sitemap');
}

// get sitemap page url
//$sitemap_html_page_url = get_page_url('sitemap-html');
