<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Subscription functionality
 * ------------------------------------------------------------------------------------------------
 */

 if( ! get_field( 'enable_subscriptions','option' ) ) return;

 if ( ! function_exists( 'subscriptions_init' ) ) {
   add_action( 'init', 'subscriptions_init', 10 );
   function subscriptions_init() {
     $labels = array(
       'name' => esc_html__( 'Подписчики', 'lptheme' ),
       'singular_name' => esc_html__( 'Подписчики', 'lptheme' ),
       'add_new' => esc_html__( 'Добавить подписку', 'lptheme' ),
       'add_new_item' => esc_html__( 'Добавить подписку', 'ltheme' ),
       'edit_item' => esc_html__( 'Редактировать подписку', 'lptheme' ),
       'new_item' => esc_html__( 'Добавить подписку', 'lptheme' ),
       'view_item' => esc_html__( 'Смотреть подписку', 'lptheme' ),
       'search_items' => esc_html__( 'Поиск', 'lptheme' ),
       'not_found' => esc_html__( 'Подписки не найдены', 'lptheme' ),
       'not_found_in_trash' => esc_html__( 'Корзина пуста', 'lptheme' )
     );
     $args = array(
       'labels' => $labels,
       'public' => true,
       'show_ui' => true,
       'supports' => array( 'title' ),
       'capability_type' => 'post',
       'menu_position' => 7,
       'query_var' => true,
       'has_archive' => false,
       'hierarchical'  => false,
       'menu_icon' => 'dashicons-email-alt',
       'publicly_queryable' => false, 
       'exclude_from_search' => true,
       'show_in_nav_menus' => false,
       'register_meta_box_cb' => 'subscriptions_meta_boxes',
     );
     $args = apply_filters('subscription_args', $args);
     register_post_type('subscriptions', $args);
     flush_rewrite_rules();
   }
 }

 // add meta boxes =============================================================================
function subscriptions_meta_boxes() {
	add_meta_box( 'subscriptions_admin_form', 'Данные подписки', 'subscriptions_admin_form', 'subscriptions', 'normal', 'high' );
}
function subscriptions_admin_form() {
	$post_id = get_the_ID();
	$subscription_data = get_post_meta( $post_id, 'subscription', true );
	$client_email = ( empty( $subscription_data['subscription_email'] ) ) ? '' : $subscription_data['subscription_email'];
	wp_nonce_field( 'subscriptions', 'subscriptions' );
	?>

<div class="stuffbox">
	<div class="inside">
		<fieldset>
			<table class="form-table editcomment" role="presentation">
				<tbody>
					<tr>
						<td class="first"><label for="email">Email</label></td>
						<td>
							<input type="email" id="email" value="<?php echo $client_email; ?>" name="subscription[subscription_email]"
								aria-describedby="email-description" size="30">
						</td>
					</tr>

				</tbody>
			</table>
		</fieldset>

	</div>
</div>
	

	<?php
}

// save metabox info =============================================================================

add_action('save_post', 'subscriptions_save_post');
function subscriptions_save_post($post_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	if (!isset($_POST['subscriptions']) || !wp_verify_nonce($_POST['subscriptions'], 'subscriptions'))
		return;
	if (!isset($_POST['post_type']) || 'page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return;
	} else {
		if (!current_user_can('edit_post', $post_id))
			return;
	}
	if (!wp_is_post_revision($post_id) && 'subscriptions' == get_post_type($post_id)) {
		remove_action('save_post', 'subscriptions_save_post');
		wp_update_post(array(
			'ID' => $post_id,
		));
		add_action('save_post', 'subscriptions_save_post');
	}
	if (!empty($_POST['subscription'])) {
		$subscription_data['subscription_email'] = (empty($_POST['subscription']['subscription_email'])) ? '' : sanitize_text_field($_POST['subscription']['subscription_email']);
		update_post_meta($post_id, 'subscription', $subscription_data);

	} else {
		delete_post_meta($post_id, 'subscription');
	}
}


// admin column info =============================================================================
add_filter( 'manage_edit-subscriptions_columns', 'subscriptions_edit_columns' );
function subscriptions_edit_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => 'Заголовок',
		'subscription-client-email' => 'Email',
		'date' => 'Дата'
	);
	return $columns;
}

add_action( 'manage_posts_custom_column', 'subscriptions_columns', 10, 2 );
function subscriptions_columns( $column, $post_id ) {
	$subscription_data = get_post_meta( $post_id, 'subscription', true );
	if ( ! empty( $subscription_data['subscription_email'] ) )
				echo $subscription_data['subscription_email'];
}


