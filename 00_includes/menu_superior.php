<nav class="main-header navbar navbar-expand navbar-white navbar-light">

  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">

    <!-- BOTÓN CAMBIAR CONTRASEÑA -->
    <button class="btn btn-warning mr-2" data-toggle="modal" data-target="#passwordModal">
      <i class="fas fa-key"></i>
    </button>

    <!-- LOGOUT -->
    <a href="../m-admin/logout.php">
      <button type="button" class="btn btn-danger">Cerrar Sesión</button>
    </a>

  </ul>

</nav>


<!-- ========================= -->
<!-- MODAL FUERA DEL NAV (CLAVE) -->
<!-- ========================= -->

<div class="modal fade" id="passwordModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    
    <div class="modal-content">

      <div class="modal-header bg-warning">
        <h5 class="modal-title">
          <i class="fas fa-key"></i> Cambiar contraseña
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <form id="formPassword">

          <!-- ACTUAL -->
          <div class="form-group">
            <label>Contraseña actual</label>
            <div class="input-group">
              <input type="password" id="current_password" class="form-control" required>
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="fa fa-eye" onclick="togglePassword('current_password', this)"></i>
                </span>
              </div>
            </div>
          </div>

          <!-- NUEVA -->
          <div class="form-group">
            <label>Nueva contraseña</label>
            <div class="input-group">
              <input type="password" id="new_password" class="form-control" required>
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="fa fa-eye" onclick="togglePassword('new_password', this)"></i>
                </span>
              </div>
            </div>
          </div>

          <!-- CONFIRMAR -->
          <div class="form-group">
            <label>Confirmar nueva contraseña</label>
            <div class="input-group">
              <input type="password" id="confirm_password" class="form-control" required>
              <div class="input-group-append">
                <span class="input-group-text">
                  <i class="fa fa-eye" onclick="togglePassword('confirm_password', this)"></i>
                </span>
              </div>
            </div>
          </div>

        </form>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Cancelar
        </button>
        <button type="button" class="btn btn-warning" onclick="submitPassword()">
          Actualizar
        </button>
      </div>

    </div>

  </div>
</div>


<!-- ========================= -->
<!-- JS -->
<!-- ========================= -->

<script>

// 👁️ MOSTRAR / OCULTAR
function togglePassword(id, icon){
  let input = document.getElementById(id);

  if(input.type === "password"){
    input.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
}


// 🚀 ENVÍO
function submitPassword(){

  let current = document.getElementById("current_password").value;
  let nueva = document.getElementById("new_password").value;
  let confirm = document.getElementById("confirm_password").value;

  fetch("../m-admin/update_password.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: `current_password=${encodeURIComponent(current)}&new_password=${encodeURIComponent(nueva)}&confirm_password=${encodeURIComponent(confirm)}`
  })
  .then(res => res.text())
  .then(data => {

    alert(data);

    if(data.includes("correctamente")){
      window.location.href = "../m-admin/logout.php";
    }

  });

}

</script>