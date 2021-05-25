<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/Log.php';

class LoggerMiddleware
{
    public static function LogOperacion(Request $request, RequestHandler $handler): Response
    {
        //Logueo de acciones de las acciones(cambio de estados de pedidos, mesas, usuarios)
        $response = new Response();
        Log::Add(json_encode($request->getParsedBody()));
        //antes de la api
        $response = $handler->handle($request);
        Log::Add('Http status: ' . $response->getStatusCode() . ' Response: ' . $response->getBody());
        //despues de la api
        return $response;
    }
}