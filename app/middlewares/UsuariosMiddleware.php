<?php

require_once './models/Validador.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class UsuariosMiddleware{

    private static $id_tipo_socio = 5;
    private static $id_tipo_mozo = 1;


    public function VerificarAccesoSocios(Request $request, RequestHandler $handler): Response{
        /**
         * En este metodo, se verifica que, desde el mw anterior, del token, los datos que me mandan
         * el id_usuario y codigo_tipo_usuario, sea de usuario.
         */
        $dataToken = json_decode($request->getParsedBody()["dataToken"], true);
        $response = new Response();
        if(isset($dataToken)){
            //Verifico que el codigo_tipo_usuario sea socio
            if($dataToken['codigo_tipo_usuario'] == self::$id_tipo_socio){
                //avanzo al siguiente MW o paso al controller de la apii
                $response = $handler->handle($request);
            }else{
                $response->getBody()->write(json_encode(array("error" => "No tienes accesos.")));
                $response = $response->withStatus(401);
            }
        }else{
            $response->getBody()->write(json_encode(array("error" => "Error en los datos ingresados en el dataToken")));
            $response = $response->withStatus(400);
        }
     return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarAccesoUsuariosPedidos(Request $request, RequestHandler $handler): Response{
        //Pedidos
        /**
         * Cargar Uno => solo por el mozo
         * TraerTodos => cada sector con su pedido
         * ModificarUno => cada sector con su pedido
         */
        $response = new Response();
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarAccesoUsuariosMesas(Request $request, RequestHandler $handler): Response{
        /**
         * Mesas
         * CargarUno => solo por el mozo
         * ModificarUno => Cambia el estado de la mesa mozo o socio.
         */
        $response = new Response();

        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>