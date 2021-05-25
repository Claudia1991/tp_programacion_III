<?php

class Validador{
    public static function ValidarCambioEstadoMesa($codigo_tipo_usuario, $estado_mesa){
        //Solo el socio puede pasar el estado de la mesa a cerrada
        $esValido = false;
        if($codigo_tipo_usuario == 5 && $estado_mesa == 4){
            $esValido = true;
        }elseif($codigo_tipo_usuario == 1 && in_array($estado_mesa, self::ObtenerCodigosEstadosMesasSinSocios())){
            $esValido = true;
        }
        return $esValido;
    }

    public static function ValidarCambioEstadoPedido($codigo_tipo_usuario, $estado_pedido, $tipo_pedido){
        //Solo los empleados pueden cambiar de estado los pedidos de su sector, nada de andar chingando otros sectores
        $esValido = false;
        if($codigo_tipo_usuario == $tipo_pedido && in_array($estado_pedido, self::ObtenerCodigosEstadosPedidos())){
            $esValido = true;
        }
        return $esValido;
    }


    private static function ObtenerCodigosEstadosMesasSinSocios(){
        return [1,2,3];
    }

    private static function ObtenerCodigosEstadosPedidos(){
        return [1,2,3];
    }
}


?>