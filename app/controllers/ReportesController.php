<?php

require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Usuario.php';
require_once './servicio/PdfServicio.php';

class ReportesController{
    public function ReportesEmpleados($request, $response, $args){
        /**a- Los días y horarios que se Ingresaron al sistema.
            b- Cantidad de operaciones de todos por sector.
            c- Cantidad de operaciones de todos por sector, listada por cada empleado.
            d- Cantidad de operaciones de cada uno por separado. */
            $estadistica = filter_var($args["estadistica"], FILTER_VALIDATE_BOOLEAN);
            $fecha_inicio = $args["inicio"];
            $fecha_fin = $args["fin"];
            $payload = '';
            if(!isset($estadistica) || !isset($fecha_inicio) || !isset($fecha_fin)){
                $payload = json_encode(array("error" => "Error en los parametros ingresados para obtener reportes de mesas."));
                $response = $response->withStatus(400);
            }else{
                if($estadistica){
                    //TODO: ESTADISTICAS 30 DIAS (desde hoy 30 dias hacia atras)
                    //LOGICA PARA TRAER LAS FECHAS
                }else{
                    //TODO: LOGICA VALIDAR FECHAS
                    ob_start();
                    $pdf = new PdfServicio();
                    $pdf->SetTitle("Reportes Mesas");
                    $pdf->Output();
                    ob_end_flush();
                    $payload = json_encode(array("mensaje" => "Descargado"));
                    $response->getBody()->write($payload);
                    return $response->withHeader('Content-Type', 'application/pdf');
                }
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');

    }
    public function ReportesPedidos($request, $response, $args){
        /**a- Lo que más se vendió.
            b- Lo que menos se vendió.
            c- Los que no se entregaron en el tiempo estipulado.
            d- Los cancelados. */
        $estadistica = filter_var($args["estadistica"], FILTER_VALIDATE_BOOLEAN);
        $fecha_inicio = $args["inicio"];
        $fecha_fin = $args["fin"];
        $payload = '';
        if(!isset($estadistica)){
            $payload = json_encode(array("error" => "Error en los parametros ingresados para obtener reportes de mesas."));
            $response = $response->withStatus(400);
        }else{
            if(!$estadistica && (!$this->validateDate($fecha_inicio) || !$this->validateDate($fecha_fin))){
                var_dump($this->validateDate($fecha_inicio));
                var_dump($fecha_inicio);
                $payload = json_encode(array("error" => "Error en las fechas ingresadas sin estadisticas."));
                $response = $response->withStatus(400);
            }else{
                ob_clean();
                ob_start();
                $pdf = new PdfServicio();
                if($estadistica){
                    $fecha_fin = date('Y-m-d');
                    $fecha_inicio = date('Y-m-d', strtotime($fecha_fin . ' -30 days')); 
                }
                $pdf->SetTitle("Reportes Pedidos");
                $pdf->AddPage();
                $pdf->Cell(40,10,'Lo que mas se vendio: ' . Mesa::masUsada($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Cell(40,10,'Lo que menos se vendio: ' . Mesa::menosUsada($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Cell(40,10,'Id mesa mas facturo: ' . Mesa::mayorFacturacion($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Cell(40,10,'Los cancelados: ' . Mesa::menorFacturacion($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Output();
                ob_end_flush();
                $payload = json_encode(array("mensaje" => "Descargado"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/pdf');
            }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    public function ReportesMesas($request, $response, $args){
        /**a- La más usada.
            b- La menos usada.
            c- La que más facturó.
            d- La que menos facturó.
            e- La/s que tuvo la factura con el mayor importe.
            f- La/s que tuvo la factura con el menor importe.
            g- Lo que facturó entre dos fechas dadas. //TODO
            h- Mejores comentarios.
            i- Peores comentarios.
        */
        $estadistica = filter_var($args["estadistica"], FILTER_VALIDATE_BOOLEAN);
        $fecha_inicio = $args["inicio"];
        $fecha_fin = $args["fin"];
        $payload = '';
        if(!isset($estadistica)){
            $payload = json_encode(array("error" => "Error en los parametros ingresados para obtener reportes de mesas."));
            $response = $response->withStatus(400);
        }else{
            if(!$estadistica && (!$this->validateDate($fecha_inicio) || !$this->validateDate($fecha_fin))){
                var_dump($this->validateDate($fecha_inicio));
                var_dump($fecha_inicio);
                $payload = json_encode(array("error" => "Error en las fechas ingresadas sin estadisticas."));
                $response = $response->withStatus(400);
            }else{
                ob_clean();
                ob_start();
                $pdf = new PdfServicio();
                if($estadistica){
                    $fecha_fin = date('Y-m-d');
                    $fecha_inicio = date('Y-m-d', strtotime($fecha_fin . ' -30 days')); 
                }
                $pdf->SetTitle("Reportes Mesas");
                $pdf->AddPage();
                $pdf->Cell(40,10,'Id mesa mas usada: ' . Mesa::masUsada($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Cell(40,10,'Id mesa menos usada: ' . Mesa::menosUsada($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Cell(40,10,'Id mesa mas facturo: ' . Mesa::mayorFacturacion($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Cell(40,10,'Id mesa menos facturo: ' . Mesa::menorFacturacion($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Cell(40,10,'Id mesa factura mayor importe: ' . Mesa::tieneFacturaMayorImporte($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Cell(40,10,'Id mesa factura menor importe: ' . Mesa::tieneFacturaMenorImporte($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Cell(40,10,'Id mesa mejor comentario: ' . Mesa::tieneMejoresComentarios($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Cell(40,10,'Id mesa peor comentario: ' . Mesa::tienePeoresComentarios($fecha_inicio, $fecha_fin) ,0,1);
                $pdf->Output();
                ob_end_flush();
                $payload = json_encode(array("mensaje" => "Descargado"));
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/pdf');
            }
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}

?>