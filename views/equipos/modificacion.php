<div class="row justify-content-center mb-3">
    <div class="col-lg-8 col-md-10 col-sm-12">
        <div class="card custom-card shadow-lg " style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body">
                <form class=" bg-ligth rounded p-4" id="formularioUsuario">
                    <h3 class="text-center mb-3 text-danger"><b>Modificacion de Equipos</b></h3>
                    <input type="hidden" name="eqp_id" id="eqp_id">
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <label for="eqp_clase" class="form-label">CLASE DE EQUIPO</label>
                            <select name="eqp_clase" id="eqp_clase" class="form-control" >
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
                        <div class="col-lg-4 d-flex flex-column align-items-start">
                            <label for="eqp_serie" class="me-2 mb-2">NUMERO DE SERIE</label>
                            <div class="d-flex align-items-center">
                                <input type="text" name="eqp_serie" id="eqp_serie" class="form-control" >
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label for="eqp_gama" class="form-label">GAMA DEL EQUIPO</label>
                            <select name="eqp_gama" id="eqp_gama" class="form-control">
                                <option value="">SELECCIONE...</option>
                                <option value="7">HF</option>
                                <option value="8">VHF</option>
                                <option value="9">UHF</option>
                                <option value="10">SATELITAL</option>
                                <option value="11">NINGUNA</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-lg-4">
                            <label for="eqp_marca" class="form-label">MARCA DEL EQUIPO</label>
                            <select name="eqp_marca" id="eqp_marca" class="form-control">
                                <option value="">SELECCIONE...</option>
                                <?php foreach ($marcas as $marca) : ?>
                                    <option value="<?= $marca['mar_id'] ?>"> <?= $marca['mar_descripcion'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="eqp_estado" class="form-label">ESTADO DEL EQUIPO</label>
                            <select name="eqp_estado" id="eqp_estado" class="form-control">
                                <option value="">SELECCIONE...</option>
                                <option value="1">BUEN ESTADO</option>
                                <option value="2">REGULAR ESTADO</option>
                                <option value="3">MAL ESTADO</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12 mt-3" id="accesorios-container" style="display: none;">
                            <label class="mb-2" for="accesorios" class="form-label">Accesorios del Equipo</label>
                            <p class="mb-2"> <b>Si algun equipo ya no tiene asignado el accesorio solo desmarquelo</b> </p>
                            <p class="mb-2">(Recuerde que al desmarcar se eliminará el accesorio asignado)</p>
                            <div id="accesorios" class="row"></div>
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center text-center ">
                        <div class="col-auto">
                            <button type="button" id="BtnModificar" class="btn btn-warning w-100 shadow border-0">Modificar</button>
                        </div>
                        <div class="col-auto">
                            <button type="button" id="BtnLimpiar" form="formularioUsuario" class="btn btn-success w-100 shadow border-0">LIMPIAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mb-3">
    <div class="col-lg-10 col-md-10 col-sm-12">
        <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body">
                <h5 class="text-center text-primary">SELECCIONE EL EQUIPO QUE DESEA MODIFICAR</h5>
                <div class="table-responsive p-2" style="max-height: 700px; overflow-y: auto;">
                    <table class="table table-striped table-hover table-bordered w-100" id="TablaEquipos">
                      
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?= asset('build/js/equipos/modificacion.js') ?>"></script>