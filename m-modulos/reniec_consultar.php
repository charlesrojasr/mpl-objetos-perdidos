<?php

header('Content-Type: application/json');

// 🔒 DATOS (pon los tuyos reales)
$nuDniConsulta = $_POST['dni'] ?? '';

$nuDniUsuario = '43605157';
$nuRucUsuario = '20131377062';
$password     = 'JG43605157';

// 🔒 VALIDACIÓN
if (empty($nuDniConsulta)) {
    echo json_encode(['error' => 'DNI vacío']);
    exit;
}

// ================= REQUEST =================
$url = "https://ws2.pide.gob.pe/Rest/RENIEC/Consultar?out=json";

$data = [
    "PIDE" => [
        "nuDniConsulta" => $nuDniConsulta,
        "nuDniUsuario" => $nuDniUsuario,
        "nuRucUsuario" => $nuRucUsuario,
        "password" => $password
    ]
];

// ================= CURL =================
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json; charset=UTF-8'
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => curl_error($ch)]);
    exit;
}

curl_close($ch);

// ================= RESPUESTA =================
echo $response;

?>