<?php
include 'listarobjetos_config.php';

$registro_id = $_POST['registro_id'];
$nombre_acta = strtoupper($_POST['nombre_acta']);

$archivo = "";

// ================= VALIDAR ARCHIVO =================
$permitidos = ['pdf', 'jpg', 'jpeg', 'png'];

if (!empty($_FILES['archivo_acta']['name'])) {

    $carpeta = "../dist/actas/";

    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    $nombre_original = $_FILES['archivo_acta']['name'];
    $ext = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

    // Validar extensión
    if (!in_array($ext, $permitidos)) {
        die("<script>alert('Formato no permitido'); window.history.back();</script>");
    }

    // Nombre limpio
    $archivo = time() . "_" . preg_replace('/[^A-Za-z0-9.\-]/', '_', $nombre_original);

    move_uploaded_file($_FILES['archivo_acta']['tmp_name'], $carpeta . $archivo);
}

// ================= VERIFICAR EXISTENCIA =================
$check = $conn->query("SELECT * FROM objetosperdidos_actas WHERE registro_id = '$registro_id'");
$existe = $check->fetch_assoc();

if ($existe) {

    // 👉 ELIMINAR ARCHIVO ANTERIOR SI SUBE NUEVO
    if ($archivo != "" && !empty($existe['nombre_archivo'])) {
        $ruta_anterior = "../dist/actas/" . $existe['nombre_archivo'];
        if (file_exists($ruta_anterior)) {
            unlink($ruta_anterior);
        }
    }

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

// ================= EJECUTAR =================
if ($conn->query($sql)) {

    // ACTUALIZAR ESTADO
    $conn->query("UPDATE objetosperdidos_registros 
              SET estado = '2' 
              WHERE id = '$registro_id' AND estado = '1'");

    echo "<script>
        window.location.href = 'listarobjetos.php?ok=1';
    </script>";
} else {

    echo "<script>
        alert('Error al guardar acta');
        window.history.back();
    </script>";
}
