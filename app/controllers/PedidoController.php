<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_producto = $parametros['id_producto'];
        $id_sector = $parametros['id_sector'];
        $observacion = $parametros['observacion'];
        $codigo_cliente = $parametros['codigo_cliente'];
        $cantidad = $parametros['cantidad'];

        // Creamos el pedido
        $pedido = new Pedido();
        $pedido->id_producto = $id_producto;
        $pedido->id_sector = $id_sector;
        $pedido->observacion = $observacion;
        $pedido->codigo_cliente = $codigo_cliente;
        $pedido->cantidad = $cantidad;
        $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos producto por id
        $id_pedido = $args['id'];
        $pedido = Pedido::obtenerSegunId($id_pedido);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosSegunSector($request, $response, $args)
    {
        $id_sector = $args['id_sector'];
        $lista = Pedido::obtenerSegunSector($id_sector);
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $estado = $parametros['estado'];

        Pedido::modificarEstadoPedido($id, $estado);

        $payload = json_encode(array("mensaje" => "Estado pedido modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id_pedido = $parametros['id'];
        Pedido::borrarPedido($id_pedido);

        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
