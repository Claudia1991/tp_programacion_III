<?php

class Pedido
{
    public $id;
    public $id_mesa;
    public $id_estado;
    public $estado_descripcion;
    public $id_producto;
    public $producto_descripcion;
    public $id_sector;
    public $sector_descripcion;
    public $id_empleado;
    public $empleado_descripcion;
    public $minutos_preparacion;
    public $fecha_hora_inicio;
    public $fecha_hora_fin;
    public $codigo_cliente;
    public $cantidad;
    public $baja_logica;

    public function crearPedido()
    {
        //Los pedidos se crean con estado pendientes y baja_logica = 0, fecha_hora_inicio/fin en nulos, estos valores los
        //cambias los empleados de cada sector, 
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Pedidos (cantidad,id_mesa, codigo_cliente, id_estado, id_producto, id_sector, baja_logica) VALUES (:cantidad,:id_mesa, :codigo_cliente, :id_estado, :id_producto, :id_sector, :baja_logica)");
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigo_cliente', $this->codigo_cliente, PDO::PARAM_STR);
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.id_mesa, p.codigo_cliente,ep.codigo as id_estado, ep.descripcion as estado_descripcion,
         pr.id as id_producto, pr.nombre as producto_descripcion, p.id_sector, sr.descripcion as sector_descripcion, 
        p.id_empleado, u.nombre as empleado_descripcion , p.minutos_preparacion, p.fecha_hora_inicio, p.fecha_hora_fin, p.cantidad, p.baja_logica 
        FROM Pedidos p
        left JOIN Estados_Pedidos ep on ep.codigo = p.id_estado
        left JOIN Productos pr on pr.id = p.id_producto
        left JOIN Sectores_Restaurant sr on sr.codigo =  p.id_sector
        left JOIN Usuarios u on u.id = p.id_empleado
        where p.baja_logica = :baja_logica");
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerSegunId($id_pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.id_mesa, p.codigo_cliente,ep.codigo as id_estado, 
        ep.descripcion as estado_descripcion,
        pr.id as id_producto, pr.nombre as producto_descripcion, p.id_sector, sr.descripcion as sector_descripcion, 
       p.id_empleado, u.nombre as empleado_descripcion , p.minutos_preparacion, p.fecha_hora_inicio, p.fecha_hora_fin, p.cantidad, 
       p.baja_logica FROM Pedidos p
        INNER JOIN Estados_Pedidos ep on ep.codigo = p.id_estado
        INNER JOIN Productos pr on pr.id = p.id_producto
        left JOIN Sectores_Restaurant sr on sr.codigo =  p.id_sector
        left JOIN Usuarios u on u.id = p.id_empleado
        where p.id = :id and p.baja_logica = :baja_logica");
        $consulta->bindValue(':id', $id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerSegunIdySector($id_pedido, $id_sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.id_mesa, p.codigo_cliente,ep.codigo as id_estado, ep.descripcion as estado_descripcion,
        pr.id as id_producto, pr.nombre as producto_descripcion, p.id_sector, sr.descripcion as sector_descripcion, 
       p.id_empleado, u.nombre as empleado_descripcion , p.minutos_preparacion, p.fecha_hora_inicio, p.fecha_hora_fin, p.cantidad, p.baja_logica FROM Pedidos p
        INNER JOIN Estados_Pedidos ep on ep.codigo = p.id_estado
        INNER JOIN Productos pr on pr.id = p.id_producto
        left JOIN Sectores_Restaurant sr on sr.codigo =  p.id_sector
        left JOIN Usuarios u on u.id = p.id_empleado
        where p.id = :id and p.id_sector = :id_sector and p.baja_logica = :baja_logica and p.id_estado <> 4");
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
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT p.id, p.id_mesa, p.codigo_cliente,ep.codigo as id_estado, ep.descripcion as estado_descripcion,
        pr.id as id_producto, pr.nombre as producto_descripcion, p.id_sector, sr.descripcion as sector_descripcion, 
       p.id_empleado, u.nombre as empleado_descripcion , p.minutos_preparacion, p.fecha_hora_inicio, p.fecha_hora_fin, p.cantidad, p.baja_logica FROM Pedidos p
        INNER JOIN Estados_Pedidos ep on ep.codigo = p.id_estado
        INNER JOIN Productos pr on pr.id = p.id_producto
        left JOIN Sectores_Restaurant sr on sr.codigo =  p.id_sector
        left JOIN Usuarios u on u.id = p.id_empleado
        where p.id_sector = :id_sector and p.baja_logica = :baja_logica and p.id_estado <> 4");
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

    public static function tomarPedido($id_pedido, $tiempo_preparacion, $id_empleado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedidos SET id_estado = :id_estado, fecha_hora_inicio = :fecha_hora_inicio, 
        id_empleado = :id_empleado, minutos_preparacion = :minutos_preparacion
         WHERE id = :id and baja_logica = :baja_logica");
        $consulta->bindValue(':id_estado', 2, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':id_empleado', $id_empleado, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_hora_inicio', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->bindValue(':minutos_preparacion', $tiempo_preparacion, PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function entregarPedido($id_pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedidos SET id_estado = :id_estado, fecha_hora_fin = :fecha_hora_fin
         WHERE id = :id and baja_logica = :baja_logica");
        $consulta->bindValue(':id_estado',3, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_hora_fin', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function cancelarPedido($id_pedido, $id_usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedidos SET id_estado = :id_estado, fecha_hora_fin = :fecha_hora_fin, 
        id_empleado = :id_usuario
         WHERE id = :id and baja_logica = :baja_logica");
        $consulta->bindValue(':id_estado',4, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_hora_fin', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function volverAPendientePedido($id_pedido)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedidos SET id_estado = :id_estado, fecha_hora_fin = :fecha_hora_fin
         WHERE id = :id and baja_logica = :baja_logica");
        $consulta->bindValue(':id_estado',1, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id_pedido, PDO::PARAM_INT);
        $consulta->bindValue(':baja_logica', 0, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_hora_fin', date('Y-m-d H:i'), PDO::PARAM_STR);
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

    public static function borrarPedidos($id_mesa, $codigo_cliente)
    {
        //Baja Logica
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Pedidos SET baja_logica = 1 WHERE id_mesa = :id_mesa and codigo_cliente = :codigo_cliente");
        $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigo_cliente', $codigo_cliente, PDO::PARAM_INT);
        $consulta->execute();
    }

    /** Reportes */
    
    public static function masVendido($fecha_inicio, $fecha_fin)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(cantidad) as cantidad_vendida, id_producto, p.nombre FROM Pedidos pp
        inner join Productos p on p.id = pp.id_producto
        where cast(fecha_hora_fin AS date) BETWEEN :fecha_inicio AND :fecha_fin and id_estado <> 4 and id_estado = 3 and baja_logica = 1
        GROUP by id_producto 
        ORDER by cantidad_vendida DESC LIMIT 1");
        $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $consulta->execute();
        $masvendido = $consulta->fetch();
        return 'Id producto: ' . $masvendido['id_producto'] . ' Nombre: ' . $masvendido['nombre'] . 
        ' Cantidad Vendida: ' .$masvendido['cantidad_vendida'];
    }
    
    public static function menosVendido($fecha_inicio, $fecha_fin)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(cantidad) as cantidad_vendida, id_producto, p.nombre FROM Pedidos pp
        inner join Productos p on p.id = pp.id_producto
        where cast(fecha_hora_fin AS date) BETWEEN :fecha_inicio AND :fecha_fin and id_estado <> 4 and id_estado = 3 and baja_logica = 1
        GROUP by id_producto 
        ORDER by cantidad_vendida asc LIMIT 1");
        $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $consulta->execute();
        $masvendido = $consulta->fetch();
        return 'Id producto: ' . $masvendido['id_producto'] . ' Nombre: ' . $masvendido['nombre'] . 
        ' Cantidad Vendida: ' .$masvendido['cantidad_vendida'];
    }

    public static function entregadosFueraTiempo($fecha_inicio, $fecha_fin)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT count(id_producto) as entregados_fuera_tiempo, id_producto, p.nombre
        FROM Pedidos pp
        inner join Productos p on p.id = pp.id_producto
        where cast(fecha_hora_fin AS date) BETWEEN :fecha_inicio AND :fecha_fin and id_estado = 3 and baja_logica = 1
        and ROUND(TIME_TO_SEC(TIMEDIFF(fecha_hora_fin, fecha_hora_inicio))/60) > minutos
        GROUP by id_producto 
        ORDER by id_producto asc");
        $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $consulta->execute();
        $entregadosFueraTiempo = $consulta->fetch();
        return $entregadosFueraTiempo; //TODO
    }
    
    public static function cancelados($fecha_inicio, $fecha_fin)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT count(id_producto) as cancelados, id_producto, p.nombre FROM Pedidos pp
        inner join Productos p on p.id = pp.id_producto
        where cast(fecha_hora_fin AS date) BETWEEN :fecha_inicio AND :fecha_fin and id_estado = 4 and baja_logica = 1
        GROUP by id_producto 
        ORDER by id_producto asc");
        $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $consulta->execute();
        $masvendido = $consulta->fetch();
        return $masvendido; //TODO
    }
}