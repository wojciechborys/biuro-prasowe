<?php

namespace PERN;

class PostSender {

    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_repeater_meta_box']);

    }

    public function add_repeater_meta_box() {
        add_meta_box(
            'repeater_fields_box',      // ID meta boxa
            'Biuro Prasowe',          // Tytuł meta boxa
            [$this, 'display_repeater_fields'],  // Funkcja wyświetlająca zawartość meta boxa
            'post',                     // Post type
            'side',                   // Lokalizacja meta boxa (normal, advanced, side)
            'default'                   // Priorytet
        );
    }

    public function display_repeater_fields() {
        // Pobranie pola repeatera na podstawie jego klucza
        $repeater_field = get_field('mailing_lists','option');
        
        if ($repeater_field) {
            echo '<div id="repeater_fields_container">';

            foreach($repeater_field as $field):
                echo '<label>';
                echo '<input type="checkbox" name="selected_repeater_fields[]" value="' . esc_attr($index) . '">';
                echo esc_html($field['name']);
                echo '</label><br>';
            endforeach;

    
            echo '</div>';
            echo '<br><button class="button button-primary" type="submit" name="submit_repeater_fields">Wyślij</button>';
        }
    }
}
