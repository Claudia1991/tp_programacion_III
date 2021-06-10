<?php
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './db/AccesoDatos.php';
require_once './middlewares/LoggerMiddleware.php';
require_once './middlewares/AutenticacionMiddelware.php';
require_once './middlewares/UsuariosMiddleware.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/EncuestaController.php';
require_once './controllers/ReportesController.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

$app->setBasePath("/laComanda");
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

date_default_timezone_set('America/Argentina/Buenos_Aires');

// Routes
$app->group('/autenticacion', function (RouteCollectorProxy $group) {
    //Accesible para todos los usuarios.
    $group->post('/login', \UsuarioController::class . ':Login'); 
})->add(\LoggerMiddleware::class . ':LogOperacion');

$app->group('/usuarios', function (RouteCollectorProxy $group) { 
    //Accesible solo por los Socios.
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{id}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
    $group->put('[/]', \UsuarioController::class . ':ModificarUno');
    $group->put('/activarTemporada', \UsuarioController::class . ':ActivarTemporada');
    $group->delete('[/]', \UsuarioController::class . ':BorrarUno');
})->add(\UsuariosMiddleware::class . ':verificarAccesoSocios')
->add(\AutenticacionMiddelware::class . ':verificarTokenUsuario');

$app->group('/productos', function (RouteCollectorProxy $group) {
    //Accesible solo por los Socios.
    $group->get('/descargaCsv', \ProductoController::class . ':DescargaCsv');
    $group->get('/', \ProductoController::class . ':TraerTodos');
    $group->get('/{id}', \ProductoController::class . ':TraerUno');
    $group->post('/cargaCsv', \ProductoController::class . ':CargarDesdeCsv');
    $group->post('[/]', \ProductoController::class . ':CargarUno');
    $group->put('[/]', \ProductoController::class . ':ModificarUno');
    $group->delete('/{id}', \ProductoController::class . ':BorrarUno');
})->add(\UsuariosMiddleware::class . ':verificarAccesoSocios')
->add(\AutenticacionMiddelware::class . ':verificarTokenUsuario');

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    // Lo pueden ver los socios y los empleados de cada sector
    $group->get('[/]', \PedidoController::class . ':TraerTodos'); // Segun el sector y el mozo y el socio 
    $group->get('/{id}', \PedidoController::class . ':TraerUno'); // Segun el sector y el mozo y el socio
    $group->post('[/]', \PedidoController::class . ':CargarUno')
    ->add(\UsuariosMiddleware::class . ':verificarAccesoMozo'); // Solo por el mozo
    $group->put('[/]', \PedidoController::class . ':ModificarUno')
    ->add(\UsuariosMiddleware::class . ':VerificarAccesoSectores'); // Segun el sector, el socio y el mozo no pueden modificar 
})->add(\LoggerMiddleware::class . ':LogOperacion')
->add(\AutenticacionMiddelware::class . ':verificarTokenUsuario');

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos')->add(\UsuariosMiddleware::class . ':verificarAccesoSocioMozo'); //Por el mozo y el socio
    $group->get('/{codigo_cliente}', \MesaController::class . ':TraerUno')->add(\UsuariosMiddleware::class . ':verificarAccesoSocioMozo'); //Por el mozo y el socio
    $group->post('[/]', \MesaController::class . ':CargarUno')->add(\UsuariosMiddleware::class . ':verificarAccesoMozo'); //solo por el mozo
    $group->put('[/]', \MesaController::class . ':ModificarUno')->add(\UsuariosMiddleware::class . ':verificarAccesoSocioMozo'); //Cambia el estado de la mesa, solo por mesero o socio
    $group->delete('[/]', \MesaController::class . ':BorrarUno')->add(\UsuariosMiddleware::class . ':verificarAccesoSocios'); 
})->add(\LoggerMiddleware::class . ':LogOperacion')
->add(\AutenticacionMiddelware::class . ':verificarTokenUsuario');

$app->group('/encuestas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \EncuestaController::class . ':TraerTodos')
    ->add(\UsuariosMiddleware::class . ':verificarAccesoSocios')
    ->add(\AutenticacionMiddelware::class . ':verificarTokenUsuario'); 
    $group->get('/{codigo_cliente}', \EncuestaController::class . ':TraerUno')
    ->add(\UsuariosMiddleware::class . ':verificarAccesoSocios')
    ->add(\AutenticacionMiddelware::class . ':verificarTokenUsuario');
    $group->post('[/]', \EncuestaController::class . ':CargarUno'); 
});


$app->group('/reportes', function (RouteCollectorProxy $group) {
    $group->get('/empleados/estadistica={estadistica}&fecha_inicio={inicio}&fecha_fin={fin}', \ReportesController::class . ':ReportesEmpleados'); 
    $group->get('/pedidos/estadistica={estadistica}&fecha_inicio={inicio}&fecha_fin={fin}', \ReportesController::class . ':ReportesPedidos'); 
    $group->get('/mesas/estadistica={estadistica}&fecha_inicio={inicio}&fecha_fin={fin}', \ReportesController::class . ':ReportesMesas');  
});
    //->add(\UsuariosMiddleware::class . ':verificarAccesoSocios')
// ->add(\AutenticacionMiddelware::class . ':verificarTokenUsuario');

$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Desarrollado por Claudia Jara");
    return $response;

});

$app->run();
