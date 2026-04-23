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

    console.log("ID ENTREGA:", id); // DEBUG

    $('#entrega_registro_id').val(id);

    $('#modal_entrega').modal('show');
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

    // 🔥 CERRAR MODAL DETALLE
    $('#modal_detalle').modal('hide');

    // LIMPIAR
    $('#acta_registro_id').val('');
    $('#nombre_acta').val('');
    $('#archivo_acta').val('');
    $('#preview_acta_actual').html('');
    $('.custom-file-label').text('Seleccionar archivo...');

    // SET ID
    $('#acta_registro_id').val(id);

    $.post('listarobjetos_acta_row.php', {
      id: id
    }, function(res) {

      let r = JSON.parse(res);

      if (r && r.nombre_archivo) {

        $('#nombre_acta').val(r.nombre_acta);

        $('#preview_acta_actual').html(`
        <iframe src="../dist/actas/${r.nombre_archivo}" 
                width="100%" height="300"></iframe>
      `);

      }

      // 🔥 abrir DESPUÉS de cerrar el otro
      setTimeout(() => {
        $('#modal_acta_registro').modal('show');
      }, 400);

    });

  }


  function limpiarModalActa() {
    $('#nombre_acta').val('');
    $('#archivo_acta').val('');
    $('.custom-file-label').html('Seleccionar archivo...');
    $('#preview_acta_actual').html('');
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

<script>
  function verEntrega(id) {

    // 🔥 CERRAR TODOS LOS MODALES
    $('.modal').modal('hide');

    setTimeout(() => {

      // 🔥 ABRIR MODAL CORRECTO
      $('#modal_ver_entrega').modal({
        backdrop: 'static',
        keyboard: false
      });

      // LOADING
      $('#contenido_entrega').html(`
      <center>
        <i class="fas fa-spinner fa-spin"></i>
        <p>Cargando...</p>
      </center>
    `);

      // AJAX
      $.post('listarobjetos_entrega_detalle.php', {
        id: id
      }, function(res) {
        $('#contenido_entrega').html(res);
      });

    }, 300);
  }
</script>

<script>
  function validarEntrega(id, estado) {

    if (estado == 2) {
      // ✔ permitido
      funcionEntrega(id);

    } else if (estado == 1) {
      // ❌ bloquear
      alert("Adjuntar el Acta de Registro");

      // opcional: toastr bonito
      // toastr.warning("Adjuntar el Acta de Registro");

    } else {
      // fallback
      alert("No se puede realizar esta acción");
    }
  }
</script>

<script>
  $(document).on('change', '#input_acta_entrega', function() {

    let file = this.files[0];

    if (!file) return;

    let url = URL.createObjectURL(file);

    $('#preview_acta_entrega').html(`
    <iframe src="${url}" width="100%" height="300"></iframe>
  `);
  });

  function editarActaEntrega(id, nombre) {

    $('#nombre_acta_entrega').val(nombre);

    $('html, body').animate({
      scrollTop: $("#nombre_acta_entrega").offset().top - 100
    }, 300);

  }
</script>

<script>
  function abrirModalActaEntrega(entrega_id) {

    // LIMPIAR TODO
    $('#acta_entrega_id').val('');
    $('#acta_nombre').val('');
    $('#input_acta_entrega_modal').val('');
    $('#preview_acta_modal').html('');

    $('#acta_entrega_id').val(entrega_id);

    $.post('listarobjetos_entrega_acta_row.php', {
      entrega_id
    }, function(res) {

      let r = JSON.parse(res);

      if (r && r.nombre_archivo) {

        $('#acta_nombre').val(r.nombre_acta);

        // 🔥 MISMO CONTENEDOR SIEMPRE
        $('#preview_acta_modal').html(`
        <iframe src="../dist/actas_entrega/${r.nombre_archivo}" 
                width="100%" height="300"></iframe>
      `);

      }

      $('#modal_acta_entrega').modal('show');

    });

  }
</script>
<script>
  $(document).on('change', '#input_acta_entrega_modal', function() {

    let file = this.files[0];

    if (!file) return;

    let url = URL.createObjectURL(file);

    $('#preview_acta_modal').html(`
    <iframe src="${url}" width="100%" height="300"></iframe>
  `);
  });
</script>

<script>
  $(document).ready(function() {

    window.timeoutGlobal = null;

    // ================= INPUT DOCUMENTO =================
    $(document).on('keyup', '#dni_input', function() {

      let input = $(this);

      // 🔥 DETECTA SIEMPRE EL MODAL CORRECTO
      let modal = input.closest('.modal');

      let doc = input.val();
      let tipo = modal.find('#tipo_documento').val();

      clearTimeout(window.timeoutGlobal);

      // DNI
      if (tipo === 'DNI' && doc.length === 8) {
        window.timeoutGlobal = setTimeout(() => consultarRENIEC(doc, modal), 400);
        return;
      }

      // CE
      if (tipo === 'CE' && doc.length >= 9) {
        window.timeoutGlobal = setTimeout(() => consultarCE(doc, modal), 400);
        return;
      }

      limpiarCampos(modal);
      bloquearCampos(false, modal);

    });


    // ================= CAMBIO TIPO DOC =================
    $(document).on('change', '#tipo_documento', function() {

      let modal = $(this).closest('.modal');

      modal.find('#dni_input').val('');
      limpiarCampos(modal);
      bloquearCampos(false, modal);

    });


    // ================= LIMPIAR MODALES =================
    $(document).on('hidden.bs.modal', '.modal', function() {

      let modal = $(this);

      if (modal.find('form').length) {
        modal.find('form')[0].reset();
      }

      limpiarCampos(modal);
      bloquearCampos(false, modal);

      modal.find('#dni_input').removeClass('is-loading');

    });

  });


  // ================= RENIEC =================
  function consultarRENIEC(dni, modal) {

    modal.find('#dni_input').addClass('is-loading');

    $.ajax({
      url: 'reniec_consultar.php',
      type: 'POST',
      data: {
        dni: dni
      },
      dataType: 'json',

      success: function(res) {

        let data = res.consultarResponse?.return || res.PIDE || res;

        if (data.coResultado === "0000") {

          let p = data.datosPersona;

          modal.find('[name="nombre"]').val(p.prenombres);
          modal.find('[name="apellido_paterno"]').val(p.apPrimer);
          modal.find('[name="apellido_materno"]').val(p.apSegundo);

          // 🔥 DIRECCIÓN RENIEC
          let direccion = '';
          if (p.ubigeo) direccion += p.ubigeo;
          if (p.direccion) direccion += (direccion ? ' - ' : '') + p.direccion;

          modal.find('[name="direccion"]').val(direccion);

          bloquearCampos(true, modal);

        } else {

          let mensaje = data.deResultado || "DNI no encontrado en RENIEC";

          alert("RENIEC: " + mensaje);

          limpiarCampos(modal);
          bloquearCampos(false, modal);

        }

      },

      complete: function() {
        modal.find('#dni_input').removeClass('is-loading');
      }

    });

  }


  // ================= MIGRACIONES =================
  function consultarCE(doc, modal) {

    modal.find('#dni_input').addClass('is-loading');

    $.ajax({
      url: 'migraciones_consultar.php',
      type: 'POST',
      data: {
        doc: doc
      },
      dataType: 'json',

      success: function(res) {

        let data = res.jsonObject || res;

        if (data.codRespuesta === "0000") {

          let p = data.datosPersonales;

          modal.find('[name="nombre"]').val(p.nombres);
          modal.find('[name="apellido_paterno"]').val(p.apepaterno);
          modal.find('[name="apellido_materno"]').val(p.apematerno);

          // 🔥 DIRECCIÓN MIGRACIONES
          let direccion = '';
          if (p.ubiactual) direccion += p.ubiactual;
          if (p.domactual) direccion += (direccion ? ' - ' : '') + p.domactual;

          modal.find('[name="direccion"]').val(direccion);

          bloquearCampos(true, modal);

        } else {

          let mensaje = data.desRespuesta || "Carné de extranjería no encontrado";

          alert("MIGRACIONES: " + mensaje);

          limpiarCampos(modal);
          bloquearCampos(false, modal);

        }

      },

      complete: function() {
        modal.find('#dni_input').removeClass('is-loading');
      }

    });

  }


  // ================= BLOQUEAR =================
  function bloquearCampos(estado, modal) {

    modal.find('[name="nombre"]').prop('readonly', estado);
    modal.find('[name="apellido_paterno"]').prop('readonly', estado);
    modal.find('[name="apellido_materno"]').prop('readonly', estado);

  }


  // ================= LIMPIAR =================
  function limpiarCampos(modal) {

    modal.find('[name="nombre"]').val('');
    modal.find('[name="apellido_paterno"]').val('');
    modal.find('[name="apellido_materno"]').val('');
    modal.find('[name="direccion"]').val('');

  }
</script>

<script>
  function buscar() {

    $.post('listarobjetos_buscar.php', $('#formFiltros').serialize(), function(html) {

      $('#contenedorTabla').html(html).show();

      // 🔥 REINICIAR DATATABLE
      $("#example1").DataTable({
        destroy: true,
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: ["excel"],
        order: [
          [0, "desc"]
        ]
      });

    });

  }

  function limpiarFiltros() {

    // 🔥 resetear formulario
    $('#formFiltros')[0].reset();

    // 🔥 limpiar tabla
    $('#contenedorTabla').html('').hide();

  }

  function exportarExcel() {

    let datos = $('#formFiltros').serialize();

    window.open('exportar_excel_objetos.php?' + datos, '_blank');

  }
</script>