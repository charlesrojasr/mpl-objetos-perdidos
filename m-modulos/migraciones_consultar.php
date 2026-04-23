<?php
header('Content-Type: application/json');

$doc = $_POST['doc'] ?? '';

// 🔒 TUS CREDENCIALES
$username = '47623721';
$password = '47623721';
$ip       = '168.121.50.168';

if (empty($doc)) {
    echo json_encode(['error' => 'Documento vacío']);
    exit;
}

$url = "https://ws2.pide.gob.pe/Rest/MIGRACIONES/CEE?out=json";

$data = [
    "PIDE" => [
        "username" => $username,
        "password" => $password,
        "ip" => $ip,
        "nivelacceso" => "personalizado",
        "docconsulta" => $doc
    ]
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json; charset=UTF-8'
]);

// 🔥 IMPORTANTE
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => curl_error($ch)]);
    exit;
}

curl_close($ch);

echo $response;