<div class="row justify-content-center">
    <h2 class="text-center ">
        <u>
            Marcas Registradas
        </u>
    </h2>
</div>

<div class="row justify-content-center p-2">
    <div class="col-lg-8 mt-4">
        <div class="table-responsive p-2">
            <table class="table table-striped table-hover table-bordered w-100" id="MarcasRegistradas">
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalMarcas" tabindex="-1" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white" id="ModalTitle1">
                <h5 class="modal-title text-center" id="modalTitleId">Registro de Marcas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-header bg-warning text-white d-none" id="ModalTitle2">
                <h5 class="modal-title text-center" id="modalTitle">Modificar Marca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="modal-body" id="FormMarcas">
                <input type="hidden" name="mar_id" id="mar_id">
                <div class="mb-3">
                    <label for="mar_descripcion" class="form-label">Nombre</label>
                    <input type="text" name="mar_descripcion" id="mar_descripcion" placeholder="Ingrese aquí el nombre de la marca" class="form-control">
                </div>
            </form>
            <div class="justify-content-center modal-footer">
                <div class="row p-1 justify-content-center">
                    <div class="col-auto">
                        <button type="submit" form="FormMarcas" id="BtnCrear" class="btn btn-primary w-100">Crear </button>
                    </div>
                    <div class="col-auto">
                        <button id="BtnModificar" class="btn btn-warning w-100">Modificar </button>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div style="position: fixed; bottom: 20px; right: 20px; text-align: center;">
    <button
        data-bs-toggle="modal"
        data-bs-target="#ModalMarcas"
        class="btn btn-lg"
        style="
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background-color: #00a1f7; 
            color: #fff; 
            border: none; 
            border-radius: 50px; 
            padding: 10px 15px; 
            box-shadow: 0 8px 16px rgba(0, 82, 204, 0.3);
            transition: all 0.3s ease; 
            width: 60px; 
            height: 60px; 
        "
        onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 12px 24px rgba(0, 0, 0, 0.2)'; this.querySelector('.text').style.opacity='1'; this.querySelector('i').style.display='none'; this.style.width='172px';"
        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 8px 16px rgba(0, 82, 204, 0.3)'; this.querySelector('.text').style.opacity='0'; this.querySelector('i').style.display='inline'; this.style.width='60px';">


        <i class="bi bi-plus-circle-fill" style="font-size: 30px; margin-left: 130px;"></i>
        <span class="text" style="
            opacity: 0; 
            transition: opacity 0.3s ease; 
            white-space: nowrap; 
        ">
            Agregar Marca
        </span>
    </button>
</div>


<script src="<?= asset('build/js/marcas/index.js')?>"></script>