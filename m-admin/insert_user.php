<?php
include '../00_includes/conn.php';

// Datos del usuario a insertar
$username = 'indigo'; // Cambia el nombre de usuario aquí
$password = 'MPL@2026'; // Cambia la contraseña aquí
$role_id = 2; // Asignar el rol de administrador (ID 1)

// Encriptar la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Preparar la consultaobjetosperdidos_documentos
$stmt = $conn->prepare("INSERT INTO objetosperdidos_users (username, password, role_id) VALUES (?, ?, ?)");
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

$stmt->bind_param("ssi", $username, $hashed_password, $role_id);

if ($stmt->execute()) {
    echo "Usuario insertado correctamente.";
} else {
    echo "Error al insertar usuario: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
