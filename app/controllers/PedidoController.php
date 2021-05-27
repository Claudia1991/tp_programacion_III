<?php
require_once './models/Pedido.php';

require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $id_producto = $parametros['id_producto'];
        $id_sector = $parametros['id_sector'];
        $codigo_cliente = $parametros['codigo_cliente'];
        $cantidad = $parametros['cantidad'];
        $payload = null;
        if(!isset($parametros) || !isset($id_producto) || !isset($id_sector) || !isset($codigo_cliente) || !isset($cantidad)){
          $payload = json_encode(array("error" => "Error en los parametros ingresados."));
          $response = $response->withStatus(400);
        }else{
          //TODO: verificar si existe el producto, y el cliente, y actualizar la consumicion de la mesa
          //y si el cliente no esta, primero decir que cargue la mesa
          // Creamos el pedido
          $pedido = new Pedido();
          $pedido->id_producto = $id_producto;
          $pedido->id_sector = $id_sector;
          $pedido->codigo_cliente = $codigo_cliente;
          $pedido->cantidad = $cantidad;
          $id_pedido_creado = $pedido->crearPedido();
          $payload = json_encode(array("mensaje" => "Pedido creado con exito. Id pedido creado: " . $id_pedido_creado));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos producto por id
        //Si es del sector 6, es un socio, debe de traer igual
        //Sino, cada sector con su pedido
        $id_pedido = $args['id'];
        $dataToken = json_decode($request->getParsedBody()["dataToken"], true);
        $id_sector = $dataToken['id_sector'];
        $payload = null;
        $pedido = null;
        if(!isset($id_pedido) || !isset($dataToken) || !isset($id_sector)){
          $payload = json_encode(array("error" => "Error en los parametros para traer un pedido."));
          $response = $response->withStatus(400);
        }else{
          //TO DO: se puede refactor metodo MODIFICAR UNO
          if($id_sector == 6){
            //es un socio
            $pedido = Pedido::obtenerSegunId($id_pedido);
          }else{
            //Es algun sector(cocina 1 / candy bar 2 / barra tragos 3 / barra cervezas 4)
            $pedido = Pedido::obtenerSegunIdySector($id_pedido, $id_sector);
          }
          if(!$pedido){
            //Verifico si existe el pedido
            $payload = json_encode(array("error" => "Error no existe el pedido."));
            $response = $response->withStatus(404);
          }else{
            $payload = json_encode($pedido);
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $dataToken = json_decode($request->getParsedBody()["dataToken"], true);
        $id_sector = $dataToken['id_sector'];
        if(!isset($dataToken) || !isset($id_sector)){
          $payload = json_encode(array("error" => "Error en los parametros para traer los pedidos."));
          $response = $response->withStatus(400);
        }else{
          $lista = null;
          if($id_sector == 6){
            //Es un socio, sector 6 traer todos los pedidos
            $lista = Pedido::obtenerTodos();
          }else{
            $lista = Pedido::obtenerSegunSector($id_sector);
          }
          $payload = json_encode(array("listaPedidos" => $lista));
          $response = $response->withStatus(200);
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
      //Modificar uno es solo por el sector, no por el mozo sector 5 o socio sector 6
        $dataToken = json_decode($request->getParsedBody()["dataToken"], true);
        $id_sector = $dataToken['id_sector'];
        $parametros = $request->getParsedBody()["body"];
        $id_pedido = $parametros['id'];
        $estado_pedido = $parametros['estado'];
        $payload = null;
        if(!isset($parametros) || !isset($id_pedido) || !isset($estado_pedido) || !isset($id_sector)){
          $payload = json_encode(array("error" => "Error en los parametros ingresados para modificar el estado de un pedido."));
          $response = $response->withStatus(400);
        }else{
          //Obtengo el pedido , verifico el sector con el sector del pedido
          //TO DO: se puede refactor metodo TRAER UNO
            //Es algun sector(cocina 1 / candy bar 2 / barra tragos 3 / barra cervezas 4)
          $pedido = Pedido::obtenerSegunIdySector($id_pedido, $id_sector);
          if(!$pedido){
            $payload = json_encode(array("error" => "No existe el pedido que quiere actualizar o no es de su sector."));
          $response = $response->withStatus(400);
          }else{
            Pedido::modificarEstadoPedido($id_pedido, $estado_pedido);
            $payload = json_encode(array("mensaje" => "Estado pedido modificado con exito."));
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
      //El borrado logico ocurre, cuando se cierra una mesa, ojo al piojo, solo los socios
        $id_pedido = $args['id'];
        Pedido::borrarPedido($id_pedido);

        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
