<?php
include 'config.php';

$appModulo = 'GESTIÓN DE USUARIOS';

$where = "WHERE 1=1";

// 🔥 FILTROS
if (!empty($_POST['nombre'])) {
    $where .= " AND CONCAT(up.nombre,' ',up.apellido_paterno,' ',up.apellido_materno) 
                LIKE '%{$_POST['nombre']}%'";
}

if (!empty($_POST['documento'])) {
    $where .= " AND up.documento_identidad LIKE '%{$_POST['documento']}%'";
}

if (!empty($_POST['role_id'])) {
    $where .= " AND u.role_id = '{$_POST['role_id']}'";
}

if (isset($_POST['estado']) && $_POST['estado'] !== '') {
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);
    $where .= " AND u.estado = '$estado'";
}

// 🔥 SQL LIMPIO
$sql = "SELECT 
    u.id,
    u.username,
    u.password,
    u.role_id,
    r.role_name,
    u.estado,
    u.created_at,
    u.updated_at,

    up.nombre,
    up.apellido_paterno,
    up.apellido_materno,
    up.documento_identidad,

    CONCAT(up.nombre,' ',up.apellido_paterno,' ',up.apellido_materno) AS nombre_completo

FROM objetosperdidos_users u

LEFT JOIN objetosperdidos_user_profile up ON u.id = up.user_id
LEFT JOIN objetosperdidos_roles r ON u.role_id = r.id

$where

ORDER BY u.id DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Error SQL: " . $conn->error);
}
