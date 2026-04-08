<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../00_includes/conn.php';

$idiomaM = "es";

$appModulo = "INICIO"; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../m-admin/index.php');
    exit();
}

// Obtener los permisos del usuario
$role_id = $_SESSION['role_id'];
$stmt = $conn->prepare("

    SELECT objetosperdidos_permissions.permission_name, 
           objetosperdidos_permissions.permission_modulo 
    FROM 
           objetosperdidos_permissions
    JOIN 
           objetosperdidos_role_permissions 
    ON 
           objetosperdidos_permissions.id = 
           objetosperdidos_role_permissions.permission_id

    WHERE 
           objetosperdidos_role_permissions.role_id = ?");

$stmt->bind_param("i", $role_id);
$stmt->execute();
$permissions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>


