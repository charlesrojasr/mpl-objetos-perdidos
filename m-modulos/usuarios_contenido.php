 <div class="content-wrapper">

   <div class="content-header">
     <div class="container-fluid">
       <div class="row mb-2">
         <div class="col-sm-6 mb-2">
           <div style=" display: flex;align-items: center;">

             <h1 class="m-0"><?php echo $appModulo; ?></h1>

           </div>
         </div>
         <div class="col-sm-6">
           <button type="button"
             onclick="AddUsuario()"
             class="btn btn-dark btn-sm">
             <i class="fas fa-plus"></i> AÑADIR NUEVO USUARIO
           </button>
         </div>
       </div>
     </div>
   </div>



   <section class="content">
     <div class="container-fluid">


       <div class="card">
         <!-- /.card-header -->
         <div class="card-body">


           <?php include 'usuarios_table.php'; ?>

         </div>
         <!-- /.card-body -->
       </div>


     </div>
   </section>

 </div>