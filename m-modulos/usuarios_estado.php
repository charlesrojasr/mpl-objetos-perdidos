<?php
include 'config.php';

session_start();

$id = $_POST['id'] ?? '';
$estado = $_POST['estado'] ?? '';

if ($id == '') {
    echo json_encode(["status" => false, "msg" => "ID inválido"]);
    exit;
}

// 🔒 seguridad (opcional)
if ($_SESSION['role_id'] != 1) {
    echo json_encode(["status" => false, "msg" => "No autorizado"]);
    exit;
}

$sql = "UPDATE objetosperdidos_users 
        SET estado = '$estado' 
        WHERE id = '$id'";

if ($conn->query($sql)) {
    echo json_encode(["status" => true]);
} else {
    echo json_encode(["status" => false, "msg" => $conn->error]);
}