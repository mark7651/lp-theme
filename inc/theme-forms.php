<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Contact Form Shortcodes 
 * ------------------------------------------------------------------------------------------------
 */

if (! function_exists('lp_contact_form_shortcode')) {
    function lp_contact_form_shortcode()
    {
        ob_start();
        get_template_part('template-parts/forms/form', 'contact');
        return ob_get_clean();
    }
    add_shortcode('form-contact', 'lp_contact_form_shortcode');
}

if (! function_exists('lp_callback_form_shortcode')) {
    function lp_callback_form_shortcode()
    {
        ob_start();
        get_template_part('template-parts/forms/form', 'callback');
        return ob_get_clean();
    }
    add_shortcode('form-callback', 'lp_callback_form_shortcode');
}

/**
 * ------------------------------------------------------------------------------------------------
 * Custom SMTP mailer
 * ------------------------------------------------------------------------------------------------
 */

function lp_send_smtp_email($phpmailer)
{
    if (! function_exists('get_field') || ! get_field('enable_smtp', 'option')) {
        return;
    }

    // Retrieve SMTP settings
    $host     = get_field('smtp_host', 'option');
    $port     = get_field('smtp_port', 'option');
    $username = get_field('smtp_username', 'option');
    $password = get_field('smtp_password', 'option');
    $secure   = get_field('smtp_secure', 'option') ?: 'ssl'; // Default to 'ssl' if not set

    if (empty($host) || empty($port) || empty($username) || empty($password)) {
        return;
    }

    // Configure PHPMailer for SMTP
    $phpmailer->isSMTP();
    $phpmailer->CharSet    = 'UTF-8';
    $phpmailer->Host       = sanitize_text_field($host);
    $phpmailer->Port       = absint($port);
    $phpmailer->SMTPSecure = in_array($secure, ['ssl', 'tls'], true) ? $secure : 'ssl';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = sanitize_text_field($username);
    $phpmailer->Password   = $password;
    $phpmailer->From       = sanitize_email($username);
    $phpmailer->FromName   = esc_attr(get_bloginfo('name'));

    // Ensure AltBody is set for plain-text fallback
    $phpmailer->AltBody = wp_strip_all_tags($phpmailer->Body);

    // Optional: Add Reply-To (uncomment and configure if needed)
    // $phpmailer->addReplyTo( sanitize_email( 'reply@example.com' ), esc_attr__( 'Information', 'lp-seo' ) );
}

add_action('phpmailer_init', 'lp_send_smtp_email');


/**
 * ------------------------------------------------------------------------------------------------
 * email template
 * ------------------------------------------------------------------------------------------------
 */

function email_template($content)
{
    $logo_rastr = get_field('header_logo', 'option') ?: [];
    $logo = esc_url($logo_rastr['url'] ?? get_template_directory_uri() . '/assets/default-logo.png');
    $site_name = get_bloginfo('name');
    $site_url = home_url();

    $output = '
     <!DOCTYPE html>
     <html lang="en">
     <head>
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
         <title>' . esc_html($site_name) . '</title>
         <style>
             /* GLOBAL RESETS */
             body {
                 font-family: Helvetica, sans-serif;
                 -webkit-font-smoothing: antialiased;
                 font-size: 16px;
                 line-height: 1.3;
                 -ms-text-size-adjust: 100%;
                 -webkit-text-size-adjust: 100%;
                 background-color: #f4f5f6;
                 margin: 0;
                 padding: 0;
             }
             table {
                 border-collapse: separate;
                 mso-table-lspace: 0pt;
                 mso-table-rspace: 0pt;
                 width: 100%;
             }
             table td {
                 font-family: Helvetica, sans-serif;
                 font-size: 16px;
                 vertical-align: top;
             }
             /* BODY & CONTAINER */
             .body {
                 background-color: #f4f5f6;
                 width: 100%;
             }
             .container {
                 margin: 0 auto !important;
                 max-width: 600px;
                 padding: 0;
                 padding-top: 24px;
                 width: 600px;
             }
             .content {
                 box-sizing: border-box;
                 display: block;
                 margin: 0 auto;
                 max-width: 600px;
                 padding: 0;
             }
             /* HEADER, FOOTER, MAIN */
             .main {
                 background: #ffffff;
                 border: 1px solid #eaebed;
                 border-radius: 16px;
                 width: 100%;
             }
             .header {
                 padding: 24px;
                 text-align: center;
             }
             .wrapper {
                 box-sizing: border-box;
                 padding: 24px;
             }
             .footer {
                 clear: both;
                 padding-top: 24px;
                 text-align: center;
                 width: 100%;
             }
             .footer td, .footer p, .footer span, .footer a {
                 color: #9a9ea6;
                 font-size: 16px;
                 text-align: center;
             }
             /* TYPOGRAPHY */
             p {
                 font-family: Helvetica, sans-serif;
                 font-size: 16px;
                 font-weight: normal;
                 margin: 0;
                 margin-bottom: 16px;
             }
             a {
                 color: #0867ec;
                 text-decoration: underline;
             }
             .logo {
                 max-width: 200px;
                 height: auto;
             }
             /* RESPONSIVE STYLES */
             @media only screen and (max-width: 640px) {
                 .main p, .main td, .main span { font-size: 16px !important; }
                 .wrapper { padding: 8px !important; }
                 .content { padding: 0 !important; }
                 .container { padding: 0 !important; padding-top: 8px !important; width: 100% !important; }
                 .main { border-left-width: 0 !important; border-radius: 0 !important; border-right-width: 0 !important; }
             }
             /* PRESERVE STYLES */
             @media all {
                 .ExternalClass { width: 100%; }
                 .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
                 .apple-link a { color: inherit !important; font-family: inherit !important; font-size: inherit !important; font-weight: inherit !important; line-height: inherit !important; text-decoration: none !important; }
                 #MessageViewBody a { color: inherit; text-decoration: none; font-size: inherit; font-family: inherit; font-weight: inherit; line-height: inherit; }
             }
         </style>
     </head>
     <body>
         <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
             <tr>
                 <td>&nbsp;</td>
                 <td class="container">
                     <div class="content">
                         <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="main">
                             <tr>
                                 <td class="header">
                                     <img src="' . $logo . '" alt="' . esc_attr($site_name) . '" class="logo">
                                 </td>
                             </tr>
                             <tr>
                                 <td class="wrapper">
                                     ' . $content . '
                                 </td>
                             </tr>
                         </table>
                         <div class="footer">
                             <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                 <tr>
                                     <td class="content-block">
                                         <span class="apple-link">' . esc_html($site_name) . '</span>
                                     </td>
                                 </tr>
                             </table>
                         </div>
                     </div>
                 </td>
                 <td>&nbsp;</td>
             </tr>
         </table>
     </body>
     </html>';

    return $output;
}


