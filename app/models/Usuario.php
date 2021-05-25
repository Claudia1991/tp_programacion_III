<?php

class Usuario
{
    public $id;
    public $nombre;
    public $apellido;
    public $clave;
    public $codigo_usuario;
    public $baja_logica;
    public $activo_temporada;

    public function crearUsuario()
    {
        //Cuando creo un usuario, por defecto, activo_temporada = 1 y baja_logica = 0
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Usuarios (nombre, apellido, clave, codigo_usuario, activo_temporada, baja_logica) VALUES (:nombre, :apellido, :clave, :codigo_usuario, :activo_temporada, :baja_logica)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':codigo_usuario', $this->codigo_usuario, PDO::PARAM_INT);
        $consulta->bindValue(':activo_temporada', 1, PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Usuarios where baja_logica = 0");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Usuarios WHERE id = :id and baja_logica = 0");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        //$consulta->bindValue(':baja_logica', 0, PDO::PARAM_BOOL);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public static function modificarUsuario(Usuario $usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Usuarios SET nombre = :nombre, apellido = :apellido, clave = :clave, codigo_usuario = :codigo_usuario, activo_temporada = :activo_temporada WHERE id = :id");
        $claveHash = password_hash($usuario->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':nombre', $usuario->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $usuario->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->bindValue(':codigo_usuario', $usuario->codigo_usuario, PDO::PARAM_INT);
        $consulta->bindValue(':activo_temporada', $usuario->activo_temporada, PDO::PARAM_INT);
        $consulta->bindValue(':id', $usuario->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarUsuario($id)
    {
        //Se realiza borrado logico
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Usuarios SET baja_logica = 1 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function RevertirBajaTemporada($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Usuarios SET activo_temporada = 1 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}