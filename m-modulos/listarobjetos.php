<?php include 'config.php' ?>

<?php include 'listarobjetos_config.php' ?>

<?php include '../00_includes/head.php' ?>

<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">
    <!-- Preloader -->
    <?php //include '../00_includes/preloader.php'; ?>  
    <!-- Navbar -->
    <?php include '../00_includes/menu_superior.php'; ?>  
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <?php include '../00_includes/menu_lateral_izquierdo.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <?php include 'listarobjetos_contenido.php' ?>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <?php include '../00_includes/footer.php'; ?> 

  <?php include 'listarobjetos_modal_add.php'; ?>  
  <?php include 'listarobjetos_modal_acta.php'; ?>  
  <?php include 'listarobjetos_modal_ver_detalle.php'; ?>  
  <?php include 'listarobjetos_modal_edit_photo.php'; ?>  
  <?php include 'listarobjetos_modal_entrega.php'; ?>  

  <?php include '../00_includes/script.php'; ?>
  <?php include 'listarobjetos_script.php'; ?>

  

</body>
</html>