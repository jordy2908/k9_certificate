<?php

function cron_validates_certificates() {
    global $wpdb;
    $query = "SELECT * FROM wp_info_certificates WHERE date_expiration < NOW()";
    $results = $wpdb->get_results($query);

    foreach ($results as $result) {
        $wpdb->update(
            'wp_info_certificates',
            array(
                'certificate_status' => 'vencido',
                'is_active' => false
            ),
            array(
                'id' => $result->id
            )
        );
    }
}

// add_action('k9_cron_hook', 'cron_validates_certificates');