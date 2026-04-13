<?php
include '../00_includes/conn.php';

if (isset($_POST['id'])) {

    $id = $_POST['id'];

    $sql = "SELECT 
        r.*,
        c.nombre_categoria

    FROM objetosperdidos_registros r

    LEFT JOIN objetosperdidos_categorias c 
        ON r.categoria_id = c.id

    WHERE r.id = '$id'";

    $query = $conn->query($sql);
    $row = $query->fetch_assoc();

    echo json_encode($row);
}
?>