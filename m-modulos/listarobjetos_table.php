<form id="formFiltros">

  <div class="card card-outline card-info">
    <div class="card-header"><b>🔎 Datos del Objeto</b></div>
    <div class="card-body">
      <div class="row">

        <div class="col-md-2">
          <label>Estado</label>
          <select name="estado" class="form-control">
            <option value="">Todos</option>
            <option value="1">Registrado</option>
            <option value="2">Con Acta</option>
            <option value="3">Entregado</option>
            <option value="4">Cerrado</option>
          </select>
        </div>

        <div class="col-md-3">
          <label>Objeto</label>
          <input type="text" name="objeto" class="form-control">
        </div>

        <div class="col-md-3">
          <label>Categoría</label>
          <select name="categoria_id" class="form-control">
            <option value="">Todas</option>
            <?php
            $cat = $conn->query("SELECT * FROM objetosperdidos_categorias");
            while ($c = $cat->fetch_assoc()) {
              echo "<option value='{$c['id']}'>{$c['nombre_categoria']}</option>";
            }
            ?>
          </select>
        </div>

      </div>
    </div>
  </div>

  <div class="card card-outline card-primary">
    <div class="card-header"><b>📥 Datos de Registro</b></div>
    <div class="card-body">
      <div class="row">

        <div class="col-md-3">
          <label>Persona Registro</label>
          <input type="text" name="persona_registro" class="form-control">
        </div>

        <div class="col-md-2">
          <label>DNI Registro</label>
          <input type="text" name="documento_persona_registro" class="form-control">
        </div>

        <div class="col-md-2">
          <label>Tipo Persona</label>
          <select name="tipo_persona" class="form-control">
            <option value="">Todos</option>
            <option value="ANÓNIMO">ANÓNIMO</option>
            <option value="CIUDADANO">CIUDADANO</option>
            <option value="SERENO">SERENO</option>
          </select>
        </div>

        <div class="col-md-3">
          <label>Usuario Registro</label>
          <select name="user_registro" class="form-control">
            <option value="">Todos</option>
            <?php
            $u = $conn->query("
            SELECT u.id, CONCAT(up.nombre,' ',up.apellido_paterno) as nombre
            FROM objetosperdidos_users u
            JOIN objetosperdidos_user_profile up ON u.id = up.user_id
            WHERE u.role_id = 2
          ");
            while ($x = $u->fetch_assoc()) {
              echo "<option value='{$x['id']}'>{$x['nombre']}</option>";
            }
            ?>
          </select>
        </div>

        <div class="col-md-2">
          <label>Fecha Inicio</label>
          <input type="date" name="fecha_ini" class="form-control">
        </div>

        <div class="col-md-2">
          <label>Fecha Fin</label>
          <input type="date" name="fecha_fin" class="form-control">
        </div>

        <div class="col-md-3">
          <label>Acta Recepción</label>
          <input type="text" name="acta_recepcion" class="form-control">
        </div>

      </div>
    </div>
  </div>

  <div class="card card-outline card-success">
    <div class="card-header"><b>🚚 Datos de Entrega</b></div>
    <div class="card-body">
      <div class="row">

        <div class="col-md-3">
          <label>Persona Entrega</label>
          <input type="text" name="persona_entrega" class="form-control">
        </div>

        <div class="col-md-2">
          <label>DNI Entrega</label>
          <input type="text" name="dni_entrega" class="form-control">
        </div>

        <div class="col-md-3">
          <label>Usuario Entrega</label>
          <select name="user_entrega" class="form-control">
            <option value="">Todos</option>
            <?php
            $u = $conn->query("
            SELECT u.id, CONCAT(up.nombre,' ',up.apellido_paterno) as nombre
            FROM objetosperdidos_users u
            JOIN objetosperdidos_user_profile up ON u.id = up.user_id
            WHERE u.role_id = 2
          ");
            while ($x = $u->fetch_assoc()) {
              echo "<option value='{$x['id']}'>{$x['nombre']}</option>";
            }
            ?>
          </select>
        </div>

        <div class="col-md-2">
          <label>Fecha Inicio</label>
          <input type="date" name="fecha_entrega_ini" class="form-control">
        </div>

        <div class="col-md-2">
          <label>Fecha Fin</label>
          <input type="date" name="fecha_entrega_fin" class="form-control">
        </div>

        <div class="col-md-3">
          <label>Acta Entrega</label>
          <input type="text" name="acta_entrega" class="form-control">
        </div>

      </div>
    </div>
  </div>

  <div class="text-right mt-3">
    <button type="button" class="btn btn-primary" onclick="buscar()">
      <i class="fas fa-search"></i> Buscar
    </button>

    <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
      <i class="fas fa-eraser"></i> Limpiar
    </button>

    <button type="button" class="btn btn-success" onclick="exportarExcel()">
      <i class="fas fa-file-excel"></i> Exportar Excel
    </button>
  </div>

</form>

<div id="contenedorTabla"></div>