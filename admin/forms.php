<?php

require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpWord\Settings;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use QRcode as GlobalQRcode;

function register_data_certificates(){
    $d = date('YmdHis');

    wp_register_style( 'style', plugins_url('/includes/main.css?t='.$d.'', __FILE__) );
    wp_enqueue_style('style');


    global $wpdb;
    $table_name = $wpdb->prefix . 'info_certificates';
    $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id desc");

    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        $last_name = $_POST['last_name'];
        $cedula = $_POST['cedula'];
        $chip = $_POST['chip'];
        $categoria = $_POST['categoria'];
        $date_emision = $_POST['date_emision'];
        $date_expiration = $categoria == 'guia' || $categoria == 'empresa' ? '' : date("Y-m-d", strtotime(date("Y-m-d", strtotime($date_emision)) . " + 1 year"));
        $certificate_status = $_POST['certificate_status'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        if ($wpdb->get_row("SELECT * FROM $table_name WHERE cedula = $cedula")) {
            $wpdb->update(
                $table_name,
                array(
                    'first_name' => $name,
                    'last_name' => $last_name,
                    'cedula' => $cedula,
                    'chip' => $chip,
                    'categoria' => $categoria,
                    'date_emision' => $date_emision == '' ? date('Y-m-d H:i:s') : $date_emision,
                    'date_expiration' => $date_expiration,
                    'certificate_status' => $certificate_status,
                    'phone' => $phone,
                    'email' => $email
                ),
                array(
                    'cedula' => $cedula
                )
            );
        }
    
        $wpdb->insert(
            $table_name,
            array(
                'first_name' => $name,
                'last_name' => $last_name,
                'cedula' => $cedula,
                'chip' => $chip,
                'categoria' => $categoria,
                'date_emision' => $date_emision == '' ? date('Y-m-d H:i:s') : $date_emision,
                'date_expiration' => $date_expiration,
                'certificate_status' => $certificate_status,
                'phone' => $phone,
                'email' => $email
            )
        );
        echo '<script>window.location.href = "' . $_SERVER['REQUEST_URI'] . '";</script>';

        exit;
    }

    if (isset($_POST['edit_certificate'])) {
        $edit_cedula = $_POST['edit_cedula'];
        edit_certificate($edit_cedula);
    }    

    if (isset($_POST['generate_certificate'])) {
        $cedula = $_POST['cedula'];
        generate_certificate($cedula);
        echo '<script>window.location.href = "' . $_SERVER['REQUEST_URI'] . '";</script>';

    }
    
    ?>

    <div class='cont-new-register'>
        <h2>Nuevo registro</h2>
        <form method="post">
            <div class='item-form'>
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" placeholder="Nombre" required>
            </div>
            <div class='item-form'>
                <label for="last_name">Apellido</label>
                <input type="text" name="last_name" id="last_name" placeholder="Apellido" required>
            </div>
            <div class='item-form'>
                <label for="cedula">Cédula</label>
                <input type="text" name="cedula" id="cedula" placeholder="Cédula" required>
            </div>
            <div class='item-form'>
                <label for="chip">Chip</label>
                <input type="text" name="chip" id="chip" placeholder="Chip" >
            </div>

            <div class='item-form'>
                <label for="categoria">Categoría</label>
                <select name="categoria" id="categoria">
                    <option value="" selected disabled>Selecciona una categoria</option>
                    <option value="guia">Guía</option>
                    <option value="binomio">Binomio</option>
                    <option value="can">Can</option>
                    <option value="empresa">Empresa</option>
                </select>
            </div>
            <div class='item-form'>
                <label for="date_emision">Fecha de emisión</label>
                <input type="date" name="date_emision" id="date_emision" placeholder="Fecha de emisión" required>
            </div>
            <div class='item-form'>
                <label for="certificate_status">Estatus</label>
                <select name="certificate_status" id="certificate_status">
                    <option value="" selected disabled>Estatus del certificado</option>
                    <option value="activo">Activo</option>
                    <option value="consolidado">Consolidado</option>
                    <option value="Vencido">Vencido</option>
                </select>
            </div>
            <div class='item-form'>
                <label for="phone">Teléfono</label>
                <input type="text" name="phone" id="phone" placeholder="Teléfono" required>
            </div>
            <div class='item-form'>
                <label for="email">Correo</label>
                <input type="email" name="email" id="email" placeholder="Correo electrónico" required>
            </div>
            <input type="submit" value="Enviar">
        </form>
    </div>
    <div class='register-table'>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cédula</th>
                    <th>Chip</th>
                    <th>Categoría</th>
                    <th>Fecha de emisión</th>
                    <th>Estado del certificado</th>
                    <th>Teléfono</th>
                    <th>Correo electrónico</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($results as $result) {
                        echo "<tr>";
                        echo "<td>" . $result->first_name . "</td>";
                        echo "<td>" . $result->last_name . "</td>";
                        echo "<td>" . $result->cedula . "</td>";
                        echo "<td>" . $result->chip . "</td>";
                        echo "<td>" . $result->categoria . "</td>";
                        echo "<td>" . $result->date_emision . "</td>";
                        echo "<td>" . $result->certificate_status . "</td>";
                        echo "<td>" . $result->phone . "</td>";
                        echo "<td>" . $result->email . "</td>";
                        // Agregar el botón "Generar Certificado" con un formulario
                        echo '<td>
                            <form method="post">
                                <input type="hidden" name="cedula" value="' . $result->cedula . '" style="width:0px !important">
                                <input type="submit" name="generate_certificate" value="Generar Certificado">
                            </form>
                        </td>';
                        echo '<td>
                            <form method="post">
                                <input type="hidden" name="edit_cedula" value="' . $result->cedula . '" style="width:0px !important">
                                <input type="submit" name="edit_certificate" value="Editar Registro">
                            </form>
                        </td>';
                
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    
    <style>

        td {
            width: 12rem !important;
        }
    input, select {
    min-width: 12rem;
    max-width: 12rem;
    }
        .cont-new-register {
    max-width: 600px;
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
    align-items: start;
}

form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    column-gap: 1rem;
    row-gap: 0.3rem;
}

.item-form {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
    </style>
    <?php
}

function edit_certificate($cedula) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'info_certificates';
    $result = $wpdb->get_row("SELECT * FROM $table_name WHERE cedula = $cedula");

    if ($result) {
        $name = $result->first_name;
        $last_name = $result->last_name;
        $cedula = $result->cedula;
        $chip = $result->chip;
        $categoria = $result->categoria;
        $date_emision = $result->date_emision;
        $certificate_status = $result->certificate_status;
        $phone = $result->phone;
        $email = $result->email;

        // Ahora, puedes utilizar estos valores para llenar el formulario de edición
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var nameElement = document.getElementById("name");
            if (nameElement) {
                nameElement.value = "' . $name . '";
            }
            var last_nameElement = document.getElementById("last_name");
            if (last_nameElement) {
                last_nameElement.value = "' . $last_name . '";
            }
            var cedulaElement = document.getElementById("cedula");
            if (cedulaElement) {
                cedulaElement.value = "' . $cedula . '";
            }
            var chipElement = document.getElementById("chip");
            if (chipElement) {
                chipElement.value = "' . $chip . '";
            }
            var categoriaElement = document.getElementById("categoria");
            if (categoriaElement) {
                categoriaElement.value = "' . $categoria . '";
            }
            var date_emisionElement = document.getElementById("date_emision");
            if (date_emisionElement) {
                date_emisionElement.value = "' . $date_emision . '";
            }
            var certificate_statusElement = document.getElementById("certificate_status");
            if (certificate_statusElement) {
                certificate_statusElement.value = "' . $certificate_status . '";
            }
            var phoneElement = document.getElementById("phone");
            if (phoneElement) {
                phoneElement.value = "' . $phone . '";
            }
            var emailElement = document.getElementById("email");
            if (emailElement) {
                emailElement.value = "' . $email . '";
            }
        });
    </script>';
    }
}


    function generate_certificate($cedula) {

        $qr = new QrCode($cedula);
        $qr_writer = new PngWriter;
        $result_ = $qr_writer->write($qr);
        $result_->saveToFile(ABSPATH . 'wp-content/plugins/k9_certificate/qr_' . $cedula . '.png');

        // Configura la biblioteca PDF
        Settings::setPdfRendererName(Settings::PDF_RENDERER_TCPDF);
        Settings::setPdfRendererPath(__DIR__ . '/../vendor/tecnickcom/tcpdf');

        $certificate_path = ABSPATH . 'wp-content/plugins/k9_certificate/';
    
        // Carga el archivo de plantilla
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($certificate_path . 'certificado.docx');
    
        global $wpdb;
        $table_name = $wpdb->prefix . 'info_certificates';
        $result = $wpdb->get_row("SELECT * FROM $table_name WHERE cedula = $cedula");
    
        if ($result) {
            $name = $result->first_name;
            $last_name = $result->last_name;
            $cedula = $result->cedula;
            $chip = $result->chip;
            $categoria = $result->categoria;
            $date_emision = $result->date_emision;
            $certificate_status = $result->certificate_status;
            $phone = $result->phone;
            $email = $result->email;


    
            // Asigna los valores a la plantilla
            $templateProcessor->setValue('name', $last_name . ' ' . $name);
            $templateProcessor->setImageValue('qr', array('path' => $certificate_path . 'qr_' . $cedula . '.png', 'width' => 80, 'height' => 80));
            // $templateProcessor->setValue('last_name', $last_name);
            // $templateProcessor->setValue('cedula', $cedula);
            // $templateProcessor->setValue('chip', $chip);
            // $templateProcessor->setValue('categoria', $categoria);
            // $templateProcessor->setValue('date_emision', $date_emision);
            // $templateProcessor->setValue('certificate_status', $certificate_status);
            // $templateProcessor->setValue('phone', $phone);
            // $templateProcessor->setValue('email', $email);
    
            // Guarda el certificado con un nombre único
            $outputFileName = $certificate_path . $cedula . '_certificado.docx';
            $templateProcessor->saveAs($outputFileName);

            // Descarga el certificado
            // header('Content-Description: File Transfer');
            // header('Content-Type: application/octet-stream');
            // header('Content-Disposition: attachment; filename="' . $cedula . '_certificado.docx' . '"');
            // header('Expires: 0');
            // header('Cache-Control: must-revalidate');
            // header('Pragma: public');

            // Guarda la ruta del certificado en la base de datos para poder descargarlo desde el listado de certificados
            $wpdb->update(
                $table_name,
                array(
                    'certificate_path' => $certificate_path . $cedula . '_certificado.docx'
                ),
                array(
                    'cedula' => $cedula
                )
            );

            echo '<script>window.location.href = "' . $_SERVER['REQUEST_URI'] . '";</script>';

            exit;
            
        } else {
            // Manejo de error si no se encuentra la cédula en la base de datos
            echo 'Cédula no encontrada en la base de datos.';
            
            echo '<script>window.location.href = "' . $_SERVER['REQUEST_URI'] . '";</script>';

            exit;
        }
    }