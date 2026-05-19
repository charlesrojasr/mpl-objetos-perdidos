<script>
  function buscarUsuarios() {

    let tabla = $('#tablaUsuarios table');

    // 🔥 destruir ANTES de reemplazar HTML
    if ($.fn.DataTable.isDataTable(tabla)) {
      tabla.DataTable().destroy();
    }

    $.post('usuarios_buscar.php', $('#formUsuarios').serialize(), function(html) {

      $('#tablaUsuarios').html(html);

      // 🔥 pequeña pausa para asegurar render DOM
      setTimeout(function() {

        $('#tablaUsuarios table').DataTable({
          destroy: true,
          lengthChange: false,
          autoWidth: false,
          responsive: true,
          order: [
            [0, "desc"]
          ],
          buttons: ["excel"]
        }).buttons().container().appendTo('#tablaUsuarios .col-md-6:eq(0)');

      }, 50);

    });

  }


  // 🔥 SOLO UN READY
  $(document).ready(function() {

    let filtros = localStorage.getItem('filtrosUsuarios');

    if (filtros) {

      let params = new URLSearchParams(filtros);

      params.forEach((value, key) => {
        $('[name="' + key + '"]').val(value);
      });

    }

    buscarUsuarios(); // 🔥 siempre después de cargar filtros

  });


  // 🔥 SWITCH
  $(document).off('change', '.switch-estado')
    .on('change', '.switch-estado', function() {

      let checkbox = $(this);
      let user_id = checkbox.data('id');
      let estado = checkbox.is(':checked') ? 1 : 0;

      let texto = estado == 1 ? 'activar' : 'desactivar';

      if (!confirm("¿Seguro que deseas " + texto + " este usuario?")) {
        checkbox.prop('checked', !checkbox.prop('checked'));
        return;
      }

      // guardar filtros
      let filtros = $('#formUsuarios').serialize();
      localStorage.setItem('filtrosUsuarios', filtros);

      $.post('usuarios_estado.php', {
        id: user_id,
        estado: estado
      }, function(res) {

        let r = JSON.parse(res);

        if (r.status) {

          // 🔥 refresco controlado
          setTimeout(() => {
            buscarUsuarios();
          }, 200);

        } else {
          alert(r.msg);
          checkbox.prop('checked', !checkbox.prop('checked'));
        }

      });

    });
</script>

<script>
  // abrir modal
  function AddUsuario() {

    $('#formUsuario')[0].reset();

    // 🔥 bloquear documento
    $('#documento').prop('disabled', true).val('');

    limpiarCamposUsuario();

    $('#modalUsuario').modal('show');
  }




  // CONSULTA AUTOMÁTICA
  let timeoutDoc = null;

  $(document).on('keyup', '#documento', function() {

    let input = $(this);

    clearTimeout(timeoutDoc);

    timeoutDoc = setTimeout(() => {

      let tipo = $('#tipo_doc').val();
      let doc = input.val();

      if (!tipo) {
        return;
      }

      if (
        (tipo === 'DNI' && doc.length === 8) ||
        (tipo === 'CE' && doc.length >= 9)
      ) {

        // 🔥 validar duplicado primero
        $.post('usuarios_validar_doc.php', {
          doc: doc
        }, function(res) {

          let r = JSON.parse(res);

          if (r.existe) {

            alert("Este documento ya está registrado");

            input.val('');
            limpiarCamposUsuario();

            return;
          }

          // 🔥 si no existe → consulta API
          if (tipo === 'DNI') {
            consultarRENIEC(doc);
          }

          if (tipo === 'CE') {
            consultarCE(doc);
          }

        });

      }

    }, 400); // 🔥 delay inteligente

  });

  function limpiarCamposUsuario() {
    $('[name="nombre"]').val('');
    $('[name="apellido_paterno"]').val('');
    $('[name="apellido_materno"]').val('');
    $('[name="username"]').val('');
  }


  // RENIEC
  function consultarRENIEC(dni) {

    $.post('reniec_consultar.php', {
      dni: dni
    }, function(res) {

      let r = typeof res === 'string' ? JSON.parse(res) : res;

      let data = null;

      // 🔥 soporta ambos formatos
      if (r.consultarResponse && r.consultarResponse.return) {
        data = r.consultarResponse.return;
      } else {
        data = r;
      }

      if (data.coResultado === "0000") {

        let p = data.datosPersona;

        llenarDatos(p.prenombres, p.apPrimer, p.apSegundo);

      } else {
        alert("No encontrado en RENIEC");
      }

    });

  }


  // MIGRACIONES
  function consultarCE(doc) {

    $.post('migraciones_consultar.php', {
      doc: doc
    }, function(res) {

      let r = typeof res === 'string' ? JSON.parse(res) : res;

      let data = r.jsonObject ? r.jsonObject : r;

      if (data.codRespuesta === "0000") {

        let p = data.datosPersonales;

        llenarDatos(p.nombres, p.apepaterno, p.apematerno);

      } else {
        alert("No encontrado en MIGRACIONES");
      }

    });

  }


  // LLENAR DATOS
  function llenarDatos(nombre, ap, am) {

    $('[name="nombre"]').val(nombre);
    $('[name="apellido_paterno"]').val(ap);
    $('[name="apellido_materno"]').val(am);

    generarUsername(nombre, ap, am);
  }


  // USERNAME ÚNICO
  function generarUsername(nombre, ap, am) {

    let inicial = nombre.trim().charAt(0).toLowerCase();
    let base = inicial + ap.toLowerCase();

    $.post('usuarios_generar_username.php', {
      base: base,
      ap_m: am.toLowerCase()
    }, function(res) {

      let r = JSON.parse(res);
      $('[name="username"]').val(r.username);

    });

  }


  // GUARDAR
  $('#formUsuario').submit(function(e) {
    e.preventDefault();

    if (!confirm("¿Guardar usuario?")) return;

    $.post('usuarios_guardar.php', $(this).serialize(), function(res) {

      let r = JSON.parse(res);

      if (r.status) {
        alert("Usuario creado correctamente");
        $('#modalUsuario').modal('hide');
        location.reload(); // 🔥 recarga total
      } else {
        alert(r.msg);
      }

    });

  });

  //campo de tipo de documento

  $(document).on('change', '#tipo_doc', function() {

    let tipo = $(this).val();

    if (tipo) {

      $('#documento')
        .prop('disabled', false)
        .val('')
        .attr('placeholder', 'Ingrese número de ' + tipo)
        .focus();

      limpiarCamposUsuario();

    } else {

      $('#documento')
        .prop('disabled', true)
        .val('')
        .attr('placeholder', 'Seleccione tipo de documento');

      limpiarCamposUsuario();

    }

  });
</script>

<script>
  $(document).off('click', '.btn-reset-clave')
    .on('click', '.btn-reset-clave', function() {

      let id = $(this).data('id');
      let doc = $(this).data('doc');

      if (!confirm(
          "¿Seguro que desea resetear su clave?\n\nLa nueva clave será su número de documento."
        )) {
        return;
      }

      $.post('usuarios_reset_password.php', {
        id: id,
        doc: doc
      }, function(res) {

        let r = JSON.parse(res);

        if (r.status) {

          alert("Clave reseteada correctamente");

        } else {

          alert(r.msg);

        }

      });

    });
</script>