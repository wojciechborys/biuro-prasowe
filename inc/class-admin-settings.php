<?php 

namespace PERN;

class AdminSettings {

    public function __construct() {
        add_action('acf/init', [$this, 'acf_add_options_page']);
        add_action('acf/init', [$this, 'acf_add_fields']);
    }

    public function acf_add_options_page() {
        acf_add_options_page([
            'page_title' => 'Ustawienia Biuro Prasowe',
            'menu_title' => 'Ustawienia Biuro Prasowe',
            'menu_slug' => 'pern-settings',
            'capability' => 'manage_options',
            'icon_url' => 'dashicons-media-text',
            'redirect' => false,
        ]);
    }

    public function acf_add_fields() {
        if (function_exists('acf_add_local_field_group')) {
            
            $json_file_path = PLUGIN_DIR . '/acf-json/acf-fields.json';
            
            if (file_exists($json_file_path)) {
                $json_data = file_get_contents($json_file_path);
                $acf_fields = json_decode($json_data, true);
                acf_add_local_field_group($acf_fields);
            }
        }
    }
}