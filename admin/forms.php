<?php

function register_data_certificates(){
    ?>

    <div>
        <h1>Registro de datos</h1>
        <form method="post">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" placeholder="Nombre" required>
            <label for="last_name">Apellido</label>
            <input type="text" name="last_name" id="last_name" placeholder="Apellido" required>
            <label for="cedula">Cédula</label>
            <input type="text" name="cedula" id="cedula" placeholder="Cédula" required>
            <label for="chip">Chip</label>
            <input type="text" name="chip" id="chip" placeholder="Chip" required>
            <label for="categoria">Categoría</label>
            <input type="text" name="categoria" id="categoria" placeholder="Categoría" required>
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

    <?php
}