<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class Logger
{
    public static function LogOperacion(Request $request, RequestHandler $handler): Response
    {
        //Logueo de acciones de las acciones(cambio de estados de pedidos, mesas, usuarios)
        $response = new Response();
        //antes de la api
        $response = $handler->handle($request);
        //despues de la api
        return $response;
    }
}