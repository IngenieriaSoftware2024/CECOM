<div class="container">

  <div class="row justify-content-center text-primary">
    <h1 class="text-center mb-2" style="font-weight: bold;">
      CONTROL DE EQUIPOS DE COMUNICACIÓN
    </h1>
  </div>


  <div class="row justify-content-center">
    <div class="col-lg-4 d-flex flex-column align-items-center">
      <img src="./images/BCE.png" alt="Logo BCE" class="img-fluid mb-3">
      <h1 class="text-center text-info" style="font-weight: bold;">CECOM</h1>
    </div>
  </div>


  <div class="row text-center justify-content-center mt-5">
    <div class="col-md-8 col-lg-6">
      <div style="border: 1px solid #dee2e6; border-radius: 12px; padding: 20px; background-color: #f8f9fa; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
        <h5 class="text-muted mt-1" >
          Bienvenido: 
          <span style="font-weight: bold; color: #007bff;">
            <?= $user['gra_desc_ct'] . " " . $user['per_nom1'] . " " . $user['per_nom2'] . " " . $user['per_ape1'] . " " . $user['per_ape2'] ?>
          </span>
        </h5>

        <p class="fw-bold mb-1" style="color: #6c757d; font-size: 1.1rem;">Dependencia:</p>
        <p class="text-muted" style="font-size: 1.1rem; margin-bottom: 0;">
          <span style="font-weight: bold; color: #28a745;"><?= $user['dep_desc_lg'] ?></span>
        </p>
      </div>
    </div>
  </div>
</div>


<script src="build/js/inicio.js"></script>