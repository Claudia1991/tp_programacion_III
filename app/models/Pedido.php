<?php

class Pedido
{
    public $id;
    public $id_estado;
    public $id_producto;
    public $id_sector;
    public $obsercacion;
    public $fecha_hora;
    public $codigo_cliente;
    public $cantidad;

    public function crearPedido()
    {
        //Los pedidos se crean con estado pendientes
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Pedidos (cantidad, codigo_cliente, fecha_hora, id_estado, id_producto, id_sector, observacion) VALUES (:cantidad, :codigo_cliente, :fecha_hora, :id_estado, :id_producto, :id_sector, :observacion)");
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':codigo_cliente', $this->codigo_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_hora', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->bindValue(':id_estado',1, PDO::PARAM_INT);
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':id_sector', $this->id_sector, PDO::PARAM_INT);
        $consulta->bindValue(':observacion', $this->observacion, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerSegunEstado($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos where id_estado = :id_estado");
        $consulta->bindValue(':id_estado', $estado, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerSegunEstadoYCliente($codigo_cliente, $estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos where codigo_cliente = :codigo_cliente and id_estado = :id_estado");
        $consulta->bindValue(':codigo_cliente', $codigo_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado', $estado, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedidoSegunCliente($codigo_cliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos WHERE codigo_cliente = :codigo_cliente");
        $consulta->bindValue(':codico_cliente', $codigo_cliente, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function modificarEstadoPedido($id_pedido, $estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedidos SET id_estado = :id_estado, fecha_hora = :fecha_hora WHERE id = :id");
        $consulta->bindValue(':id_estado', $estado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_hora', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM Pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}