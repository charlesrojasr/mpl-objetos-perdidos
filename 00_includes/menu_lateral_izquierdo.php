<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <a href="index.php" class="brand-link responsive">
    <center>
      <img src="<?php echo $appLogoImg; ?>" style="width: 180px; height:70px">
    </center>
    <center>
      <span class="brand-text" style="font-size: 14px; color: white;">
        <?php echo $appName; ?>
      </span>
    </center>
  </a>

  <div class="sidebar">

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column"
        data-widget="treeview"
        role="menu"
        data-accordion="false">

        <?php foreach ($permissions as $permission): ?>

          <?php
          $raw = $permission['permission_modulo'];

          // Extraer el icono <i>...</i>
          preg_match('/<i.*?>.*?<\/i>/', $raw, $iconMatch);
          $icon = $iconMatch[0] ?? '';

          // Extraer el texto (sin etiquetas HTML)
          $label = trim(strip_tags($raw));
          ?>

          <li class="nav-item">
            <a class="nav-link"
              href="<?php echo $permission['permission_name']; ?>.php"
              title="<?php echo $label; ?>">

              <!-- ICONO -->
              <?php echo $icon; ?>

              <!-- TEXTO -->
              <span class="nav-label">
                <?php echo $label; ?>
              </span>

            </a>
          </li>

        <?php endforeach; ?>

      </ul>
    </nav>

  </div>

</aside>

<style>
  /* ===============================
   BASE
================================ */
  .nav-sidebar .nav-item {
    list-style: none;
  }

  .nav-sidebar .nav-link {
    display: flex;
    align-items: flex-start;
    gap: 10px;
  }

  /* Texto ocupa el ancho restante */
  .nav-sidebar .nav-label {
    flex: 1;
    white-space: normal;
    word-break: break-word;
    line-height: 1.2;
  }

  /* ===============================
   SIDEBAR CERRADO
================================ */
  .sidebar-collapse .nav-label {
    display: none;
  }

  .sidebar-collapse .nav-sidebar .nav-link i {
    margin: 0 auto;
    font-size: 18px;
  }
</style>