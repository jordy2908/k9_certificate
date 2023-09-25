<?php
/**
 * Plugin Name: K9 certificates
 * Plugin URI: 
 * Description: Gestiona certificados.
 * Version: 1.0.1
 * Author: JEC.
 * Author URI:
 * License: GNU GENERAL PUBLIC LICENSE
 */

// Incluye el archivo upgrade.php para tener acceso a dbDelta()

require_once plugin_dir_path(__FILE__) . 'admin/admin.php';

// Create tables

function certificates_activate() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    $table_name = $wpdb->prefix . 'info_certificates';
    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        first_name varchar(255) NOT NULL,
        last_name text NOT NULL,
        cedula varchar(13) NOT NULL,
        chip varchar(255) NOT NULL,
        categoria varchar(255) NOT NULL,
        date_emision datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        certificate_status varchar(255) NOT NULL,
        phone text NOT NULL,
        email text NOT NULL,
        certificate_path text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'certificates_activate');

// Register admin menu
add_action('admin_menu', 'menu');
