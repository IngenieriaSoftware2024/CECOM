<?php 
require_once __DIR__ . '/../includes/app.php';

use Controllers\AccesorioController;
use Controllers\AsignacionEquipoController;
use Controllers\EquipoController;
use MVC\Router;
use Controllers\InicioController;
use Controllers\MarcaController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [InicioController::class,'index']);

// MARCAS
$router->get('/marcas', [MarcaController::class, 'index']);
$router->get('/API/marcas/buscar', [MarcaController::class, 'buscarAPI']);
$router->post('/API/marcas/guardar', [MarcaController::class, 'guardarAPI']);
$router->post('/API/marca/modificar', [MarcaController::class, 'modificarAPI']);

// ACCESORIOS
$router->get('/accesorios', [AccesorioController::class, 'index']);
$router->get('/API/accesorios/buscar', [AccesorioController::class, 'buscarAPI']);
$router->post('/API/accesorios/guardar', [AccesorioController::class, 'guardarAPI']);
$router->post('/API/accesorios/modificar', [AccesorioController::class, 'modificarAPI']);

// EQUIPOS
$router->get('/equipo', [EquipoController::class, 'index']);
$router->get('/API/equipo/seleccionado', [EquipoController::class, 'AccesoriosEquipoAPI']);
$router->get('/API/verficar/serie', [EquipoController::class, 'VerificarSerieAPI']);
$router->post('/API/equipo/guardar', [EquipoController::class, 'GuardarAPI']);



// ASIGNACIONEQUIPO
$router->get('/asignaciones', [AsignacionEquipoController::class, 'index']);
$router->get('/API/equiposingresados/buscar', [AsignacionEquipoController::class, 'BuscarEquiposAPI']);
$router->post('/API/fotografia_ofical', [AsignacionEquipoController::class, 'informacionOficialAPI']);
$router->post('/API/asignacion_dependencia/guardar', [AsignacionEquipoController::class, 'AsignarDependenciaAPI']);



// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
