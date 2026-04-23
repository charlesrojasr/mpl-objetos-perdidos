<?php
include 'listarobjetos_config.php';

$where = "WHERE 1=1";

// 🔥 FILTROS DINÁMICOS

if ($_POST['estado'] != '')
    $where .= " AND r.estado = '{$_POST['estado']}'";

if ($_POST['objeto'] != '')
    $where .= " AND r.descripcion_objeto LIKE '%{$_POST['objeto']}%'";

if ($_POST['categoria_id'] != '')
    $where .= " AND r.categoria_id = '{$_POST['categoria_id']}'";

if (!empty($_POST['documento_persona_registro']))
  $where .= " AND r.nro_documento LIKE '%{$_POST['documento_persona_registro']}%'";

if ($_POST['persona_registro'] != '')
    $where .= " AND CONCAT(r.nombre,' ',r.apellido_paterno,' ',r.apellido_materno) LIKE '%{$_POST['persona_registro']}%'";

if ($_POST['tipo_persona'] != '')
    $where .= " AND r.tipo_persona = '{$_POST['tipo_persona']}'";

if ($_POST['user_registro'] != '')
    $where .= " AND r.user_id = '{$_POST['user_registro']}'";

if ($_POST['fecha_ini'] != '')
    $where .= " AND DATE(r.fecha_registro) >= '{$_POST['fecha_ini']}'";

if ($_POST['fecha_fin'] != '')
    $where .= " AND DATE(r.fecha_registro) <= '{$_POST['fecha_fin']}'";

if ($_POST['acta_recepcion'] != '')
    $where .= " AND a.nombre_acta LIKE '%{$_POST['acta_recepcion']}%'";

if ($_POST['persona_entrega'] != '')
    $where .= " AND CONCAT(e.nombre,' ',e.apellido_paterno,' ',e.apellido_materno) LIKE '%{$_POST['persona_entrega']}%'";

if ($_POST['dni_entrega'] != '')
    $where .= " AND e.nro_documento LIKE '%{$_POST['dni_entrega']}%'";

if ($_POST['fecha_entrega_ini'] != '')
    $where .= " AND DATE(e.fecha_entrega) >= '{$_POST['fecha_entrega_ini']}'";

if ($_POST['fecha_entrega_fin'] != '')
    $where .= " AND DATE(e.fecha_entrega) <= '{$_POST['fecha_entrega_fin']}'";

if ($_POST['user_entrega'] != '')
    $where .= " AND e.user_id = '{$_POST['user_entrega']}'";

if ($_POST['acta_entrega'] != '')
    $where .= " AND ae.nombre_acta LIKE '%{$_POST['acta_entrega']}%'";

// 🔥 TU QUERY BASE + WHERE
$sql = "SELECT r.id,
    CASE 
        WHEN r.estado = '1' THEN 'Registrado'
        WHEN r.estado = '2' THEN 'Con Acta'
        WHEN r.estado = '3' THEN 'Entregado'
        WHEN r.estado = '4' THEN 'Cerrado'
        ELSE 'Desconocido'
    END AS estado_texto,

    r.descripcion_objeto,
    c.nombre_categoria,
    r.lugar_referencia,

    CONCAT(r.nombre, ' ', r.apellido_paterno, ' ', r.apellido_materno) AS persona_registro,
    r.nro_documento AS documento_persona_registro,
    r.tipo_persona,

    CONCAT(upr.nombre, ' ', upr.apellido_paterno, ' ', upr.apellido_materno) AS usuario_registro_nombre,

    r.fecha_registro,

    a.nombre_acta AS acta_recepcion,
    a.fecha_subida AS fecha_acta_recepcion,

    CONCAT(e.nombre, ' ', e.apellido_paterno, ' ', e.apellido_materno) AS persona_entrega,
    e.nro_documento,
    e.correo,
    e.numero_contacto,
    e.direccion,
    e.fecha_entrega,
    CONCAT(up.nombre, ' ', up.apellido_paterno, ' ', up.apellido_materno) AS usuario_entrega_nombre,
    ae.nombre_acta AS acta_entrega,
    ae.fecha_subida AS fecha_acta_entrega
FROM objetosperdidos_registros r
LEFT JOIN objetosperdidos_categorias c ON r.categoria_id = c.id
LEFT JOIN objetosperdidos_actas a ON r.id = a.registro_id
LEFT JOIN objetosperdidos_entregas e ON r.id = e.registro_id
LEFT JOIN objetosperdidos_users u ON e.user_id = u.id
LEFT JOIN objetosperdidos_user_profile up ON u.id = up.user_id
LEFT JOIN objetosperdidos_users ur ON r.user_id = ur.id
LEFT JOIN objetosperdidos_user_profile upr ON ur.id = upr.user_id
LEFT JOIN objetosperdidos_entrega_actas ae ON e.id = ae.entrega_id
$where
ORDER BY r.id DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Error SQL: " . $conn->error);
}
?>




<table id="example1" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Objeto</th>
      <th>Categoría</th>
      <th>Persona Registro</th>
      <th>Fecha Registro</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
  </thead>

  <tbody>

    <?php while ($row = $result->fetch_assoc()) { ?>

      <tr>
        <td><?= $row['id'] ?></td>

        <td><?= $row['descripcion_objeto'] ?></td>

        <td><?= $row['nombre_categoria'] ?></td>

        <td><?= $row['persona_registro'] ?></td>

        <td>
          <?php
          $fecha = new DateTime($row['fecha_registro']);
          $fecha->modify('-1 hour');
          echo $fecha->format('Y-m-d H:i:s');
          ?>
        </td>

        <td>
          <?php
          $estado = $row['estado_texto'];

          if ($estado == 'Registrado') {
            echo '<span class="badge badge-warning">Registrado</span>';
          } elseif ($estado == 'Con Acta') {
            echo '<span class="badge badge-primary">Con Acta</span>';
          } elseif ($estado == 'Entregado') {
            echo '<span class="badge badge-success">Entregado</span>';
          } elseif ($estado == 'Cerrado') {
            echo '<span class="badge badge-dark">Cerrado</span>';
          }
          ?>
        </td>

        <td>

          <a href="#" class="btn btn-warning btn-sm"
            onclick="funcionEditar(<?= $row['id'] ?>)">
            <i class="fas fa-edit"></i>
          </a>

          <a href="#" class="btn btn-info btn-sm"
            onclick="verDetalle(<?= $row['id'] ?>)">
            <i class="fas fa-box-open"></i>
          </a>

          <?php if ($row['estado_texto'] != 'Entregado' && $row['estado_texto'] != 'Cerrado') { ?>

            <a href="#" class="btn btn-success btn-sm"
              onclick="validarEntrega(<?= $row['id'] ?>, <?= $row['estado'] ?? 1 ?>)">
              <i class="fas fa-truck"></i>
            </a>

          <?php } else { ?>

            <a href="#" class="btn btn-dark btn-sm"
              onclick="verEntrega(<?= $row['id'] ?>)">
              <i class="fas fa-file-alt"></i>
            </a>

          <?php } ?>

        </td>

      </tr>

    <?php } ?>

  </tbody>
</table>


