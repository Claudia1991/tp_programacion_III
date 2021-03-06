<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{

  public function CargarDesdeCsv($request,$response, $args)
  {
        $archivo_csv =$_FILES["archivoCsv"];
        $nombre_real = $_FILES["archivoCsv"]["name"];
        $extension = explode(".", $nombre_real)[1];
        if(!isset($archivo_csv) || $extension != "csv"){
          $payload = json_encode(array("error" => "Error en el archivo CSV."));
          $response = $response->withStatus(400);
        }else{
          $fechaActual = new DateTime();
          $marcaTemporal = $fechaActual->getTimestamp();
          $ruta_temporal = "./temporales/" . $marcaTemporal . "." . $extension;
          move_uploaded_file($_FILES["archivoCsv"]["tmp_name"], $ruta_temporal);
          $archivo_abierto = fopen($ruta_temporal, 'r');
        if ($archivo_abierto != null) {
          $cantidad_procesado_exito = 0;
          $cantidad_procesado_error = 0;
          while (!feof($archivo_abierto)) {
            $linea = fgets($archivo_abierto);
            $lectura = explode(',', $linea);
            if (isset($lectura[0]) && !empty($lectura[0]) && isset($lectura[1]) && !empty($lectura[1]) && isset($lectura[2]) && !empty($lectura[2]) ) {
              $producto_nuevo = new Producto();
              $producto_nuevo->nombre = $lectura[0];
              $producto_nuevo->precio = $lectura[1];
              $producto_nuevo->tipo = $lectura[2];
              $producto_nuevo->crearProducto();
              $cantidad_procesado_exito++;
            }else{
              $cantidad_procesado_error++;;
            }
          }
          fclose($archivo_abierto);
          $payload = json_encode(array("mensaje" => "Se procesaron " . $cantidad_procesado_exito . " Registros que dieron error: " . $cantidad_procesado_error));
          $response = $response->withStatus(200);
        }else{
          $payload = json_encode(array("error" => "Error en el archivo subido."));
          $response = $response->withStatus(400);
        }
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }

  public function DescargaCsv($request,$response, $args)
  {
    //La descarga funciona en el navegador
      $fechaActual = new DateTime();
      $marcaTemporal = $fechaActual->getTimestamp();
      $ruta_archivo_descargar = "./temporales/" . $marcaTemporal . "-productos.csv";
      $archivo_descargar = fopen($ruta_archivo_descargar, 'a');
      $payload = null;
      if($archivo_descargar != null){
        $productos = Producto::obtenerTodos();
        foreach ($productos as $producto) {
          $linea = Producto::productoToString($producto);
          fwrite($archivo_descargar, $linea . "\n");
        }
        fclose($archivo_descargar);
        ob_end_clean(); 
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="Productos.csv"');
        readfile($ruta_archivo_descargar);
        return $response->withHeader('Content-Type', 'text/csv');
      }else{
        $payload = json_encode(array("error" => "Ocurrio un error al descargar los productos en CSV"));
      }
      $response->getBody()->write($payload);
      return $response->withHeader('Content-Type', 'application/json');
  }

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
