<?php
require_once('neonConnection.php');

class PlanData
{
    public function crearPlan($nombreEmpresa, $fecha, $promotores, $logo)
    {
        // Inicia la sesión para obtener el idusuario
        session_start();  
        $idusuario = $_SESSION['idusuario'];  // idusuario en la sesión

        $db = new Conexion();
        $conn = $db->getConnection();
        $sql = "INSERT INTO plan (idusuario, nombreempresa, fecha, promotores, logo) VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }

        $stmt->bindValue(1, $idusuario, PDO::PARAM_INT);
        $stmt->bindValue(2, $nombreEmpresa, PDO::PARAM_STR);
        $stmt->bindValue(3, $fecha, PDO::PARAM_STR);
        $stmt->bindValue(4, $promotores, PDO::PARAM_STR);
        $stmt->bindValue(5, $logo, PDO::PARAM_STR);

        $resultado = $stmt->execute();
        $stmt->closeCursor();
        $conn = null;

        return $resultado;
    }

    function obtenerPlanesPorUsuario($idusuario) {
        // Crear una instancia de la conexión a la base de datos
        $conexion = new Conexion();
        $conn = $conexion->getConnection(); 
    
        // Preparar la consulta SQL para obtener los planes del usuario
        $sql = "SELECT idplan, nombreempresa, logo FROM plan WHERE idusuario = :idusuario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idusuario', $idusuario, PDO::PARAM_INT);
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }
}
