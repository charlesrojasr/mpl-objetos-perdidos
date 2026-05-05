<?php
include 'listarobjetos_config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    function limpiar($conn, $texto)
    {
        return strtoupper(mysqli_real_escape_string($conn, trim($texto)));
    }

    // =============================
    // DATOS
    // =============================
    // =============================
    // VALIDAR SI ES ANÓNIMO
    // =============================
    $es_anonimo = isset($_POST['anonimo']) ? 1 : 0;

    if ($es_anonimo) {

        $nro_documento = '00000000';
        $nombre = 'ANÓNIMO';
        $apellido_paterno = 'ANÓNIMO';
        $apellido_materno = 'ANÓNIMO';
        $tipo_persona = 'ANÓNIMO';
    } else {

        $nro_documento = limpiar($conn, $_POST['nro_documento']);
        $nombre = limpiar($conn, $_POST['nombre']);
        $apellido_paterno = limpiar($conn, $_POST['apellido_paterno']);
        $apellido_materno = limpiar($conn, $_POST['apellido_materno']);
        $tipo_persona = limpiar($conn, $_POST['tipo_persona']);
    }

    $descripcion_objeto = limpiar($conn, $_POST['descripcion_objeto']);
    $categoria_id = $_POST['categoria_id'];
    $lugar_referencia = limpiar($conn, $_POST['lugar_referencia']);

    $user_id = $_SESSION['user_id'] ?? 0;

    // =============================
    // INSERT
    // =============================
    $registro_id = $_POST['registro_id'] ?? '';

    if ($registro_id == '') {

        // ================= INSERT =================
        $sql = "INSERT INTO objetosperdidos_registros 
    (nro_documento, nombre, apellido_paterno, apellido_materno, tipo_persona,
    descripcion_objeto, categoria_id, lugar_referencia, user_id, estado)
    VALUES 
    ('$nro_documento', '$nombre', '$apellido_paterno', '$apellido_materno', '$tipo_persona',
    '$descripcion_objeto', '$categoria_id', '$lugar_referencia', '$user_id', '1')";
    } else {

        // ================= UPDATE =================
        $sql = "UPDATE objetosperdidos_registros SET
        nro_documento='$nro_documento',
        nombre='$nombre',
        apellido_paterno='$apellido_paterno',
        apellido_materno='$apellido_materno',
        tipo_persona='$tipo_persona',
        descripcion_objeto='$descripcion_objeto',
        categoria_id='$categoria_id',
        lugar_referencia='$lugar_referencia'
    WHERE id='$registro_id'";
    }

    if ($conn->query($sql)) {

        if ($registro_id == '') {
            $registro_id = $conn->insert_id;
        }

        // =============================
        // GUARDAR FOTOS
        // =============================
        if (!empty($_FILES['fotos']['name'][0])) {

            $carpeta = "../dist/fotos_objetos/";

            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {

                $nombreArchivo = time() . "_" . $_FILES['fotos']['name'][$key];
                $ruta = $carpeta . $nombreArchivo;

                if (move_uploaded_file($tmp_name, $ruta)) {

                    $conn->query("INSERT INTO objetosperdidos_registro_fotos 
                    (registro_id, nombre_archivo)
                    VALUES ('$registro_id', '$nombreArchivo')");
                }
            }
        }

        // =============================
        // ALERT + REDIRECCIÓN
        // =============================
        echo "<script>
            alert('Registro guardado correctamente');
            window.location.href = document.referrer;
        </script>";
    } else {

        echo "<script>
            alert('Error al guardar el registro');
            window.location.href = document.referrer;
        </script>";
    }
} else {
    header('location: listarobjetos.php');
}
