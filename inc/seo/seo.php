<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * seo functions
 * ------------------------------------------------------------------------------------------------
 */

require_once LP_FRAMEWORK . '/seo/seo-admin.php';
require_once LP_FRAMEWORK . '/seo/seo-tags.php';
require_once LP_THEMEROOT . '/seo/schema-org.php';

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

if (!function_exists('lp_stop_heartbeat')) {
	function lp_stop_heartbeat()
	{
		if (!get_field('enable_heartbeat_api', 'option')) return;
		//wp_deregister_script('heartbeat');
		add_filter('heartbeat_send', '__return_false');
	}
	add_action('init', 'lp_stop_heartbeat', 1);
}

/**
 * ------------------------------------------------------------------------------------------------
 * clean some stuff
 * ------------------------------------------------------------------------------------------------
 */

if (! get_field('enable_rest_api', 'option')) {

	add_filter('rest_enabled', '__return_false');
	// filters REST API
	remove_action('xmlrpc_rsd_apis',            'rest_output_rsd');
	remove_action('wp_head',                    'rest_output_link_wp_head', 10, 0);
	remove_action('template_redirect',          'rest_output_link_header', 11, 0);
	remove_action('auth_cookie_malformed',      'rest_cookie_collect_status');
	remove_action('auth_cookie_expired',        'rest_cookie_collect_status');
	remove_action('auth_cookie_bad_username',   'rest_cookie_collect_status');
	remove_action('auth_cookie_bad_hash',       'rest_cookie_collect_status');
	remove_action('auth_cookie_valid',          'rest_cookie_collect_status');
	remove_filter('rest_authentication_errors', 'rest_cookie_check_errors', 100);

	// events REST API
	remove_action('init',          'rest_api_init');
	remove_action('rest_api_init', 'rest_api_default_filters', 10, 1);
	remove_action('parse_request', 'rest_api_loaded');

	//Embeds connected with REST API =================
	remove_filter('the_content', 'wptexturize');
	// Remove REST API endpoint for embeds
	remove_action('rest_api_init', 'wp_oembed_register_route');
	// Turn off oEmbed auto discovery
	remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10);
	// Don't filter oEmbed results
	remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
	// Remove oEmbed discovery links
	remove_action('wp_head', 'wp_oembed_add_discovery_links');
	// Remove oEmbed JavaScript from header
	remove_action('wp_head', 'wp_oembed_add_host_js');
	// Remove filter of the oEmbed result before any HTTP requests are made
	remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
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

function disable_xmlrpc()
{
	add_filter('xmlrpc_enabled', '__return_false');
}
add_action('init', 'disable_xmlrpc');

// remove users from wp-sitemap 
add_filter('wp_sitemaps_add_provider', function ($provider, $name) {
	return ($name == 'users') ? false : $provider;
}, 10, 2);

// Disable pingbacks completely
function disable_pingbacks(&$links)
{
	foreach ($links as $l => $link) {
		if (0 === strpos($link, get_option('home'))) {
			unset($links[$l]);
		}
	}
}
add_action('pre_ping', 'disable_pingbacks');

// Remove pingback header
function remove_pingback_header($headers)
{
	if (isset($headers['X-Pingback'])) {
		unset($headers['X-Pingback']);
	}
	return $headers;
}
add_filter('wp_headers', 'remove_pingback_header');


// disable commnets globally
if (! get_field('enable_comments', 'option')) {
	add_action('admin_init', function () {
		foreach (get_post_types() as $type) {
			if (post_type_supports($type, 'comments')) {
				remove_post_type_support($type, 'comments');
				remove_post_type_support($type, 'trackbacks');
			}
		}
	});
}

/**
 * ------------------------------------------------------------------------------------------------
 * MINIFY HTML OUTPUT
 * ------------------------------------------------------------------------------------------------
 */

class WP_HTML_Compression
{
	// Settings
	protected $compress_css = true;
	protected $compress_js = true;
	protected $info_comment = true;
	protected $remove_comments = true;

