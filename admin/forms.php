<?php

require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;

function register_data_certificates(){

    global $wpdb;
    $table_name = $wpdb->prefix . 'info_certificates';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        $last_name = $_POST['last_name'];
        $cedula = $_POST['cedula'];
        $chip = $_POST['chip'];
        $categoria = $_POST['categoria'];
        $date_emision = $_POST['date_emision'];
        $certificate_status = $_POST['certificate_status'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
    
        $wpdb->insert(
            $table_name,
            array(
                'first_name' => $name,
                'last_name' => $last_name,
                'cedula' => $cedula,
                'chip' => $chip,
                'categoria' => $categoria,
                'date_emision' => $date_emision,
                'certificate_status' => $certificate_status,
                'phone' => $phone,
                'email' => $email
            )
        );
    }

    if (isset($_POST['generate_certificate'])) {
        $cedula = $_POST['cedula'];
        generate_certificate($cedula);
    }
    
    ?>

    <div>
        <form method="post">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" placeholder="Nombre" required>
            <label for="last_name">Apellido</label>
            <input type="text" name="last_name" id="last_name" placeholder="Apellido" required>
            <label for="cedula">Cédula</label>
            <input type="text" name="cedula" id="cedula" placeholder="Cédula" required>
            <label for="chip">Chip</label>
            <input type="text" name="chip" id="chip" placeholder="Chip" required>

            <select name="categoria" id="categoria">
                <option value="" selected disabled>Selecciona una categoria</option>
                <option value="volvo">Volvo</option>
                <option value="saab">Saab</option>
                <option value="opel">Opel</option>
                <option value="audi">Audi</option>
            </select>            
            <label for="date_emision">Fecha de emisión</label>
            <input type="date" name="date_emision" id="date_emision" placeholder="Fecha de emisión" required>
            <label for="certificate_status">Estado del certificado</label>
            <input type="text" name="certificate_status" id="certificate_status" placeholder="Estado del certificado" required>
            <label for="phone">Teléfono</label>
            <input type="text" name="phone" id="phone" placeholder="Teléfono" required>
            <label for="email">Correo electrónico</label>
            <input type="email" name="email" id="email" placeholder="Correo electrónico" required>
            <input type="submit" value="Enviar">
        </form>
    </div>
    <div>
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
                                <input type="hidden" name="cedula" value="' . $result->cedula . '">
                                <input type="submit" name="generate_certificate" value="Generar Certificado">
                            </form>
                        </td>';
                
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    }

    function generate_certificate($cedula) {

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
            $templateProcessor->setValue('name', $name);
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

            // convierte el archivo .docx a .pdf
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($outputFileName);
            $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
            $xmlWriter->save($certificate_path . 'certificates/' . $cedula . '_certificado.pdf');
    
            // Redirecciona al usuario para descargar el certificado
            header('Location: ' . plugins_url('k9_certificate/certificates/' . $cedula . '_certificado.pdf'));

            // Guarda la ruta del certificado en la base de datos para poder descargarlo desde el listado de certificados
            $wpdb->update(
                $table_name,
                array(
                    'certificate_path' => $certificate_path . $cedula . '_certificado.pdf'
                ),
                array(
                    'cedula' => $cedula
                )
            );

            exit;
        } else {
            // Manejo de error si no se encuentra la cédula en la base de datos
            echo 'Cédula no encontrada en la base de datos.';
        }
    }
    