/**
 * ------------------------------------------------------------------------------------------------
 * form shortcode
 * ------------------------------------------------------------------------------------------------
 */

 if ( ! function_exists( 'subscriptions_form_shortcode' ) ) {
	function subscriptions_form_shortcode() {
			ob_start();
			get_template_part( 'template-parts/forms/form', 'subscription' ); 
			return ob_get_clean();
	}
	add_shortcode( 'subscription-form', 'subscriptions_form_shortcode' );
}


/**
 * ------------------------------------------------------------------------------------------------
 * Ajax create subscription (listing)
 * ------------------------------------------------------------------------------------------------
 */

 if( !function_exists('create_subscription')) {
	function create_subscription($data) {
		if (isset($data['subscription-email'])) {

			$email = sanitize_email($data['subscription-email']);

			$existing_email = get_posts(array(
					'post_type' => 'subscriptions',
					'meta_key' => 'subscription_email',
					'meta_value' => $email,
			));

			if (empty($existing_email)) {

						$subscription_data = array(
							'subscription_email' => $email
						);

						$new_testimonial = array(
							'post_title'   => 'Подписка - '.$email,
							'post_status'=> 'publish',
							'post_type'=> 'subscriptions',
						);

						$post_id = wp_insert_post($new_testimonial);
					

					if ($post_id) {

						update_post_meta($post_id, 'subscription', $subscription_data);
				
						// Send admin notice ==============================
						if(get_post_type($post_id) !== 'subscriptions' && get_post_status($post_id) == 'draft') {
							return;
						}
						
						$post_title = get_the_title($post_id);
						$post_url = get_edit_post_link($post_id);
						$subject 	= "Новая подписка";
						$body 	= "Новая подписка на сайте:\n\n";
						$body   .= "<a href=".$post_url.">$post_title</a>";
				
						$administrators = get_users(array(
							'role'	=> 'administrator'
						));
				
						foreach ($administrators as &$administrator) {
							wp_mail( $administrator->data->user_email, $subject, $body );
						}

						return true;

					}  
			}

		} else {
			return false;
		}
		
	}
}

/**
 * ------------------------------------------------------------------------------------------------
 * add subscription to db
 * ------------------------------------------------------------------------------------------------
 */

class subscription_form {
   
	public static function send_subscription() {

			$responce_message = '';
			$response_code = '';

			if ($_SERVER["REQUEST_METHOD"] == "POST") {

					if (! wp_verify_nonce( $_POST['nonce'], 'lp-nonce')) {
							$responce_message = esc_html__( 'Verification error, try again', 'lptheme' );
					} else {

						$response_code = 200;
						$success = create_subscription($_POST);

							if ($success == true) {
								$responce_message =  translate_pll( 'Спасибо за подписку!', 'Дякуємо за підписку!' ); 

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

add_action('wp_ajax_send_subscription', array('subscription_form', 'send_subscription') );
add_action('wp_ajax_nopriv_send_subscription', array('subscription_form', 'send_subscription') );
add_filter('wp_mail_content_type', array('subscription_form', 'lp_mail_content_type') );

/**
 * ------------------------------------------------------------------------------------------------
 * notification to user when new post created
 * ------------------------------------------------------------------------------------------------
 */

function notify_subscribers_on_new_post($new_status, $old_status, $post ) {
	if (($new_status === 'publish' && $post->post_type === 'post') || ($old_status === 'publish' && $post->post_type === 'post')) {

			$args = array(
				'post_type' => 'subscriptions',
				'posts_per_page' => -1,
			);

		$subscribers = get_posts($args);

		$post_title = get_the_title($post->ID);
		$post_url = get_permalink($post->ID);

		$subject = 'Нова стаття';
		$body 	= "Нова стаття на сайті Fashion Public. Перейдіть в онлайн-журнал, щоб ознайомитись:\n\n";
		$body  .= "<a href=".$post_url.">$post_title</a>";

		foreach ($subscribers as $subscriber) {
				$subscriber_data = get_post_meta( $subscriber->ID, 'subscription', true );
				$email = ( empty( $subscriber_data['subscription_email'] ) ) ? '' : $subscriber_data['subscription_email'];
				wp_mail( $email, $subject, $body );
		}
	}

}
add_action( 'transition_post_status', 'notify_subscribers_on_new_post', 10, 3 );
