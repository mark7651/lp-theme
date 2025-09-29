<?php if (!defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Schema.org Structured Data Implementation
 * ------------------------------------------------------------------------------------------------
 * This file implements JSON-LD structured data for SEO
 */

class LP_Schema_Markup
{
	private static $instance = null;

	public static function init()
	{
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct()
	{
		add_action('wp_head', [$this, 'output_schema_markup'], 1);
	}

	/**
	 * Main function to output all schema markup
	 */
	public function output_schema_markup()
	{
		// Check if schema is enabled
		if (!get_field('enable_schema', 'option')) {
			return;
		}

		$schemas = [];

		// Always add Organization schema
		$schemas[] = $this->get_organization_schema();

		// Add WebSite schema
		$schemas[] = $this->get_website_schema();

		// Conditional schemas based on page type
		if (is_front_page()) {
			// Nothing extra for now
		} elseif (is_single()) {
			if (get_post_type() === 'post') {
				$schemas[] = $this->get_article_schema();
			} elseif (get_post_type() === 'services') {
				$schemas[] = $this->get_service_schema();
			} elseif (get_post_type() === 'doctors') {
				$schemas[] = $this->get_person_schema();
			}
		} elseif (is_page()) {
			$schemas[] = $this->get_webpage_schema();
		}

		// Add BreadcrumbList if not on homepage
		if (!is_front_page()) {
			$breadcrumb = $this->get_breadcrumb_schema();
			if ($breadcrumb) {
				$schemas[] = $breadcrumb;
			}
		}

		// Output all schemas
		$this->output_json_ld($schemas);
	}

	/**
	 * Organization Schema
	 */
	private function get_organization_schema()
	{
		$org_type = get_field('schema_org_type', 'option') ?: 'Organization';
		$logo = get_field('header_logo', 'option');
		$logo_url = $logo ? $logo['url'] : '';

		$schema = [
			'@context' => 'https://schema.org',
			'@type' => $org_type,
			'name' => get_bloginfo('name'),
			'url' => home_url(),
		];

		// Add logo
		if ($logo_url) {
			$schema['logo'] = $logo_url;
			$schema['image'] = $logo_url;
		}

		// Add legal name
		$legal_name = get_field('schema_legal_name', 'option');
		if ($legal_name) {
			$schema['legalName'] = $legal_name;
		}

		// Add alternate name
		$alternate_name = get_field('schema_alternate_name', 'option');
		if ($alternate_name) {
			$schema['alternateName'] = $alternate_name;
		}

		// Add description
		$description = get_field('schema_description', 'option');
		if ($description) {
			$schema['description'] = $description;
		} else {
			$schema['description'] = get_bloginfo('description');
		}

		// Add founding date
		$founding_date = get_field('schema_founding_date', 'option');
		if ($founding_date) {
			$schema['foundingDate'] = $founding_date;
		}

		// Add VAT ID
		$vat_id = get_field('schema_vat_id', 'option');
		if ($vat_id) {
			$schema['vatID'] = $vat_id;
		}

		// Add price range for business types
		$price_range = get_field('schema_price_range', 'option');
		if ($price_range) {
			$schema['priceRange'] = $price_range;
		}

		// Add contact information
		$phone = get_field('phone_1', 'option');
		$email = get_field('email', 'option');

		if ($phone || $email) {
			$schema['contactPoint'] = [
				'@type' => 'ContactPoint',
				'contactType' => 'customer service',
			];
			if ($phone) {
				$schema['contactPoint']['telephone'] = $phone;
			}
			if ($email) {
				$schema['contactPoint']['email'] = $email;
			}
		}

		// Add address with detailed schema fields
		$street = get_field('schema_street_address', 'option');
		$locality = get_field('schema_locality', 'option');
		$region = get_field('schema_region', 'option');
		$postal = get_field('schema_postal_code', 'option');
		$country = get_field('schema_country', 'option') ?: 'UA';

		if ($street || $locality) {
			$schema['address'] = [
				'@type' => 'PostalAddress',
				'addressCountry' => $country,
			];

			if ($street) {
				$schema['address']['streetAddress'] = $street;
			}
			if ($locality) {
				$schema['address']['addressLocality'] = $locality;
			}
			if ($region) {
				$schema['address']['addressRegion'] = $region;
			}
			if ($postal) {
				$schema['address']['postalCode'] = $postal;
			}
		}

		// Add geo coordinates
		$latitude = get_field('schema_latitude', 'option');
		$longitude = get_field('schema_longitude', 'option');

		if ($latitude && $longitude) {
			$schema['geo'] = [
				'@type' => 'GeoCoordinates',
				'latitude' => floatval($latitude),
				'longitude' => floatval($longitude),
			];
		}

		// Add opening hours
		$opening_hours = get_field('schema_opening_hours_spec', 'option');
		if ($opening_hours && is_array($opening_hours)) {
			$schema['openingHoursSpecification'] = [];
			foreach ($opening_hours as $hours) {
				if (!empty($hours['day_of_week']) && !empty($hours['opens']) && !empty($hours['closes'])) {
					$schema['openingHoursSpecification'][] = [
						'@type' => 'OpeningHoursSpecification',
						'dayOfWeek' => $hours['day_of_week'],
						'opens' => $hours['opens'],
						'closes' => $hours['closes'],
					];
				}
			}
		}

		// Add social media profiles
		$social_profiles = [];
		$social_fields = ['facebook_link', 'twitter_link', 'instagram_link', 'linkedin_link', 'telegram_link'];

		foreach ($social_fields as $field) {
			$url = get_field($field, 'option');
			if ($url) {
				$social_profiles[] = $url;
			}
		}

		if (!empty($social_profiles)) {
			$schema['sameAs'] = $social_profiles;
		}

		return $schema;
	}

	/**
	 * WebSite Schema with Search Action
	 */
	private function get_website_schema()
	{
		$schema = [
			'@context' => 'https://schema.org',
			'@type' => 'WebSite',
			'name' => get_bloginfo('name'),
			'url' => home_url(),
			'description' => get_bloginfo('description'),
		];

		// Add search action if search is available
		if (get_option('blog_public')) {
			$schema['potentialAction'] = [
				'@type' => 'SearchAction',
				'target' => [
					'@type' => 'EntryPoint',
					'urlTemplate' => home_url('/?s={search_term_string}')
				],
				'query-input' => 'required name=search_term_string'
			];
		}

		return $schema;
	}

	/**
	 * Article Schema for Blog Posts
	 */
	private function get_article_schema()
	{
		if (!is_single()) {
			return null;
		}

		global $post;

		$schema = [
			'@context' => 'https://schema.org',
			'@type' => 'Article',
			'headline' => get_the_title(),
			'description' => get_the_excerpt(),
			'url' => get_permalink(),
			'datePublished' => get_the_date('c'),
			'dateModified' => get_the_modified_date('c'),
			'author' => [
				'@type' => 'Person',
				'name' => get_the_author(),
			],
			'publisher' => [
				'@type' => 'Organization',
				'name' => get_bloginfo('name'),
				'logo' => [
					'@type' => 'ImageObject',
					'url' => $this->get_logo_url(),
				]
			],
		];

		// Add featured image
		if (has_post_thumbnail()) {
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			if ($image) {
				$schema['image'] = [
					'@type' => 'ImageObject',
					'url' => $image[0],
					'width' => $image[1],
					'height' => $image[2],
				];
			}
		}

		return $schema;
	}

	/**
	 * Service Schema for Services Custom Post Type
	 */
	private function get_service_schema()
	{
		if (!is_single() || get_post_type() !== 'services') {
			return null;
		}

		$schema = [
			'@context' => 'https://schema.org',
			'@type' => 'Service',
			'name' => get_the_title(),
			'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 30),
			'url' => get_permalink(),
			'provider' => [
				'@type' => 'Organization',
				'name' => get_bloginfo('name'),
				'url' => home_url(),
			],
		];

		// Add service image
		if (has_post_thumbnail()) {
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			if ($image) {
				$schema['image'] = $image[0];
			}
		}

		// Add service category
		$terms = get_the_terms(get_the_ID(), 'service-cat');
		if ($terms && !is_wp_error($terms)) {
			$schema['serviceType'] = $terms[0]->name;
		}

		return $schema;
	}

	/**
	 * Person Schema for Doctors Custom Post Type
	 */
	private function get_person_schema()
	{
		if (!is_single() || get_post_type() !== 'doctors') {
			return null;
		}

		$schema = [
			'@context' => 'https://schema.org',
			'@type' => 'Person',
			'name' => get_the_title(),
			'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 30),
			'url' => get_permalink(),
		];

		// Add person image
		if (has_post_thumbnail()) {
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			if ($image) {
				$schema['image'] = $image[0];
			}
		}

		// Add organization affiliation
		$schema['worksFor'] = [
			'@type' => 'Organization',
			'name' => get_bloginfo('name'),
			'url' => home_url(),
		];

		return $schema;
	}

	/**
	 * WebPage Schema for Regular Pages
	 */
	private function get_webpage_schema()
	{
		if (!is_page()) {
			return null;
		}

		$schema = [
			'@context' => 'https://schema.org',
			'@type' => 'WebPage',
			'name' => get_the_title(),
			'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 30),
			'url' => get_permalink(),
			'datePublished' => get_the_date('c'),
			'dateModified' => get_the_modified_date('c'),
			'isPartOf' => [
				'@type' => 'WebSite',
				'name' => get_bloginfo('name'),
				'url' => home_url(),
			],
		];

		return $schema;
	}

	/**
	 * BreadcrumbList Schema
	 */
	private function get_breadcrumb_schema()
	{
		if (is_front_page()) {
			return null;
		}

		$items = [
			[
				'@type' => 'ListItem',
				'position' => 1,
				'name' => 'Home',
				'item' => home_url(),
			]
		];

		$position = 2;

		// Add custom post type archive
		if (is_singular() && !is_singular('post')) {
			$post_type = get_post_type_object(get_post_type());
			if ($post_type && $post_type->has_archive) {
				$items[] = [
					'@type' => 'ListItem',
					'position' => $position++,
					'name' => $post_type->labels->name,
					'item' => get_post_type_archive_link(get_post_type()),
				];
			}
		}

		// Add categories for posts
		if (is_single() && get_post_type() === 'post') {
			$categories = get_the_category();
			if ($categories) {
				$category = $categories[0];
				$items[] = [
					'@type' => 'ListItem',
					'position' => $position++,
					'name' => $category->name,
					'item' => get_category_link($category->term_id),
				];
			}
		}

		// Add taxonomy terms for custom post types
		if (is_singular('services')) {
			$terms = get_the_terms(get_the_ID(), 'service-cat');
			if ($terms && !is_wp_error($terms)) {
				$term = $terms[0];
				$items[] = [
					'@type' => 'ListItem',
					'position' => $position++,
					'name' => $term->name,
					'item' => get_term_link($term),
				];
			}
		}

		// Add current page
		if (is_singular()) {
			$items[] = [
				'@type' => 'ListItem',
				'position' => $position,
				'name' => get_the_title(),
				'item' => get_permalink(),
			];
		} elseif (is_category() || is_tag() || is_tax()) {
			$term = get_queried_object();
			$items[] = [
				'@type' => 'ListItem',
				'position' => $position,
				'name' => $term->name,
				'item' => get_term_link($term),
			];
		} elseif (is_archive()) {
			$items[] = [
				'@type' => 'ListItem',
				'position' => $position,
				'name' => get_the_archive_title(),
				'item' => get_pagenum_link(),
			];
		}

		if (count($items) <= 1) {
			return null;
		}

		return [
			'@context' => 'https://schema.org',
			'@type' => 'BreadcrumbList',
			'itemListElement' => $items,
		];
	}

	/**
	 * Helper function to get logo URL
	 */
	private function get_logo_url()
	{
		$logo = get_field('header_logo', 'option');
		if ($logo && isset($logo['url'])) {
			return $logo['url'];
		}
		return '';
	}

	/**
	 * Output JSON-LD formatted schema markup
	 */
	private function output_json_ld($schemas)
	{
		if (empty($schemas)) {
			return;
		}

		// Filter out null schemas
		$schemas = array_filter($schemas);

		if (empty($schemas)) {
			return;
		}

		// If multiple schemas, wrap in @graph
		if (count($schemas) > 1) {
			$output = [
				'@context' => 'https://schema.org',
				'@graph' => $schemas,
			];
		} else {
			$output = $schemas[0];
		}

		echo '<script type="application/ld+json">' . "\n";
		echo wp_json_encode($output, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		echo "\n" . '</script>' . "\n";
	}
}

/**
 * Initialize Schema Markup
 */
function init_lp_schema_markup()
{
	LP_Schema_Markup::init();
}
add_action('init', 'init_lp_schema_markup');
