<?php
include 'usuarios_config.php';
?>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre completo</th>
      <th>Documento</th>
      <th>Usuario</th>
      <th>Rol</th>
      <th>Estado</th>
      <th>Creado</th>
      <th>Actualizado</th>
      <th>Reset</th>
    </tr>
  </thead>

  <tbody>

    <?php while ($row = $result->fetch_assoc()) { ?>

      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['nombre_completo'] ?></td>
        <td><?= $row['documento_identidad'] ?></td>
        <td><?= $row['username'] ?></td>
        <td><?= $row['role_name'] ?></td>
        <td class="text-center">

          <?php if ($row['id'] == 1) { ?>

            <span class="badge badge-dark">
              PROTEGIDO
            </span>

          <?php } else { ?>

            <input type="checkbox"
              class="switch-estado"
              data-id="<?= $row['id'] ?>"
              <?= $row['estado'] == 1 ? 'checked' : '' ?>>

          <?php } ?>

        </td>
        <td><?= date('Y-m-d', strtotime($row['created_at'] . ' -1 hour')) ?></td>
        <td><?= date('Y-m-d', strtotime($row['updated_at'] . ' -1 hour')) ?></td>

        <td class="text-center">

          <?php if ($row['id'] == 1) { ?>

            <button class="btn btn-secondary btn-sm" disabled>
              <i class="fas fa-lock"></i>
            </button>

          <?php } else { ?>

            <button
              class="btn btn-warning btn-sm btn-reset-clave"
              data-id="<?= $row['id'] ?>"
              data-doc="<?= $row['documento_identidad'] ?>">
              <i class="fas fa-key"></i>
            </button>

          <?php } ?>

        </td>
      </tr>

    <?php } ?>

  </tbody>
</table>