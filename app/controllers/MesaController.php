<?php
require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $dataToken = json_decode($request->getParsedBody()["dataToken"], true);
        $nombre_cliente = $parametros['nombre_cliente'];
        $id_sector = $dataToken['id_sector'];
        $id_usuario = $dataToken['id_usuario'];
        $payload = null;
        if(!isset($parametros) || !isset($nombre_cliente) || !isset($id_sector)){
          $payload = json_encode(array("error" => "Error en los parametros ingresados para crear la mesa."));
          $response = $response->withStatus(400);
        }else{
          if(!($id_sector == 5)){
            $payload = json_encode(array("error" => "No podes crear mesas. Solo mozos."));
            $response = $response->withStatus(401);
          }else{
            /**
             * Logica Negocio: busco la primer mesa libre. La creo.
             */
            $mesa_libre = Mesa::obtenerPrimeraMesaLibre();
            if(!$mesa_libre){
              $payload = json_encode(array("error" => "No hay mesa libre."));
              $response = $response->withStatus(400);
            }else{
              $mesa = new Mesa();
              $mesa->nombre_cliente = $nombre_cliente;
              $mesa_creada = $mesa->crearMesa($mesa_libre->id, $id_usuario);
              $payload = json_encode(array("mensaje" => "Mesa creado con exito: " . $mesa_creada));
              $response = $response->withStatus(201);
            }
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
        if(!isset($codigo_cliente)){
          $payload = json_encode(array("error" => "Error en los parametros ingresados para traer la mesa."));
          $response = $response->withStatus(400);
        }else{
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
        $id_mesa = $parametros['id_mesa'];
        $codigo_cliente = $parametros['codigo_cliente'];
        $codigo_mesa_estado = $parametros['codigo_mesa_estado'];
        $id_sector = $dataToken['id_sector'];
        $payload = null;
        if(!isset($parametros) || !isset($codigo_cliente) || !isset($codigo_mesa_estado) || !isset($dataToken) || !isset($id_sector)){
          $payload = json_encode(array("error" => "Error en los parametros para modificar la mesa."));
          $response = $response->withStatus(400);
        }else{
          $mesaModificar = Mesa::obtenerMesaSegunCodigoCliente($codigo_cliente);
          if(!$mesaModificar){
            $payload = json_encode(array("error" => "No existe la mesa que quiere modificar."));
            $response = $response->withStatus(400);
          }else{
            if(($id_sector == 6 && $codigo_mesa_estado == 4) || ($id_sector == 5 && $codigo_mesa_estado != 4)){
              /**
               * Logica de negocio: es socio y quiere cambiar el estado de la mesa a cerrada , es mozo y quiere cambiar el estado y no a cerrada.
               */
              if(($codigo_mesa_estado == 4 && $mesaModificar->codigo_estado_mesa != 3) || ($codigo_mesa_estado == 3 && $mesaModificar->codigo_estado_mesa != 2) ||
              ($codigo_mesa_estado == 2 && $mesaModificar->codigo_estado_mesa != 1) ){
                $payload = json_encode(array("error" => "No coinciden los estados a cambiar las mesas."));
                $response = $response->withStatus(400);
              }else{
                  Mesa::modificarMesa($codigo_cliente, $codigo_mesa_estado);
                if($codigo_mesa_estado == 4){
                  /**
                  * Logica de negocio: se cierra le mesa, se pasan datos a facturacion y los pedidos se dan baja logica
                  */
                  Mesa::pasarFacturacionHistoricos($mesaModificar->id, $mesaModificar->codigo_cliente,$mesaModificar->total_consumicion, $mesaModificar->id_mozo);
                  Pedido::borrarPedidos($mesaModificar->id, $mesaModificar->codigo_cliente);
                }
                $payload = json_encode(array("mensaje" => "Mesa modificado con exito."));
              }
            }else{
              $payload = json_encode(array("error" => "No coinciden los estados a cambiar las mesas con los permisos."));
              $response = $response->withStatus(401);
            }
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $id_mesa = $parametros['id'];
        if(!isset($parametros) || !isset($id_mesa)){
          $payload = json_encode(array("error" => "Error al querer borrar las mesas."));
          $response = $response->withStatus(400);
        }else{
          Mesa::borrarMesa($id_mesa);
          $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
