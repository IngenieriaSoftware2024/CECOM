<style>
    body {
        background-color: #01111448;
    }

    #EquiposRegistrados {
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border: 1px solid #ddd;
    }

    #EquiposRegistrados tbody tr:hover {
        background-color: #f2f2f2;
        cursor: pointer;
    }

    .form-select.custom-select {
        border-radius: 10px;
        border: 2px solid #007bff;
        padding: 0.375rem 0.75rem;
        transition: all 0.3s ease;
        background-color: #fafafa;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-select.custom-select:focus {
        border-color: #0056b3;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }

    .custom-card {
        border-radius: 15px;
        margin-bottom: 25px;
        background-color: #ffffff;
        padding: 5px;
        position: relative;
        transition: all 0.3s ease;
    }

    .custom-card.shadow-xl {
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .custom-card:hover {
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        transform: translateY(-5px);
    }

    .card-header {
        background-color: #007bff;
        color: white;
        font-size: 1.5rem;
        font-weight: 600;
        border-radius: 10px 10px 0 0;
        padding: 5px;
    }

    .card-body h3 {
        font-size: 1.75rem;
        color: #007bff;
        font-weight: 700;
    }

    #EquiposRegistrados th {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    #EquiposRegistrados td {
        text-align: center;
    }

    #EquiposRegistrados td:hover {
        background-color: #e9f5ff;
    }

    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
    }

    .form-select.custom-select:hover {
        border-color: #0056b3;
    }
</style>



<div class="row justify-content-center mt-2">
    <div class="col-lg-8">
        <div class="card custom-card shadow-lg border-0 rounded-4">
            <div class="card-body">
                <h3 class="text-center mb-2">Aministracion de Equipos de:</h3>
                <h3 class="text-center mb-4"> <b><?php echo $dependencia['dependencia']; ?></b></h3>
                <div class="table-responsive p-2">
                    <table class="table table-striped table-hover table-bordered w-100" id="EquiposRegistrados">
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 ">
        <div class="card custom-card shadow-lg border-0 rounded-4">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <label for="asi_dependencia" class="form-label">Seleccione el Destino</label>
                <select name="asi_dependencia" id="asi_dependencia" class="form-select custom-select w-100 mb-3">
                    <option value="">SELECCIONE...</option>
                    <?php foreach ($destacamentos as $opciones) : ?>
                        <option value="<?= $opciones['ubi_id'] ?>"><?= $opciones['ubi_nombre'] ?></option>
                    <?php endforeach ?>
                    <option value="mantenimiento" style="font-weight: bold; color: red; background-color: yellow;">
                        ENVIAR A MANTENIMIENTO A BRIGADA DE COMUNICACIONES
                    </option>
                </select>
            </div>
        </div>

        <div class="card custom-card shadow-lg border-0 rounded-4 mt-1 p-2">
            <div class="row justify-content-center mb-3">
                <label for="catalogo_oficial" class="form-label text-center fw-bold">
                    Catálogo del responsable a quien se le cargará el equipo
                </label>
                <div class="col-lg-5 position-relative">
                        <input type=" text" class="form-control border border-danger" name="catalogo_oficial" id="catalogo_oficial"  placeholder="Ingrese el catálogo ">

                    <span id="icon-check" class="position-absolute top-50 end-0 translate-middle-y me-3" style="display: none;">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </span>
                    <span id="icon-error" class="position-absolute top-50 end-0 translate-middle-y me-3" style="display: none;">
                        <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                    </span>
                </div>
            </div>
            <div class="row justify-content-center" id="Informacion_Oficial">
                <div class="col-md-4 d-flex justify-content-center align-items-center mb-3">
                    <div id="FotoOficial" class="rounded-circle shadow" style="width: 100px; height: 100px; display: flex; justify-content: center; align-items: center; background-color: #f0f0f0;">
                        <i class="bi bi-person-fill text-muted" style="font-size: 40px;"></i>
                    </div>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control mb-3" id="Grado_Oficial" placeholder="Grado del responsable" disabled>
                    <input type="text" class="form-control mb-3" id="Nombre_Oficial" placeholder="Nombre del responsable" disabled>
                    <input type="hidden" class="form-control" id="Plaza_Oficial"  >
                    <textarea id="MotivoCambio" class="form-control mb-3 border-danger" placeholder="Indique el motivo del cambio" rows="4"></textarea>
                </div>
            </div>
        </div>

        <div class="card custom-card shadow-lg border-0 rounded-4 mt-3">
            <div class="card-body d-flex justify-content-center">
                <div class="row w-100">
                    <div class="col-12 mb-2">
                        <button type="submit" id="BtnGuardar" class="btn btn-warning w-100">ASIGNAR</button>
                    </div>
                    <div class="col-12">
                        <button type="button" id="BtnLimpiar" class="btn btn-success w-100 shadow border-0">LIMPIAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="<?= asset('build/js/asignaciones/administracion.js')?>"></script>