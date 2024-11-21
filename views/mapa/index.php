<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 25px;

    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .switch label {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: red;
        transition: 0.4s;
        border-radius: 50px;
    }

    .switch input:checked+label {
        background-color: green;
    }

    .switch label:before {
        content: "";
        position: absolute;
        height: 18px;
        width: 18px;
        border-radius: 50%;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
    }

    .switch input:checked+label:before {
        transform: translateX(25px);
    }

    @media (max-width: 768px) {
        .switch {
            width: 60px;

            height: 30px;

        }

        .switch label:before {
            height: 20px;
            width: 20px;
        }

        .switch input:checked+label:before {
            transform: translateX(30px);
        }
    }
</style>



<div class="row justify-content-center">
    <div class="col-lg-6 d-flex justify-content-around">

        <div class="col text-center">
            <label for="Todo">Ver Todo</label>
            <div class="switch">
                <input type="checkbox" id="Todo" class="estado">
                <label for="Todo"></label>
            </div>
        </div>


        <div class="col text-center">
            <label for="Radios">Radios</label>
            <div class="switch">
                <input type="checkbox" id="Radios" class="estado">
                <label for="Radios"></label>
            </div>
        </div>


        <div class="col text-center">
            <label for="Antenas">Antenas</label>
            <div class="switch">
                <input type="checkbox" id="Antenas" class="estado">
                <label for="Antenas"></label>
            </div>
        </div>


        <div class="col text-center">
            <label for="Repetidoras">Repetidoras</label>
            <div class="switch">
                <input type="checkbox" id="Repetidoras" class="estado">
                <label for="Repetidoras"></label>
            </div>
        </div>


        <div class="col text-center">
            <label for="Enlaces">Enlaces</label>
            <div class="switch">
                <input type="checkbox" id="Enlaces" class="estado">
                <label for="Enlaces"></label>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mb-4" style="margin: 0; padding: 10px; height: 85vh;">
    <div class="col-lg-12 col-md-10 col-sm-12" style="padding: 0; height: 100%;">
        <div class="card shadow-lg" style="border-radius: 10px; border: 1px solid #007bff; height: 100%; margin: 0;">
            <div class="card-body" style="padding: 10px; height: 100%;">
                <h5 class="card-title text-center" style="margin-bottom: 10px;">Equipos desplegados a nivel nacional</h5>
                <div style="height: calc(100% - 40px); overflow: hidden;">
                    <div id="map" style="height: 100%; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modalEquipos" tabindex="-1" aria-labelledby="modalEquiposTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" style="max-width: 80%; margin-top: 5vh;">
        <div class="modal-content" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">

            <div class="modal-header bg-primary text-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <h5 class="modal-title text-center" id="modalEquiposTitulo">Equipos del Destacamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>


            <div class="modal-body" id="modalEquiposBody" style="padding: 15px 20px; text-align: center; min-height: 200px;">
                <div class="col-lg-12 mt-4">
                    <div class="table-responsive" style="padding: 10px; border: 1px solid #ddd; border-radius: 10px; background-color: #f9f9f9;">
                        <table class="table table-striped table-hover table-bordered w-100" id="EquiposDestacamento" style="text-align: center; font-size: 14px;">

                        </table>
                    </div>
                </div>
            </div>


            <div class="modal-footer" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                <div class="row p-1 justify-content-center w-100">
                    <div class="col-auto">
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="<?= asset('build/js/mapa/index.js') ?>"></script>