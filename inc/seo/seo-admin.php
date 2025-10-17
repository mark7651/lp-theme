<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * SEO patterns admin settings
 * ------------------------------------------------------------------------------------------------
 */


class LP_SEO_Settings_Admin
{

	private $option_name = 'lp_seo_patterns';

	public function __construct()
	{
		add_action('admin_menu', [$this, 'add_admin_menu']);
		add_action('admin_init', [$this, 'settings_init']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
		add_action('wp_ajax_lp_save_seo_pattern', [$this, 'save_seo_pattern']);
		add_action('wp_ajax_lp_delete_seo_pattern', [$this, 'delete_seo_pattern']);
	}

	public function add_admin_menu()
	{
		add_options_page(
			__('SEO Settings', 'lptheme'),
			__('SEO Patterns', 'lptheme'),
			'manage_options',
			'lp-seo-settings',
			[$this, 'settings_page']
		);
	}

	public function settings_init()
	{
		register_setting('lp_seo_settings', $this->option_name);
	}


	public function enqueue_admin_scripts($hook)
	{
		if ($hook !== 'settings_page_lp-seo-settings') {
			return;
		}

		wp_enqueue_script('jquery');

		$inline_script = "
        jQuery(document).ready(function($) {
            // Add new pattern
            $('#add-new-pattern').click(function() {
                var template = $('#pattern-template').html();
                $('.wp-list-table tbody').append(template);
            });
            
            // Delete pattern
            $(document).on('click', '.delete-pattern', function() {
                if (confirm('" . __('Are you sure you want to delete this pattern?', 'lptheme') . "')) {
                    $(this).closest('tr').remove();
                }
            });
            
            // Save patterns
            $('#save-patterns').click(function() {
                var button = $(this);
                button.prop('disabled', true).text('" . __('Saving...', 'lptheme') . "');
                
                // Remove any existing notices
                $('.notice-seo-patterns').remove();
                
                var patterns = [];
                $('.pattern-row').each(function() {
                    var row = $(this);
                    var pattern = {
                        post_type: row.find('.post-type').val() || 'all',
                        context: row.find('.context').val() || 'single',
                        locale: row.find('.locale').val() || 'all',
                        title_pattern: row.find('.title-pattern').val() || '',
                        description_pattern: row.find('.description-pattern').val() || '',
                        enabled: row.find('.enabled').is(':checked')
                    };
                    patterns.push(pattern);
                });
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'lp_save_seo_pattern',
                        patterns: JSON.stringify(patterns),
                        nonce: '" . wp_create_nonce('lp_seo_nonce') . "'
                    },
                    success: function(response) {
                        if (response.success) {
                            showNotice('success', response.data || '" . __('Patterns saved successfully!', 'lptheme') . "');
                        } else {
                            showNotice('error', response.data || '" . __('Unknown error occurred', 'lptheme') . "');
                        }
                    },
                    error: function(xhr, status, error) {
                        showNotice('error', '" . __('AJAX Error: ', 'lptheme') . "' + error);
                    },
                    complete: function() {
                        button.prop('disabled', false).text('" . __('Save All Patterns', 'lptheme') . "');
                    }
                });
            });
            
            // Function to show WordPress-style notices
            function showNotice(type, message) {
                var noticeClass = type === 'success' ? 'notice-success' : 'notice-error';
                var notice = $('<div class=\"notice ' + noticeClass + ' is-dismissible notice-seo-patterns\"><p>' + message + '</p><button type=\"button\" class=\"notice-dismiss\"><span class=\"screen-reader-text\">" . __('Dismiss this notice.', 'lptheme') . "</span></button></div>');
                
                $('.wrap h1').after(notice);
                
                // Handle dismiss button
                notice.find('.notice-dismiss').on('click', function() {
                    notice.fadeOut(function() {
                        notice.remove();
                    });
                });
                
                // Auto-dismiss success notices after 5 seconds
                if (type === 'success') {
                    setTimeout(function() {
                        notice.fadeOut(function() {
                            notice.remove();
                        });
                    }, 5000);
                }
            }
        });
        ";

		wp_add_inline_script('jquery', $inline_script);

		// Minimal CSS - use WordPress defaults
		$inline_css = "
        .pattern-input {
            width: 100%;
        }
        .notice-seo-patterns {
            margin: 15px 0 5px 0;
        }
        ";

		wp_add_inline_style('wp-admin', $inline_css);
	}

	public function settings_page()
	{
		$patterns = get_option($this->option_name, []);
		$post_types = $this->get_available_post_types();
		$locales = $this->get_available_locales();
?>
		<div class="wrap">
			<h1><?php _e('SEO Patterns Settings', 'lptheme'); ?></h1>

			<?php echo '<p><strong>' . __('Available placeholders:', 'lptheme') . '</strong></p>';
			echo '<ul>';
			echo '<li><code>[post_title]</code> - ' . __('Post title', 'lptheme') . '</li>';
			echo '<li><code>[site_name]</code> - ' . __('Site name', 'lptheme') . '</li>';
			echo '<li><code>[post_excerpt]</code> - ' . __('Post excerpt', 'lptheme') . '</li>';
			echo '<li><code>[term_name]</code> - ' . __('Primary term name', 'lptheme') . '</li>';
			echo '<li><code>[archive_title]</code> - ' . __('Archive title', 'lptheme') . '</li>';
			echo '<li><code>[author_name]</code> - ' . __('Author display name', 'lptheme') . '</li>';
			echo '<li><code>[date]</code> - ' . __('Post date', 'lptheme') . '</li>';
			echo '<li><code>[custom_field:field_name]</code> - ' . __('Custom field value', 'lptheme') . '</li>';
			echo '<li><code>[acf field="field_name"]</code> - ' . __('Custom acf field value', 'lptheme') . '</li>';
			echo '</ul>'; ?>

			<?php if (empty($patterns)): ?>
				<div class="notice notice-warning">
					<p><?php _e('No patterns configured yet. Add some patterns below or', 'lptheme'); ?>
						<a href="<?php echo admin_url('options-general.php?page=lp-seo-settings&install_defaults=1'); ?>" class="button button-secondary">
							<?php _e('Install Default Patterns', 'lptheme'); ?>
						</a>
					</p>
				</div>
			<?php endif; ?>

			<form method="post" action="">
				<table class="wp-list-table widefat fixed striped">
					<thead>
						<tr>
							<th width="20%" scope="col" class="manage-column"><?php _e('Post Type', 'lptheme'); ?></th>
							<th width="10%" scope="col" class="manage-column"><?php _e('Context', 'lptheme'); ?></th>
							<th width="10%" scope="col" class="manage-column"><?php _e('Locale', 'lptheme'); ?></th>
							<th width="25%" scope="col" class="manage-column"><?php _e('Title Pattern', 'lptheme'); ?></th>
							<th width="25%" scope="col" class="manage-column"><?php _e('Description Pattern', 'lptheme'); ?></th>
							<th width="5%" scope="col" class="manage-column"><?php _e('Enabled', 'lptheme'); ?></th>
							<th width="5%" scope="col" class="manage-column"><?php _e('Actions', 'lptheme'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($patterns)): ?>
							<?php foreach ($patterns as $pattern): ?>
								<tr class="pattern-row">
									<td>
										<select class="post-type regular-text">
											<?php foreach ($post_types as $value => $label): ?>
												<option value="<?php echo esc_attr($value); ?>" <?php selected($pattern['post_type'], $value); ?>>
													<?php echo esc_html($label); ?>
												</option>
											<?php endforeach; ?>
										</select>
									</td>
									<td>
										<select class="context regular-text">
											<option value="single" <?php selected($pattern['context'], 'single'); ?>><?php _e('Single', 'lptheme'); ?></option>
											<option value="archive" <?php selected($pattern['context'], 'archive'); ?>><?php _e('Archive', 'lptheme'); ?></option>
										</select>
									</td>
									<td>
										<select class="locale regular-text">
											<?php foreach ($locales as $value => $label): ?>
												<option value="<?php echo esc_attr($value); ?>" <?php selected($pattern['locale'], $value); ?>>
													<?php echo esc_html($label); ?>
												</option>
											<?php endforeach; ?>
										</select>
									</td>
									<td>
										<input type="text" class="title-pattern pattern-input regular-text" value="<?php echo esc_attr($pattern['title_pattern']); ?>" placeholder="<?php _e('e.g., [post_title] - [site_name]', 'lptheme'); ?>">
									</td>
									<td>
										<textarea class="description-pattern pattern-input regular-text" rows="4" placeholder="<?php _e('e.g., [post_excerpt] - [term_name]', 'lptheme'); ?>"><?php echo esc_textarea($pattern['description_pattern']); ?></textarea>
									</td>
									<td>
										<input type="checkbox" class="enabled" <?php checked($pattern['enabled'], true); ?>>
									</td>
									<td>
										<button type="button" class="delete-pattern button button-link-delete"><?php _e('Delete', 'lptheme'); ?></button>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>

				<p class="submit">
					<button type="button" id="add-new-pattern" class="button button-secondary">
						<?php _e('Add New Pattern', 'lptheme'); ?>
					</button>

					<button type="button" id="save-patterns" class="button button-primary">
						<?php _e('Save All Patterns', 'lptheme'); ?>
					</button>
				</p>
			</form>

			<!-- Template for new rows -->
			<script type="text/template" id="pattern-template">
				<tr class="pattern-row">
                    <td>
                        <select class="post-type regular-text">
                            <?php foreach ($post_types as $value => $label): ?>
                                <option value="<?php echo esc_attr($value); ?>">
                                    <?php echo esc_html($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select class="context regular-text">
                            <option value="single"><?php _e('Single', 'lptheme'); ?></option>
                            <option value="archive"><?php _e('Archive', 'lptheme'); ?></option>
                        </select>
                    </td>
                    <td>
                        <select class="locale regular-text">
                            <?php foreach ($locales as $value => $label): ?>
                                <option value="<?php echo esc_attr($value); ?>">
                                    <?php echo esc_html($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="title-pattern pattern-input regular-text" placeholder="<?php _e('e.g., [post_title] - [site_name]', 'lptheme'); ?>">
                    </td>
                    <td>
                        <textarea class="description-pattern pattern-input regular-text" rows="2" placeholder="<?php _e('e.g., [post_excerpt] - [term_name]', 'lptheme'); ?>"></textarea>
                    </td>
                    <td>
                        <input type="checkbox" class="enabled" checked>
                    </td>
                    <td>
                        <button type="button" class="delete-pattern button button-link-delete"><?php _e('Delete', 'lptheme'); ?></button>
                    </td>
                </tr>
            </script>
		</div>
<?php
	}

	private function get_available_post_types()
	{
		$post_types = get_post_types(['public' => true], 'objects');
		$available = ['all' => __('All Post Types', 'lptheme')];

		foreach ($post_types as $post_type) {
			$available[$post_type->name] = $post_type->label;
		}

		return $available;
	}

	private function get_available_locales()
	{
		$locales = ['all' => __('All Locales', 'lptheme')];

		// Check if Polylang is active
		if (function_exists('pll_languages_list')) {
			$languages = pll_languages_list(['fields' => false]);
			foreach ($languages as $lang) {
				$locales[$lang->slug] = $lang->name;
			}
		} elseif (function_exists('icl_get_languages')) {
			// WPML support
			$languages = icl_get_languages('skip_missing=0');
			foreach ($languages as $lang) {
				$locales[$lang['language_code']] = $lang['native_name'];
			}
		} else {
			// Default WordPress locale
			$locales[get_locale()] = __('Default', 'lptheme');
		}

		return $locales;
	}

	public function save_seo_pattern()
	{
		if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'lp_seo_nonce')) {
			wp_send_json_error(__('Security check failed - invalid nonce', 'lptheme'));
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error(__('Security check failed - insufficient permissions', 'lptheme'));
		}

		if (!isset($_POST['patterns'])) {
			wp_send_json_error(__('No patterns data received', 'lptheme'));
		}

		$patterns_json = stripslashes($_POST['patterns']);
		$patterns = json_decode($patterns_json, true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			wp_send_json_error(__('Invalid JSON data: ', 'lptheme') . json_last_error_msg());
		}

		if (!is_array($patterns)) {
			wp_send_json_error(__('Patterns data is not an array', 'lptheme'));
		}

		$sanitized_patterns = [];
		foreach ($patterns as $pattern) {
			if (!is_array($pattern)) {
				continue;
			}

			$sanitized_patterns[] = [
				'post_type' => isset($pattern['post_type']) ? sanitize_text_field($pattern['post_type']) : 'all',
				'context' => isset($pattern['context']) ? sanitize_text_field($pattern['context']) : 'single',
				'locale' => isset($pattern['locale']) ? sanitize_text_field($pattern['locale']) : 'all',
				'title_pattern' => isset($pattern['title_pattern']) ? sanitize_text_field($pattern['title_pattern']) : '',
				'description_pattern' => isset($pattern['description_pattern']) ? sanitize_textarea_field($pattern['description_pattern']) : '',
				'enabled' => isset($pattern['enabled']) ? (bool) $pattern['enabled'] : true
			];
		}

		$result = update_option($this->option_name, $sanitized_patterns);

		if ($result !== false) {
			wp_send_json_success(__('Patterns saved successfully!', 'lptheme'));
		} else {
			wp_send_json_error(__('Failed to save patterns to database', 'lptheme'));
		}
	}

	public function delete_seo_pattern()
	{
		if (!wp_verify_nonce($_POST['nonce'], 'lp_seo_nonce') || !current_user_can('manage_options')) {
			wp_send_json_error(__('Security check failed', 'lptheme'));
		}

		wp_send_json_success();
	}
}

