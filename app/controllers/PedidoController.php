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
        //TODO: MODIFICAR
        $id_producto = $args['id'];
        $producto = Producto::obtenerProducto($id_producto);
        $payload = json_encode($producto);

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
    
    public function ModificarUno($request, $response, $args)
    {
      //TODO: MODIFICAR
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $tipo = $parametros['tipo'];
        $producto = new Producto();
        $producto->id = $id;
        $producto->nombre = $nombre;
        $producto->tipo = $tipo;
        $producto->precio = $precio;
        Producto::modificarProducto($producto);

        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
      //TODO : MODIFICAR
        $parametros = $request->getParsedBody();

        $productoId = $parametros['id'];
        Producto::borrarProducto($productoId);

        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
