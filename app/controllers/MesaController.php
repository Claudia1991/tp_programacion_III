<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $dataToken = json_decode($request->getParsedBody()["dataToken"], true);
        $nombre_cliente = $parametros['nombre_cliente'];
        $id_sector = $dataToken['id_sector'];
        $payload = null;
        if(!isset($parametros) || !isset($nombre_cliente) || !isset($id_sector)){
          $payload = json_encode(array("error" => "Error en los parametros ingresados para crear la mesa."));
          $response = $response->withStatus(400);
        }else{
          //TODO: LIMITAR LA CANTIDAD DE MESAS DE ALGUNA FORMA
          //y hacerlas reutilizables
          if(!($id_sector == 5)){
            $payload = json_encode(array("error" => "No podes crear mesas. Solo mozos."));
            $response = $response->withStatus(401);
          }else{
            // Creamos la mesa
            $mesa = new Mesa();
            $mesa->nombre_cliente = $nombre_cliente;
            $mesa->crearMesa();
            //TODO: DEVOLVER EL CODIGO MESA O ID CREADO
            $payload = json_encode(array("mensaje" => "Mesa creado con exito"));
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos mesa por codigo_cliente
        $codigo_cliente = $args['codigo_cliente'];
        $payload = null;
        var_dump($codigo_cliente);
        if(!isset($codigo_cliente)){
          $payload = json_encode(array("error" => "Error en los parametros ingresados para traer la mesa."));
          $response = $response->withStatus(400);
        }else{
        var_dump($codigo_cliente);

          $mesa = Mesa::obtenerMesaSegunCodigoCliente($codigo_cliente);
          $payload = json_encode($mesa);
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesas" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $dataToken = json_decode($request->getParsedBody()["dataToken"], true);
        $codigo_mesa = $parametros['codigo_mesa'];
        $codigo_mesa_estado = $parametros['codigo_mesa_estado'];
        $id_sector = $dataToken['id_sector'];
        $payload = null;
        if(!isset($parametros) || !isset($codigo_mesa) || !isset($codigo_mesa_estado) || !isset($dataToken) || !isset($id_sector)){
          $payload = json_encode(array("error" => "Error en los parametros para modificar la mesa."));
          $response = $response->withStatus(400);
        }else{
          var_dump($codigo_mesa);
          $mesaModificar = Mesa::obtenerMesaSegunCodigoCliente($codigo_mesa);
          if(!$mesaModificar){
            $payload = json_encode(array("error" => "No existe la mesa que quiere modificar."));
          }else{
            if(($id_sector == 6 && $codigo_mesa_estado == 4) || ($id_sector == 5 && $codigo_mesa_estado != 4)){
              //Es socio y quiere cambiar el estado de la mesa a cerrada
              //Es mozo y quiere cambiar el estado y no a cerrada
              //TODO: CUANDO CIERRO UNA MESA, DAR DE BAJA LOS PEDIDOS DE ESA MESA
              $mesa = new Mesa();
              $mesa->codigo_mesa = $codigo_mesa;
              $mesa->codigo_mesa_estado = $codigo_mesa_estado;
              Mesa::modificarMesa($codigo_mesa, $codigo_mesa_estado);
              $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
            }else{
              $payload = json_encode(array("error" => "No existe la mesa que quiere modificar."));
              $response = $response->withStatus(401);
            }
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_mesa = $parametros['id'];
        Mesa::borrarMesa($id_mesa);

        $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
