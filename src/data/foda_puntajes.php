<?php
    include_once 'neonConnection.php'; // Asegúrate de incluir la clase de conexión a la base de datos

    class FodaPuntajes {
        public function guardarPuntaje($plan_id, $fortaleza_nombre, $oportunidad_nombre, $puntaje) {
            // Establecer la conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Consultar si ya existe un puntaje para esta relación
            $sql = "SELECT * FROM foda_puntajes WHERE plan_id = :plan_id AND fortaleza_nombre = :fortaleza_nombre AND oportunidad_nombre = :oportunidad_nombre";
            $stmt = $conn->prepare($sql);
            
            // Vincular los parámetros usando bindValue (PDO)
            $stmt->bindValue(":plan_id", $plan_id, PDO::PARAM_INT);
            $stmt->bindValue(":fortaleza_nombre", $fortaleza_nombre, PDO::PARAM_STR);
            $stmt->bindValue(":oportunidad_nombre", $oportunidad_nombre, PDO::PARAM_STR);
            $stmt->execute();
    
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Usamos fetchAll para obtener todos los resultados
    
            // Si existe un puntaje, lo actualizamos
            if (count($result) > 0) {
                $sql_update = "UPDATE foda_puntajes SET puntaje = :puntaje, fecha_registro = CURRENT_TIMESTAMP WHERE plan_id = :plan_id AND fortaleza_nombre = :fortaleza_nombre AND oportunidad_nombre = :oportunidad_nombre";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bindValue(":puntaje", $puntaje, PDO::PARAM_INT);
                $stmt_update->bindValue(":plan_id", $plan_id, PDO::PARAM_INT);
                $stmt_update->bindValue(":fortaleza_nombre", $fortaleza_nombre, PDO::PARAM_STR);
                $stmt_update->bindValue(":oportunidad_nombre", $oportunidad_nombre, PDO::PARAM_STR);
                $stmt_update->execute();
            } else {
                // Si no existe, lo insertamos como un nuevo registro
                $sql_insert = "INSERT INTO foda_puntajes (plan_id, fortaleza_nombre, oportunidad_nombre, puntaje) VALUES (:plan_id, :fortaleza_nombre, :oportunidad_nombre, :puntaje)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bindValue(":plan_id", $plan_id, PDO::PARAM_INT);
                $stmt_insert->bindValue(":fortaleza_nombre", $fortaleza_nombre, PDO::PARAM_STR);
                $stmt_insert->bindValue(":oportunidad_nombre", $oportunidad_nombre, PDO::PARAM_STR);
                $stmt_insert->bindValue(":puntaje", $puntaje, PDO::PARAM_INT);
                $stmt_insert->execute();
            }
        }
    
        // Método para obtener los puntajes de un plan
        public function obtenerPuntajes($plan_id) {
            // Establecer la conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Consultar los puntajes asociados al plan
            $sql = "SELECT fortaleza_nombre, oportunidad_nombre, puntaje FROM foda_puntajes WHERE plan_id = :plan_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(":plan_id", $plan_id, PDO::PARAM_INT); // Vinculamos el parámetro correctamente
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener todos los resultados
    
            return $result;
        }
        // Método para guardar el puntaje total
        public function guardarPuntajeTotal($plan_id, $total_puntaje) {
            // Establecer la conexión a la base de datos
            $db = new Conexion();
            $conn = $db->getConnection();
    
            // Consultar si ya existe un registro para el puntaje total de este plan
            $sql = "SELECT * FROM foda_puntajes WHERE plan_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $plan_id, PDO::PARAM_INT); // Vincula el parámetro correctamente
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($result) {
                // Si ya existe, actualizamos el puntaje total
                $sql_update = "UPDATE foda_puntajes SET puntaje = ?, fecha_registro = CURRENT_TIMESTAMP WHERE plan_id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bindParam(1, $total_puntaje, PDO::PARAM_INT);  // Vincula el puntaje total
                $stmt_update->bindParam(2, $plan_id, PDO::PARAM_INT);         // Vincula el plan_id
                $stmt_update->execute();
            } else {
                // Si no existe, insertamos el puntaje total
                $sql_insert = "INSERT INTO foda_puntajes (plan_id, puntaje) VALUES (?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bindParam(1, $plan_id, PDO::PARAM_INT);        // Vincula el plan_id
                $stmt_insert->bindParam(2, $total_puntaje, PDO::PARAM_INT);  // Vincula el puntaje total
                $stmt_insert->execute();
            }
        }
    }
?>
