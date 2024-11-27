<?php
require_once('neonConnection.php');

class AnalisisPest
{
    // Función para actualizar el análisis PEST en un plan existente
    public function actualizarPest($planId, $pestResultados, $conclusionEconomico, $conclusionPolitico, $conclusionSocial, $conclusionTecnologico, $conclusionAmbiental)
    {
        $idusuario = $_SESSION['idusuario'];  // idusuario en la sesión

        $db = new Conexion();
        $conn = $db->getConnection();
    
        // Consulta SQL para actualizar el análisis PEST y las conclusiones en el plan
        $sql = "UPDATE plan SET pest = ?, conclusioneconomico = ?, conclusionpolitico = ?, conclusionsocial = ?, conclusiontecnologico = ?, conclusionambiental = ? WHERE idplan = ? AND idusuario = ?";
    
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }
    
        // Bind de los valores
        $stmt->bindValue(1, $pestResultados, PDO::PARAM_STR);
        $stmt->bindValue(2, $conclusionEconomico, PDO::PARAM_STR);
        $stmt->bindValue(3, $conclusionPolitico, PDO::PARAM_STR);
        $stmt->bindValue(4, $conclusionSocial, PDO::PARAM_STR);
        $stmt->bindValue(5, $conclusionTecnologico, PDO::PARAM_STR);
        $stmt->bindValue(6, $conclusionAmbiental, PDO::PARAM_STR);
        $stmt->bindValue(7, $planId, PDO::PARAM_INT);
        $stmt->bindValue(8, $idusuario, PDO::PARAM_INT);
    
        // Ejecutar la consulta
        $resultado = $stmt->execute();
    
        // Verificar si la actualización fue exitosa
        if ($resultado) {
            return true; // Si la actualización fue exitosa
        } else {
            return false; // Si hubo un error
        }
    
        // Cerrar el cursor y la conexión
        $stmt->closeCursor();
        $conn = null;
    }

    // Función para obtener los resultados del análisis PEST de un plan
    public function obtenerPest($idPlan)
    {
        try {
            // Conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Preparar la consulta SQL para obtener el autodiagnóstico del plan
            $query = "SELECT pest FROM plan WHERE idplan = :idPlan";
            $stmt = $conn->prepare($query);
    
            // Asignar el valor al parámetro
            $stmt->bindParam(':idPlan', $idPlan);
    
            // Ejecutar la consulta
            $stmt->execute();
    
            // Obtener el resultado
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Retornar el autodiagnóstico si se encuentra, si no, retorna null
            return $resultado ? $resultado['pest'] : null;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
}
?>
