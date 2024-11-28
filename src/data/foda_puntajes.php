<?php
// foda_puntajes.php

require_once 'neonConnection.php';  // Se asume que la clase Conexion está incluida aquí.

class EvaluacionMatrizData {
    private $db;

    public function __construct() {
        $conexion = new Conexion();
        $this->db = $conexion->getConnection(); // Obtener la conexión
    }

    // Verificar si ya existe una evaluación para el plan_id
    public function existeEvaluacion($idPlan) {
        $query = "SELECT COUNT(*) FROM evaluaciones_matriz WHERE plan_id = :plan_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':plan_id', $idPlan);
        $stmt->execute();
        return $stmt->fetchColumn() > 0; // Retorna true si existe al menos un registro
    }

    // Guardar puntaje en la base de datos
    public function guardarPuntaje($idPlan, $puntajes_json, $puntaje_final) {
        $query = "INSERT INTO evaluaciones_matriz (plan_id, puntajes, puntaje_final) 
                  VALUES (:plan_id, :puntajes, :puntaje_final)";
        
        // Preparar la consulta
        $stmt = $this->db->prepare($query);
        
        // Bind de parámetros
        $stmt->bindParam(':plan_id', $idPlan);
        $stmt->bindParam(':puntajes', $puntajes_json);
        $stmt->bindParam(':puntaje_final', $puntaje_final);
        
        // Ejecutar la consulta
        return $stmt->execute();  // Retorna true si se ejecutó correctamente
    }

    // Actualizar puntaje en la base de datos
    public function actualizarPuntaje($idPlan, $puntajes_json, $puntaje_final) {
        $query = "UPDATE evaluaciones_matriz SET puntajes = :puntajes, puntaje_final = :puntaje_final 
                  WHERE plan_id = :plan_id";
        
        // Preparar la consulta
        $stmt = $this->db->prepare($query);
        
        // Bind de parámetros
        $stmt->bindParam(':plan_id', $idPlan);
        $stmt->bindParam(':puntajes', $puntajes_json);
        $stmt->bindParam(':puntaje_final', $puntaje_final);
        
        // Ejecutar la consulta
        return $stmt->execute();  // Retorna true si se ejecutó correctamente
    }

    // Obtener puntajes de la base de datos
    public function obtenerPuntajes($idPlan) {
        $query = "SELECT puntajes, puntaje_final FROM evaluaciones_matriz WHERE plan_id = :plan_id LIMIT 1";
        
        // Preparar la consulta
        $stmt = $this->db->prepare($query);
        
        // Bind de parámetros
        $stmt->bindParam(':plan_id', $idPlan);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Retorna el puntaje como un array
        return $result;
    }
}
?>
