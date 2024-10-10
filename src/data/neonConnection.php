<?php

class Conexion {
    private $pdo;

    public function __construct() {
        $host = getenv('DB_HOST');
        $db = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASSWORD');
        $sslmode = 'require';

        // Cadena DSN para PostgreSQL con puerto y SSL
        $dsn = "pgsql:host=$host;dbname=$db;sslmode=$sslmode";

        try {
            $this->pdo = new PDO($dsn, $user, $pass);
            // Configurar el manejo de errores a modo de excepción
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Manejo de errores de conexión
            echo 'Error de conexión: ' . $e->getMessage();
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}