<style>
    body {
        background-color: #01111448;
    }
</style>

<div class="row justify-content-center mb-3">
    <div class="col-lg-8 col-md-10 col-sm-12">
        <div class="card custom-card shadow-lg " style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body">
                <h5 class="text-center mb-2">Destacamentos y Ubicaciones Ingresadas de: </h5>
                <h5 class="text-center mb-2"><b><?php echo $dependencia['dependencia']; ?></b></h5>
                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100" id="DestacamentosIngresados">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mb-4">
    <div class="col-lg-8 col-md-10 col-sm-12">
        <div class="card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body">
                <h5 class="card-title text-center">Mapa con las ubicaciones ingresadas</h5>
                <div class="p-2" style="height: 500px; overflow: hidden;">
                    <div id="map" style="height: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12 col-sm-12">
        <div class="card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff;">
            <div class="card-body">
                <h5 class="text-center text-primary mb-3"  id="TituloCrear" style="font-weight: bold;">Agregar Destacamento / Ubicación</h5>

                <div class="row mb-3 justify-content-center" id="TituloBuscarNombre" >
                    <div class="col-lg-12">
                        <div class="row mb-2">
                            <label for="BuscarLocalizacion" class="form-label" style="font-weight: bold;">Buscar Lugar por Nombre:</label>
                        </div>

                        <div class="row mb-2">
                            <input type="text" id="BuscarLocalizacion" class="form-control form-control-sm" placeholder="Ej. Salamá, Baja Verapaz">
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="d-flex justify-content-center">
                                <button type="button" id="BtnBuscarNombre" class="btn btn-success btn-sm w-50">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>


                <form id="FormDestacamentos">
                    <div class="row mb-4 justify-content-center">

                        <input type="text" id="ubi_id" name="ubi_id" class="form-control form-control-sm d-none">


                        <div class="col-lg-12 mb-3">
                            <label for="ubi_nombre" class="form-label" style="font-weight: bold;">Nombre del Destacamento:</label>
                            <input type="text" id="ubi_nombre" name="ubi_nombre" class="form-control form-control-sm" placeholder="Ej. Destacamento Militar Pipiles" required>
                        </div>


                        <div class="col-lg-12 mb-3">
                            <label for="ubi_latitud" class="form-label" style="font-weight: bold;">Latitud:</label>
                            <input type="text" id="ubi_latitud" name="ubi_latitud" class="form-control form-control-sm" placeholder="Ej. 14.6349" required>
                        </div>


                        <div class="col-lg-12 mb-3">
                            <label for="ubi_longitud" class="form-label" style="font-weight: bold;">Longitud:</label>
                            <input type="text" id="ubi_longitud" name="ubi_longitud" class="form-control form-control-sm" placeholder="Ej. -90.5069" required>
                        </div>


                        <div class="col-lg-12 mb-3">
                            <div class="d-flex justify-content-center">
                                <button type="button" id="BtnBuscarCoordenadas" class="btn btn-info btn-sm w-50">Buscar Coordenadas</button>
                            </div>
                        </div>

                        <div class="row p-1 justify-content-center">
                            <div class="col-auto">
                                <button type="submit" id="BtnGuardar" class="btn btn-primary text-uppercase btn-lg w-100">Agregar Destacamento</button>
                            </div>

                            <div class="col-auto">
                                <button type="button" id="BtnModificar" class="btn btn-warning text-uppercase shadow border-0">Modificar</button>
                            </div>
                            <div class="col-auto">
                                <button type="button" id="BtnCancelar" class="btn btn-secondary text-uppercase shadow border-0">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/destacamentos/index.js')?>"></script>