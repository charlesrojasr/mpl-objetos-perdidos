<?php
include 'listarobjetos_config.php';

$id = $_POST['id'];

$sql = "SELECT * FROM objetosperdidos_actas WHERE registro_id = '$id'";
$res = $conn->query($sql);

if ($row = $res->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(null);
}
?>