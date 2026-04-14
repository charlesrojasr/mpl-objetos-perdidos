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

    $entrega_id = $_POST['entrega_id'];
    $nombre_acta = limpiar($conn, $_POST['nombre_acta']);

    $archivo = "";

    // ================= VALIDAR ARCHIVO =================
    $permitidos = ['pdf'];

    if (!empty($_FILES['acta']['name'])) {

        $carpeta = "../dist/actas_entrega/";

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $nombre_original = $_FILES['acta']['name'];
        $ext = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

        if (!in_array($ext, $permitidos)) {
            die("<script>alert('Solo se permite PDF'); window.history.back();</script>");
        }

        $archivo = time() . "_" . preg_replace('/[^A-Za-z0-9.\-]/', '_', $nombre_original);

        move_uploaded_file($_FILES['acta']['tmp_name'], $carpeta . $archivo);
    }

    // ================= VERIFICAR SI YA EXISTE =================
    $check = $conn->query("SELECT * FROM objetosperdidos_entrega_actas WHERE entrega_id = '$entrega_id'");
    $existe = $check->fetch_assoc();

    if ($existe) {

        // 🔥 ELIMINAR ARCHIVO ANTERIOR SI SUBE NUEVO
        if ($archivo != "" && !empty($existe['nombre_archivo'])) {

            $ruta_anterior = "../dist/actas_entrega/" . $existe['nombre_archivo'];

            if (file_exists($ruta_anterior)) {
                unlink($ruta_anterior);
            }
        }

        // ================= UPDATE =================
        if ($archivo != "") {

            $sql = "UPDATE objetosperdidos_entrega_actas 
                    SET nombre_acta = '$nombre_acta',
                        nombre_archivo = '$archivo',
                        fecha_subida = NOW()
                    WHERE entrega_id = '$entrega_id'";
        } else {

            $sql = "UPDATE objetosperdidos_entrega_actas 
                    SET nombre_acta = '$nombre_acta'
                    WHERE entrega_id = '$entrega_id'";
        }
    } else {

        // ================= INSERT =================
        $sql = "INSERT INTO objetosperdidos_entrega_actas 
                (entrega_id, nombre_acta, nombre_archivo, fecha_subida)
                VALUES ('$entrega_id', '$nombre_acta', '$archivo', NOW())";
    }

    // ================= EJECUTAR =================
    if ($conn->query($sql)) {

        // 🔥 OBTENER REGISTRO_ID DESDE ENTREGA
        $res = $conn->query("SELECT registro_id FROM objetosperdidos_entregas WHERE id = '$entrega_id'");
        $row = $res->fetch_assoc();

        $registro_id = $row['registro_id'];

        // 🔥 ACTUALIZAR ESTADO A 4 (ENTREGADO)
        $conn->query("UPDATE objetosperdidos_registros 
                  SET estado = '4' 
                  WHERE id = '$registro_id'");

        echo "<script>
        alert('Acta de entrega guardada correctamente');
        window.location.href='listarobjetos.php';
    </script>";
    } else {

        echo "<script>
            alert('Error al guardar el acta');
            window.history.back();
        </script>";
    }
} else {
    header('location: listarobjetos.php');
}
