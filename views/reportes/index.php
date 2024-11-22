<div class="row justify-content-center mb-3">
    <div class="col-lg-10 col-md-11 col-sm-12">
        <div class="card custom-card shadow-lg" style="border-radius: 15px; border: 1px solid #007bff;">
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
                            <label for="asi_estatus" class="form-label">ESTATUS DEL EQUIPO</label>
                            <select name="asi_estatus" id="asi_estatus" class="form-select">
                                <option value="">SELECCIONE...</option>
                                <option value="3">OPERATIVOS</option>
                                <option value="5">ALMACEN DE LA BRIGADA DE COMUNICACIONES</option>
                                <option value="6">EN MANTENIMIENTO/REPARACIÓN</option>
                                <option value="7">BAJA</option>
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


                        <div class="col-md-4">
                            <label for="asi_dependencia" class="form-label">POR DEPENDENCIA</label>
                            <select name="asi_dependencia" id="asi_dependencia" class="form-select">
                                <option value="">SELECCIONE...</option>
                                <?php foreach ($dependencias as $dependencia) : ?>
                                    <option value="<?= $dependencia['dep_llave'] ?>"><?= $dependencia['dep_desc_md'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>


                        <div class="col-md-4">
                            <label for="ubi_id" class="form-label">POR DESTACAMENTOS</label>
                            <select name="ubi_id" id="ubi_id" class="form-select">
                                <option value="">SELECCIONE...</option>
                                <?php foreach ($destacamentos as $destamento) : ?>
                                    <option value="<?= $destamento['ubi_id'] ?>"><?= $destamento['ubi_nombre'] ?></option>
                                <?php endforeach ?>
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
        <div class="card custom-card shadow-lg " style="border-radius: 10px; border: 1px solid #007bff;">
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


<script src="<?= asset('build/js/reportes/reportes.js') ?>"></script>