<!-- Update Photo -->
<div class="modal fade" id="edit_photo">
  <div class="modal-dialog">
    <div class="modal-content">

      <form class="form-horizontal" method="POST" action="listarobjetos_modal_edit_photo_f.php" enctype="multipart/form-data">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b><span class="del_employee_name"></span></b></h4>
        </div>
        <div class="modal-body">

          <input type="hidden" class="id_photo" name="id">

          <div class="form-group">
            <label for="edit_photo_x" class="col-sm-12 control-label"><?php echo $titulocampobd20P; ?></label>

            <div>
              <input type="file"
                id="edit_photo_x"
                name="<?php echo "$titulocampobd20"; ?>"
                accept="application/pdf,.pdf"
                required>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cerrar</button>
          <button type="submit" class="btn btn-success btn-flat" name="upload"><i class="fa fa-check-square-o"></i> Actualizar</button>

        </div>

        <?php foreach ($_GET as $key => $value): ?>
          <input type="hidden" name="filtro_<?php echo $key; ?>" value="<?php echo htmlspecialchars($value); ?>">
        <?php endforeach; ?>

      </form>

    </div>
  </div>
</div>