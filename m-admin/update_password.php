<?php
session_start();
include '../00_includes/conn.php';

if (!isset($_SESSION['user_id'])) {
    echo "Sesión no válida.";
    exit;
}

$user_id = $_SESSION['user_id'];

$current = $_POST['current_password'] ?? '';
$new = $_POST['new_password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if ($current == '' || $new == '' || $confirm == '') {
    echo "Todos los campos son obligatorios.";
    exit;
}

if ($new !== $confirm) {
    echo "Las contraseñas no coinciden.";
    exit;
}

if (strlen($new) < 6) {
    echo "La contraseña debe tener al menos 6 caracteres.";
    exit;
}

// Obtener contraseña actual
$stmt = $conn->prepare("SELECT password FROM objetosperdidos_users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($hash);
$stmt->fetch();
$stmt->close();

if (!password_verify($current, $hash)) {
    echo "Contraseña actual incorrecta.";
    exit;
}

// Actualizar
$new_hash = password_hash($new, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE objetosperdidos_users SET password = ? WHERE id = ?");
$stmt->bind_param("si", $new_hash, $user_id);

if ($stmt->execute()) {
    echo "Contraseña actualizada correctamente.";
} else {
    echo "Error al actualizar.";
}

$stmt->close();
$conn->close();
