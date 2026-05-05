<?php
include 'config.php';

$doc = $_POST['doc'];

$stmt = $conn->prepare("SELECT id FROM objetosperdidos_user_profile WHERE documento_identidad = ?");
$stmt->bind_param("s", $doc);
$stmt->execute();
$res = $stmt->get_result();

echo json_encode([
  "existe" => $res->num_rows > 0
]);