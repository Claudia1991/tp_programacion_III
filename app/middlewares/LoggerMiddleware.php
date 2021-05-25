<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/Log.php';

class LoggerMiddleware
{
    public static function LogOperacion(Request $request, RequestHandler $handler): Response
    {
        $fechaActual = new DateTime();
        $marcaTemporal = $fechaActual->getTimestamp();
        //Logueo de acciones de las acciones(cambio de estados de pedidos, mesas, usuarios)
        $response = new Response();
        Log::Add($marcaTemporal, 'Method: ' .$request->getMethod() . ' Path: ' . $request->getUri()->getPath() . ' Request: ' . json_encode($request->getParsedBody()));
        //antes de la api
        $response = $handler->handle($request);
        Log::Add($marcaTemporal, 'Http status: ' . $response->getStatusCode() . ' Response: ' . $response->getBody());
        //despues de la api
        return $response;
    }
}