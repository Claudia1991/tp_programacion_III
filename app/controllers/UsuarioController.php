<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './models/Autenticador.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function Login($request, $response, $args){
      //Se verifica el usuario y devuelve el token
      $parametros = $request->getParsedBody();
      $id = $parametros['id'];
      $clave = $parametros['clave'];
      if(!isset($id) || !isset($clave)){
        $response->getBody()->write(json_encode(array("error" => "Error en los datos ingresados para login.")));
        $response = $response->withStatus(400);
      }else{
        $usuario = Usuario::obtenerUsuario($id);
        if(isset($usuario)){
          //Existe el usuario, verificamos el password
          if(password_verify($clave, $usuario->clave)){
            $datos = json_encode(array("id_usuario" => $usuario->id, "tipo_usuario" => $usuario->tipo_usuario, "id_sector" => $usuario->id_sector));
            $token = AutentificadorJWT::CrearToken($datos);
            $response->getBody()->write(json_encode(array("token" => $token)));
          }else{
          $response->getBody()->write(json_encode(array("error" => "Ocurrió un error, password incorrecto.")));
          $response = $response->withStatus(400);
          }        
        }else{
          $response->getBody()->write(json_encode(array("error" => "Ocurrió un error al generar el token.")));
          $response = $response->withStatus(400);
        }
      }
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $clave = $parametros['clave'];
        $tipo_usuario = $parametros['tipo_usuario'];
        $id_sector = $parametros['id_sector'];
        if(!isset($parametros) || !isset($nombre) || !isset($apellido) || !isset($clave) || !isset($tipo_usuario) || !isset($id_sector)){
          $payload = json_encode(array("error" => "Error en los parametros ingresados para crear el usuario."));
          $response = $response->withStatus(400);
        }else{
          if($tipo_usuario == 1 || $id_sector == 6){
            $payload = json_encode(array("error" => "No se puede crear otro socios. Son solo 3."));
            $response = $response->withStatus(400);
          }elseif($tipo_usuario != 2 || !in_array($id_sector, $this->TraerSectores())){
            $payload = json_encode(array("error" => "Verifique los datos tipo usuario y / o id sector."));
            $response = $response->withStatus(400);
          }else{
            $usr = new Usuario();
            $usr->nombre = $nombre;
            $usr->apellido = $apellido;
            $usr->clave = $clave;
            $usr->tipo_usuario = $tipo_usuario;
            $usr->id_sector = $id_sector;
            $id_generado = $usr->crearUsuario();
            
            $payload = json_encode(array("mensaje" => "Usuario creado con exito, id generado: " . $id_generado));
            $response = $response->withStatus(201);
          }
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por id
        $id_usuario = $args['id'];
        if(!isset($id_usuario)){
          $payload = json_encode(array("error" => "Error en los parametros ingresados para obtener un usuario."));
          $response = $response->withStatus(400);
        }else{
          // Creamos el usuario
          $usuario = Usuario::obtenerUsuario($id_usuario);
          $payload = json_encode(array("usuario" => $usuario));
          $response = $response->withStatus(200);
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $clave = $parametros['clave'];
        $id = $parametros['id'];

        if(!isset($parametros) || !isset($nombre) || !isset($apellido) || !isset($clave) || !isset($id)){
          $payload = json_encode(array("error" => "Error en los parametros para modificar usuario."));
          $response = $response->withStatus(400);
        }else{
          //Verificar si existe el usuario ingresado
          $usuarioAModificar = Usuario::obtenerUsuario($id);
          if(!$usuarioAModificar){
            $payload = json_encode(array("error" => "No existe el usuario a modificar"));
            $response = $response->withStatus(400);
          }else{
            $usr = new Usuario();
            $usr->id = $id;
            $usr->nombre = $nombre;
            $usr->apellido = $apellido;
            $usr->clave = $clave;
            Usuario::modificarUsuario($usr);
            $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
            $response = $response->withStatus(200);
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $usuarioId = $parametros['id'];
        if(!isset($parametros) || !isset($usuarioId)){
          $payload = json_encode(array("error" => "Error en datos para eliminar el usuario."));
          $response = $response->withStatus(400);
        }else{
          //Verifico si existe el usuario a eliminar
          $usuarioAEliminar = Usuario::obtenerUsuario($usuarioId);
          if(!isset($usuarioAEliminar)){
            $payload = json_encode(array("error" => "No existe el usuario a eliminar."));
            $response = $response->withStatus(400);
          }else{
            Usuario::borrarUsuario($usuarioId);
            $payload = json_encode(array("Mensaje" => "Usuario borrado con exito"));
            $response = $response->withStatus(200);
          }
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ActivarTemporada($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $usuarioId = $parametros['id'];
        if(!isset($usuarioId)){
          $payload = json_encode(array("error" => "Error en datos para activar el usuario por temporada."));
          $response = $response->withStatus(400);
        }else{
          //Verifico si existe el usuario a activar
          $usuarioActivar = Usuario::obtenerUsuario($usuarioId, true);
          if(!$usuarioActivar){
            $payload = json_encode(array("error" => "No existe el usuario a activar."));
            $response = $response->withStatus(400);
          }else{
            Usuario::revertirBajaUsuario($usuarioId);
            $payload = json_encode(array("Mensaje" => "Usuario activado con exito"));
            $response = $response->withStatus(200);
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function TraerSectores(){
      return [1,2,3,4,5];
    }
}
