<?php
include 'listarobjetos_config.php';

$id = $_POST['id'];

$sql = "SELECT id, nombre_archivo 
        FROM objetosperdidos_registro_fotos 
        WHERE registro_id = '$id'";

$res = $conn->query($sql);

$fotos = [];

while ($row = $res->fetch_assoc()) {
    $fotos[] = $row;
}

echo json_encode($fotos);

?>