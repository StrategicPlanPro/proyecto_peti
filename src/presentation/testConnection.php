<?php

require_once '../data/neonConnection.php';

try {
    // Instancia de la clase Conexion
    $db = new Conexion();
    $conn = $db->getConnection();

    if ($conn) {
        echo "Conexión exitosa a la base de datos PostgreSQL.";
    }
} catch (Exception $e) {
    echo "Error en la conexión: " . $e->getMessage();
}