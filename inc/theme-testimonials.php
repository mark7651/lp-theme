<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Register testimonials post type and metaboxes
 * ------------------------------------------------------------------------------------------------
 */

if( ! get_field( 'enable_testimonials','option' ) ) return;

if ( ! function_exists( 'testimonials_init' ) ) {
	add_action( 'init', 'testimonials_init', 10 );
	function testimonials_init() {
		$labels = array(
			'name' => esc_html__( 'Отзывы', 'lptheme' ),
			'singular_name' => esc_html__( 'Отзывы', 'lptheme' ),
			'add_new' => esc_html__( 'Добавить отзыв', 'lptheme' ),
			'add_new_item' => esc_html__( 'Добавить отзыв', 'ltheme' ),
			'edit_item' => esc_html__( 'Редактировать отзыв', 'lptheme' ),
			'new_item' => esc_html__( 'Добавить отзыв', 'lptheme' ),
			'view_item' => esc_html__( 'Смотреть отзыв', 'lptheme' ),
			'search_items' => esc_html__( 'Поиск', 'lptheme' ),
			'not_found' => esc_html__( 'Отзывы не найдены', 'lptheme' ),
			'not_found_in_trash' => esc_html__( 'Корзина пуста', 'lptheme' )
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true,
			'supports' => array( 'title', 'thumbnail', 'comments' ),
			'capability_type' => 'post',
			'menu_position' => 6,
			'query_var' => true,
			'has_archive' => false,
			'hierarchical'  => false,
			'menu_icon' => 'dashicons-format-chat',
			'publicly_queryable' => false, 
			'exclude_from_search' => true,
			'show_in_nav_menus' => false,
			'register_meta_box_cb' => 'testimonials_meta_boxes',
		);
		$args = apply_filters('testimonials_args', $args);
		register_post_type('testimonials', $args);
		flush_rewrite_rules();
	}
}

// add meta boxes =============================================================================
function testimonials_meta_boxes() {
	add_meta_box( 'testimonials_admin_form', 'Данные отзыва', 'testimonials_admin_form', 'testimonials', 'normal', 'high' );
}
function testimonials_admin_form() {
	$post_id = get_the_ID();
	$testimonial_data = get_post_meta( $post_id, '_testimonial', true );
	$client_name = ( empty( $testimonial_data['client_name'] ) ) ? '' : $testimonial_data['client_name'];
	$client_email = ( empty( $testimonial_data['client_email'] ) ) ? '' : $testimonial_data['client_email'];
	$client_message = ( empty( $testimonial_data['client_message'] ) ) ? '' : $testimonial_data['client_message'];
	$client_rating = ( empty( $testimonial_data['rating'] ) ) ? '' : $testimonial_data['rating'];
	wp_nonce_field( 'testimonials', 'testimonials' );
	?>

<div class="stuffbox">
	<div class="inside">
		<fieldset>
			<table class="form-table editcomment" role="presentation">
				<tbody>
					<tr>
						<td class="first"><label for="name">Имя</label></td>
						<td><input type="text" id="name" value="<?php echo $client_name; ?>" name="testimonial[client_name]"
								size="30">
						</td>
					</tr>
					<tr>
						<td class="first"><label for="email">Email</label></td>
						<td>
							<input type="email" id="email" value="<?php echo $client_email; ?>" name="testimonial[client_email]"
								aria-describedby="email-description" size="30">
						</td>
					</tr>
					<tr class="user-description-wrap">
					<td class="first"><label for="testimonial[client_message]">Отзыв</label></td>
						<td>
							<?php
							$editor_settings = array(
								'textarea_name' => 'testimonial[client_message]',
								'textarea_rows' => 15,
							);
							wp_editor( $client_message, 'testimonial-editor', $editor_settings );
							?>
						</td>
				</tr>
				<tr>
						<td class="first"><label for="rating">Рейтинг</label></td>
						<td><input type="number" id="rating" value="<?php echo $client_rating; ?>" name="testimonial[rating]" size="2" min="1" max="5"></td>
				</tr>
				</tbody>
			</table>
		</fieldset>

	</div>
</div>
	

	<?php
}

// save metabox info =============================================================================

add_action('save_post', 'testimonials_save_post');
function testimonials_save_post($post_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	if (!isset($_POST['testimonials']) || !wp_verify_nonce($_POST['testimonials'], 'testimonials'))
		return;
	if (!isset($_POST['post_type']) || 'page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return;
	} else {
		if (!current_user_can('edit_post', $post_id))
			return;
	}
	if (!wp_is_post_revision($post_id) && 'testimonials' == get_post_type($post_id)) {
		remove_action('save_post', 'testimonials_save_post');
		wp_update_post(array(
			'ID' => $post_id,
		));
		add_action('save_post', 'testimonials_save_post');
	}
	if (!empty($_POST['testimonial'])) {
		$testimonial_data['client_name'] = (empty($_POST['testimonial']['client_name'])) ? '' : sanitize_text_field($_POST['testimonial']['client_name']);
		$testimonial_data['client_email'] = (empty($_POST['testimonial']['client_email'])) ? '' : sanitize_text_field($_POST['testimonial']['client_email']);
		$testimonial_data['client_message'] = (empty($_POST['testimonial']['client_message'])) ? '' : wp_kses_post($_POST['testimonial']['client_message']);
		$testimonial_data['rating'] = (empty($_POST['testimonial']['rating'])) ? '' : sanitize_text_field($_POST['testimonial']['rating']);
		update_post_meta($post_id, '_testimonial', $testimonial_data);

	} else {
		delete_post_meta($post_id, '_testimonial');
	}
}


