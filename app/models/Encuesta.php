<?php

class Encuesta
{
    public $id;
    public $id_mesa;
    public $codigo_cliente;
    public $puntuacion_descripcion;
    public $puntuacion_mesa;
    public $puntuacion_restaurante;
    public $puntuacion_mozo;
    public $puntuacion_cocinero;
    public $fecha_hora;

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Encuesta (puntuacion_descripcion, puntuacion_mozo, puntuacion_mesa, puntuacion_cocinero,
        puntuacion_restaurante,id_mesa, codigo_cliente,fecha_hora) VALUES (:puntuacion_descripcion, :puntuacion_mozo, :puntuacion_mesa, :puntuacion_cocinero,
        :puntuacion_restaurante, :id_mesa, :codigo_cliente, :fecha_hora)");
        $consulta->bindValue(':puntuacion_descripcion', $this->puntuacion_descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':puntuacion_mozo', $this->puntuacion_mozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacion_mesa', $this->puntuacion_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacion_cocinero', $this->puntuacion_cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacion_restaurante', $this->puntuacion_restaurante, PDO::PARAM_INT);
        $consulta->bindValue(':codigo_cliente', $this->codigo_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
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