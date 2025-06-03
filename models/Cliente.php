<?php
require_once 'Database.php';

class Cliente {
    public static function obtenerClientes() {
        $db = Database::connect();
        $statement = $db->query('SELECT * FROM clientes');
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function agregarCliente($nombre, $zona) {
        $db = Database::connect();
        $statement = $db->prepare('INSERT INTO clientes (nombre, zona) VALUES (:nombre, :zona)');
        $statement->execute(['nombre' => $nombre, 'zona' => $zona]);
        return $db->lastInsertId();
    }

    public static function buscarClientePorNombre($nombre) {
        $db = Database::connect();
        $statement = $db->prepare('SELECT * FROM clientes WHERE nombre = :nombre');
        $statement->execute(['nombre' => $nombre]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}
?>
