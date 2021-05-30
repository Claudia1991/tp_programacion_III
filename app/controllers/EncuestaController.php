<?php
require_once './models/Encuesta.php';
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class EncuestaController extends Encuesta implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
      /**
       * Logica Negocio: la mesa tiene que tener estado 4 - cerrada.
       */
        $parametros = $request->getParsedBody()["body"];
        $puntuacion_descripcion = $parametros['puntuacion_descripcion'];
        $puntuacion_numero = $parametros['puntuacion_numero'];
        $codigo_cliente = $parametros['codigo_cliente'];
        $payload =  null;
        if(!isset($parametros) || !isset($codigo_cliente) || !isset($puntuacion_descripcion) || !isset($puntuacion_numero)){
          $payload = json_encode(array("error" => "Error en los parametros para crear la encuesta."));
          $response = $response->withStatus(400);
        }else{
          $mesa = Mesa::obtenerMesaSegunCodigoCliente($codigo_cliente);
          if(!$mesa || $mesa->codigo_estado_mesa != 4){
            $payload = json_encode(array("error" => "No existe la mesa para hacer la encuesta."));
            $response = $response->withStatus(400);
          }elseif($puntuacion_numero < 1 || $puntuacion_numero > 10){
            $payload = json_encode(array("error" => "Error en el numero de puntuacion."));
            $response = $response->withStatus(400);
          }else{
            $encuesta = new Encuesta();
            $encuesta->puntuacion_descripcion = $puntuacion_descripcion;
            $encuesta->puntuacion_numero = $puntuacion_numero;
            $encuesta->codigo_cliente = $codigo_cliente;
            $encuesta->crearEncuesta();
            $payload = json_encode(array("mensaje" => "Encuesta cargada."));
            $response = $response->withStatus(201);
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $encuesta = Encuesta::obtenerUno($id);
        $payload = json_encode($encuesta);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Encuesta::obtenerTodos();
        $payload = json_encode(array("listaEncuesta" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Metodo no permitido"));
      $response = $response->withStatus(405);
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
      $payload = json_encode(array("error" => "Metodo no permitido"));
      $response = $response->withStatus(405);
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
    }
}
