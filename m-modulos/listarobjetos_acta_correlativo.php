<?php
include 'listarobjetos_config.php';

$year = date('Y');

$sql = "SELECT COUNT(*) + 1 AS correlativo
FROM objetosperdidos_actas
WHERE YEAR(fecha_subida) = '$year'
";

$res = $conn->query($sql);

if(!$res){
    die("Error SQL: " . $conn->error);
}

$row = $res->fetch_assoc();

$num = str_pad($row['correlativo'], 4, "0", STR_PAD_LEFT);

$nombre = "ACTA DE RECEPCION - Nº {$num} - {$year} - MPL-GCSC-CAPL";

echo json_encode([
    "nombre" => $nombre
]);