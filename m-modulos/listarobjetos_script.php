<script>
  // ================= EDITAR =================
  function funcionEditar(id) {
    $('#edit').modal('show');
    getRow(id);
  }

  // ================= ENTREGA =================
  function funcionEntrega(id) {
    $('#modal_entrega').modal('show');
    getRow(id);
  }

  // ================= DETALLE =================
  function verDetalle(id) {

    $('#modal_detalle').modal('show');

    $("#contenido_detalle").html(`
    <center>
      <i class="fas fa-spinner fa-spin fa-2x"></i>
      <p>Cargando información...</p>
    </center>
  `);

    $.ajax({
      url: "listarobjetos_modal_detalle.php", // 👈 CAMBIO AQUÍ
      type: "POST",
      data: {
        id: id
      },
      success: function(response) {
        $("#contenido_detalle").html(response);
      },
      error: function(xhr) {
        console.log(xhr.responseText);
        $("#contenido_detalle").html("<p class='text-danger'>Error al cargar</p>");
      }
    });
  }


  // ================= TRAER DATOS =================
  function getRow(id) {
    var tabla = '<?php echo $primaryTable; ?>';

    $.ajax({
      type: 'POST',
      url: 'listarobjetos_row.php',
      data: {
        id: id,
        tabla: tabla
      },
      dataType: 'json',
      success: function(response) {

        console.log(response);

        $('.empid').val(response.id);

        $('#edit_nombre').val(response.nombre);
        $('#edit_descripcion_objeto').val(response.descripcion_objeto);

      }
    });
  }
</script>




<script>
  $(function() {

    // SOLO NÚMEROS
    $(document).on('input', '.only-numbers', function() {
      this.value = this.value.replace(/[^0-9]/g, '');
    });

    // MAYÚSCULAS
    $(document).on('input', '.text-uppercase', function() {
      this.value = this.value.toUpperCase();
    });

  });
</script>

<script>
  let archivosSeleccionados = [];

  // CUANDO SELECCIONA ARCHIVOS
  $(document).on('change', '#inputFotos', function(e) {

    let files = Array.from(e.target.files);

    archivosSeleccionados = archivosSeleccionados.concat(files);

    renderPreview();

    // ACTUALIZAR LABEL
    let label = $(this).siblings('.custom-file-label');
    label.html(archivosSeleccionados.length + " archivos seleccionados");

    // RESET INPUT
    $(this).val('');
  });


  // RENDER PREVIEW
  function renderPreview() {

    let contenedor = $("#preview_fotos");
    contenedor.html("");

    archivosSeleccionados.forEach((file, index) => {

      let reader = new FileReader();

      reader.onload = function(e) {

        let html = `
                <div class="col-md-3 mb-2">
                    <div class="card">
                        <img src="${e.target.result}" class="img-fluid" style="height:150px; object-fit:cover;">
                        <div class="card-body p-2 text-center">
                            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFoto(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

        contenedor.append(html);
      };

      reader.readAsDataURL(file);

    });
  }


  // ELIMINAR FOTO
  function eliminarFoto(index) {
    archivosSeleccionados.splice(index, 1);
    renderPreview();

    // actualizar label
    $('.custom-file-label').html(archivosSeleccionados.length + " archivos seleccionados");
  }


  // ANTES DE ENVIAR FORM
  $('#formAdd').on('submit', function() {

    let input = document.getElementById('inputFotos');

    let dataTransfer = new DataTransfer();

    archivosSeleccionados.forEach(file => {
      dataTransfer.items.add(file);
    });

    input.files = dataTransfer.files;

  });
</script>

<script>
  function cargarActaRegistro(id) {

    $('#acta_registro_id').val(id);

    $.ajax({
      url: "listarobjetos_acta_row.php",
      type: "POST",
      data: {
        id: id
      },
      dataType: "json",
      success: function(res) {

        if (res) {
          $('#nombre_acta').val(res.nombre_acta);

          if (res.nombre_archivo) {
            $('#acta_actual').html(`
            <a href="../dist/actas/${res.nombre_archivo}" target="_blank" class="btn btn-info btn-sm">
              <i class="fas fa-eye"></i> Ver Acta Actual
            </a>
          `);
          } else {
            $('#acta_actual').html('<p class="text-muted">No hay archivo</p>');
          }

        } else {
          $('#nombre_acta').val('');
          $('#acta_actual').html('');
        }

      }
    });

  }
</script>

<script>
  function abrirActa(id) {

    $('#acta_registro_id').val(id);

    $('#modal_detalle').modal('hide');

    $('#modal_detalle').one('hidden.bs.modal', function() {

      $('#modal_acta_registro').modal({
        backdrop: 'static',
        keyboard: false
      });

    });
  }

  function abrirActaEditar(id) {

    limpiarModalActa();

    $('#modal_detalle').modal('hide');

    $('#modal_detalle').one('hidden.bs.modal', function() {

      $.post('listarobjetos_acta_row.php', {
        id: id
      }, function(data) {

        let res = JSON.parse(data);

        $('#acta_registro_id').val(id);
        $('#nombre_acta').val(res.nombre_acta);

        $('#acta_actual').html(`
                <div class="alert alert-secondary">
                    ${res.nombre_acta}<br>
                    <a href="../dist/actas/${res.nombre_archivo}" target="_blank">
                        Ver archivo actual
                    </a>
                </div>
            `);

        $('#modal_acta_registro').modal('show');

      });

    });
  }

  function limpiarModalActa() {
    $('#nombre_acta').val('');
    $('#archivo_acta').val('');
    $('.custom-file-label').html('Seleccionar archivo...');
    $('#acta_actual').html('');
  }
</script>

<script>
  $('.custom-file-input').on('change', function(e) {
    var fileName = e.target.files[0].name;
    $(this).next('.custom-file-label').html(fileName);
  });
</script>