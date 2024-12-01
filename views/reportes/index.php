<div class="row justify-content-center mb-3">
    <div class="col-lg-10 col-md-11 col-sm-12">
        <div class="card custom-card shadow" style="border-radius: 15px; border: 1px solid #007bff;">
            <div class="card-body">
                <form class="rounded p-4" id="FormularioBusqueda">
                    <h3 class="text-center mb-2 text-primary"><b>BUSQUEDA DE EQUIPOS</b></h3>
                    <h6 class="text-center mb-4">Seleccione las condiciones que desee </h6>
                    <div class="row g-3 mb-3">

                        <div class="col-md-4">
                            <label for="eqp_clase" class="form-label">CLASE DE EQUIPO</label>
                            <select name="eqp_clase" id="eqp_clase" class="form-select">
                                <option value="">SELECCIONE...</option>
                                <option value="13">ROCKET (ENLACES)</option>
                                <option value="12">RADIO SATELITAL</option>
                                <option value="6">RADIO AÉREO(TIERRA/AIRE)</option>
                                <option value="5">RADIO MOVIL</option>
                                <option value="4">RADIO PORTATIL</option>
                                <option value="3">RADIO BASE(ESTACION FIJA)</option>
                                <option value="2">REPETIDORA</option>
                                <option value="1">ANTENA</option>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label for="eqp_serie" class="form-label">NUMERO DE SERIE</label>
                            <input type="text" name="eqp_serie" id="eqp_serie" class="form-control">
                        </div>


                        <div class="col-md-4">
                            <label for="eqp_gama" class="form-label">GAMA DEL EQUIPO</label>
                            <select name="eqp_gama" id="eqp_gama" class="form-select">
                                <option value="">SELECCIONE...</option>
                                <option value="7">HF</option>
                                <option value="8">VHF</option>
                                <option value="9">UHF</option>
                                <option value="10">SATELITAL</option>
                                <option value="11">NINGUNA</option>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label for="eqp_marca" class="form-label">MARCA DEL EQUIPO</label>
                            <select name="eqp_marca" id="eqp_marca" class="form-select">
                                <option value="">SELECCIONE...</option>
                                <?php foreach ($marcas as $marca) : ?>
                                    <option value="<?= $marca['mar_id'] ?>"> <?= $marca['mar_descripcion'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label for="eqp_estado" class="form-label">ESTADO DEL EQUIPO</label>
                            <select name="eqp_estado" id="eqp_estado" class="form-select">
                                <option value="">SELECCIONE...</option>
                                <option value="1">BUEN ESTADO</option>
                                <option value="2">REGULAR ESTADO</option>
                                <option value="3">MAL ESTADO</option>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label for="asi_status" class="form-label">ESTATUS DEL EQUIPO</label>
                            <select name="asi_status" id="asi_status" class="form-select">
                                <option value="">SELECCIONE...</option>
                                <option value="3">OPERATIVOS</option>

                                <?php if ($_SESSION['CECOM_ADMINISTR']) : ?>
                                    <option value="5">ALMACEN DE LA BRIGADA DE COMUNICACIONES</option>
                                <?php endif; ?>

                                <option value="6">EN MANTENIMIENTO/REPARACIÓN</option>

                                <?php if ($_SESSION['CECOM_ADMINISTR']) : ?>
                                    <option value="7">BAJA</option>
                                <?php endif; ?>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label for="rep_status" class="form-label">ENVIADOS A REPARACIÓN</label>
                            <select name="rep_status" id="rep_status" class="form-select">
                                <option value="">SELECCIONE...</option>
                                <option value="9">EN REPARACIÓN</option>
                                <option value="10">REPARADOS</option>
                            </select>
                        </div>

                        <?php if ($_SESSION['CECOM_ADMINISTR']) : ?>

                            <div class="col-md-4">
                                <label for="asi_dependencia" class="form-label">POR DEPENDENCIA</label>
                                <select name="asi_dependencia" id="asi_dependencia" class="form-select">
                                    <option value="">SELECCIONE...</option>
                                    <?php foreach ($dependencias as $dependencia) : ?>
                                        <option value="<?= $dependencia['dep_llave'] ?>"><?= $dependencia['dep_desc_md'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                        <?php endif; ?>

                        <div class="col-md-4">
                            <label for="ubi_id" class="form-label">POR DESTACAMENTOS</label>
                            <select name="ubi_id" id="ubi_id" class="form-select">
                                <option value="">SELECCIONE...</option>
                                <?php if ($_SESSION['CECOM_USUARIO']) : ?>
                                    <?php foreach ($destacamentos as $destamento) : ?>
                                        <option value="<?= $destamento['ubi_id'] ?>"><?= $destamento['ubi_nombre'] ?></option>
                                    <?php endforeach ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row justify-content-center mb-3">
                        <div class="col-auto">
                            <p class="text-center mb-4">Presione Buscar para ver todos los equipos</p>
                            <button type="submit" id="BtnBuscar" class="btn btn-primary w-100 shadow border-0">BUSCAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mb-3">
    <div class="col-lg-10 col-md-10 col-sm-12">
        <div class="card custom-card shadow " style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body">
                <h5 class="text-center mb-2 text-primary">EQUIPOS ENCONTRADOS </h5>
                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100" id="EquiposEcontrados">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalEquipo" tabindex="-1" aria-labelledby="modalEquipoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEquipoLabel">Información del Equipo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Pestañas -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="equipo-tab" data-bs-toggle="tab" href="#equipo" role="tab" aria-controls="equipo" aria-selected="true">Equipo</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="accesorios-tab" data-bs-toggle="tab" href="#accesorios" role="tab" aria-controls="accesorios" aria-selected="false">Accesorios</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="movimientos-tab" data-bs-toggle="tab" href="#movimientos" role="tab" aria-controls="movimientos" aria-selected="false">Historial de Movimientos</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="reparaciones-tab" data-bs-toggle="tab" href="#reparaciones" role="tab" aria-controls="reparaciones" aria-selected="false">Historial de Reparaciones</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- Pestaña de información del equipo -->
                    <div class="tab-pane fade show active" id="equipo" role="tabpanel" aria-labelledby="equipo-tab">
                        <div class="row justify-content-center mt-3">
                            <div class="col-lg-10 col-md-8 col-sm-12">
                                <div class="card custom-card shadow border-0 rounded">
                                    <div class="card-body">
                                        <h3 class="text-center mb-2">Información General</h3>
                                        <div class="form-container p-4">
                                            <form id="InformacionEquipo">
                                                <div class="row mb-3">

                                                    <div class="col-md-4 d-flex flex-column align-items-center">

                                                        <label class="form-label mb-3 text-center" for="info_oficial" style="font-weight: bold; font-size: 1.2rem;">RESPONSABLE DEL EQUIPO</label>

                                                        <div id="FotoOficial" class="rounded-circle shadow mb-3" style="width: 120px; height: 120px; background-color: #f0f0f0; display: flex; justify-content: center; align-items: center;">
                                                            <i class="bi bi-person-fill text-muted" style="font-size: 50px;"></i>
                                                        </div>

                                                        <div class="w-100">
                                                            <input type="text" class="form-control mb-3" id="Grado_Oficial" placeholder="Grado " readonly>
                                                            <input type="text" class="form-control mb-3" id="Nombre_Oficial" placeholder="Nombre " readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-8">

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="eqp_clase1" class="form-label">Clase de Equipo</label>
                                                                <input type="text" name="eqp_clase1" id="eqp_clase1" readonly class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="eqp_serie1" class="form-label">No. Serie</label>
                                                                <input type="text" name="eqp_serie1" id="eqp_serie1" readonly class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="eqp_gama1" class="form-label">Gama del Equipo</label>
                                                                <input type="text" name="eqp_gama1" id="eqp_gama1" readonly class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="eqp_marca1" class="form-label">Marca del Equipo</label>
                                                                <input type="text" name="eqp_marca1" id="eqp_marca1" readonly class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="eqp_estado1" class="form-label">Estado del Equipo</label>
                                                                <input type="text" name="eqp_estado1" id="eqp_estado1" readonly class="form-control">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="eqp_status" class="form-label">Estatus del Equipo</label>
                                                                <input type="text" name="eqp_status" id="eqp_status" readonly class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="eqp_ubicacion1" class="form-label">Ubicado en</label>
                                                                <input type="text" name="eqp_ubicacion1" id="eqp_ubicacion1" readonly class="form-control">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pestaña de accesorios -->
                    <div class="tab-pane fade" id="accesorios" role="tabpanel" aria-labelledby="accesorios-tab">
                        <div class="row justify-content-center mt-3">
                            <div class="col-lg-12">
                                <div class="card custom-card shadow border-0 rounded">
                                    <div class="card-body">
                                        <div class="table-responsive p-2">
                                            <table class="table table-striped table-hover table-bordered w-100 table-sm" id="AccesoriosEquipo">
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pestaña de historial de movimientos -->
                    <div class="tab-pane fade" id="movimientos" role="tabpanel" aria-labelledby="movimientos-tab">
                        <div class="row justify-content-center mt-3">
                            <div class="col-lg-12">
                                <div class="card custom-card shadow border-0 rounded">
                                    <div class="card-body">
                                        <div class="table-responsive p-2">
                                            <table class="table table-striped table-hover table-bordered w-100 table-sm" id="MovimientosEquipo">
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pestaña de historial de reparaciones -->
                    <div class="tab-pane fade" id="reparaciones" role="tabpanel" aria-labelledby="reparaciones-tab">
                        <div class="row justify-content-center mt-3">
                            <div class="col-lg-12">
                                <div class="card custom-card shadow border-0 rounded">
                                    <div class="card-body">
                                        <div class="table-responsive p-2">
                                            <table class="table table-striped table-hover table-bordered w-100 table-sm" id="ReparacionesEquipo">
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>



<script src="<?= asset('build/js/reportes/reportes.js') ?>"></script>