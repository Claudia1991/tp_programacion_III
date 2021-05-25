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
//require_once './middlewares/Logger.php';
//require_once './middlewares/CorsMiddleware.php';

require_once './middlewares/AutenticacionMiddelware.php';
require_once './middlewares/UsuariosMiddleware.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/MesaController.php';

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
    $group->post('/login', \UsuarioController::class . ':Login'); //Por todos los usuarios. 
});

$app->group('/usuarios', function (RouteCollectorProxy $group) { //Solo por los Socios.
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{id}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
    $group->put('[/]', \UsuarioController::class . ':ModificarUno');
    $group->put('/{id}', \UsuarioController::class . ':ActivarTemporada');
    $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
})->add(\UsuariosMiddleware::class . ':verificarAccesoSocios')
->add(\AutenticacionMiddelware::class . ':verificarTokenUsuario');

// $app->group('/productos', function (RouteCollectorProxy $group) {
//     $group->get('[/]', \ProductoController::class . ':TraerTodos');//por todos los usuarios
//     $group->get('/{id}', \ProductoController::class . ':TraerUno');
//     $group->post('[/]', \ProductoController::class . ':CargarUno');
//     $group->put('[/]', \ProductoController::class . ':ModificarUno');
//     $group->delete('/{id}', \ProductoController::class . ':BorrarUno');
// });

// $app->group('/pedidos', function (RouteCollectorProxy $group) {
//     $group->get('[/]', \PedidoController::class . ':TraerTodos');
//     $group->get('/{id}', \PedidoController::class . ':TraerUno');
//     $group->post('[/]', \PedidoController::class . ':CargarUno'); // solo por el mozo
//     $group->put('[/]', \PedidoController::class . ':ModificarUno'); //tiene su logica de negocio
// });

// $app->group('/mesas', function (RouteCollectorProxy $group) {
//     $group->get('[/]', \MesaController::class . ':TraerTodos');
//     $group->get('/{id}', \MesaController::class . ':TraerUno');
//     $group->post('[/]', \MesaController::class . ':CargarUno'); //solo por el mozo
//     $group->put('[/]', \MesaController::class . ':ModificarUno'); //Cambia el estado de la mesa, solo por mesero o socio
// });

$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("Desarrollado por Claudia Jara");
    return $response;

});

$app->run();
