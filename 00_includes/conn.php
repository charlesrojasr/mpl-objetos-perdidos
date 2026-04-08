<?php
/*$host = 'localhost';
$db = 'admin_db_arbolado';
$user = 'root';
$pass = '';*/

$host = '175.15.20.64';
$db = 'admin_db_arbolado';
$user = 'admin_arbolado';
$pass = 'vmZMz6U5tZ';

$conn = new mysqli($host, $user, $pass, $db);
mysqli_set_charset($conn, "utf8");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
