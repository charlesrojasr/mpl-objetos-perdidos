<?php
include 'config.php';

$id = $_POST['id'];
$doc = $_POST['doc'];

// 🔥 nueva clave = documento
$hashed = password_hash($doc, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE objetosperdidos_users
SET password = ?
WHERE id = ?
");

$stmt->bind_param("si", $hashed, $id);

if ($stmt->execute()) {

    echo json_encode([
        "status" => true
    ]);
} else {

    echo json_encode([
        "status" => false,
        "msg" => $conn->error
    ]);
}
