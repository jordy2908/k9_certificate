<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once('forms.php');

/**
 * admin page
 */

function menu() {

    $capability = (current_user_can('certificates')) ? 'certificates' : 'manage_options';

    add_menu_page(
            'K9 Certificados', // page title
            'K9 Certificados', // menu title
            $capability, // capability
            'certificados', // menu slug
            'certificates_admin_page', // callback function
        );

}

function certificates_admin_page() {
    ?>
    <div>
        <h1>Certificados</h1>
    </div>
    <!-- <nav>
        <ul>
            
            <li><a href="admin.php?page=users">Usuarios</a></li>
        </ul>
    </nav> -->
    <?php

    register_data_certificates();
}