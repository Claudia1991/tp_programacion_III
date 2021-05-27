<?php

class Pedido
{
    //manejo todos los pedidos por baja logica, en las consultas
    public $id;
    public $id_estado;
    public $estado_descripcion;
    public $id_producto;
    public $producto_descripcion;
    public $id_sector;
    public $sector_descripcion;
    public $fecha_hora;
    public $codigo_cliente;
    public $cantidad;
    public $baja_logica;

    public function crearPedido()
    {
        //Los pedidos se crean con estado pendientes y baja_logica = 0
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Pedidos (cantidad, codigo_cliente, fecha_hora, id_estado, id_producto, id_sector, baja_logica) VALUES (:cantidad, :codigo_cliente, :fecha_hora, :id_estado, :id_producto, :id_sector, :baja_logica)");
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':codigo_cliente', $this->codigo_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_hora', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->bindValue(':id_estado',1, PDO::PARAM_INT);
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':id_sector', $this->id_sector, PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.codigo_cliente,ep.codigo as id_estado, ep.descripcion as estado_descripcion, pr.id as id_producto, pr.nombre as producto_descripcion, p.id_sector, sr.descripcion as sector_descripcion, p.fecha_hora, p.cantidad, p.baja_logica FROM Pedidos p
        INNER JOIN Estados_Pedidos ep on ep.codigo = p.id_estado
        INNER JOIN Productos pr on pr.id = p.id_producto
        INNER JOIN Sectores_Restaurant sr on sr.codigo = p.id_sector
        where p.baja_logica = :baja_logica");
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerSegunId($id_pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.codigo_cliente,ep.codigo as id_estado, ep.descripcion as estado_descripcion, pr.id as id_producto, pr.nombre as producto_descripcion, p.id_sector, sr.descripcion as sector_descripcion, p.fecha_hora, p.cantidad, p.baja_logica FROM Pedidos p
        INNER JOIN Estados_Pedidos ep on ep.codigo = p.id_estado
        INNER JOIN Productos pr on pr.id = p.id_producto
        INNER JOIN Sectores_Restaurant sr on sr.codigo = p.id_sector
        where p.id = :id and p.baja_logica = :baja_logica");
        // $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos where id = :id and (:id_sector is null or id_sector = :id_sector) 
        // and baja_logica = :baja_logica");
        $consulta->bindValue(':id', $id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerSegunIdySector($id_pedido, $id_sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.codigo_cliente,ep.codigo as id_estado, ep.descripcion as estado_descripcion, pr.id as id_producto, pr.nombre as producto_descripcion, p.id_sector, sr.descripcion as sector_descripcion, p.fecha_hora, p.cantidad, p.baja_logica FROM Pedidos p
        INNER JOIN Estados_Pedidos ep on ep.codigo = p.id_estado
        INNER JOIN Productos pr on pr.id = p.id_producto
        INNER JOIN Sectores_Restaurant sr on sr.codigo = p.id_sector
        where p.id = :id and p.id_sector = :id_sector and p.baja_logica = :baja_logica");
        // $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos where id = :id and (:id_sector is null or id_sector = :id_sector) 
        // and baja_logica = :baja_logica");
        $consulta->bindValue(':id', $id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':id_sector', $id_sector, PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerSegunEstadoYSector($estado, $sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos where id_estado = :id_estado and id_sector = :id_sector and baja_logica = 0");
        $consulta->bindValue(':id_estado', $estado, PDO::PARAM_INT);
        $consulta->bindValue(':id_sector', $sector, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerSegunSector($sector)
    {
        var_dump($sector);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.codigo_cliente,ep.codigo as id_estado, ep.descripcion as estado_descripcion, pr.id as id_producto, pr.nombre as producto_descripcion, p.id_sector, sr.descripcion as sector_descripcion, p.fecha_hora, p.cantidad, p.baja_logica FROM Pedidos p
        INNER JOIN Estados_Pedidos ep on ep.codigo = p.id_estado
        INNER JOIN Productos pr on pr.id = p.id_producto
        INNER JOIN Sectores_Restaurant sr on sr.codigo = p.id_sector
        where p.id_sector = :id_sector and p.baja_logica = :baja_logica");
        $consulta->bindValue(':id_sector', $sector, PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerSegunEstadoYCliente($codigo_cliente, $estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos where codigo_cliente = :codigo_cliente and id_estado = :id_estado and baja_logica = 0");
        $consulta->bindValue(':codigo_cliente', $codigo_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado', $estado, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedidoSegunCliente($codigo_cliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM Pedidos WHERE codigo_cliente = :codigo_cliente and baja_logica = 0");
        $consulta->bindValue(':codico_cliente', $codigo_cliente, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function modificarEstadoPedido($id_pedido, $estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedidos SET id_estado = :id_estado, fecha_hora = :fecha_hora WHERE id = :id and baja_logica = 0");
        $consulta->bindValue(':id_estado', $estado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_hora', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarPedido($id)
    {
        //Baja Logica
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedidos SET baja_logica = 1 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}