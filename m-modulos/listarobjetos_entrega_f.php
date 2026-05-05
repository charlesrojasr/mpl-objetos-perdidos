<?php
include 'listarobjetos_config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Lima');

function limpiar($conn, $texto)
{
    return strtoupper(mysqli_real_escape_string($conn, trim($texto)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $registro_id = $_POST['registro_id'] ?? '';

    if ($registro_id == '') {
        die("Error: registro_id vacío");
    }

    // ===== DATOS PERSONA ENTREGA =====
    $dni   = limpiar($conn, $_POST['nro_documento']);
    $nom   = limpiar($conn, $_POST['nombre']);
    $ap_p  = limpiar($conn, $_POST['apellido_paterno']);
    $ap_m  = limpiar($conn, $_POST['apellido_materno']);
    $tel   = limpiar($conn, $_POST['numero_contacto']);
    $mail  = limpiar($conn, $_POST['correo'] ?? '');
    $dir   = limpiar($conn, $_POST['direccion']);

    // ⚠️ NO usar 0 si tienes FK
    if (!isset($_SESSION['user_id'])) {
        die("Error: usuario no logueado");
    }
    $user_id = $_SESSION['user_id'];

    // ===== INSERT ENTREGA =====
    $sql = "INSERT INTO objetosperdidos_entregas
    (registro_id, nro_documento, nombre, apellido_paterno, apellido_materno,
     numero_contacto, correo, direccion, user_id, fecha_entrega)
    VALUES
    ('$registro_id', '$dni', '$nom', '$ap_p', '$ap_m',
     '$tel', '$mail', '$dir', '$user_id', NOW())";

    if ($conn->query($sql)) {

        $entrega_id = $conn->insert_id;

        // ================= SUBIR ARCHIVOS =================
        if (!empty($_FILES['archivos']['name'][0])) {

            $carpeta = __DIR__ . "/../dist/archivos_entrega/";

            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            foreach ($_FILES['archivos']['tmp_name'] as $key => $tmp_name) {

                if ($_FILES['archivos']['error'][$key] == 0) {

                    $nombreOriginal = $_FILES['archivos']['name'][$key];

                    // limpiar nombre
                    $nombreLimpio = preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $nombreOriginal);

                    $nombreArchivo = time() . "_" . $nombreLimpio;

                    $ruta = $carpeta . $nombreArchivo;

                    if (move_uploaded_file($tmp_name, $ruta)) {

                        $insert = $conn->query("INSERT INTO objetosperdidos_entrega_archivos
                        (entrega_id, nombre_archivo)
                        VALUES ('$entrega_id', '$nombreArchivo')");

                        if (!$insert) {
                            echo "Error BD archivos: " . $conn->error;
                        }

                    } else {
                        echo "Error moviendo archivo: " . $nombreOriginal . "<br>";
                    }
                }
            }
        }

        // ===== ACTUALIZAR ESTADO =====
        $conn->query("UPDATE objetosperdidos_registros 
                      SET estado = '3' 
                      WHERE id = '$registro_id'");

        echo "<script>
            alert('Entrega registrada correctamente');
            window.location.href = document.referrer;
        </script>";

    } else {

        die("Error SQL: " . $conn->error);
    }

} else {
    header('location: listarobjetos.php');
}
?>