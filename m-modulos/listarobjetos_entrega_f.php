<?php
include 'listarobjetos_config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Lima');

function limpiar($conn, $texto) {
    return strtoupper(mysqli_real_escape_string($conn, trim($texto)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $registro_id = $_POST['registro_id'];

    // ===== DATOS PERSONA ENTREGA =====
    $dni   = limpiar($conn, $_POST['nro_documento']);
    $nom   = limpiar($conn, $_POST['nombre']);
    $ap_p  = limpiar($conn, $_POST['apellido_paterno']);
    $ap_m  = limpiar($conn, $_POST['apellido_materno']);
    $tel   = limpiar($conn, $_POST['numero_contacto']);
    $mail  = limpiar($conn, $_POST['correo'] ?? '');
    $dir   = limpiar($conn, $_POST['direccion']);

    $user_id = $_SESSION['user_id'] ?? 0;

    // ===== INSERT ENTREGA =====
    $sql = "INSERT INTO objetosperdidos_entregas
    (registro_id, nro_documento, nombre, apellido_paterno, apellido_materno,
     numero_contacto, correo, direccion, user_id, fecha_entrega)
    VALUES
    ('$registro_id', '$dni', '$nom', '$ap_p', '$ap_m',
     '$tel', '$mail', '$dir', '$user_id', NOW())";

    if ($conn->query($sql)) {

        $entrega_id = $conn->insert_id;

        // ===== SUBIR ARCHIVOS =====
        if (!empty($_FILES['archivos']['name'][0])) {

            $carpeta = "../dist/archivos_entrega/";
            if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

            foreach ($_FILES['archivos']['tmp_name'] as $key => $tmp) {

                $nombreArchivo = time() . "_" . $_FILES['archivos']['name'][$key];
                move_uploaded_file($tmp, $carpeta . $nombreArchivo);

                $conn->query("INSERT INTO objetosperdidos_entrega_archivos
                (entrega_id, nombre_archivo)
                VALUES ('$entrega_id', '$nombreArchivo')");
            }
        }

        // ===== ACTUALIZAR ESTADO =====
        $conn->query("UPDATE objetosperdidos_registros 
                      SET estado = '4' 
                      WHERE id = '$registro_id'");

        echo "<script>
            alert('Entrega registrada correctamente');
            window.location.href='listarobjetos.php';
        </script>";

    } else {

        echo "<script>
            alert('Error al registrar entrega');
            window.history.back();
        </script>";
    }

} else {
    header('location: listarobjetos.php');
}
?>