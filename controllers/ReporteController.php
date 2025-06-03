<?php
require_once '../models/Venta.php';
require_once '../vendor/autoload.php'; // Asegúrate de tener DomPDF instalado

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];
    $ventas = Venta::obtenerVentasPorEstado($tipo); // Asegúrate de tener este método en tu modelo Venta

    // Verifica si hay ventas disponibles para generar el reporte
    if (!$ventas) {
        echo "No hay ventas registradas para este estado.";
        exit;
    }

    // Crear instancia de Dompdf con opciones
    $options = new Options();
    $options->set('defaultFont', 'Courier');
    $dompdf = new Dompdf($options);

    // Generar contenido HTML para el PDF
    $html = "
    <h1>Reporte de Ventas</h1>
    <h2>Estado: " . ($tipo ? ucfirst($tipo) : "Todos") . "</h2>
    <table border='1' cellpadding='5' cellspacing='0' width='100%'>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Zona</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>";

    foreach ($ventas as $venta) {
        $html .= "
            <tr>
                <td>{$venta['fecha']}</td>
                <td>{$venta['nombre_cliente']}</td>
                <td>{$venta['zona']}</td>
                <td>{$venta['total']}</td>
                <td>{$venta['estado']}</td>
            </tr>";
    }

    $html .= "</tbody></table>";

    // Configurar contenido HTML en Dompdf
    $dompdf->loadHtml($html);

    // Renderizar el PDF
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    // Mostrar el PDF en el navegador
    $dompdf->stream("Reporte_Ventas.pdf", ["Attachment" => false]);
} else {
    echo "No se proporcionó un tipo de estado para generar el reporte.";
}
?>
