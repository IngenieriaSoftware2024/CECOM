<?php
require_once __DIR__ . '/../includes/app.php';

use Controllers\AccesorioController;
use Controllers\AsignacionEquipoController;
use Controllers\DestacamentoController;
use Controllers\EquipoController;
use Controllers\MantenimientoController;
use MVC\Router;
use Controllers\InicioController;
use Controllers\MapaController;
use Controllers\MarcaController;
use Controllers\ReportesController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [InicioController::class, 'index']);

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
$router->get('/API/datosusuario/buscar', [AsignacionEquipoController::class, 'DatosUsuarioAPI']);
$router->post('/API/asignacion_destino/guardar', [AsignacionEquipoController::class, 'AsignarDestinoAPI']);


//DESTACAMENTOS
$router->get('/destacamentos', [DestacamentoController::class, 'index']);
$router->get('/API/destacamentos/buscar', [DestacamentoController::class, 'BuscarDestacamentoAPI']);
$router->post('/API/destacamentos/guardar', [DestacamentoController::class, 'GuardarDestacamentoAPI']);
$router->post('/API/destacamentos/modificar', [DestacamentoController::class, 'ModificarDestacamentoAPI']);
$router->post('/API/destacamento/eliminar', [DestacamentoController::class, 'EliminarDestacamentoAPI']);


// MAPA
$router->get('/mapa/index', [MapaController::class, 'index']);
$router->get('/API/destacamentos/mostrar-todos', [MapaController::class, 'DestacamentoActivosAPI']);
$router->get('/API/equipos/destacamento', [MapaController::class, 'EquiposPorDestacamentoAPI']);
$router->get('/API/seleccion/tipo', [MapaController::class, 'EquipoTipoAPI']);

// MANTENIMINETO
$router->get('/mantenimiento', [MantenimientoController::class, 'index']);
$router->get('/API/mantenimientos/buscar', [MantenimientoController::class, 'buscarAPI']);
$router->get('/API/mantenimientos/BuscarEntrega', [MantenimientoController::class, 'buscarEntregaAPI']);
$router->post('/API/mantenimiento/guardar', [MantenimientoController::class, 'GuardarAPI']);
$router->get('/API/mantenimiento/validarcatalogo', [MantenimientoController::class, 'ValidarCatalogoAPI']);
$router->get('/API/mantenimientos/datosEquipo', [MantenimientoController::class, 'datosEquipoAPI']);
$router->post('/API/mantenimiento/Entregar', [MantenimientoController::class, 'EntregarAPI']);


// MODIFICACION DE EQUIPOOS
$router->get('/modificacion/equipos', [EquipoController::class, 'indexModficacion']);
$router->get('/API/mantenimientos/buscartodos', [EquipoController::class, 'BuscarTodosEquiposAPI']);
$router->post('/API/equipo/tipo-accesorios', [EquipoController::class, 'AccesoriosInformacionAPI']);
$router->post('/API/equipo/modificar', [EquipoController::class, 'ModificarEquipoAPI']);

$router->post('/API/accesoriosnuevo/agregar', [AsignacionEquipoController::class, 'AgregarAccesoriosNuevosAPI']);
$router->post('/API/accesorios/eliminar', [AsignacionEquipoController::class, 'EliminarAccesoriosAPI']);


// GENERACION DE REPORTES
$router->get('/reportes', [ReportesController::class, 'index']);
$router->post('/API/reportes/buscar', [ReportesController::class, 'BuscarCondiciones']);
$router->get('/API/informaciongeneral/buscar', [ReportesController::class, 'InformacionEquipoSeleccionadoAPI']);



//HISTORIAL DE MANTENIMIENTOS Y REPARACIONES
$router->get('/historial/mantenimientos', [ReportesController::class, 'indexHistorial']);
$router->get('/API/historial/buscar', [ReportesController::class, 'HistorialBuscarAPI']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
    