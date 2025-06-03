<?php
require_once 'models/Database.php';

try {
    $db = Database::connect();

    // Crear la tabla clientes
    $db->exec("
        CREATE TABLE IF NOT EXISTS clientes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL,
            zona TEXT NOT NULL
        );
    ");

    // Crear la tabla ventas
    $db->exec("
        CREATE TABLE IF NOT EXISTS ventas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            cliente_id INTEGER,
            fecha TEXT DEFAULT (DATE('now')),
            estado TEXT DEFAULT 'debe',
            total REAL NOT NULL,
            FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        );
    ");

    // Crear la tabla productos
    $db->exec("
        CREATE TABLE IF NOT EXISTS productos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            venta_id INTEGER,
            nombre TEXT NOT NULL,
            cantidad INTEGER NOT NULL,
            precio_unitario REAL NOT NULL,
            total REAL NOT NULL,
            FOREIGN KEY (venta_id) REFERENCES ventas(id)
        );
    ");

    echo "Las tablas se han creado correctamente.";
} catch (PDOException $e) {
    echo "Error al crear las tablas: " . $e->getMessage();
}
?>
