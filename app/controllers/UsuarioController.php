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
      $usuario = Usuario::obtenerUsuario($id);
      if(isset($usuario)){
        //Existe el usuario, verificamos el password
        if(password_verify($clave, $usuario->clave)){
          $datos = json_encode(array("id_usuario" => $usuario->id, "codigo_tipo_usuario" => $usuario->codigo_usuario));
          $token = AutentificadorJWT::CrearToken($datos);
          $response->getBody()->write(json_encode(array("token" => $token)));
        }else{
        $response->getBody()->write(json_encode(array("error" => "Ocurrió un error, password incorrecto.")));
        }        
      }else{
        $response->getBody()->write(json_encode(array("error" => "Ocurrió un error al generar el token.")));
      }
      return $response->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody()["body"];
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $clave = $parametros['clave'];
        $codigo_usuario = $parametros['codigo_usuario'];
        if(!isset($parametros) || !isset($nombre) || !isset($apellido) || !isset($clave) || !isset($codigo_usuario)){
          $payload = json_encode(array("error" => "Error en los parametros ingresados para crear el usuario."));
          $response = $response->withStatus(400);
        }else{
          // Creamos el usuario
          $usr = new Usuario();
          $usr->nombre = $nombre;
          $usr->apellido = $apellido;
          $usr->clave = $clave;
          $usr->codigo_usuario = $codigo_usuario;
          $id_generado = $usr->crearUsuario();
          
          $payload = json_encode(array("mensaje" => "Usuario creado con exito, id generado: " . $id_generado));
          $response = $response->withStatus(200);
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
        $codigo_usuario = $parametros['codigo_usuario'];
        $activo_temporada = $parametros['activo_temporada'];
        $id = $parametros['id'];

        if(!isset($parametros) || !isset($nombre) || !isset($apellido) || !isset($clave) || !isset($codigo_usuario) || !isset($activo_temporada) || !isset($id)){
          $payload = json_encode(array("error" => "Error en los parametros para modificar usuario."));
          $response = $response->withStatus(400);
        }else{
          //Verificar si existe el usuario ingresado
          $usuarioAModificar = Usuario::obtenerUsuario($id);
          if(!isset($usuarioAModificar)){
            $payload = json_encode(array("error" => "No existe el usuario a modificar"));
            $response = $response->withStatus(400);
          }else{
            $usr = new Usuario();
            $usr->id = $id;
            $usr->nombre = $nombre;
            $usr->apellido = $apellido;
            $usr->clave = $clave;
            $usr->codigo_usuario = $codigo_usuario;
            $usr->activo_temporada = $activo_temporada;
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
        $usuarioId = $args['id'];
        if(!isset($usuarioId)){
          $payload = json_encode(array("error" => "Error en datos para activar el usuario por temporada."));
          $response = $response->withStatus(400);
        }else{
          //Verifico si existe el usuario a activar
          $usuarioActivar = Usuario::obtenerUsuario($usuarioId);
          if(!$usuarioActivar){
            $payload = json_encode(array("error" => "No existe el usuario a activar."));
            $response = $response->withStatus(400);
          }else{
            Usuario::RevertirBajaTemporada($usuarioId);
            $payload = json_encode(array("Mensaje" => "Usuario activado con exito"));
            $response = $response->withStatus(200);
          }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
