<?php

function shortcode_list_certificates() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'info_certificates';
    $results = $wpdb->get_results("SELECT * FROM $table_name");
    ?>
    <div>
        <h1>Certificados</h1>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cédula</th>
                    <th>Chip</th>
                    <th>Categoría</th>
                    <th>Fecha de emisión</th>
                    <th>Estado</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Certificado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result) { ?>
                    <tr>
                        <td><?php echo $result->first_name ?></td>
                        <td><?php echo $result->last_name ?></td>
                        <td><?php echo $result->cedula ?></td>
                        <td><?php echo $result->chip ?></td>
                        <td><?php echo $result->categoria ?></td>
                        <td><?php echo $result->date_emision ?></td>
                        <td><?php echo $result->certificate_status ?></td>
                        <td><?php echo $result->phone ?></td>
                        <td><?php echo $result->email ?></td>
                        <!-- obten el link del certificado -->
                        <?php $certificate_link = get_home_url() . '/wp-content/plugins/k9-certificates/certificates/' . $result->cedula . '.pdf'; ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php
}

add_shortcode('list_certificates', 'shortcode_list_certificates');