<?php
include 'listarobjetos_config.php';

$id = $_POST['id'];

$sql = "SELECT e.*, 
CONCAT(up.nombre, ' ', up.apellido_paterno, ' ', up.apellido_materno) AS usuario_nombre
FROM objetosperdidos_entregas e
LEFT JOIN objetosperdidos_users u ON e.user_id = u.id
LEFT JOIN objetosperdidos_user_profile up ON u.id = up.user_id
WHERE e.registro_id = '$id'";

$res = $conn->query($sql);
$row = $res->fetch_assoc();

if (!$row) {
    echo "<div class='alert alert-danger'>No se encontró la entrega</div>";
    exit;
}

// ARCHIVOS
$archivos = $conn->query("SELECT * FROM objetosperdidos_entrega_archivos WHERE entrega_id = '{$row['id']}'");

$acta = $conn->query("SELECT * FROM objetosperdidos_entrega_actas WHERE entrega_id = '{$row['id']}'");
$acta_row = $acta->fetch_assoc();
?>

<div class="container-fluid">

    <!-- DATOS -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-user-check"></i> Datos de la Entrega</h5>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <p><b>DNI:</b> <?php echo $row['nro_documento']; ?></p>
                    <p><b>Nombre:</b> <?php echo $row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']; ?></p>
                    <p><b>Teléfono:</b> <?php echo $row['numero_contacto']; ?></p>
                </div>

                <div class="col-md-6">
                    <p><b>Correo:</b> <?php echo $row['correo'] ?: '<span class="text-muted">No registrado</span>'; ?></p>
                    <p><b>Dirección:</b> <?php echo $row['direccion']; ?></p>
                </div>

            </div>


        </div>
    </div>

    <!-- ENTREGADO POR -->
    <div class="card mt-3">
        <div class="card-header bg-info">
            <h5 class="mb-0">
                <i class="fas fa-user-tie"></i> REGISTRADO POR
            </h5>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">
                    <p><b>Nombre del Usuario:</b><br>
                        <span class="text-primary font-weight-bold">
                            <?php echo $row['usuario_nombre'] ?: 'No registrado'; ?>
                        </span>
                    </p>
                </div>

                <div class="col-md-6">
                    <p><b>Fecha de Entrega:</b><br>
                        <span class="text-dark">
                            <?php echo $row['fecha_entrega']; ?>
                        </span>
                    </p>
                </div>

            </div>

        </div>
    </div>

    <!-- ARCHIVOS -->
    <div class="card mt-3">
        <div class="card-header bg-secondary">
            <h5 class="mb-0"><i class="fas fa-folder-open"></i> Evidencias</h5>
        </div>

        <div class="card-body">
            <div class="row">

                <?php if ($archivos->num_rows > 0) { ?>

                    <?php while ($a = $archivos->fetch_assoc()) {
                        $ruta = "../dist/archivos_entrega/" . $a['nombre_archivo'];
                        $ext = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
                    ?>

                        <div class="col-md-4 mb-3">

                            <div class="border p-2 rounded">

                                <!-- PREVIEW -->
                                <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) { ?>

                                    <img src="<?php echo $ruta; ?>" class="img-fluid mb-2" style="height:150px;object-fit:cover;">

                                <?php } elseif ($ext == 'pdf') { ?>

                                    <iframe src="<?php echo $ruta; ?>" width="100%" height="150"></iframe>

                                <?php } else { ?>

                                    <div class="text-center p-3">
                                        <i class="fas fa-file fa-3x text-secondary"></i>
                                    </div>

                                <?php } ?>

                                <!-- NOMBRE -->
                                <p class="small text-truncate mb-2">
                                    <?php echo $a['nombre_archivo']; ?>
                                </p>

                                <!-- BOTONES -->
                                <div class="d-flex justify-content-between">

                                    <a href="<?php echo $ruta; ?>" target="_blank" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="<?php echo $ruta; ?>" download class="btn btn-success btn-sm">
                                        <i class="fas fa-download"></i>
                                    </a>

                                </div>

                            </div>

                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <div class="col-12 text-muted">
                        No hay archivos adjuntos
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>

    <!-- ACTA -->
    <div class="card mt-3">
        <div class="card-header bg-dark">
            <h5 class="mb-0">
                <i class="fas fa-file-signature"></i> Acta de Entrega
            </h5>
        </div>

        <div class="card-body text-center">

            <?php if ($acta_row) { ?>

                <!-- NOMBRE DEL ACTA -->
                <div class="mb-2">
                    <span class="font-weight-bold text-dark">
                        <i class="fas fa-file-pdf text-danger"></i>
                        <?php echo $acta_row['nombre_acta']; ?>
                    </span>
                </div>

                <!-- BOTONES -->
                <div class="d-flex justify-content-center gap-2">

                    <!-- VER -->
                    <a href="../dist/actas_entrega/<?php echo $acta_row['nombre_archivo']; ?>"
                        target="_blank" class="btn btn-info btn-sm mr-2">
                        <i class="fas fa-eye"></i> Ver Acta
                    </a>

                    <!-- EDITAR -->
                    <button class="btn btn-warning btn-sm"
                        onclick="abrirModalActaEntrega(<?php echo $row['id']; ?>)">
                        <i class="fas fa-edit"></i> Editar Acta
                    </button>

                </div>

            <?php } else { ?>

                <!-- SIN ACTA -->
                <div class="text-muted mb-2">
                    <i class="fas fa-exclamation-circle"></i>
                    No hay acta registrada
                </div>

                <button class="btn btn-success btn-sm"
                    onclick="abrirModalActaEntrega(<?php echo $row['id']; ?>)">
                    <i class="fas fa-upload"></i> Subir Acta
                </button>

            <?php } ?>
        </div>
    </div>

</div>