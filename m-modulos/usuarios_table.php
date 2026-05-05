<div class="card mb-3">
  <div class="card-header"><b>Filtros</b></div>
  <div class="card-body">

    <form id="formUsuarios">

      <div class="row">

        <div class="col-md-3">
          <label>Nombre completo</label>
          <input type="text" name="nombre" class="form-control" placeholder="Ej: CARLA">
        </div>

        <div class="col-md-3">
          <label>Nro Documento</label>
          <input type="text" name="documento" class="form-control">
        </div>

        <div class="col-md-2">
          <label>Rol</label>
          <select name="role_id" class="form-control">
            <option value="">Todos</option>
            <?php
            $roles = $conn->query("SELECT * FROM objetosperdidos_roles");
            while ($r = $roles->fetch_assoc()) {
              echo "<option value='{$r['id']}'>{$r['role_name']}</option>";
            }
            ?>
          </select>
        </div>

        <div class="col-md-2">
          <label>Estado</label>
          <select name="estado" class="form-control">
            <option value="">Todos</option>
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
          </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
          <button type="button" class="btn btn-primary w-100" onclick="buscarUsuarios()">
            <i class="fas fa-search"></i> Buscar
          </button>
        </div>

      </div>

    </form>

  </div>
</div>

<div id="tablaUsuarios"></div>