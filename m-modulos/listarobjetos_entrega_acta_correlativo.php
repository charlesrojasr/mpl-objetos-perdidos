<?php
include 'listarobjetos_config.php';

$year = date('Y');

// 🔥 correlativo anual
$sql = "SELECT COUNT(*) + 1 AS correlativo
FROM objetosperdidos_entrega_actas
WHERE YEAR(fecha_subida) = '$year'
";

$res = $conn->query($sql);

if(!$res){
    die("Error SQL: " . $conn->error);
}

$row = $res->fetch_assoc();

$numero = str_pad($row['correlativo'], 4, "0", STR_PAD_LEFT);

$nombre = "ACTA DE ENTREGA - Nº {$numero} - {$year} - MPL-GCSC-CAPL";

echo json_encode([
    "nombre_acta" => $nombre
]);