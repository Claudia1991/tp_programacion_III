<?php

class Mesa
{
    public $id;
    public $codigo_mesa;
    public $nombre_cliente;
    public $total_consumicion;
    public $codigo_estado_mesa;
    public $codigo_estado_mesa_descripcion;
    public $fecha_hora_inicio;
    public $fecha_hora_fin;
    public $baja_logica;

    public function crearMesa()
    {
        //Al crear la mesa, se crea con estado Con Cliente Esperando Pedido
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Mesas (nombre_cliente, codigo_mesa, codigo_estado_mesa, fecha_hora_inicio, fecha_hora_fin, baja_logica) VALUES (:nombre_cliente, :codigo_mesa, :codigo_estado_mesa, :fecha_hora_inicio, :fecha_hora_fin, :baja_logica)");
        $consulta->bindValue(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':codigo_mesa', $this->CrearCodigoMesa(), PDO::PARAM_STR);
        $consulta->bindValue(':codigo_estado_mesa', 1, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_hora_inicio', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->bindValue(':fecha_hora_fin', null);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Mesas where baja_logica = 0");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesaSegunCodigoCliente($codigo_mesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Mesas WHERE codigo_mesa = :codigo_mesa and baja_logica = 0");
        $consulta->bindValue(':codigo_mesa', $codigo_mesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa($codigo_mesa, $codigo_estado_mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Mesas SET codigo_estado_mesa = :codigo_estado_mesa WHERE codigo_mesa = :codigo_mesa and baja_logica = 0");
        $consulta->bindValue(':codigo_mesa', $codigo_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':codigo_estado_mesa', $codigo_estado_mesa, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarMesa($codigo_mesa)
    {
        //Borrado logico de la mesa
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Mesas SET baja_logica = 1 WHERE codigo_mesa = :codigo_mesa");
        $consulta->bindValue(':codigo_mesa', $codigo_mesa, PDO::PARAM_STR);
        $consulta->execute();
    }

    private static function CrearCodigoMesa(){
        $numero = rand(1, 1000);
        return 'AB' . $numero + 1000;
    }
}