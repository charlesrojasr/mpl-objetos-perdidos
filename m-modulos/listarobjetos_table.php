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

    <?php while ($row = $getAllRegistros->fetch_assoc()) { ?>

      <tr>
        <td><?php echo $row['id']; ?></td>

        <td><?php echo $row['descripcion_objeto']; ?></td>

        <td><?php echo $row['nombre_categoria']; ?></td>

        <td><?php echo $row['persona_registro']; ?></td>

        <td>
          <?php
          $fecha = new DateTime($row['fecha_registro']);
          $fecha->modify('-1 hour');
          echo $fecha->format('Y-m-d H:i:s');
          ?>
        </td>

        <!-- ESTADO CON COLOR -->
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
          } else {
            echo '<span class="badge badge-secondary">Desconocido</span>';
          }
          ?>
        </td>

        <!-- ACCIONES -->
        <td>

          <!-- VER DETALLE (MODAL) -->
          <a href="#modal_detalle" data-toggle="modal"
            class="btn btn-info btn-sm"
            onclick="verDetalle(<?php echo $row['id']; ?>)">
            <i class="fas fa-eye"></i>
          </a>

          <!-- EDITAR -->
          <a href="#edit" data-toggle="modal"
            class="btn btn-warning btn-sm"
            onclick="funcionEditar(<?php echo $row['id']; ?>)">
            <i class="fas fa-edit"></i>
          </a>

          <!-- ENTREGA -->
          <?php if ($row['estado_texto'] != 'Entregado' && $row['estado_texto'] != 'Cerrado') { ?>
            <a href="#modal_entrega" data-toggle="modal"
              class="btn btn-success btn-sm"
              onclick="funcionEntrega(<?php echo $row['id']; ?>)">
              <i class="fas fa-truck"></i>
            </a>
          <?php } ?>

        </td>

      </tr>

    <?php } ?>

  </tbody>
</table>