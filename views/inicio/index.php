<div class="row justify-content-center">
  <h1 class="text-center">CONTROL DE EQUIPOS DE COMUNICACIÓN</h1>
  <div class="col-lg-4 d-flex flex-column align-items-center">
    <img src="./images/BCE.png" width="80%" alt="" class="img-fluid">
    <h1 class="text-center text-info"><b>CECOM</b></h1>
  </div>
</div>

<div class="row text-center justify-content-center">
  <div class="col">
    <h5 class="text-muted mt-3">Bienvenido: <?= $user['gra_desc_ct'] . " " . $user['per_nom1'] . " " . $user['per_nom2'] . " " . $user['per_ape1'] . " " . $user['per_ape2'] ?></h5>

    <p class="text-muted fw-bold">Dependencia:</p>
    <p class="text-muted"><?= $user['dep_desc_lg'] ?></p>
  </div>
</div>




<script src="build/js/inicio.js"></script>