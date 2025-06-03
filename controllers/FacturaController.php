<?php
require_once '../models/Venta.php';
require_once '../vendor/autoload.php'; // Incluye DomPDF

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['id'])) {
    $venta_id = $_GET['id'];
    $productos = Venta::obtenerDetallesVenta($venta_id);
    $venta = Venta::obtenerVentaPorId($venta_id); // Obtener datos de la venta, incluyendo información del cliente

    // Crear instancia de Dompdf con opciones
    $options = new Options();
    $options->set('defaultFont', 'Courier');
    $dompdf = new Dompdf($options);

    // Generar contenido HTML para el PDF
    $html = "
    <h1>Factura de Venta</h1>
    <h2>Detalles del Cliente</h2>
    <p><strong>Cliente:</strong> {$venta['nombre_cliente']}</p>
    <p><strong>Zona:</strong> {$venta['zona']}</p>
    <p><strong>Fecha:</strong> {$venta['fecha']}</p>

    <h2>Productos</h2>
    <table border='1' cellpadding='5' cellspacing='0' width='100%'>
        <thead>
            <tr>
                <th>Nombre del Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>";

    foreach ($productos as $producto) {
        $html .= "
            <tr>
                <td>{$producto['nombre']}</td>
                <td>{$producto['cantidad']}</td>
                <td>{$producto['precio_unitario']}</td>
                <td>{$producto['total']}</td>
            </tr>";
    }

    $html .= "</tbody></table>";
    $html .= "<p><strong>Total de la Venta:</strong> {$venta['total']}</p>";

    // Configurar contenido HTML en Dompdf
    $dompdf->loadHtml($html);

    // Renderizar el PDF
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Mostrar el PDF en el navegador
    $dompdf->stream("Factura_Venta_$venta_id.pdf", ["Attachment" => false]);
} else {
    echo "No se proporcionó el ID de la venta.";
}
?>
