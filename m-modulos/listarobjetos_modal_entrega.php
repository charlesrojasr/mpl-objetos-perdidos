<div class="modal fade" id="modal_entrega">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow">

            <form method="POST" action="listarobjetos_entrega_f.php" enctype="multipart/form-data">

                <input type="hidden" name="registro_id" id="entrega_registro_id">

                <!-- HEADER -->
                <div class="modal-header bg-gradient-success">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-truck mr-2"></i> Registro de Entrega
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo de Documento</label>
                                    <select id="tipo_documento" class="form-control">
                                        <option value="DNI">DNI</option>
                                        <option value="CE">CARNÉ DE EXTRANJERÍA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <!-- INFO -->
                                <div class="alert alert-light border">
                                    <i class="fas fa-info-circle text-success"></i>
                                    Complete los datos de la persona que recibe el objeto.
                                </div>
                            </div>
                        </div>


                        <div class="row">

                            <!-- DNI -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">NRO DE DOCUMENTO</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-success">
                                                <i class="fas fa-id-card text-white"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="dni_input" name="nro_documento" class="form-control only-numbers">
                                    </div>
                                </div>
                            </div>

                            <!-- NOMBRE -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Nombre</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-success">
                                                <i class="fas fa-user text-white"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="nombre"
                                            class="form-control text-uppercase"
                                            placeholder="Nombre" required>
                                    </div>
                                </div>
                            </div>

                            <!-- APELLIDO PATERNO -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Apellido Paterno</label>
                                    <input type="text" name="apellido_paterno"
                                        class="form-control text-uppercase"
                                        placeholder="Apellido paterno" required>
                                </div>
                            </div>

                            <!-- APELLIDO MATERNO -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Apellido Materno</label>
                                    <input type="text" name="apellido_materno"
                                        class="form-control text-uppercase"
                                        placeholder="Apellido materno" required>
                                </div>
                            </div>

                            <!-- TELÉFONO -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Teléfono</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-success">
                                                <i class="fas fa-phone text-white"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="numero_contacto"
                                            class="form-control only-numbers"
                                            placeholder="999999999" required>
                                    </div>
                                </div>
                            </div>

                            <!-- CORREO -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Correo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-success">
                                                <i class="fas fa-envelope text-white"></i>
                                            </span>
                                        </div>
                                        <input type="email" name="correo"
                                            class="form-control"
                                            placeholder="correo@email.com">
                                    </div>
                                </div>
                            </div>

                            <!-- DIRECCIÓN -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Dirección</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-success">
                                                <i class="fas fa-map-marker-alt text-white"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="direccion"
                                            class="form-control text-uppercase"
                                            placeholder="Dirección completa" required>
                                    </div>
                                </div>
                            </div>

                            <!-- ARCHIVOS -->
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label class="font-weight-bold">Evidencias</label>

                                    <div class="custom-file">
                                        <input type="file" id="archivos_entrega" name="archivos[]" multiple>
                                        <label class="custom-file-label" for="archivos_entrega">
                                            Seleccionar archivos...
                                        </label>
                                    </div>

                                    <small class="text-muted">
                                        Puedes subir fotos, PDF u otros documentos.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div id="preview_archivos_entrega" class="row mt-2"></div>
                            </div>

                        </div>

                        <hr>


                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer justify-content-between">

                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Registrar Entrega
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>