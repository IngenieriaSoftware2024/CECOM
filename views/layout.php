<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="build/js/app.js"></script>
    <link rel="shortcut icon" href="<?= asset('images/BCE.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>CECOM</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container-fluid">
            <!-- Logo y Nombre -->
            <a class="navbar-brand d-flex align-items-center" href="/CECOM/">
                <img src="<?= asset('./images/BCE.png') ?>" width="40" alt="cit" class="me-2">
                <span class="fw-bold">CECOM</span>
            </a>

            <!-- Botón de colapso para móviles -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Enlaces de navegación -->
            <div class="collapse navbar-collapse" id="navbarToggler">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/CECOM/">
                            <i class="bi bi-house-fill me-2"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-2"></i>Mantenimientos
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/CECOM/marcas"><i class="bi bi-award me-2"></i>Marcas</a></li>
                            <li><a class="dropdown-item" href="/CECOM/accesorios"><i class="bi bi-box-seam me-2"></i>Accesorios</a></li>
                            <li><a class="dropdown-item" href="/CECOM/equipo"><i class="bi bi-clipboard-plus-fill me-2"></i>Registro de Equipo</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/CECOM/asignaciones">
                            <i class="bi bi-clipboard-plus me-2"></i>Asignacion de Equipo
                        </a>
                    </li>
                </ul>

                <!-- Botón Menú -->
                <div class="d-flex align-items-center">
                    <a href="/menu/" class="btn btn-danger d-flex align-items-center">
                        <i class="bi bi-arrow-bar-left me-1"></i> MENÚ
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="container-fluid pt-5 mb-4" style="min-height: 85vh">

        <?php echo $contenido; ?>
    </div>
    <div class="container-fluid ">
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size:xx-small; font-weight: bold;">
                    Brigada de Comunicaciones del Ejercito de Guatemala, <?= date('Y') ?> &copy;
                </p>
            </div>
        </div>
    </div>
</body>

</html>