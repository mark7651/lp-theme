<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Contact Form Shortcodes 
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'lp_contact_form_shortcode' ) ) {
    function lp_contact_form_shortcode() {
        ob_start();
        get_template_part( 'template-parts/forms/form', 'contact' ); 
        return ob_get_clean();
    }
    add_shortcode( 'form-contact', 'lp_contact_form_shortcode' );
}

if ( ! function_exists( 'lp_callback_form_shortcode' ) ) {
    function lp_callback_form_shortcode() {
        ob_start();
        get_template_part( 'template-parts/forms/form', 'callback' ); 
        return ob_get_clean();
    }
    add_shortcode( 'form-callback', 'lp_callback_form_shortcode' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Custom SMTP mailer
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'send_smtp_email' ) ) {
    add_action( 'phpmailer_init', 'send_smtp_email' );
    function send_smtp_email( $phpmailer ) {
        if( !get_field( 'enable_smtp' , 'option' ) ) return;
        $phpmailer->isSMTP();
        $phpmailer->CharSet    = 'UTF-8';
        $phpmailer->Host       = get_field('smtp_host', 'option');
        $phpmailer->Port       = get_field('smtp_port', 'option');
        $phpmailer->SMTPSecure = 'ssl';
        $phpmailer->SMTPAuth   = true;
        $phpmailer->Username   = get_field('smtp_username', 'option');
        $phpmailer->Password   = get_field('smtp_password', 'option');
        $phpmailer->From       = get_field('smtp_username', 'option');
        $phpmailer->FromName   = esc_attr(get_bloginfo( 'name' ));
        //$phpmailer->addReplyTo('lpunity.info@gmail.com', 'Information');
        $phpmailer->AltBody = strip_tags($phpmailer->Body);
    }
}


/**
 * ------------------------------------------------------------------------------------------------
 * email template
 * ------------------------------------------------------------------------------------------------
 */

 function email_template($content) {
    $logo_rastr = get_field('header_logo', 'option');
    $logo = esc_url($logo_rastr['url']);
    $site_name = get_bloginfo('name');
    $site_url = get_bloginfo('url');

    ob_start();
    $output = '
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="utf-8">
                <title>' . $site_name . '</title>
            </head>

            <body>
                <div style="max-width: 600px; margin: 0 auto;">
                <table role="presentation"
                style="width:100%; max-width:600px;border-collapse:collapse;border:0;border-spacing:0;background:black;">
                <tr>
                    <td align="center" style="padding:0;">
                        <table role="presentation"
                            style="width:100%;border-collapse:collapse;border-spacing:0;text-align:left;">
                            <thead>
                                <tr>
                                    <td align="center" style="padding:40px 0 30px 0;background:#333;">
                                        <img src="'. $logo .'" alt="logo" width="200"
                                        style="height:auto;display:block;">
                                    </td>
                                </tr>
                            </thead>
                            <tr>
                                <td style="padding:36px 30px 42px 30px;">
                                    <table role="presentation"
                                        style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                        <tr>
                                            <td style="padding:0;">
                                                <table role="presentation"
                                                    style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                                    <tr>
                                                        <td style="color: #fff;line-height: 1.5;">
                                                        '.$content.'
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:30px;background:#333;">
                                    <table role="presentation"
                                        style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:16px;font-family:Arial,sans-serif;">
                                        <tr>
                                            <td style="padding:0;width:40%;" align="left">
                                                <p
                                                    style="margin:0;font-size:16px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                                    <a href="' . $site_url . '">
                                                        ' . $site_name . '
                                                    </a>
                                                </p>
                                            </td>
                                            <td style="padding:0;width:60%;" align="right">
                                                <table role="presentation"
                                                    style="border-collapse:collapse;border:0;border-spacing:0;">
                                                    <tr>
                                                        <td style="padding:0 0 0 10px; font-size:16px;">

                                                        </td>

                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
                </div>
            </body>

        </html>
    ';
    ob_get_clean();
    return $output;
    
}


 /**
 * ------------------------------------------------------------------------------------------------
 * mail functions
 * ------------------------------------------------------------------------------------------------
 */

if (!function_exists('email_recipients')) {
    function email_recipients() {

        $admin_email = get_option('admin_email');

        $callback_email = get_field('callback_email', 'option');
        $contact_email = get_field('contact_email', 'option');

        $form_callback = isset($_POST["callback-form"]);
        $form_contact = isset($_POST["contact-form"]);

        $recipients = array();
        $recipients[] = $admin_email;


        if ($form_callback && !empty($callback_email)) {
            $recipients[] = $callback_email;
        }

        if ($form_contact && !empty($contact_email)) {
            $recipients[] = $contact_email;
        }

        return $recipients;
    }
}



