<?php 
require_once __DIR__ . '/../includes/app.php';

use Controllers\AccesorioController;
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
$router->get('/ingreso/equipo"', [EquipoController::class, 'index']);



// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
