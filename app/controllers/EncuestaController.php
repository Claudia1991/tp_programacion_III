<?php
require_once './models/Encuesta.php';
require_once './interfaces/IApiUsable.php';

class EncuestaController extends Encuesta implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre_cliente = $parametros['nombre_cliente'];

        // Creamos la mesa
        $producto = new Mesa();
        $producto->nombre_cliente = $nombre_cliente;
        $producto->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos encuesta por codigo_cliente
        $id = $args['id'];
        $encuesta = Encuesta::obtenerUno($id);
        $payload = json_encode($encuesta);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Encuesta::obtenerTodos();
        $payload = json_encode(array("listaEncuesta" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $puntuacion_descripcion = $parametros['puntuacion_descripcion'];
        $puntuacion_numero = $parametros['puntuacion_numero'];

        Encuesta::modificarEncuesta($id, $puntuacion_descripcion, $puntuacion_numero);

        $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_encuesta = $parametros['id'];
        Encuesta::borrarEncuesta($id_encuesta);

        $payload = json_encode(array("mensaje" => "Encuesta borrada con éxito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
