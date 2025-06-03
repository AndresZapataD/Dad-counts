<?php
class Database {
    private static $connection;

    public static function connect() {
        if (!self::$connection) {
            try {
                self::$connection = new PDO('sqlite:' . __DIR__ . '/../database/sales.db');
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
?>
