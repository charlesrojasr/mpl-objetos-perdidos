<?php
include 'listarobjetos_config.php';

$id = $_POST['id'];

// ================= REGISTRO + USUARIO =================
$sql = "SELECT 
r.*,
c.nombre_categoria,

-- USUARIO (quien registra en el sistema)
up.nombre AS user_nombre,
up.apellido_paterno AS user_apellido_paterno,
up.apellido_materno AS user_apellido_materno

FROM objetosperdidos_registros r
LEFT JOIN objetosperdidos_categorias c ON r.categoria_id = c.id
LEFT JOIN objetosperdidos_users u ON r.user_id = u.id
LEFT JOIN objetosperdidos_user_profile up ON u.id = up.user_id
WHERE r.id = '$id'";

$res = $conn->query($sql);
$row = $res->fetch_assoc();

// ================= FOTOS =================
$fotos = $conn->query("SELECT * FROM objetosperdidos_registro_fotos WHERE registro_id = '$id'");

// ================= ACTA REGISTRO =================
$acta = $conn->query("SELECT * FROM objetosperdidos_actas WHERE registro_id = '$id'");
$acta_row = $acta->fetch_assoc();

// ================= FECHA (-1 HORA) =================
$fecha = date("Y-m-d H:i:s", strtotime($row['fecha_registro'] . " -1 hour"));
?>

<div class="container-fluid">

    <!-- ================= DATOS DEL REGISTRO ================= -->
    <div class="card card-outline card-primary">
        <div class="card-header"><b>DATOS DEL REGISTRO</b></div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <p><b>DNI:</b> <?php echo $row['nro_documento']; ?></p>
                    <p><b>Nombre:</b>
                        <?php echo $row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']; ?>
                    </p>
                    <p><b>Tipo:</b> <?php echo $row['tipo_persona']; ?></p>
                </div>

                <div class="col-md-6">
                    <p><b>Descripción:</b> <?php echo $row['descripcion_objeto']; ?></p>
                    <p><b>Categoría:</b> <?php echo $row['nombre_categoria']; ?></p>
                    <p><b>Lugar:</b> <?php echo $row['lugar_referencia']; ?></p>
                </div>
            </div>

            <hr>

            <p><b>Estado:</b>
                <?php
                if ($row['estado'] == '1') echo "<span class='badge badge-warning'>REGISTRADO</span>";
                elseif ($row['estado'] == '2') echo "<span class='badge badge-primary'>CON ACTA DE ENTREGA</span>";
                elseif ($row['estado'] == '3') echo "<span class='badge badge-success'>ENTREGADO</span>";
                elseif ($row['estado'] == '4') echo "<span class='badge badge-dark'>CERRADO</span>";
                ?>
            </p>

        </div>
    </div>


    <!-- ================= REGISTRADO POR ================= -->
    <div class="card mt-3">
        <div class="card-header bg-info"><b>REGISTRADO POR</b></div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <p><b>Nombre completo:</b>
                        <?php echo $row['user_nombre'] . ' ' . $row['user_apellido_paterno'] . ' ' . $row['user_apellido_materno']; ?>
                    </p>
                </div>

                <div class="col-md-6">
                    <p><b>Fecha:</b> <?php echo $fecha; ?></p>
                </div>
            </div>

        </div>
    </div>


    <!-- ================= FOTOS ================= -->
    <div class="card mt-3">
        <div class="card-header bg-secondary"><b>FOTOS DEL OBJETO</b></div>
        <div class="card-body">
            <div class="row">

                <?php if ($fotos->num_rows > 0) { ?>
                    <?php while ($f = $fotos->fetch_assoc()) { ?>
                        <div class="col-md-3 mb-2">
                            <img src="../dist/fotos_objetos/<?php echo $f['nombre_archivo']; ?>"
                                class="img-fluid" style="height:150px;object-fit:cover;">
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-muted">No hay fotos registradas</p>
                <?php } ?>

            </div>
        </div>
    </div>


    <!-- ================= ACTA DE REGISTRO ================= -->
    <div class="card mt-3">
        <div class="card-header bg-dark"><b>ACTA DE REGISTRO</b></div>
        <div class="card-body">

            <?php if ($acta_row) { ?>

                <p><b><?php echo $acta_row['nombre_acta']; ?></b></p>

                <a href="../dist/actas/<?php echo $acta_row['nombre_archivo']; ?>"
                    target="_blank" class="btn btn-info btn-sm">
                    <i class="fas fa-file"></i> Ver Acta
                </a>

                <button class="btn btn-warning btn-sm"
                    onclick="abrirActaEditar(<?php echo $row['id']; ?>)">
                    <i class="fas fa-edit"></i> Editar Acta
                </button>

            <?php } else { ?>

                <button class="btn btn-dark btn-sm"
                    onclick="abrirActa(<?php echo $row['id']; ?>)">
                    <i class="fas fa-file-upload"></i> Subir Acta
                </button>

            <?php } ?>

        </div>
    </div>

</div>