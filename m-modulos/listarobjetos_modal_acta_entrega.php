<div class="modal fade" id="modal_acta_entrega">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <form method="POST" action="listarobjetos_entrega_acta_f.php" enctype="multipart/form-data">

        <!-- HEADER -->
        <div class="modal-header bg-dark">
          <h5 class="modal-title">
            <i class="fas fa-file-signature"></i> Gestión de Acta de Entrega
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>

        <!-- BODY -->
        <div class="modal-body">

          <input type="hidden" name="entrega_id" id="acta_entrega_id">

          <!-- NOMBRE -->
          <div class="form-group">
            <label>Nombre del Acta</label>
            <input type="text" name="nombre_acta" id="acta_nombre"
              class="form-control text-uppercase" required>
          </div>

          <!-- ARCHIVO -->
          <div class="form-group">
            <label>Archivo (PDF)</label>

            <div class="custom-file">
              <input type="file" name="acta"
                class="custom-file-input"
                id="input_acta_entrega_modal"
                accept=".pdf">
              <label class="custom-file-label">Seleccionar archivo...</label>
            </div>

            <!-- PREVIEW -->
            <div id="preview_acta_modal" class="mt-3"></div>

          </div>

        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button class="btn btn-success">
            <i class="fas fa-save"></i> Guardar
          </button>
        </div>

      </form>

    </div>
  </div>
</div>