<?php
require_once('neonConnection.php');

class PlanData
{
    public function crearPlan($nombreEmpresa, $fecha, $promotores, $logo)
    {
        // Iniciar la sesión para obtener el idusuario
        session_start();  
        $idusuario = $_SESSION['idusuario'];  // idusuario en la sesión
    
        $db = new Conexion();
        $conn = $db->getConnection();
    
        // Consulta SQL para insertar el nuevo plan
        $sql = "INSERT INTO plan (idusuario, nombreempresa, fecha, promotores, logo) VALUES (?, ?, ?, ?, ?)";
    
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }
    
        // Bind de los valores
        $stmt->bindValue(1, $idusuario, PDO::PARAM_INT);
        $stmt->bindValue(2, $nombreEmpresa, PDO::PARAM_STR);
        $stmt->bindValue(3, $fecha, PDO::PARAM_STR);
        $stmt->bindValue(4, $promotores, PDO::PARAM_STR);
        $stmt->bindValue(5, $logo, PDO::PARAM_STR);
    
        // Ejecutar la consulta
        $resultado = $stmt->execute();
    
        // Obtener la ID del plan recién creado si la inserción fue exitosa
        if ($resultado) {
            // Devolver el ID del último plan insertado
            $lastId = $conn->lastInsertId();
        } else {
            $lastId = false; // Si no se inserta, devolver false
        }
    
        // Cerrar el cursor y la conexión
        $stmt->closeCursor();
        $conn = null;
    
        return $lastId; // Devuelve la ID del plan creado o false si falla
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

    public function obtenerMisionPorId($idPlan) {
        $conexion = new Conexion();
        $conn = $conexion->getConnection();
    
        $sql = "SELECT mision FROM plan WHERE idPlan = :idPlan"; // Ajusta según tu tabla
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idPlan', $idPlan, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['mision'] : null;
        }
        return null;
    }
    
    public function obtenerVisionPorId($idPlan) {
        $conexion = new Conexion();
        $conn = $conexion->getConnection();
    
        $sql = "SELECT vision FROM plan WHERE idPlan = :idPlan"; // Ajusta según tu tabla
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idPlan', $idPlan, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado ? $resultado['vision'] : null;
        }
        return null;
    }

    public function obtenerPlanPorId($idplan, $idusuario) {
        $conn = new Conexion();
        $db = $conn->getConnection();
    
        $sql = "SELECT * FROM plan WHERE idplan = ? AND idusuario = ?";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $idplan, PDO::PARAM_INT);
        $stmt->bindValue(2, $idusuario, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizarMision($idPlan, $nuevaMision)
    {
        // Conexión a la base de datos
        $db = new Conexion();
        $conn = $db->getConnection();
    
        // Preparar la consulta SQL para actualizar la misión
        $sql = "UPDATE plan SET mision = ? WHERE idplan = ?";
    
        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }
    
        // Vincular los parámetros
        $stmt->bindValue(1, $nuevaMision, PDO::PARAM_STR);
        $stmt->bindValue(2, $idPlan, PDO::PARAM_INT);
    
        // Ejecutar la consulta
        $resultado = $stmt->execute();
        $stmt->closeCursor(); // Cerrar el cursor
        $conn = null; // Cerrar la conexión
    
        return $resultado; // Retornar el resultado de la ejecución
    }

    public function actualizarVision($idPlan, $nuevaVision)
    {
        // Conexión a la base de datos
        $db = new Conexion();
        $conn = $db->getConnection();
    
        // Preparar la consulta SQL para actualizar la visión
        $sql = "UPDATE plan SET vision = ? WHERE idplan = ?";
    
        // Preparar la declaración
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }
    
        // Vincular los parámetros
        $stmt->bindValue(1, $nuevaVision, PDO::PARAM_STR);
        $stmt->bindValue(2, $idPlan, PDO::PARAM_INT);
    
        // Ejecutar la consulta
        $resultado = $stmt->execute();
        $stmt->closeCursor(); // Cerrar el cursor
        $conn = null; // Cerrar la conexión
    
        return $resultado; // Retornar el resultado de la ejecución
    }

    public function actualizarPlan($idPlan, $nombreEmpresa, $fecha, $promotores, $logo = null)
    {
        $db = new Conexion();
        $conn = $db->getConnection();
        
        // Construir la consulta SQL
        $sql = "UPDATE plan SET nombreempresa = ?, fecha = ?, promotores = ?" . ($logo ? ", logo = ?" : "") . " WHERE idplan = ?";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta SQL: " . $conn->errorInfo());
        }

        // Asignar valores a los parámetros
        $stmt->bindValue(1, $nombreEmpresa, PDO::PARAM_STR);
        $stmt->bindValue(2, $fecha, PDO::PARAM_STR);
        $stmt->bindValue(3, $promotores, PDO::PARAM_STR);
        
        if ($logo) {
            $stmt->bindValue(4, $logo, PDO::PARAM_STR);
            $stmt->bindValue(5, $idPlan, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(4, $idPlan, PDO::PARAM_INT);
        }

        // Ejecutar la consulta
        $resultado = $stmt->execute();
        $stmt->closeCursor();
        $conn = null;

        return $resultado;
    }

}