/**
 * ------------------------------------------------------------------------------------------------
 * mail functions
 * ------------------------------------------------------------------------------------------------
 */

function email_recipients()
{
    $recipients = [get_option('admin_email', '')];

    $callback_email = get_field('callback_email', 'option');
    $contact_email = get_field('contact_email', 'option');

    if (isset($_POST["callback-form"]) && !empty($callback_email)) {
        $recipients[] = sanitize_email($callback_email);
    }

    if (isset($_POST["contact-form"]) && !empty($contact_email)) {
        $recipients[] = sanitize_email($contact_email);
    }

    return array_filter($recipients);
}


class LP_Forms
{
    private static $secret_token = '5174804345017800239';

    private static function verify_submission()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'lp-nonce')) {
            return ['code' => 403, 'message' => esc_html__('Verification error, try again', 'lptheme')];
        }

        if (
            !isset($_POST['contact_secret']) ||
            !isset($_POST['honey_field']) ||
            $_POST['contact_secret'] !== self::$secret_token ||
            !empty($_POST['honey_field'])
        ) {
            return ['code' => 400, 'message' => esc_html__('Invalid submission', 'lptheme')];
        }

        return true;
    }

    private static function process_attachments()
    {
        if (!isset($_FILES['attachment']) || $_FILES['attachment']['error'][0] == 4) {
            return [];
        }

        $attachments = [];
        $upload_overrides = ['test_form' => false];

        foreach ($_FILES['attachment']['name'] as $key => $value) {
            if (!$_FILES['attachment']['name'][$key]) continue;

            $file = [
                'name' => $_FILES['attachment']['name'][$key],
                'type' => $_FILES['attachment']['type'][$key],
                'tmp_name' => $_FILES['attachment']['tmp_name'][$key],
                'error' => $_FILES['attachment']['error'][$key],
                'size' => $_FILES['attachment']['size'][$key]
            ];

            $movefile = wp_handle_upload($file, $upload_overrides);
            if (isset($movefile['file'])) {
                $attachments[] = $movefile['file'];
            }
        }

        return $attachments;
    }

    public static function lp_send_message()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            wp_send_json(['message' => esc_html__('Method Not Allowed', 'lptheme'), 'code' => 405]);
        }

        $verification = self::verify_submission();
        if ($verification !== true) {
            wp_send_json($verification);
        }

        $fields = [
            'name' => sanitize_text_field($_POST['name'] ?? ''),
            'last_name' => sanitize_text_field($_POST['last-name'] ?? ''),
            'company' => sanitize_text_field($_POST['company'] ?? ''),
            'phone' => sanitize_text_field($_POST['phone'] ?? ''),
            'email' => sanitize_email($_POST['email'] ?? ''),
            'job' => sanitize_text_field($_POST['job-title'] ?? ''),
            'message' => sanitize_textarea_field($_POST['message'] ?? ''),
        ];

        $body = '<h2><strong>New Submission</strong></h2>';
        foreach ($fields as $key => $value) {
            if ($value) {
                $body .= '<p><strong>' . ucfirst(str_replace('_', ' ', $key)) . ':</strong> ' . esc_html($value) . '</p>';
            }
        }

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
        ];
        if (!empty($fields['email'])) {
            $headers[] = 'From: ' . $fields['name'] . ' <' . $fields['email'] . '>';
            $headers[] = 'Reply-To: ' . $fields['email'];
        } else {
            $headers[] = 'From: ' . $fields['name'] . ' <no-reply@yourdomain.com>';
        }
        $attachments = self::process_attachments();
        $recipients = email_recipients();

        $sent = wp_mail(
            $recipients,
            'New Submission',
            email_template($body),
            $headers,
            $attachments
        );

        // Clean up attachments
        foreach ($attachments as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }

        wp_send_json([
            'message' => $sent
                ? esc_html__('Message sent successfully!', 'lptheme')
                : esc_html__('Failed to send message', 'lptheme'),
            'code' => $sent ? 200 : 500
        ]);
    }

    public static function lp_mail_content_type()
    {
        return 'text/html';
    }
}

add_action('wp_ajax_lp_send_message', ['LP_Forms', 'lp_send_message']);
add_action('wp_ajax_nopriv_lp_send_message', ['LP_Forms', 'lp_send_message']);
add_filter('wp_mail_content_type', ['LP_Forms', 'lp_mail_content_type']);
