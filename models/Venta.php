<?php
require_once 'Database.php';

class Venta {
    public static function obtenerVentas() {
        $db = Database::connect();
        $query = $db->prepare("
            SELECT ventas.id, ventas.fecha, ventas.estado, ventas.total, 
                   clientes.nombre AS nombre_cliente, clientes.zona 
            FROM ventas
            JOIN clientes ON ventas.cliente_id = clientes.id
            ORDER BY ventas.id DESC
        ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function obtenerVentaPorId($id) {
        $db = Database::connect();
        $query = $db->prepare("
            SELECT ventas.*, clientes.nombre AS nombre_cliente, clientes.zona 
            FROM ventas
            JOIN clientes ON ventas.cliente_id = clientes.id
            WHERE ventas.id = :id
        ");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    

    public static function registrarVenta($cliente_id, $productos) {
        $db = Database::connect();
        $query = $db->prepare("INSERT INTO ventas (cliente_id, fecha, estado, total) VALUES (:cliente_id, :fecha, 'debe', 0)");
        $fecha = date('Y-m-d');
        $query->bindParam(':cliente_id', $cliente_id);
        $query->bindParam(':fecha', $fecha);
        $query->execute();
        $venta_id = $db->lastInsertId();

        $totalVenta = 0;

        foreach ($productos['nombre'] as $index => $nombreProducto) {
            $cantidad = $productos['cantidad'][$index];
            $precio_unitario = $productos['precio'][$index]; // Asegúrate de que sea "precio_unitario"
            $totalProducto = $cantidad * $precio_unitario;
            $totalVenta += $totalProducto;

            $queryProducto = $db->prepare("INSERT INTO productos (venta_id, nombre, cantidad, precio_unitario, total) VALUES (:venta_id, :nombre, :cantidad, :precio_unitario, :total)");
            $queryProducto->bindParam(':venta_id', $venta_id);
            $queryProducto->bindParam(':nombre', $nombreProducto);
            $queryProducto->bindParam(':cantidad', $cantidad);
            $queryProducto->bindParam(':precio_unitario', $precio_unitario); // Uso de "precio_unitario"
            $queryProducto->bindParam(':total', $totalProducto);
            $queryProducto->execute();
        }

        $queryUpdate = $db->prepare("UPDATE ventas SET total = :total WHERE id = :venta_id");
        $queryUpdate->bindParam(':total', $totalVenta);
        $queryUpdate->bindParam(':venta_id', $venta_id);
        $queryUpdate->execute();

        return $venta_id;
    }

    public static function eliminarVenta($id) {
        $db = Database::connect();
        $query = $db->prepare("DELETE FROM ventas WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }

    public static function actualizarEstadoVenta($id, $nuevoEstado) {
        $db = Database::connect();
        $query = $db->prepare("UPDATE ventas SET estado = :estado WHERE id = :id");
        $query->bindParam(':estado', $nuevoEstado, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }
    public static function obtenerVentasPorEstado($estado) {
        $db = Database::connect();
        
        if ($estado) {
            $query = $db->prepare("
                SELECT ventas.id, ventas.fecha, ventas.estado, ventas.total, 
                       clientes.nombre AS nombre_cliente, clientes.zona 
                FROM ventas
                JOIN clientes ON ventas.cliente_id = clientes.id
                WHERE ventas.estado = :estado
                ORDER BY ventas.id DESC
            ");
            $query->bindParam(':estado', $estado);
        } else {
            $query = $db->prepare("
                SELECT ventas.id, ventas.fecha, ventas.estado, ventas.total, 
                       clientes.nombre AS nombre_cliente, clientes.zona 
                FROM ventas
                JOIN clientes ON ventas.cliente_id = clientes.id
                ORDER BY ventas.id DESC
            ");
        }
        
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public static function obtenerDetallesVenta($id) {
        $db = Database::connect();
        $query = $db->prepare("
            SELECT productos.nombre, productos.cantidad, productos.precio_unitario, productos.total
            FROM productos
            WHERE productos.venta_id = :id
        ");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
