<?php
include 'listarobjetos_config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar sesión
if (!isset($_SESSION['user_id'])) {
    die("Sesión no válida");
}

if (isset($_POST['upload'])) {

    $empid = intval($_POST['id']);
    $id_usuario = $_SESSION['user_id'];
    $ip_usuario = $_SERVER['REMOTE_ADDR'];

    // =========================
    // 1. Obtener archivo anterior
    // =========================
    $sql_prev = "SELECT $titulocampobd20 FROM $primaryTable WHERE id = ?";
    $stmt_prev = $conn->prepare($sql_prev);
    $stmt_prev->bind_param("i", $empid);
    $stmt_prev->execute();
    $result_prev = $stmt_prev->get_result();
    $row_prev = $result_prev->fetch_assoc();

    $archivo_anterior = $row_prev[$titulocampobd20] ?? null;

    // =========================
    // 2. Nuevo archivo
    // =========================
    $filename = $_FILES[$titulocampobd20]['name'];

    if (!empty($filename)) {

        $uploadPath = '../dist/documentos/' . $filename;

        if (move_uploaded_file($_FILES[$titulocampobd20]['tmp_name'], $uploadPath)) {

            // =========================
            // 3. Actualizar documento
            // =========================
            $sql = "UPDATE $primaryTable 
                    SET $titulocampobd20 = ? 
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $filename, $empid);

            if ($stmt->execute()) {

                // =========================
                // 4. INSERT AUDITORÍA
                // =========================
                $accion = 'actualizar';

                $sql_audit = "INSERT INTO objetosperdidos_auditoria_archivos 
                    (id_usuario, accion, id_documento, nombre_archivo_anterior, nombre_archivo_nuevo, ip_usuario) 
                    VALUES (?, ?, ?, ?, ?, ?)";

                $stmt_audit = $conn->prepare($sql_audit);
                $stmt_audit->bind_param(
                    "isisss",
                    $id_usuario,
                    $accion,
                    $empid,
                    $archivo_anterior,
                    $filename,
                    $ip_usuario
                );

                $stmt_audit->execute();

                echo "<script>alert('PDF actualizado');</script>";

            } else {
                echo "<script>alert('Error al actualizar BD: " . addslashes($conn->error) . "');</script>";
            }

        } else {
            echo "<script>alert('Error al subir el archivo');</script>";
        }

    } else {
        echo "<script>alert('No seleccionaste archivo');</script>";
    }

} else {
    echo "<script>alert('Acceso inválido');</script>";
}

// =========================
// Mantener filtros (igual que tu código)
$params = [];

foreach ($_POST as $key => $value) {
    if (strpos($key, 'filtro_') === 0) {
        $originalKey = str_replace('filtro_', '', $key);
        $params[$originalKey] = $value;
    }
}

$queryString = http_build_query($params);

echo "<script>window.location='documentos.php?" . $queryString . "';</script>";
exit();