	// Variables
	protected $html;
	public function __construct($html)
	{
		if (!empty($html)) {
			$this->parseHTML($html);
		}
	}
	public function __toString()
	{
		return $this->html;
	}
	protected function bottomComment($raw, $compressed)
	{
		$raw = strlen($raw);
		$compressed = strlen($compressed);

		$savings = ($raw - $compressed) / $raw * 100;

		$savings = round($savings, 2);

		// return '<!--HTML compressed, size saved ' . $savings . '%. From ' . $raw . ' bytes, now ' . $compressed . ' bytes-->';
	}
	protected function minifyHTML($html)
	{
		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
		preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
		$overriding = false;
		$raw_tag = false;
		// Variable reused for output
		$html = '';
		foreach ($matches as $token) {
			$tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

			$content = $token[0];

			if (is_null($tag)) {
				if (!empty($token['script'])) {
					$strip = $this->compress_js;
				} else if (!empty($token['style'])) {
					$strip = $this->compress_css;
				} else if ($content == '<!--wp-html-compression no compression-->') {
					$overriding = !$overriding;

					// Don't print the comment
					continue;
				} else if ($this->remove_comments) {
					if (!$overriding && $raw_tag != 'textarea') {
						// Remove any HTML comments, except MSIE conditional comments
						$content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
					}
				}
			} else {
				if ($tag == 'pre' || $tag == 'textarea') {
					$raw_tag = $tag;
				} else if ($tag == '/pre' || $tag == '/textarea') {
					$raw_tag = false;
				} else {
					if ($raw_tag || $overriding) {
						$strip = false;
					} else {
						$strip = true;

						// Remove any empty attributes, except:
						// action, alt, content, src
						$content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);

						// Remove any space before the end of self-closing XHTML tags
						// JavaScript excluded
						$content = str_replace(' />', '/>', $content);
					}
				}
			}

			if ($strip) {
				$content = $this->removeWhiteSpace($content);
			}

			$html .= $content;
		}

		return $html;
	}

	public function parseHTML($html)
	{
		$this->html = $this->minifyHTML($html);

		if ($this->info_comment) {
			$this->html .= "\n" . $this->bottomComment($html, $this->html);
		}
	}

	protected function removeWhiteSpace($str)
	{
		$str = str_replace("\t", ' ', $str);
		$str = str_replace("\n",  '', $str);
		$str = str_replace("\r",  '', $str);

		while (stristr($str, '  ')) {
			$str = str_replace('  ', ' ', $str);
		}

		return $str;
	}
}

function wp_html_compression_finish($html)
{
	return (string) new WP_HTML_Compression($html);
}

function wp_html_compression_start()
{
	ob_start('wp_html_compression_finish');
}

if (get_field('minify_html', 'option')) {
	add_action('get_header', 'wp_html_compression_start');
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
	$page_slug     = 'sitemap-html';
	$page_title    = __('HTML Sitemap', 'lp-seo');
	$template_path = 'sitemap-html.php';

	// Stop if the page already exists by slug
	if (get_page_by_path($page_slug, OBJECT, 'page')) {
		return;
	}

	// Stop if we've already created one and it still exists
	$existing_id = get_option('lp_html_sitemap_id');
	if ($existing_id && get_post_status($existing_id)) {
		return;
	}

	$page_args = [
		'post_type'      => 'page',
		'post_title'     => $page_title,
		'post_status'    => 'publish',
		'post_author'    => get_current_user_id() ?: 1,
		'post_name'      => $page_slug,
		'meta_input'     => [
			'_wp_page_template' => $template_path,
		],
	];

	$page_id = wp_insert_post($page_args);

	if (!is_wp_error($page_id)) {
		update_option('lp_html_sitemap_id', $page_id);
	}
}

if (function_exists('get_field') && get_field('html_sitemap', 'option')) {
	add_action('init', 'lp_html_sitemap');
}



