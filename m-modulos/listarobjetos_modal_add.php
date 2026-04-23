<!-- ================== MODAL ADD ================== -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form method="POST" action="listarobjetos_modal_add_f.php" enctype="multipart/form-data" id="formAdd">
                <input type="hidden" name="registro_id" id="registro_id">
                <!-- HEADER -->
                <div class="modal-header bg-dark">
                    <h4 class="modal-title">
                        <i class="fas fa-box-open"></i> Nuevo Registro de Objeto Perdido
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="row">

                        <!-- ================= PERSONA ================= -->
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-user"></i> Datos de la Persona</h3>
                                </div>

                                <div class="card-body">

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="anonimo" value="1" class="custom-control-input" id="checkAnonimo">
                                            <label class="custom-control-label" for="checkAnonimo">
                                                Registro Anónimo
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Tipo de Documento</label>
                                        <select id="tipo_documento" class="form-control">
                                            <option value="DNI">DNI</option>
                                            <option value="CE">CARNÉ DE EXTRANJERÍA</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Documento de Identidad</label>
                                        <input type="text" id="dni_input" name="nro_documento" class="form-control only-numbers datos-persona" maxlength="12">
                                    </div>

                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" name="nombre" class="form-control text-uppercase datos-persona" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Apellido Paterno</label>
                                        <input type="text" name="apellido_paterno" class="form-control text-uppercase datos-persona" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Apellido Materno</label>
                                        <input type="text" name="apellido_materno" class="form-control text-uppercase datos-persona" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Tipo de Persona</label>
                                        <select name="tipo_persona" class="form-control text-uppercase datos-persona" required>
                                            <option value="">Seleccionar</option>
                                            <option value="CIUDADANO">CIUDADANO</option>
                                            <option value="SERENO">SERENO</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- ================= OBJETO ================= -->
                        <div class="col-md-6">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-box"></i> Datos del Objeto</h3>
                                </div>

                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Descripción del Objeto</label>
                                        <textarea name="descripcion_objeto" class="form-control text-uppercase" rows="3" required></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Categoría</label>
                                        <select name="categoria_id" class="form-control" required>
                                            <option value="">Seleccionar</option>
                                            <?php
                                            $cat = $conn->query("SELECT * FROM objetosperdidos_categorias");
                                            while ($c = $cat->fetch_assoc()) {
                                                echo "<option value='" . $c['id'] . "'>" . $c['nombre_categoria'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Lugar de referencia</label>
                                        <input type="text" name="lugar_referencia" class="form-control text-uppercase" required>
                                    </div>

                                    <!-- INPUT FILE -->
                                    <div class="form-group">
                                        <label>Fotos del objeto</label>

                                        <div class="custom-file">
                                            <input type="file" name="fotos[]" id="inputFotos" class="custom-file-input" multiple accept="image/*">
                                            <label class="custom-file-label">Seleccionar archivos</label>
                                        </div>

                                        <small class="text-muted">Puedes subir varias imágenes</small>

                                        <!-- PREVIEW -->
                                        <div class="mt-2">
                                            <label><b>Fotos actuales</b></label>
                                            <div id="preview_existentes" class="row"></div>
                                        </div>

                                        <div class="mt-2">
                                            <label><b>Nuevas fotos</b></label>
                                            <div id="preview_nuevas" class="row"></div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Registro
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
<style>
    input[type="text"],
    textarea {
        text-transform: uppercase;
    }
</style>
<!-- ================== SCRIPT ================== -->
<script>
    $(document).ready(function() {
        // SOLO NÚMEROS
        $(document).on('input', '.only-numbers', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

    });
</script>