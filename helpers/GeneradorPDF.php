<?php


namespace Helpers;

use TCPDF;

class GeneradorPDF {

    private $uploadDir;

    public function __construct($uploadDir = 'uploads/') {
        $this->uploadDir = rtrim($uploadDir, '/') . '/'; // Asegura que haya una barra al final
    }


    
    private function crearPDF($titulo, $contenido, $nombreArchivo) {
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('Helvetica', '', 12);

        // Título
        $pdf->Cell(0, 10, $titulo, 0, 1, 'C');
        $pdf->Ln(10); // Salto de línea

        // Contenido
        $pdf->MultiCell(0, 10, $contenido, 0, 'L');

        // Ruta del archivo
        $filePath = $this->uploadDir . $nombreArchivo . '.pdf';

        // Guardar el PDF en el servidor
        $pdf->Output($filePath, 'F');

        return $filePath;
    }



    public function generarBoletaPago($nombreArchivo, $clave, $empleado, $empresa, $salario_base, $bonos, $descuentos) {
        $total_pago = $salario_base + $bonos - $descuentos;
        $fecha_emision = date("d/m/Y");

        $contenido = "Empresa: $empresa\n"
            . "Empleado: $empleado\n"
            . "Clave: $clave\n"
            . "Fecha de emisión: $fecha_emision\n\n"
            . "Salario Base: $" . number_format($salario_base, 2) . "\n"
            . "Bonos: $" . number_format($bonos, 2) . "\n"
            . "Descuentos: -$" . number_format($descuentos, 2) . "\n\n"
            . "Total a Pagar: $" . number_format($total_pago, 2);

        return $this->crearPDF("BOLETA DE PAGO", $contenido, $nombreArchivo);
    }




    public function generarConstanciaLaboral($nombreArchivo, $empleado, $empresa, $cargo, $fecha_inicio, $fecha_emision = null) {
        $fecha_emision = $fecha_emision ?? date("d/m/Y");

        $contenido = "Por medio de la presente, se hace constar que el Sr(a). $empleado, "
            . "con documento de identidad XXXXXXXX, labora en nuestra empresa $empresa "
            . "desempeñando el cargo de $cargo desde el $fecha_inicio.\n\n"
            . "La presente constancia se emite a solicitud del interesado(a) para los fines que considere convenientes.\n\n"
            . "Fecha de emisión: $fecha_emision";

        return $this->crearPDF("CONSTANCIA DE TRABAJO", $contenido, $nombreArchivo);
    }



    public function generarConstanciaSalarial($nombreArchivo, $empleado, $empresa, $salario_base, $bonos, $descuentos, $fecha_emision = null) {
        $fecha_emision = $fecha_emision ?? date("d/m/Y");
        $total_pago = $salario_base + $bonos - $descuentos;

        $contenido = "Por medio de la presente, se hace constar que el Sr(a). $empleado, "
            . "con documento de identidad XXXXXXXX, labora en nuestra empresa $empresa "
            . "y recibe un salario mensual de la siguiente manera:\n\n"
            . "Salario Base: $" . number_format($salario_base, 2) . "\n"
            . "Bonos: $" . number_format($bonos, 2) . "\n"
            . "Descuentos: -$" . number_format($descuentos, 2) . "\n\n"
            . "Total Salarial: $" . number_format($total_pago, 2) . "\n\n"
            . "Fecha de emisión: $fecha_emision";

        return $this->crearPDF("CONSTANCIA SALARIAL", $contenido, $nombreArchivo);
    }
}
