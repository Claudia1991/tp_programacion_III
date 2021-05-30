<?php

class Encuesta
{
    public $id;
    public $puntuacion_descripcion;
    public $puntuacion_numero;
    public $codigo_cliente;
    public $fecha_hora;

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Encuesta (puntuacion_descripcion, puntuacion_numero, codigo_cliente,fecha_hora) VALUES (:puntuacion_descripcion, :puntuacion_numero, :codigo_cliente,:fecha_hora)");
        $consulta->bindValue(':puntuacion_descripcion', $this->puntuacion_descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':puntuacion_numero', $this->puntuacion_numero, PDO::PARAM_INT);
        $consulta->bindValue(':codigo_cliente', $this->codigo_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_hora', date('Y-m-d H:i'), PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Encuesta");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerUno($id_encuesta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Encuesta WHERE id = :id");
        $consulta->bindValue(':id', $id_encuesta, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }   
}