class LP_Forms {

    private static function hasValidToken() {
        $token = '5174804345017800239';
        if (isset($_POST['contact_secret']) && isset($_POST['honey_field'])) {
            return $_POST['contact_secret'] === $token && $_POST['honey_field'] === '';
        }
        return false;
    }    

    public static function lp_send_message() {

        $responce_message = '';
        $response_code = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (! wp_verify_nonce( $_POST['nonce'], 'lp-nonce')) {
                $responce_message = esc_html__( 'Verification error, try again', 'lptheme' );
            } elseif (!self::hasValidToken()) {
                $responce_message = esc_html__( 'SPAM', 'lptheme' );
            } else {

                $to = email_recipients();
                $current_time = date('Y-m-d H:i:s');

                // Sanitize the form data
                $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
                $phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
                $email = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
                $message  = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
                $form_page = isset( $_POST['form_page'] ) ? sanitize_text_field( $_POST['form_page'] ) : '';
                $form_name = isset( $_POST['form_name'] ) ? sanitize_text_field( $_POST['form_name'] ) : '';

                $headers = 'From: ' . $name. "\r\n";
                $headers = 'Reply-to:' . $email. "\r\n";
                
                // Message Template ==============================

				if (isset($_POST["contact-form"])) { // contact form submit
                    $subject = $form_name;
                    $body = '<h2 style="color: #fff;">Данные заявки:</h2>';
                    if($name){ $body .= '<p><b>Имя:</b> '.$name.'</p>';}
                    if($phone){ $body .= '<p><b>Телефон:</b> '.$phone.'</p>';}
					if($email){ $body .= '<p><b>Email:</b> '.$email.'</p>';}
					if($message){ $body .= '<p><b>Сообщение:</b> '.$message.'</p>';}
                    $body .= '<p><b>Время отправки:</b> ' . $current_time . '</p>';

				} else { //other forms submit
					$subject = $form_name;
                    $body = '<h2 style="color: #fff;">Данные заявки:</h2>';
                    if($name){ $body .= '<p><b>Имя:</b> '.$name.'</p>';}
                    if($phone){ $body .= '<p><b>Телефон:</b> '.$phone.'</p>';}
					if($email){ $body .= '<p><b>Email:</b> '.$email.'</p>';}
					if($message){ $body .= '<p><b>Cообщщение:</b> '.$message.'</p>';}
                    if($form_page){ $body .= '<p><b>Страница заявки:</b> '.$form_page.'</p>';}
                    $body .= '<p><b>Время отправки:</b> ' . $current_time . '</p>';
				}

                // Message Template ==============================
    
                // attachments
                if ( isset( $_FILES['attachment'] ) && $_FILES['attachment']['error'][0] != 4 ) {
                    $files = $_FILES[ 'attachment' ];
                    $upload_overrides = array( 'test_form' => false );
                    $attachments = array();
                    foreach ( $files['name'] as $key => $value ) {
                        if ( $files[ 'name' ][ $key ] ) {
                            $file = array(
                                'name' => $files[ 'name' ][ $key ],
                                'type' => $files[ 'type' ][ $key ],
                                'tmp_name' => $files[ 'tmp_name' ][ $key ],
                                'error' => $files[ 'error' ][ $key ],
                                'size' => $files[ 'size' ][ $key ]
                            );
                            $movefile = wp_handle_upload($file,$upload_overrides);
                            $attachments[] = $movefile[ 'file' ];
                        }
                    }
                }

                if ( wp_mail($to, $subject, email_template($body), $headers, $attachments) ) {
                    
                    $response_code = 200;
                    $responce_message =  esc_html__( 'Your message has been successfully sent!', 'lptheme' );

                    // remove files after been sent
                    if ( isset( $_FILES['attachment'] ) && $_FILES['attachment']['error'][0] != 4 ) {
                        foreach ( (array)$attachments as $file ) {
                            if( file_exists($file) ) {
                            unlink($file);
                            }
                        }
                    }
                    
                    // send data to telegram
                    
                    $token = get_field('telegram_token' , 'option');
                    $chat_id = get_field('telegram_chat_id' , 'option');

                    if (isset ($token) && ($chat_id)) {
                        $sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$message}","r");
                    }

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

add_action('wp_ajax_lp_send_message', array('LP_Forms', 'lp_send_message') );
add_action('wp_ajax_nopriv_lp_send_message', array('LP_Forms', 'lp_send_message') );
add_filter('wp_mail_content_type', array('LP_Forms', 'lp_mail_content_type') );