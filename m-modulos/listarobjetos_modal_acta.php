<div class="modal fade" id="modal_acta_registro">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="listarobjetos_acta_f.php" enctype="multipart/form-data">

        <!-- HEADER -->
        <div class="modal-header bg-dark">
          <h4 class="modal-title">
            <i class="fas fa-file"></i> Acta de Registro
          </h4>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>

        <!-- BODY -->
        <div class="modal-body">

          <!-- ID OCULTO -->
          <input type="hidden" name="registro_id" id="acta_registro_id">

          <!-- NOMBRE ACTA -->
          <div class="form-group">
            <label>Nombre del Acta</label>
            <input type="text" name="nombre_acta" id="nombre_acta" class="form-control text-uppercase" required>
          </div>

          <!-- ARCHIVO -->
          <div class="form-group">
            <label>Archivo (PDF o Imagen)</label>
            <input type="file" name="archivo_acta" class="form-control" accept=".pdf,image/*">
          </div>

          <!-- ACTUAL -->
          <div id="acta_actual" class="mt-2"></div>

        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancelar
          </button>

          <button type="submit" class="btn btn-success">
            Guardar Acta
          </button>
        </div>

      </form>

    </div>
  </div>
</div>