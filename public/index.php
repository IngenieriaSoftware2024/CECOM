<?php 
require_once __DIR__ . '/../includes/app.php';

use Controllers\AccesorioController;
use Controllers\AsignacionEquipoController;
use Controllers\DestacamentoController;
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
$router->get('/API/equiposingresados/buscar', [AsignacionEquipoController::class, 'EquiposAlmacenAPI']);
$router->post('/API/fotografia_ofical', [AsignacionEquipoController::class, 'informacionOficialAPI']);
$router->post('/API/asignacion_dependencia/guardar', [AsignacionEquipoController::class, 'AsignarDependenciaAPI']);
$router->get('/API/buscartodos/equipos', [AsignacionEquipoController::class, 'BuscarTodosAPI']);
$router->get('/administracion-equipos', [AsignacionEquipoController::class, 'index2']);
$router->get('/API/mostrarequipos/buscar', [AsignacionEquipoController::class, 'EquiposDependenciaAPI']);
$router->get('/API/mostrarequipos/buscar', [AsignacionEquipoController::class, 'EquiposDependenciaAPI']);
$router->get('/API/datosusuario/buscar', [AsignacionEquipoController::class, 'DatosUsuarioAPI']);
$router->post('/API/asignacion_destino/guardar', [AsignacionEquipoController::class, 'AsignarDestinoAPI']);



//DESTACAMENTOS
$router->get('/destacamentos', [DestacamentoController::class, 'index']);
$router->get('/API/destacamentos/buscar', [DestacamentoController::class, 'BuscarDestacamentoAPI']);
$router->post('/API/destacamentos/guardar', [DestacamentoController::class, 'GuardarDestacamentoAPI']);
$router->post('/API/destacamentos/modificar', [DestacamentoController::class, 'ModificarDestacamentoAPI']);
$router->post('/API/destacamento/eliminar', [DestacamentoController::class, 'EliminarDestacamentoAPI']);



// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
