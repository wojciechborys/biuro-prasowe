<?php

namespace PERN;

class CreateForm {

    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_repeater_meta_box']);
        add_shortcode('biuro_tresc', [$this, 'content_shortcode']);
    }

    public function add_repeater_meta_box() {
        add_meta_box(
            'repeater_fields_box',
            'Biuro Prasowe',
            [$this, 'display_repeater_fields'],
            'post',                  
            'normal', // Lokalizacja meta boxa (normal, advanced, side)
            'default'
        );
    }


    public function display_repeater_fields() { 
        $repeater_field = get_field('mailing_lists','option');
        $post_id = $_GET['post'];
        $total_sent = get_post_meta($post_id, 'mails_sent');
        $total_opened = get_post_meta($post_id, 'mails_opened');
        $total_failed = get_post_meta($post_id, 'mails_failed');
        error_log(print_r($total_failed, true));
        if ($repeater_field) {
            echo '<div id="repeater_fields_container">';
            echo '<h3>System mailingowy biura prasowego</h3>';
            echo '<p>Instrukcja obsługi: System mailingowy Biura Prasowego PERN:</br>';
            echo 'Do prawidłowego działania systemu należy stworzyć listy mailingowe składajace się z wybranych adresów email, oraz wybrać szablon e-mail używany do wysyłki.</br>';
            echo 'Ilość list oraz użytkowników znajdujących się na listach jest dowolna. Każdy szablon e-mail posiada zmienne, które można zaimplementować w treści maila.</br>';
            echo 'Aby przejść do konfiguracji Biura Prasowego PERN - kliknij w przycisk ustawienia poniżej. Aby wysłać maila - odznacz prawidłową listę (lub listy), a następni kliknij przycisk "wyślij".</p>';
            echo '<div style="padding: 20px; border: solid 1px #eee">';
            echo '<h3 style="margin: 0; margin-bottom: 15px">Twoje listy mailingowe</h3>';
                foreach($repeater_field as $field):
                    echo '<label style="margin-bottom: 10px; display: block; font-size: 16px">';
                    $usrs = $field['lista'];
                    $name = $field['name'];
                    $nice_name = $field['nice_name'];
                    $template = $field['template']-1;
                    $list = array();
                    foreach($usrs as $user):
                        $list[] = $user['user'];
                    endforeach;
                    echo '<input type="checkbox" name="emails" data-name="'. $name .'" data-template="'. $template .'" value="'. implode(", ",$list) .'">';
                    echo esc_html($nice_name);
                    echo '</label>';
                endforeach;
                ?>
                <!-- 
                <label>
                    <input id="custom_emails_checkbox" type="checkbox" name="emails" data-template="<?php //$template[0] ?>">
                    <input type="text" style="width: 90%; padding: 10px; border-radius: 8px;"  id="custom_emails" placeholder="Wpisz własne adresy" class="inline" />
                </label> -->
                </div>
            </div>
            <div style="margin-top: 30px; border-top: solid 1px #eee; padding: 20px">
                <h3>Statystyki wysyłki e-maili</h3>
                <p style="font-size: 18px">Wysłane maile: <span id="total_sent" style="font-size: 18px; font-weight: bold"><?php echo $total_sent ? $total_sent[0] : 'Brak wysłanych maili'; ?></span></p>
                <p style="font-size: 18px">Wyświetlone maile: <span id="total_sent" style="font-size: 18px; font-weight: bold"><?php echo $total_opened ? $total_opened[0] : '0'; ?></span></p>
                <p style="font-size: 18px">Maile nie wysłane: <span id="total_sent" style="font-size: 18px; font-weight: bold"><?php echo $total_failed ? $total_failed[0] : '0'; ?></span></p>
                <div style="display:flex; gap: 10px; margin-top: 20px">
                <a href="/wp-admin/admin.php?page=pern-settings&lang=pl" class="button button-primary">Ustawienia Biura Prasowego</a>
                <input type="hidden" name="post_id" value="<?php echo get_the_ID() ?>">
                <button type="button" id="send-emails" class="button button-primary" type="submit" name="send-emails">Wyślij</button>
            </div>
            </div>

    <?php }
    }
}
