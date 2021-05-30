<?php
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $id_producto = $parametros['id_producto'];
        $codigo_cliente = $parametros['codigo_cliente'];
        $cantidad = $parametros['cantidad'];
        $payload = null;
        if(!isset($parametros) || !isset($id_producto) || !isset($codigo_cliente) || !isset($cantidad)){
          $payload = json_encode(array("error" => "Error en los parametros ingresados."));
          $response = $response->withStatus(400);
        }else{
          $producto = Producto::obtenerProducto($id_producto);
          $mesa = Mesa::obtenerMesaSegunCodigoCliente($codigo_cliente);
          if(!$producto){
            $payload = json_encode(array("error" => "No existe el producto que quiere agregar al pedido."));
            $response = $response->withStatus(400);
          }elseif(!$mesa){
            $payload = json_encode(array("error" => "No existe la mesa a la cual cargar el pedido. Primero cargue la mesa."));
            $response = $response->withStatus(400);
          }else{
            $pedido = new Pedido();
            $pedido->id_mesa = $mesa->id;
            $pedido->id_producto = $id_producto;
            $pedido->id_sector = $producto->tipo;
            $pedido->codigo_cliente = $codigo_cliente;
            $pedido->cantidad = $cantidad;
            $pedido->id_empleado = 0;
            $id_pedido_creado = $pedido->crearPedido();
            $consumicion = ($producto->precio * $cantidad) + $mesa->total_consumicion;
            Mesa::actualizarConsumicionMesa($mesa->id, $mesa->codigo_cliente, $consumicion);
            $payload = json_encode(array("mensaje" => "Pedido creado con exito. Id pedido creado: " . $id_pedido_creado));
            $response = $response->withStatus(201);
          }
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
          if($id_sector == 6 || $id_sector == 5){
            //Es un socio (sector 6) o es un mozo (sector 5) traer todos los pedidos
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
          if($id_sector == 6 || $id_sector == 5){
            //Es un socio (sector 6) o es un mozo (sector 5) traer todos los pedidos
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
        $parametros = $request->getParsedBody()["body"];
        $id_sector = $dataToken['id_sector'];
        $id_usuario = $dataToken['id_usuario'];
        $id_pedido = $parametros['id'];
        $estado_pedido = $parametros['estado'];
        $minutos_preparacion = $parametros['minutos_preparacion'];
        $payload = null;
        if(!isset($parametros) || !isset($id_pedido) || !isset($estado_pedido) || !isset($id_sector)){
          $payload = json_encode(array("error" => "Error en los parametros ingresados para modificar el estado de un pedido."));
          $response = $response->withStatus(400);
        }else{
          //Es algun sector(cocina 1 / candy bar 2 / barra tragos 3 / barra cervezas 4)
          $pedido = Pedido::obtenerSegunIdySector($id_pedido, $id_sector);
          if(!$pedido){
            $payload = json_encode(array("error" => "No existe el pedido que quiere actualizar o no es de su sector."));
            $response = $response->withStatus(400);
          }else{
            /**
             * Logica de negocio:
             */
            if($estado_pedido == 2 && isset($minutos_preparacion) && $pedido->id_estado == 1){
              Pedido::tomarPedido($id_pedido, $minutos_preparacion, $id_usuario);
              $payload = json_encode(array("mensaje" => "Pedido tomado con exito."));
            }elseif($estado_pedido == 3 && $pedido->id_estado == 2 && $pedido->id_empleado == $id_usuario){
              Pedido::entregarPedido($id_pedido);
              $payload = json_encode(array("mensaje" => "Pedido terminado con exito."));
            }else{
              $payload = json_encode(array("error" => "No se puede modificar el estado del pedido."));
              $response = $response->withStatus(400);
            }
          }
        }
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