// admin column info =============================================================================
add_filter( 'manage_edit-testimonials_columns', 'testimonials_edit_columns' );
function testimonials_edit_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Заголовок',
		'testimonial-client-message' => 'Отзыв',
		'testimonial-client-name' => 'Имя',
		'testimonial-client-email' => 'Email',
		'testimonial-rating' => 'Рейтинг',
		'date' => 'Дата'
	);
	return $columns;
}

add_action( 'manage_posts_custom_column', 'testimonials_columns', 10, 2 );
function testimonials_columns( $column, $post_id ) {
	$testimonial_data = get_post_meta( $post_id, '_testimonial', true );
	switch ( $column ) {
		case 'testimonial':
			the_excerpt();
			break;
		case 'testimonial-client-name':
			if ( ! empty( $testimonial_data['client_name'] ) )
				echo $testimonial_data['client_name'];
			break;
		case 'testimonial-client-email':
			if ( ! empty( $testimonial_data['client_email'] ) )
				echo $testimonial_data['client_email'];
			break;
		case 'testimonial-client-message':
			if ( ! empty( $testimonial_data['client_message'] ) )
				echo $testimonial_data['client_message'];
			break;
			case 'testimonial-rating':
        if (!empty($testimonial_data['rating'])) {
            echo esc_html($testimonial_data['rating']);
        }
        break;
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Ajax create testimonial
 * ------------------------------------------------------------------------------------------------
 */

 // Function to handle uploaded file and return attachment ID
function handle_uploaded_file($file, $post_id) {
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$attachment_id = media_handle_upload('attachment', $post_id);

	if (is_wp_error($attachment_id)) {
			return false;
	}

	return $attachment_id;
}

if( !function_exists('create_testimonial_post')) {
	function create_testimonial_post($data) {
		if (isset($data['client-name']) && isset($data['client-email']) && isset($data['client-message'])) {

			$name = sanitize_text_field($data['client-name']);
			$email = sanitize_email($data['client-email']);
			$message = sanitize_textarea_field($data['client-message']);
			$rating = sanitize_text_field($data['rating']);

			$testimonial_data = array(
				'client_name' => $name,
				'client_email' => $email,
				'client_message' => $message, 
				'rating' => $rating, 
			);

			$new_testimonial = array(
				'post_title'   => $name,
				'post_status'=> 'draft',
				'post_type'=> 'testimonials',
			);

			$post_id = wp_insert_post($new_testimonial);

				if ($post_id) {

					update_post_meta($post_id, '_testimonial', $testimonial_data);

					 // Handle file attachment
					 if (!empty($_FILES['attachment']['name'])) {
								$attachment_id = handle_uploaded_file($_FILES['attachment'], $post_id);
								if ($attachment_id) {
										set_post_thumbnail($post_id, $attachment_id);
								}
						}
			
					// Send admin notice ==============================
					if(get_post_type($post_id) !== 'testimonials' && get_post_status($post_id) == 'draft') {
						return;
					}
					
					$post_title = get_the_title($post_id);
					$post_url = get_edit_post_link($post_id);
					$subject 	= "Новый отзыв на сайте";
					$body 	= "Проверьте отзыв перед публикацией:\n\n";
					$body   .= "<a href=".$post_url.">$post_title</a>";
			
					$administrators = get_users(array(
						'role'	=> 'administrator'
					));
			
					foreach ($administrators as &$administrator) {
						wp_mail( $administrator->data->user_email, $subject, $body );
					}

					return true;
				}  

		} else {
			return false;
		}
		
	}
}


// send testimonial form data =====================================
 class testimonial_form {
   
	public static function send_testimonial() {

			$responce_message = '';
			$response_code = '';

			if ($_SERVER["REQUEST_METHOD"] == "POST") {

					if (! wp_verify_nonce( $_POST['nonce'], 'lp-nonce')) {
							$responce_message = esc_html__( 'Verification error, try again', 'lptheme' );
					} else {

						$response_code = 200;
						$success = create_testimonial_post($_POST);

							if ($success == true) {

								$responce_message =  esc_html__( 'Спасибо за Ваш отзыв! После проверки он будет опубликован!', 'lptheme' );

							} else {
									$response_code = 500;
									$responce_message = esc_html__( 'Check the form fields', 'lptheme' );
							}

					}
					
			} else {
					$response_code = 405;
					$responce_message = esc_html__( 'Method Not Allowed', 'lptheme' );
				}


			echo json_encode(array('message' => $responce_message, 'code' => $response_code));
			exit();
		 
	}
		 
	public static function lp_mail_content_type() {
			return "text/html";
	}
}

add_action('wp_ajax_send_testimonial', array('testimonial_form', 'send_testimonial') );
add_action('wp_ajax_nopriv_send_testimonial', array('testimonial_form', 'send_testimonial') );
add_filter('wp_mail_content_type', array('testimonial_form', 'lp_mail_content_type') );


/**
 * ------------------------------------------------------------------------------------------------
 * Testimonials form shortcode
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'testimonials_form_shortcode' ) ) {
	function testimonials_form_shortcode() {
			ob_start();
			get_template_part( 'template-parts/forms/form', 'testimonials' ); 
			return ob_get_clean();
	}
	add_shortcode( 'testimonials-form', 'testimonials_form_shortcode' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Get testimonials list
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'get_testimonials' ) ) {
		function get_testimonials($pages = '') {
			$$pages = (empty($pages)) ? -1 : $pages;
			$args = array(
					'posts_per_page' => $pages,
					'post_type' => 'testimonials',
					'orderby' => 'date',
					'order' => 'DESC',
			);

			$query = new WP_Query($args);
	
			if ($query->have_posts()) {
					while ( $query->have_posts() ) : $query->the_post();
							get_template_part('/template-parts/testimonial-item');?>
					<?php endwhile;
					wp_reset_postdata();
			}
	
	}
}