<?php
use mikehaertl\wkhtmlto\Pdf;
require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Usuario.php';


class ReportesController{
    public function ReportesEmpleados($request, $response, $args){

    }
    public function ReportesPedidos($request, $response, $args){

    }
    public function ReportesMesas($request, $response, $args){
        /**a- La más usada.
            b- La menos usada.
            c- La que más facturó.
            d- La que menos facturó.
            e- La/s que tuvo la factura con el mayor importe.
            f- La/s que tuvo la factura con el menor importe.
            g- Lo que facturó entre dos fechas dadas.
            h- Mejores comentarios.
            i- Peores comentarios.
        */
        $estadistica = filter_var($args["estadistica"], FILTER_VALIDATE_BOOLEAN);
        $fecha_inicio = $args["inicio"];
        $fecha_fin = $args["fin"];
        $payload = null;
        if(!isset($estadistica) || !isset($fecha_inicio) || !isset($fecha_fin)){
            $payload = json_encode(array("error" => "Error en los parametros ingresados para obtener reportes de mesas."));
            $response = $response->withStatus(400);
        }else{
            if($estadistica){
                //TODO: ESTADISTICAS 30 DIAS (desde hoy 30 dias hacia atras)
                //LOGICA PARA TRAER LAS FECHAS
            }else{
                //TODO: LOGICA VALIDAR FECHAS
                $payload = json_encode(Mesa::usabilidadMesas($fecha_inicio, $fecha_fin));
                $filename = 'Factura-Enero2021.pdf';
                 //TODO: ESTO PARECE QUE FUNCIONA, A LA VIEJA USANSA

                var_dump(file_exists('reportes/' . $filename));
                $pdf = new Pdf('reportes/reporte.html');
                // Save the PDF
                if (!$pdf->saveAs('reportes/report.pdf')) {
                    $error = $pdf->getError();
                    var_dump($error);
                    // ... handle error here
                }

                // // ... or send to client as file download
                // if (!$pdf->send('name.pdf', false, array('Content-Length' => false,))) {
                //     $error = $pdf->getError();
                //     var_dump($error);

                //     // ... handle error here
                // }
                // var_dump($pdf->toString());
                header('Content-type: application/json');
                header('Content-Disposition: inline; filename="' . $filename . '"');
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');
                readfile('reportes/' . $filename);
            }
        }
        $response->getBody()->write($payload);
        //$response->withHeader('Content-Disposition', 'attachment;filename="Prueba.pdf"');
        //$response->withHeader('Pragma', 'Public');
        //$response->withHeader('Content-Transfer-Encoding', 'binary');
        //$response->withHeader('Content-Length', strlen($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}

?>