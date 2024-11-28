<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idPlan'])) {
    // Redirigir al usuario a la página de inicio de sesión si no está autenticado
    header("Location: login.php");
    exit();
}

// Incluir archivos necesarios
include_once '../data/foda_puntajes.php';

// Obtener el idPlan desde la sesión
$idPlan = $_SESSION['idPlan'];

// Verificar si los puntajes fueron enviados por el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['puntaje'])) {
    // Obtener los puntajes enviados (array de puntajes)
    $puntajes = $_POST['puntaje']; // Array con los puntajes

    // Crear una instancia de la clase de Evaluaciones
    $evaluacionData = new EvaluacionMatrizData();

    // Convertir el array de puntajes en un formato JSON
    $puntajes_json = json_encode($puntajes);

    // Calcular el puntaje final (por ejemplo, sumando todos los puntajes)
    $puntaje_final = 0;
    foreach ($puntajes as $fortalezas) {
        foreach ($fortalezas as $puntaje) {
            $puntaje_final += (int)$puntaje;  // Sumar los puntajes
        }
    }

    // Verificar si ya existe un registro para el plan_id
    if ($evaluacionData->existeEvaluacion($idPlan)) {
        // Si ya existe, actualizar los puntajes y puntaje final
        $evaluacionData->actualizarPuntaje($idPlan, $puntajes_json, $puntaje_final);
    } else {
        // Si no existe, insertar los puntajes
        $evaluacionData->guardarPuntaje($idPlan, $puntajes_json, $puntaje_final);
    }

    // Redirigir a la página de confirmación o al Dashboard
    header("Location: ../presentation/identificacionEstrategias.php"); // Redirigir a una página de confirmación
    exit();
} else {
    // Si no se reciben puntajes, redirigir al formulario
    header("Location: matrizCAME.php");
    exit();
}
?>