/**
 * ------------------------------------------------------------------------------------------------
 * uppercase redirect
 * ------------------------------------------------------------------------------------------------
 */

add_action('template_redirect', function () {
	$request_uri = $_SERVER['REQUEST_URI'];
	if (preg_match('/[A-Z]/', $request_uri)) {
		$lower_uri = strtolower($request_uri);
		wp_redirect(home_url($lower_uri), 301);
		exit;
	}
});

/**
 * ------------------------------------------------------------------------------------------------
 * allow the same slug for locales
 * ------------------------------------------------------------------------------------------------
 */

if (function_exists('pll_current_language')) {

	function polylang_slug_admin_notices()
	{
		echo '<div class="error"><p>' . __('Polylang Slug requires at the minimum Polylang v1.7 and WordPress 4.0', 'polylang-slug') . '</p></div>';
	}

	function polylang_slug_unique_slug_in_language($slug, $post_ID, $post_status, $post_type, $post_parent, $original_slug)
	{

		if ($original_slug === $slug) {
			return $slug;
		}

		global $wpdb;

		$lang = pll_get_post_language($post_ID);
		$options = get_option('polylang');

		if (empty($lang) || 0 === $options['force_lang'] || ! pll_is_translated_post_type($post_type)) {
			return $slug;
		}

		// " INNER JOIN $wpdb->term_relationships AS pll_tr ON pll_tr.object_id = ID".
		$join_clause  = polylang_slug_model_post_join_clause();
		// " AND pll_tr.term_taxonomy_id IN (" . implode(',', $languages) . ")".
		$where_clause = polylang_slug_model_post_where_clause($lang);

		// Polylang does not translate attachements - skip if it is one.
		// @TODO Recheck this with the Polylang settings
		if ('attachment' == $post_type) {
			$check_sql = "SELECT post_name FROM $wpdb->posts $join_clause WHERE post_name = %s AND ID != %d $where_clause LIMIT 1";
			$post_name_check = $wpdb->get_var($wpdb->prepare($check_sql, $original_slug, $post_ID));
		} elseif (is_post_type_hierarchical($post_type)) {

			$check_sql = "SELECT ID FROM $wpdb->posts $join_clause WHERE post_name = %s AND post_type IN ( %s, 'attachment' ) AND ID != %d AND post_parent = %d $where_clause LIMIT 1";
			$post_name_check = $wpdb->get_var($wpdb->prepare($check_sql, $original_slug, $post_type, $post_ID, $post_parent));
		} else {

			$check_sql = "SELECT post_name FROM $wpdb->posts $join_clause WHERE post_name = %s AND post_type = %s AND ID != %d $where_clause LIMIT 1";
			$post_name_check = $wpdb->get_var($wpdb->prepare($check_sql, $original_slug, $post_type, $post_ID));
		}

		if (! $post_name_check) {
			return $original_slug;
		}

		return $slug;
	}
	add_filter('wp_unique_post_slug', 'polylang_slug_unique_slug_in_language', 10, 6);

	function polylang_slug_filter_queries($query)
	{
		global $wpdb;

		$is_pages_sql = preg_match(
			"#SELECT ID, post_name, post_parent, post_type FROM {$wpdb->posts} .*#",
			polylang_slug_standardize_query($query),
			$matches
		);

		if (! $is_pages_sql) {
			return $query;
		}

		if (! polylang_slug_should_run()) {
			return $query;
		}

		$lang = pll_current_language();
		// " INNER JOIN $wpdb->term_relationships AS pll_tr ON pll_tr.object_id = ID".
		$join_clause  = polylang_slug_model_post_join_clause();
		// " AND pll_tr.term_taxonomy_id IN (" . implode(',', $languages) . ")".
		$where_clause = polylang_slug_model_post_where_clause($lang);

		$query = preg_match(
			"#(SELECT .* (?=FROM))(FROM .* (?=WHERE))(?:(WHERE .*(?=ORDER))|(WHERE .*$))(.*)#",
			polylang_slug_standardize_query($query),
			$matches
		);

		$matches = array_values($matches);

		// SELECT, FROM, INNER JOIN, WHERE, WHERE CLAUSE (additional), ORBER BY (if included)
		$sql_query = $matches[1] . $matches[2] . $join_clause . $matches[3] . $where_clause . $matches[4];
		return apply_filters('polylang_slug_sql_query', $sql_query, $matches, $join_clause, $where_clause);
	}
	add_filter('query', 'polylang_slug_filter_queries');

	function polylang_slug_posts_where_filter($where, $query)
	{
		if (! polylang_slug_should_run($query)) {
			return $where;
		}

		$lang = empty($query->query['lang']) ? pll_current_language() : $query->query['lang'];

		// " AND pll_tr.term_taxonomy_id IN (" . implode(',', $languages) . ")"
		$where .= polylang_slug_model_post_where_clause($lang);

		return $where;
	}
	add_filter('posts_where', 'polylang_slug_posts_where_filter', 10, 2);

	function polylang_slug_posts_join_filter($join, $query)
	{

		if (! polylang_slug_should_run($query)) {
			return $join;
		}

		// " INNER JOIN $wpdb->term_relationships AS pll_tr ON pll_tr.object_id = ID".
		$join .= polylang_slug_model_post_join_clause();

		return $join;
	}
	add_filter('posts_join', 'polylang_slug_posts_join_filter', 10, 2);

	function polylang_slug_should_run($query = '')
	{
		// Do not run in admin or if Polylang is disabled
		$disable = apply_filters('polylang_slug_disable', false, $query);
		if (is_admin() || is_feed() || ! function_exists('pll_current_language') || $disable) {
			return false;
		}
		// The lang query should be defined if the URL contains the language
		$lang          = empty($query->query['lang']) ? pll_current_language() : $query->query['lang'];
		// Checks if the post type is translated when doing a custom query with the post type defined
		$is_translated = ! empty($query->query['post_type']) && ! pll_is_translated_post_type($query->query['post_type']);

		return ! (empty($lang) || $is_translated);
	}

	function polylang_slug_standardize_query($query)
	{

		$query = str_replace(
			array("\t", " \n", "\n", " \r", "\r", "   ", "  "),
			array('', ' ', ' ', ' ', ' ', ' ', ' '),
			$query
		);
		return trim($query);
	}

	function polylang_slug_model_post_join_clause()
	{
		if (function_exists('PLL')) {
			return PLL()->model->post->join_clause();
		} elseif (array_key_exists('polylang', $GLOBALS)) {
			global $polylang;
			return $polylang->model->join_clause('post');
		}
		return '';
	}

	function polylang_slug_model_post_where_clause($lang = '')
	{
		if (function_exists('PLL')) {
			return PLL()->model->post->where_clause($lang);
		} elseif (array_key_exists('polylang', $GLOBALS)) {
			global $polylang;
			return $polylang->model->where_clause($lang, 'post');
		}
		return '';
	}

	add_filter('pll_check_language_code_in_url', '__return_false');
	add_filter('pll_load_strings_translations', '__return_false');

	add_filter('pll_rel_hreflang_attributes', function ($hreflangs) {
		$default_lang = function_exists('pll_default_language') ? pll_default_language() : 'en';

		if (isset($hreflangs[$default_lang])) {
			$hreflangs['x-default'] = $hreflangs[$default_lang];
		}
		return $hreflangs;
	});
}

/**
 * ------------------------------------------------------------------------------------------------
 * Remove 'category' slug from post category URLs
 * ------------------------------------------------------------------------------------------------
 */

function remove_category($string, $type)
{
	if ($type != 'single' && $type == 'category' && (strpos($string, 'category') !== false)) {
		$url_without_category = str_replace("/category/", "/", $string);
		return trailingslashit($url_without_category);
	}
	return $string;
}

add_filter('user_trailingslashit', 'remove_category', 100, 2);
