<?php
include 'listarobjetos_config.php';

$entrega_id = $_POST['entrega_id'];

$res = $conn->query("SELECT * FROM objetosperdidos_entrega_actas WHERE entrega_id='$entrega_id'");
$row = $res->fetch_assoc();

echo json_encode($row);