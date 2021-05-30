<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $tipo = $parametros['tipo'];
        if(!isset($parametros) || !isset($nombre) || !isset($precio) || !isset($tipo)){
          $payload = json_encode(array("error" => "Error en los parametros para crear el producto."));
          $response = $response->withStatus(400);
        }else{
          if(!in_array($tipo, $this->TraerSectores())){
            $payload = json_encode(array("error" => "Verifique el tipo del producto."));
            $response = $response->withStatus(400);
          }else{
            $producto = new Producto();
            $producto->nombre = $nombre;
            $producto->precio = $precio;
            $producto->tipo = $tipo;
            $producto->crearProducto();
            $payload = json_encode(array("mensaje" => "Producto creado con exito."));
            $response = $response->withStatus(201);
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos producto por id
        $id_producto = $args['id'];
        if(!isset($id_producto)){
          $payload = array("error" => "Error en los parametros para buscar el producto.");
          $response = $response->withStatus(400);
        }else{
          $producto = Producto::obtenerProducto($id_producto);
          $payload = json_encode(array("producto" => $producto));
          $response = $response->withStatus(200);
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $id = $parametros['id'];
        $precio = $parametros['precio'];
        if(!isset($parametros) || !isset($id) || !isset($precio)){
          $payload = json_encode(array("error" => "Error en los parametros para modificar el producto."));
          $response = $response->withStatus(400);
        }else{
          $productoModificar = Producto::obtenerProducto($id);
          if(!$productoModificar){
            $payload = json_encode(array("error" => "No existe el producto a modificar."));
            $response = $response->withStatus(400);
          }else{
            $producto = new Producto();
            $producto->id = $id;
            $producto->precio = $precio;
            Producto::modificarProducto($producto);
            $payload = json_encode(array("mensaje" => "Producto modificado con exito."));
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $productoId = $parametros['id'];
        if(!isset($parametros) || !isset($productoId)){
          $payload = json_encode(array("error" => "Error en los parametros para borrar el producto."));
          $response = $response->withStatus(400);
        }else{
          $productoBorrar = Producto::obtenerProducto($productoId);
          if(!$productoBorrar){
            $payload = json_encode(array("error" => "No existe el producto a eliminar."));
            $response = $response->withStatus(400);
          }else{
            Producto::borrarProducto($productoId);
            $payload = json_encode(array("mensaje" => "Producto borrado con exito."));
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function TraerSectores(){
      return [1,2,3,4];
    }
}
