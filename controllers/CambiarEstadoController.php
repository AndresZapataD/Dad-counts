<?php
require_once '../models/Database.php';
require_once '../models/Venta.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ventaId = $_POST['id'];
    $nuevoEstado = $_POST['estado'];

    // Actualizar el estado de la venta
    $resultado = Venta::actualizarEstadoVenta($ventaId, $nuevoEstado);

    if ($resultado) {
        echo json_encode(['status' => 'success', 'message' => 'Estado actualizado con éxito']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el estado']);
    }
}
?>
