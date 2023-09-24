<?php
require_once plugin_dir_path(__FILE__) . 'forms.php';

/**
 * admin page
 */

function menu() {

    add_menu_page(
        'certificados',
        'certificados',
        'manage_options',
        'certificados',
        'certificates_admin_page',
    );

    add_submenu_page(
        'certificados',
        'users',
        'users',
        'manage_options',
        'users',
        'users_admin_page',
    );
}

function certificates_admin_page() {
    register_data_certificates();
}