<?php

class Encuesta
{
    public $id;
    public $puntuacion_descripcion;
    public $puntuacion_numero;
    public $codigo_cliente;
    public $fecha_hora;
    public $baja_logica;

    public function crearEncuesta()
    {
        //Al crear la mesa, se crea con estado Con Cliente Esperando Pedido
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Encuesta (puntuacion_descripcion, puntuacion_numero, codigo_cliente,fecha_hora, baja_logica) VALUES (:puntuacion_descripcion, :puntuacion_numero, :codigo_cliente,:fecha_hora, :baja_logica)");
        $consulta->bindValue(':puntuacion_descripcion', $this->puntuacion_descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':puntuacion_numero', $this->puntuacion_numero, PDO::PARAM_INT);
        $consulta->bindValue(':codigo_cliente', $this->codigo_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_hora', date('Y-m-d H:i'), PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Encuesta where baja_logica = 0");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerUno($id_encuesta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Encuesta WHERE id = :id and baja_logica = 0");
        $consulta->bindValue(':id', $id_encuesta, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Encuesta');
    }

    public static function modificarEncuesta($id_encuesta, $puntuacion_descripcion, $puntuacion_numero)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Encuesta SET puntuacion_descripcion = :puntuacion_descripcion, puntuacion_numero = :puntuacion_numero WHERE id = :id and baja_logica = 0");
        $consulta->bindValue(':puntuacion_descripcion', $puntuacion_descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':puntuacion_numero', $puntuacion_numero, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id_encuesta, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarEncuesta($id_encuesta)
    {
        //Borrado logico de la mesa
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Encuesta SET baja_logica = 1 WHERE id = :id");
        $consulta->bindValue(':id', $id_encuesta, PDO::PARAM_STR);
        $consulta->execute();
    }    
}