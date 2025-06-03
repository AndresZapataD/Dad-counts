<?php
require_once '../models/Database.php';
require_once '../models/Cliente.php';
require_once '../models/Venta.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if ($action === 'eliminar') {
        $id = $_POST['id'];
        $success = Venta::eliminarVenta($id);
        echo json_encode(['status' => $success ? 'success' : 'error', 'message' => $success ? 'Venta eliminada correctamente' : 'Error al eliminar la venta']);
        exit;
    } 

    $clienteNombre = trim($_POST['cliente'] ?? '');
    $zona = trim($_POST['zona'] ?? '');
    $productos = $_POST['productos'] ?? null;

    if (empty($clienteNombre) || empty($zona) || empty($productos) || !is_array($productos['nombre']) || count($productos['nombre']) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Datos de venta incompletos o inválidos']);
        exit;
    }

    try {
        $cliente = Cliente::buscarClientePorNombre($clienteNombre);
        $cliente_id = $cliente ? $cliente['id'] : Cliente::agregarCliente($clienteNombre, $zona);
        $venta_id = Venta::registrarVenta($cliente_id, $productos);

        if ($venta_id) {
            echo json_encode(['status' => 'success', 'message' => 'Venta registrada con éxito']);
        } else {
            throw new Exception('Error al registrar la venta');
        }

    } catch (Exception $e) {
        error_log("Error en el proceso de registro de venta: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no permitido']);
}
?>
