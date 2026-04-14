<script>
  // ================= EDITAR =================
  function funcionEditar(id) {

    // LIMPIAR ESTADO PREVIO (IMPORTANTE)
    archivosSeleccionados = [];
    $('#preview_fotos').html('');
    $('.custom-file-label').html('Seleccionar archivos');

    $.post('listarobjetos_row.php', {
      id: id
    }, function(res) {

      let r = JSON.parse(res);

      console.log(r); // DEBUG

      // ================= ID =================
      $('#registro_id').val(r.id);

      // ================= ANÓNIMO =================
      if (r.tipo_persona === 'ANÓNIMO') {
        $('#checkAnonimo').prop('checked', true).trigger('change');
      } else {
        $('#checkAnonimo').prop('checked', false).trigger('change');
      }

      // ================= PERSONA =================
      $('[name="nro_documento"]').val(r.nro_documento);
      $('[name="nombre"]').val(r.nombre);
      $('[name="apellido_paterno"]').val(r.apellido_paterno);
      $('[name="apellido_materno"]').val(r.apellido_materno);
      $('[name="tipo_persona"]').val(r.tipo_persona);

      // ================= OBJETO =================
      $('[name="descripcion_objeto"]').val(r.descripcion_objeto);
      $('[name="categoria_id"]').val(r.categoria_id);
      $('[name="lugar_referencia"]').val(r.lugar_referencia);

      // ================= FOTOS EXISTENTES =================
      $.post('listarobjetos_fotos_row.php', {
        id: id
      }, function(resFotos) {

        let fotos = JSON.parse(resFotos);

        let html = '';

        fotos.forEach(f => {

          html += `
                <div class="col-md-3 mb-2" id="foto_${f.id}">
                    <div class="card">
                        <img src="../dist/fotos_objetos/${f.nombre_archivo}" 
                             class="img-fluid" style="height:150px; object-fit:cover;">
                        <div class="card-body p-2 text-center">
                            <button type="button" 
                                class="btn btn-danger btn-sm"
                                onclick="eliminarFotoBD(${f.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
        });

        $('#preview_existentes').html(html);

      });

      // ================= TÍTULO =================
      $('.modal-title').html('<i class="fas fa-edit"></i> Editar Registro');

      // ================= ABRIR MODAL =================
      $('#addnew').modal('show');

    });

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

    let contenedor = $("#preview_nuevas");
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


<script>
  // REGISTRO ANÓNIMO
  $(document).on('change', '#checkAnonimo', function() {

    if ($(this).is(':checked')) {

      // Inputs
      $('input.datos-persona').prop('readonly', true);
      $('input.datos-persona').prop('required', false);
      $('input.datos-persona').val('');

      // Select
      $('select.datos-persona').prop('disabled', true);
      $('select.datos-persona').prop('required', false);
      $('select.datos-persona').val('');

    } else {

      // Inputs
      $('input.datos-persona').prop('readonly', false);
      $('input.datos-persona').prop('required', true);

      // Select
      $('select.datos-persona').prop('disabled', false);
      $('select.datos-persona').prop('required', true);

    }

  });
</script>

<script>
  function abrirNuevo() {

    // RESET FORM
    $('#formAdd')[0].reset();

    // LIMPIAR ID (CLAVE)
    $('#registro_id').val('');

    // RESET CHECK ANÓNIMO
    $('#checkAnonimo').prop('checked', false).trigger('change');

    // LIMPIAR FOTOS
    archivosSeleccionados = [];
    $('#preview_fotos').html('');
    $('.custom-file-label').html('Seleccionar archivos');

    // TÍTULO
    $('.modal-title').html('<i class="fas fa-box-open"></i> Nuevo Registro');

    // ABRIR MODAL
    $('#addnew').modal('show');

    $('#preview_existentes').html('');
    $('#preview_nuevas').html('');
  }
</script>

<script>
  function eliminarFotoBD(id) {

    if (!confirm('¿Eliminar esta foto?')) return;

    $.post('listarobjetos_foto_delete.php', {
      id: id
    }, function() {

      $('#foto_' + id).remove();

    });

  }
</script>


<script>
  // ================= ARCHIVOS ENTREGA =================
  let archivosEntrega = [];

  // CUANDO SELECCIONA ARCHIVOS
  $(document).on('change', '#archivos_entrega', function(e) {

    let files = Array.from(e.target.files);

    archivosEntrega = archivosEntrega.concat(files);

    renderPreviewEntrega();

    // actualizar label
    let label = $(this).siblings('.custom-file-label');
    label.html(archivosEntrega.length + " archivos seleccionados");

    // reset input
    $(this).val('');
  });


  // RENDER PREVIEW ENTREGA
  function renderPreviewEntrega() {

    let contenedor = $("#preview_archivos_entrega");
    contenedor.html("");

    archivosEntrega.forEach((file, index) => {

      let reader = new FileReader();

      reader.onload = function(e) {

        let html = '';

        // IMAGEN
        if (file.type.startsWith('image/')) {

          html = `
                <div class="col-md-3 mb-2">
                    <div class="card">
                        <img src="${e.target.result}" class="img-fluid" style="height:150px; object-fit:cover;">
                        <div class="card-body p-2 text-center">
                            <button type="button" class="btn btn-danger btn-sm" onclick="eliminarArchivoEntrega(${index})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;

        } else {

          // PDF / OTROS
          html = `
                <div class="col-md-3 mb-2">
                    <div class="card text-center p-3">
                        <i class="fas fa-file fa-2x text-secondary"></i>
                        <small class="mt-2">${file.name}</small>
                        <button class="btn btn-danger btn-sm mt-2" onclick="eliminarArchivoEntrega(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>`;
        }

        contenedor.append(html);
      };

      reader.readAsDataURL(file);
    });
  }


  // ELIMINAR ARCHIVO ENTREGA
  function eliminarArchivoEntrega(index) {
    archivosEntrega.splice(index, 1);
    renderPreviewEntrega();

    $('.custom-file-label').html(archivosEntrega.length + " archivos seleccionados");
  }


  // ANTES DE ENVIAR FORM ENTREGA
  $('#modal_entrega form').on('submit', function() {

    let input = document.getElementById('archivos_entrega');

    let dataTransfer = new DataTransfer();

    archivosEntrega.forEach(file => {
      dataTransfer.items.add(file);
    });

    input.files = dataTransfer.files;

  });


  // LIMPIAR AL CERRAR MODAL
  $('#modal_entrega').on('hidden.bs.modal', function() {

    archivosEntrega = [];
    $('#preview_archivos_entrega').html('');
    $('#archivos_entrega').val('');
    $('.custom-file-label').html('Seleccionar archivos...');

  });
</script>

<script>
  $('#modal_entrega').on('hidden.bs.modal', function() {

    // 1. RESET FORM COMPLETO
    $(this).find('form')[0].reset();

    // 2. LIMPIAR INPUT FILE
    $('#archivos_entrega').val('');

    // 3. LIMPIAR PREVIEW
    $('#preview_archivos_entrega').html('');

    // 4. LIMPIAR ARRAY DE ARCHIVOS
    archivosEntrega = [];

    // 5. RESET LABEL FILE
    $('#archivos_entrega').next('.custom-file-label').html('Seleccionar archivos...');

    // 6. LIMPIAR ID OCULTO (IMPORTANTE)
    $('#entrega_registro_id').val('');

    $('#modal_entrega').on('show.bs.modal', function() {
      $('#preview_archivos_entrega').html('');
    });

  });
</script>