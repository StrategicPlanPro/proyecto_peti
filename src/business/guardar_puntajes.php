<?php
    // Iniciar sesión
    session_start();

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['idPlan'])) {
        exit();
    }

    // Incluir archivos necesarios
    include_once '../data/foda_puntajes.php';

    // Obtener el idPlan desde la sesión
    $idPlan = $_SESSION['idPlan'];

    // Crear una instancia de EvaluacionMatrizData
    $evaluacionData = new EvaluacionMatrizData();  // Esto inicializa la clase

    // Verificar si el formulario ha sido enviado y contiene los puntajes
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['puntaje']) && isset($_POST['puntaje2'])) {
        // Puntajes para la matriz Fortalezas/Oportunidades
        $puntajes = $_POST['puntaje'];  // Array de puntajes
        $puntajes_json = json_encode($puntajes);  // Convertir a JSON

        // Calcular el puntaje final para la matriz Fortalezas/Oportunidades
        $puntaje_final = 0;
        foreach ($puntajes as $fortalezas) {
            foreach ($fortalezas as $puntaje) {
                $puntaje_final += (int)$puntaje;  // Sumar los puntajes de la matriz
            }
        }

        // Puntajes para la matriz Fortalezas/Amenazas
        $puntajes2 = $_POST['puntaje2'];  // Array de puntajes
        $puntajes_json2 = json_encode($puntajes2);  // Convertir a JSON

        // Calcular el puntaje final para la matriz Fortalezas/Amenazas
        $puntaje_final2 = 0;
        foreach ($puntajes2 as $fortalezas) {
            foreach ($fortalezas as $puntaje) {
                $puntaje_final2 += (int)$puntaje;  // Sumar los puntajes de la matriz
            }
        }

        // Verificar si ya existe un registro para el plan_id
        if ($evaluacionData->existeEvaluacion($idPlan)) {
            // Si ya existe, actualizar los puntajes y puntajes finales de ambas matrices
            $evaluacionData->actualizarPuntaje(
                $idPlan, 
                $puntajes_json, 
                $puntaje_final, 
                $puntajes_json2, 
                $puntaje_final2
            );
        } else {
            // Si no existe, insertar los puntajes para ambas matrices
            $evaluacionData->guardarPuntaje(
                $idPlan, 
                $puntajes_json, 
                $puntaje_final, 
                $puntajes_json2, 
                $puntaje_final2
            );
        }

        // Redirigir a la página de confirmación o al Dashboard
        header("Location: ../presentation/identificacionEstrategias.php");
        exit();
    } else {
        // Si no se reciben puntajes, redirigir al formulario
        header("Location: ../presentation/identificacionEstrategias.php");
        exit();
    }
?>