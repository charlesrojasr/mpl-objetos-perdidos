<div class="modal fade" id="modal_acta_registro" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg">

      <form method="POST" action="listarobjetos_acta_f.php" enctype="multipart/form-data">

        <!-- HEADER -->
        <div class="modal-header bg-gradient-dark">
          <h5 class="modal-title font-weight-bold">
            <i class="fas fa-file-alt mr-2"></i> Gestión de Acta
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>

        <!-- BODY -->
        <div class="modal-body">

          <div class="container-fluid">

            <!-- ID OCULTO -->
            <input type="hidden" name="registro_id" id="acta_registro_id">

            <!-- INFO -->
            <div class="alert alert-info py-2">
              <i class="fas fa-info-circle mr-1"></i>
              Adjunte el acta en formato PDF o imagen.
            </div>

            <!-- NOMBRE ACTA -->
            <div class="form-group">
              <label class="font-weight-bold">Nombre del Acta</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-dark">
                    <i class="fas fa-file-signature text-white"></i>
                  </span>
                </div>
                <input type="text"
                  name="nombre_acta"
                  id="nombre_acta"
                  class="form-control"
                  readonly
                  required>
              </div>
            </div>

            <!-- ARCHIVO -->
            <div class="form-group">
              <label class="font-weight-bold">Archivo</label>
              <div class="custom-file">
                <input type="file"
                  name="archivo_acta"
                  class="custom-file-input"
                  id="archivo_acta"
                  accept=".pdf,image/*">
                <label class="custom-file-label" for="archivo_acta">
                  Seleccionar archivo...
                </label>
              </div>
              <small class="text-muted">
                Formatos permitidos: PDF, JPG, PNG.
              </small>
            </div>

            <!-- ACTA ACTUAL -->
            <div id="preview_acta_actual" class="mt-3"></div>

          </div>

        </div>

        <!-- FOOTER -->
        <div class="modal-footer justify-content-between">

          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>

          <button type="submit" class="btn btn-success">
            <i class="fas fa-save mr-1"></i> Guardar Acta
          </button>

        </div>

      </form>

    </div>
  </div>
</div>