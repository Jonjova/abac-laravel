<div class="modal fade" id="editPermissionModal" tabindex="-1" role="dialog"
                            aria-labelledby="editPermissionModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editPermissionModalLabel">Editar Permiso</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="editPermissionForm">
                                            @csrf
                                            <input type="hidden" id="edit_permission_id" name="id">

                                            <div class="form-group">
                                                <label for="edit_permission_name">Nombre del Permiso</label>
                                                <input type="text" class="form-control" id="edit_permission_name"
                                                    name="name" readonly>
                                            </div>

                                            <div class="form-group">
                                                <label for="edit_permission_description">Descripción</label>
                                                <textarea class="form-control" id="edit_permission_description" name="description" rows="3"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="savePermissionChanges">Guardar
                                            Cambios</button>
                                    </div>
                                </div>
                            </div>
                        </div>