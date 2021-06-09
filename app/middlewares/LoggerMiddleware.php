<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/Log.php';

class LoggerMiddleware
{
    public static function LogOperacion(Request $request, RequestHandler $handler): Response
    {
        //Logueo de acciones de las acciones(cambio de estados de pedidos, mesas, usuarios)
        $response = new Response();
        $response = $handler->handle($request);
        $path = $request->getUri()->getPath();
        $metodo = $request->getMethod();
        $http_status_code = $response->getStatusCode();
        
        if(str_contains($path, 'login')){
            /** Login */
            $request_api = $request->getParsedBody();
            $id_usuario = $request_api['id'];
            if($http_status_code == 200 ){
                Log::Add($id_usuario, 0, 'SISTEMA', null, 'Ingreso sistema', null, null);
            }
        }elseif(str_contains($path, 'pedidos')){
            /** Pedidos */
            $request_api = $request->getParsedBody()["body"];
            $data_token = json_decode($request->getParsedBody()["dataToken"], true);
            $id_usuario = $data_token['id_usuario'];
            $id_sector = $data_token['id_sector'];
            if($metodo == 'POST' && $http_status_code == 201){
                Log::Add($id_usuario, $id_sector, 'PEDIDOS', 0, 'Cargar uno', json_encode($request_api), $response->getBody());
            }elseif($metodo == 'PUT' && $http_status_code == 200){
                $id_pedido = $request_api['id'];
                Log::Add($id_usuario, $id_sector, 'PEDIDOS', $id_pedido, 'Modificar uno', json_encode($request_api), $response->getBody());
            }elseif($metodo == 'GET' && $http_status_code == 200){
                $id_pedido = $request_api['id'];
                Log::Add($id_usuario, $id_sector, 'PEDIDOS', null, 'Obtener', json_encode($request_api), $response->getBody());
            }else{
                Log::Add($id_usuario, $id_sector, 'ERROR', null, 'Error', json_encode($request_api), $response->getBody());
            }
        }elseif(str_contains($path, 'mesas')){
            /** Mesas */
            $request_api = $request->getParsedBody()["body"];
            $data_token = json_decode($request->getParsedBody()["dataToken"], true);
            $id_usuario = $data_token['id_usuario'];
            $id_sector = $data_token['id_sector'];
            if($metodo == 'POST' && $http_status_code == 201){
                Log::Add($id_usuario, $id_sector, 'MESAS', 0, 'Cargar uno', json_encode($request_api), $response->getBody());
            }elseif($metodo == 'PUT' && $http_status_code == 200){
                $codigo_cliente = $request_api['codigo_cliente'];
                Log::Add($id_usuario, $id_sector, 'MESAS', $codigo_cliente, 'Modificar uno', json_encode($request_api), $response->getBody());
            }elseif($metodo == 'GET' && $http_status_code == 200){
                Log::Add($id_usuario, $id_sector, 'MESAS', null, 'Obtener', json_encode($request_api), $response->getBody());
            }else{
                Log::Add($id_usuario, $id_sector, 'ERROR', null, 'Error', json_encode($request_api), $response->getBody());
            }
        }
        return $response;
    }
}