<?php

class Mesa
{
    public $id;
    public $codigo_cliente;
    public $nombre_cliente;
    public $total_consumicion;
    public $id_mozo;
    public $mozo_nombre;
    public $codigo_estado_mesa;
    public $codigo_estado_mesa_descripcion;
    public $fecha_hora_inicio;
    public $fecha_hora_fin;
    public $libre;
    /**Reportes */
    public $cantidad_uso;

    public function crearMesa($id_mesa, $id_mozo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(" UPDATE Mesas SET codigo_cliente = :codigo_cliente, nombre_cliente = :nombre_cliente,  
        total_consumicion = :total_consumicion, id_mozo = :id_mozo, codigo_estado_mesa = :codigo_estado_mesa,  
        fecha_hora_inicio = :fecha_hora_inicio , libre = :libre WHERE id = :id");
        $codigo_cliente_creada = $this->CrearCodigoCliente();
        $consulta->bindValue(':codigo_cliente', $codigo_cliente_creada, PDO::PARAM_STR);
        $consulta->bindValue(':nombre_cliente', $this->nombre_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':codigo_estado_mesa', 1, PDO::PARAM_INT);
        $consulta->bindValue(':total_consumicion', 0, PDO::PARAM_INT);
        $consulta->bindValue(':id_mozo', $id_mozo, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_hora_inicio', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->bindValue(':libre', 0, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id_mesa, pdo::PARAM_INT);
        $consulta->execute();
        return $codigo_cliente_creada;
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT m.id, m.codigo_cliente, m.nombre_cliente, m.total_consumicion, m.id_mozo, 
        u.nombre as mozo_nombre, m.codigo_estado_mesa, em.descripcion as codigo_estado_mesa_descripcion, m.fecha_hora_inicio, m.fecha_hora_fin   FROM Mesas m 
        left JOIN Usuarios u on u.id = m.id_mozo 
        left join Estados_Mesas em on em.codigo = m.codigo_estado_mesa");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesaSegunCodigoCliente($codigo_cliente)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT m.id, m.codigo_cliente, m.nombre_cliente, m.total_consumicion, m.id_mozo, 
        u.nombre as mozo_nombre, m.codigo_estado_mesa, em.descripcion as codigo_estado_mesa_descripcion, m.fecha_hora_inicio, m.fecha_hora_fin   FROM Mesas m 
        INNER JOIN Usuarios u on u.id = m.id_mozo 
        inner join Estados_Mesas em on em.codigo = m.codigo_estado_mesa
        where codigo_cliente = :codigo_cliente");
        $consulta->bindValue(':codigo_cliente', $codigo_cliente, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa($codigo_cliente, $codigo_estado_mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Mesas SET codigo_estado_mesa = :codigo_estado_mesa WHERE codigo_cliente = :codigo_cliente ");
        $consulta->bindValue(':codigo_cliente', $codigo_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':codigo_estado_mesa', $codigo_estado_mesa, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarMesa($id_mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Mesas SET codigo_cliente = :codigo_cliente, nombre_cliente = :nombre_cliente,
         total_consumicion = :total_consumicion, id_mozo = :id_mozo, codigo_estado_mesa = :codigo_estado_mesa, fecha_hora_inicio = :fecha_hora_inicio
         fecha_hora_fin = :fecha_hora_fin WHERE id = :id_mesa");
        $consulta->bindValue(':codigo_cliente', null);
        $consulta->bindValue(':nombre_cliente', null);
        $consulta->bindValue(':total_consumicion', null);
        $consulta->bindValue(':id_mozo', null);
        $consulta->bindValue(':codigo_estado_mesa', null);
        $consulta->bindValue(':fecha_hora_inicio', null);
        $consulta->bindValue(':fecha_hora_fin', null);
        $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function obtenerPrimeraMesaLibre(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id FROM Mesas where libre = :libre limit 1");
        $consulta->bindValue(':libre', 1, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }

    public static function pasarFacturacionHistoricos($id_mesa, $codigo_cliente, $total_consumicion, $id_mozo){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Historico_Facturacion_Mesas (id_mesa, codigo_cliente, total_consumicion, 
        fecha_hora, id_mozo) values (:id_mesa, :codigo_cliente, :total_consumicion,  :fecha_hora, :id_mozo) ");
        $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigo_cliente', $codigo_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':total_consumicion', $total_consumicion, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_hora', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->bindValue(':id_mozo', $id_mozo, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function actualizarConsumicionMesa($id_mesa, $codigo_cliente, $total_consumicion)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Mesas SET total_consumicion = :total_consumicion WHERE id = :id_mesa and codigo_cliente = :codigo_cliente");
        $consulta->bindValue(':codigo_cliente', $codigo_cliente, PDO::PARAM_STR);
        $consulta->bindValue(':total_consumicion', $total_consumicion, PDO::PARAM_STR);
        $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $consulta->execute();
    }

    /**
     * REPORTES
     */

    public static function usabilidadMesas($fecha_inicio, $fecha_fin){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT COUNT(id_mesa) AS cantidad_uso , id_mesa AS id FROM Historico_Facturacion_Mesas WHERE cast(fecha_hora AS date) BETWEEN :fecha_inicio AND :fecha_fin GROUP BY id_mesa");
        $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function facturacionMesas($fecha_inicio, $fecha_fin){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT SUM(total_consumicion) AS total_consumicion , id_mesa AS id FROM Historico_Facturacion_Mesas WHERE cast(fecha_hora AS date) BETWEEN :fecha_inicio AND :fecha_fin GROUP BY id_mesa");
        $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

   public static function tieneFacturaMenorImporte($fecha_inicio, $fecha_fin){
    $objAccesoDato = AccesoDatos::obtenerInstancia();
    $consulta = $objAccesoDato->prepararConsulta("SELECT MIN(total_consumicion) AS total_consumicion , id_mesa as id FROM Historico_Facturacion_Mesas WHERE cast(fecha_hora AS date) BETWEEN :fecha_inicio AND :fecha_fin GROUP BY id_mesa");
    $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
    $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function tieneFacturaMayorImporte($fecha_inicio, $fecha_fin){
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT MAX(total_consumicion) AS total_consumicion , id_mesa as id FROM Historico_Facturacion_Mesas WHERE cast(fecha_hora AS date) BETWEEN :fecha_inicio AND :fecha_fin GROUP BY id_mesa");
        $consulta->bindValue(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_fin', $fecha_fin, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        }

    public static function tieneMejoresComentarios($fecha_inicio, $fecha_fin){ 
    
    }
    
    public static function tienePeoresComentarios($fecha_inicio, $fecha_fin){ 
    
    }

    private static function CrearCodigoCliente() : string{
        $numero = rand(1, 1000);
        return 'C' . $numero + 1000;
    }
}