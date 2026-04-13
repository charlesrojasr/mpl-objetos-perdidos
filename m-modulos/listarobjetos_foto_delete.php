<?php
include 'listarobjetos_config.php';

$id = $_POST['id'];

// obtener nombre archivo
$q = $conn->query("SELECT nombre_archivo FROM objetosperdidos_registro_fotos WHERE id='$id'");
$row = $q->fetch_assoc();

if ($row) {

    $ruta = "../dist/fotos_objetos/" . $row['nombre_archivo'];

    if (file_exists($ruta)) {
        unlink($ruta);
    }

    $conn->query("DELETE FROM objetosperdidos_registro_fotos WHERE id='$id'");
}