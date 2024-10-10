<?php
require_once('neonConnection.php');

class PlanData
{

    public function crearPlan($nombreEmpresa, $fecha, $promotores, $logo)
    {
        $idusuario = 1;
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
}
