<?php
include 'config.php';

$base = $_POST['base'];
$ap_m = $_POST['ap_m'];

$username = $base;
$i = 0;

while(true){

    $check = $conn->query("SELECT id FROM objetosperdidos_users WHERE username='$username'");

    if($check->num_rows == 0){
        break;
    }

    $username = $base . substr($ap_m,0,++$i);
}

echo json_encode(["username"=>$username]);