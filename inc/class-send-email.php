<?php 

namespace PERN;

class SendEmail {

    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_sender_scripts']);
        add_action('wp_ajax_send_emails', [$this, 'send_emails']);
    }

    private function generate_user_identifier() {
        return wp_generate_password(12, false);
    }

    public function enqueue_sender_scripts() {
        wp_enqueue_script(
            'send-email',
            plugin_dir_url(__FILE__) . '../assets/js/send-email.js',
            array('jquery'), 
            null,
            true
        );
        wp_localize_script('send-email', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }

    public function custom_email_variables($post_id, $tpl_index) {
        $post_content = get_post_field('post_content', $post_id);
        $post_content = strip_shortcodes($post_content);
        $formatted_content = wpautop($post_content);
        $post_link = get_permalink($post_id);
        $post_title = get_the_title($post_id);
        $user_identifier = $this->generate_user_identifier();
        $variables = array(
            '%%POST_ID%%' => $post_id,
            '%%POST_CONTENT%%' => $formatted_content,
            '%%POST_LINK%%' => $post_link,
            '%%POST_TITLE%%' => $post_title,
            '%%USER_ID%%' => $user_identifier,
        );
    
        // Pobierz szablon wiadomości z pola ACF
        $tpl = get_field('my_select_values', 'option');
        $tpl_content = isset($tpl[$tpl_index]['tpl']) ? $tpl[$tpl_index]['tpl'] : '';
    
        // Zastąp zmienne w szablonie treści wiadomości
        foreach ($variables as $key => $value) {
            $tpl_content = str_replace($key, $value, $tpl_content);
        }
    
        return $tpl_content;
    }

    public function send_emails() {
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Brak uprawnień do wykonania tej akcji.');
            wp_die();
        }
    
        $allEmails = array();
        $tpl = get_field('my_select_values', 'option');
        $emails = $_POST['emails'];
        $post_id = $_POST['post_id'];

        $sent_count = 0;
        if (empty($emails) || empty($tpl) || $post_id === 0) {
            wp_send_json_error('Brak danych do przetworzenia.');
            wp_die();
        }
        
        $current_sent_count = get_post_meta($post_id, 'mails_sent', true);
        $sent_count = $current_sent_count ? intval($current_sent_count) : 0;

        $failed_sent_count = get_post_meta($post_id, 'mails_failed', true);
        $failed_count = $failed_count ? intval($failed_count) : 0;

        foreach ($emails as $list) {
            $tpl_index = $list['template'];
            $message = $this->custom_email_variables($post_id, $tpl_index);
            $subject = 'PERN Biuro Prasowe';
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $recipients = $list['emails'];
            $mail_sent = null;
            foreach ($recipients as $recipient) {
                $mail_sent = wp_mail($recipient, $subject, $message, $headers);

                if ($mail_sent) {
                    $sent_count++;
                }
            }

            update_post_meta($post_id, 'mails_sent', $sent_count);

            if (!$mail_sent) {
                $failed_sent_count++;
                error_log('Błąd podczas wysyłania e-maila do: ' . implode(', ', $recipients));
                update_post_meta($post_id, 'mails_failed', $failed_sent_count);
            }

        }
    
        wp_send_json_success(array('message' => 'Pomyślnie wysłano wiadomości.', 'sent_count' => $sent_count));
    
        wp_die();
    }  
}