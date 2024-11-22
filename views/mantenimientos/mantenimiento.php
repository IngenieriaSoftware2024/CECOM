<div class="row justify-content-center mb-3">
    <div class="col-lg-8 col-md-10 col-sm-12">
        <div class="card custom-card shadow-lg " style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body">
                <h5 class="text-center mb-2 text-primary">Equipos Enviados para Mantenimiento </h5>
                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100" id="EquiposEnviados">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mb-3">
    <div class="col-lg-8 col-md-10 col-sm-12">
        <div class="card custom-card shadow-lg " style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body">
                <h5 class="text-center mb-2 text-primary">Equipos en Mantenimiento </h5>
                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100" id="EquiposEntregar">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="ModalMantenimiento" tabindex="-1" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white" id="ModalTitle1">
                <h5 class="modal-title text-center" id="modalTitleId">CONTROL DE EQUIPOS ENVIADOS A MANTENIMIENTO Y REPARACION</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-body" id="FormMantenimiento">
                <input type="hidden" name="rep_id" id="rep_id">
                <input type="hidden" name="asi_id" id="asi_id">
                <input type="hidden" name="rep_equipo" id="rep_equipo">
                <input type="hidden" name="rep_fecha_entrada" id="rep_fecha_entrada">

                <div class="row" style="display: flex; gap: 10px;">
                    <div class="col-md-6 mb-3" style="flex: 1;">
                        <label for="rep_entrego" class="form-label">Catálogo de quien entrega el equipo:</label>
                        <div class="d-flex align-items-center">
                            <input type="number" name="rep_entrego" id="rep_entrego" placeholder="Catálogo de quien entrega al almacen" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3" style="flex: 1;">
                        <label for="rep_recibido" class="form-label">Catálogo de quien se lleva el equipo:</label>
                        <div class="d-flex align-items-center">
                            <input type="number" name="rep_recibido" id="rep_recibido" placeholder="Catálogo a quien se le entrega del almacen" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row" style="display: flex; gap: 10px;">
                    <div class="col-md-10 mb-3" style="flex: 1;">
                        <label for="rep_desc" class="form-label">Motivo del Mantenimiento o Reparación:</label>
                        <textarea name="rep_desc" id="rep_desc" class="form-control" placeholder="Ingrese el motivo de la reparación acá" rows="4"></textarea>
                    </div>
                </div>

                <div class="row" style="display: flex; gap: 10px;">
                    <div class="col-md-6 mb-3" style="flex: 1;">
                        <label for="rep_estado_ant" class="form-label">Estado del equipo al ingresar</label>
                        <select name="rep_estado_ant" id="rep_estado_ant" class="form-control">
                            <option value="">SELECCIONE...</option>
                            <option value="1">BUEN ESTADO</option>
                            <option value="2">REGULAR ESTADO</option>
                            <option value="3">MAL ESTADO</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" style="flex: 1;">
                        <label for="rep_estado_actual" class="form-label">Estado del equipo al entregar</label>
                        <select name="rep_estado_actual" id="rep_estado_actual" class="form-control">
                            <option value="">SELECCIONE...</option>
                            <option value="1">BUEN ESTADO</option>
                            <option value="2">REGULAR ESTADO</option>
                            <option value="3">MAL ESTADO</option>
                        </select>
                    </div>
                </div>

                <div class="row" style="display: flex; gap: 10px;">
                    <div class="col-md-6 mb-3" style="flex: 1;">
                        <label for="rep_responsable" class="form-label">Catálogo de quien va a realizar el mantenimiento</label>
                        <div class="d-flex align-items-center">
                            <input type="number" name="rep_responsable" id="rep_responsable" placeholder="Ingrese el catálogo acá" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3" style="flex: 1;">
                        <label for="rep_obs" class="form-label">Observaciones:</label>
                        <textarea id="rep_obs" name="rep_obs" class="form-control" placeholder="Ingrese alguna observación (si lo hubiera)" rows="4"></textarea>
                    </div>
                </div>
            </form>

            <div class="justify-content-center modal-footer">
                <div class="row p-1 justify-content-center" style="display: flex; gap: 10px;">
                    <div class="col-auto">
                        <button type="submit" form="FormMantenimiento" id="BtnCrear" class="btn btn-primary w-100">Registrar</button>
                    </div>
                    <div class="col-auto">
                        <button type="button" id="BtnEntregar" class="btn btn-success w-100" >Entregar Equipo</button>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script src="<?= asset('build/js/mantenimiento/index.js') ?>"></script>