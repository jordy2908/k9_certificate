<?php
function shortcode_list_certificates() {
    
    $t = date('YmdHis');

    wp_enqueue_style('style', plugins_url('/includes/main.css', __FILE__));
    
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css');
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'info_certificates';
    $results = [];

    if (isset($_POST['search_type']) && !empty($_POST['search_type'])) {
        $content = sanitize_text_field($_POST['content']);
        $search_type = sanitize_text_field($_POST['search_type']);

        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE $search_type LIKE '%%%s%%' ORDER BY id DESC", $content);
        $results = $wpdb->get_results($query);
    } else {
        $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
    }
    
    ob_start();
    ?>
    <div class="container-shortcode">
        <h1>Certificados</h1>
        <div class='cont-k9'>
            <form method="post" id='k9_form'>
                <select name="search_type" id="cedula">
                    <option value="" disabled>Buscar por</option>
                    <option value="first_name">Nombre</option>
                    <option value="cedula">Cédula</option>
                    <!-- <option value="certificado">N° Certificado</option> -->
                </select>

                <input type="text" name="content">

                <input type="submit" value="Buscar">
            </form>
        </div>
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
                    <th>Certificado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result) { ?>
                    <tr>
                        <td><?php echo esc_html($result->first_name); ?></td>
                        <td><?php echo esc_html($result->last_name); ?></td>
                        <td><?php echo esc_html($result->cedula); ?></td>
                        <td><?php echo esc_html($result->chip); ?></td>
                        <td><?php echo esc_html($result->categoria); ?></td>
                        <td><?php echo esc_html($result->date_emision); ?></td>
                        <td><?php echo esc_html($result->certificate_status); ?></td>
                        <?php $certificate_link = plugins_url('/k9_certificate/' . esc_attr($result->cedula) . '_certificado.docx'); ?>
                        <td><a href="<?php echo esc_url($certificate_link); ?>" target="_blank" rel="noopener noreferrer"><i class="fas fa-download"></i></a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php if (empty($results)) { ?>
            <p id="alert">No hay registro</p>
        <?php } ?>
    </div>
    <style>
        #cedula, input {
            padding: .3rem !important;
        }
    </style>
    <?php

    // Obtén el contenido del búfer y límpialo
    return ob_get_clean();

}

add_shortcode('list_certificates', 'shortcode_list_certificates');
