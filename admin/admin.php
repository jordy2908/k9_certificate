<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once('forms.php');

/**
 * admin page
 */

function menu() {

    // Verifica si el usuario actual tiene el rol 'certificates'
    if (current_user_can('certificates')) { 
        add_menu_page(
            'K9 Certificados', // page title
            'K9 Certificados', // menu title
            'manage_options', // capability
            'certificados', // menu slug
            'certificates_admin_page', // callback function
        );
    } else {
        
        add_menu_page(
            'K9 Certificados', // page title
            'K9 Certificados', // menu title
            'manage_options', // capability
            'certificados', // menu slug
            'certificates_admin_page', // callback function
        );
    
        add_submenu_page(
            'certificados', // parent slug
            'Usuarios', // page title
            'Usuarios', // menu title
            'manage_options', // capability
            'users', // menu slug
            'users_admin_page', // callback function
        );

    }

}

function certificates_admin_page() {
    ?>
    <div>
        <h1>Certificados</h1>
    </div>
    <nav>
        <ul>
            
            <li><a href="admin.php?page=users">Usuarios</a></li>
        </ul>
    </nav>
    <?php

    register_data_certificates();
}