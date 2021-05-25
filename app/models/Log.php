<?php

class Log{
    public static function Add($marcaTemporal, $log){
        //Al crear la mesa, se crea con estado Con Cliente Esperando Pedido
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO Logs (id, logs, fecha_hora) VALUES (:id, :logs, :fecha_hora)");
        $consulta->bindValue(':id', $marcaTemporal, PDO::PARAM_INT);
        $consulta->bindValue(':logs', $log, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_hora', date('Y-m-d H:i'), PDO::PARAM_STR);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }
}

?>