new LP_SEO_Settings_Admin();

// Add default patterns installer
add_action('admin_init', function () {
	if (isset($_GET['install_defaults']) && current_user_can('manage_options')) {
		$default_patterns = [
			[
				'post_type' => 'post',
				'context' => 'single',
				'locale' => 'all',
				'title_pattern' => '[post_title] - [site_name]',
				'description_pattern' => '[post_excerpt]',
				'enabled' => true
			],
			[
				'post_type' => 'services',
				'context' => 'single',
				'locale' => 'all',
				'title_pattern' => '[post_title] - Услуги [site_name]',
				'description_pattern' => '[post_excerpt] Узнайте больше о [term_name].',
				'enabled' => true
			],
			[
				'post_type' => 'services',
				'context' => 'archive',
				'locale' => 'all',
				'title_pattern' => 'Наши услуги - [site_name]',
				'description_pattern' => 'Полный каталог услуг компании [site_name]. Профессиональные решения для вашего бизнеса.',
				'enabled' => true
			]
		];

		update_option('lp_seo_patterns', $default_patterns);
		wp_redirect(admin_url('options-general.php?page=lp-seo-settings&defaults=installed'));
		exit;
	}

	if (isset($_GET['defaults']) && $_GET['defaults'] === 'installed') {
		add_action('admin_notices', function () {
			echo '<div class="notice notice-success is-dismissible">';
			echo '<p>' . __('Default patterns installed successfully!', 'lptheme') . '</p>';
			echo '</div>';
		});
	}
});
