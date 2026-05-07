<?php
include 'config.php';

$doc = $_POST['documento'];
$nombre = strtoupper($_POST['nombre']);
$ap = strtoupper($_POST['apellido_paterno']);
$am = strtoupper($_POST['apellido_materno']);
$username = $_POST['username'];

// 🔥 CONTRASEÑA = DOCUMENTO
$hashed_password = password_hash($doc, PASSWORD_DEFAULT);

$role_id = $_POST['role_id'];

// INSERT USERS
$stmt = $conn->prepare("INSERT INTO objetosperdidos_users (username, password, role_id, estado) VALUES (?, ?, ?, 1)");
$stmt->bind_param("ssi", $username, $hashed_password, $role_id);

if($stmt->execute()){

    $user_id = $stmt->insert_id;

    // INSERT PROFILE
    $stmt2 = $conn->prepare("INSERT INTO objetosperdidos_user_profile 
    (user_id, nombre, apellido_paterno, apellido_materno, documento_identidad)
    VALUES (?, ?, ?, ?, ?)");

    $stmt2->bind_param("issss", $user_id, $nombre, $ap, $am, $doc);
    $stmt2->execute();

    echo json_encode(["status"=>true]);

}else{
    echo json_encode(["status"=>false, "msg"=>$conn->error]);
}