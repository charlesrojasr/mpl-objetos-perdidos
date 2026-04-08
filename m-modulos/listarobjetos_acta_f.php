<?php
include 'listarobjetos_config.php';

$registro_id = $_POST['registro_id'];
$nombre_acta = strtoupper($_POST['nombre_acta']);

$archivo = "";

// SUBIR ARCHIVO
if (!empty($_FILES['archivo_acta']['name'])) {

    $carpeta = "../dist/actas/";

    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    $archivo = time() . "_" . $_FILES['archivo_acta']['name'];
    move_uploaded_file($_FILES['archivo_acta']['tmp_name'], $carpeta . $archivo);
}

// VERIFICAR SI YA EXISTE
$check = $conn->query("SELECT * FROM objetosperdidos_actas WHERE registro_id = '$registro_id'");

if ($check->num_rows > 0) {

    // UPDATE
    if ($archivo != "") {
        $sql = "UPDATE objetosperdidos_actas 
                SET nombre_acta='$nombre_acta', nombre_archivo='$archivo'
                WHERE registro_id='$registro_id'";
    } else {
        $sql = "UPDATE objetosperdidos_actas 
                SET nombre_acta='$nombre_acta'
                WHERE registro_id='$registro_id'";
    }

} else {

    // INSERT
    $sql = "INSERT INTO objetosperdidos_actas (registro_id, nombre_acta, nombre_archivo)
            VALUES ('$registro_id', '$nombre_acta', '$archivo')";
}

// EJECUTAR
if ($conn->query($sql)) {

    // ACTUALIZAR ESTADO A 2
    $conn->query("UPDATE objetosperdidos_registros SET estado='2' WHERE id='$registro_id'");

    echo "<script>
        alert('Acta guardada correctamente');
        window.history.back();
    </script>";

} else {

    echo "<script>
        alert('Error al guardar acta');
        window.history.back();
    </script>";
}
?>