<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formUsuario">

                <div class="modal-header bg-dark">
                    <h5 class="modal-title">Nuevo Usuario</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Tipo Documento</label>
                        <select id="tipo_doc" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="DNI">DNI</option>
                            <option value="CE">Carnet de Extranjería</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nro Documento</label>
                        <input type="text" name="documento" id="documento" class="form-control" disabled placeholder="Seleccione tipo de documento">
                    </div>

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Apellido Materno</label>
                        <input type="text" name="apellido_materno" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" name="username" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Rol</label>
                        <select name="role_id" class="form-control" required>
                            <option value="">Seleccionar</option>
                            <?php
                            $roles = $conn->query("SELECT * FROM objetosperdidos_roles");
                            while ($r = $roles->fetch_assoc()) {
                                echo "<option value='{$r['id']}'>{$r['role_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>

            </form>

        </div>
    </div>
</div>