<?php

require_once('../data/plan.php'); // Asegúrate de que esto apunte al archivo correcto donde tienes la clase PlanData

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    
    if (!isset($_SESSION['idPlan'])) {
        die("ID de plan no encontrado en la sesión.");
    }

    $idPlan = $_SESSION['idPlan'];

    // Instancia de la clase que maneja los planes
    $planData = new PlanData();

    // Procesar autovalores si se hace clic en "Realizar Autoevaluación"
    if (isset($_POST['guardarAutoevaluacion'])) {
        $autovalores = [];
        $suma = 0; // Variable para sumar todos los valores

        // Recorremos los 25 puntos de evaluación
        for ($i = 1; $i <= 25; $i++) {
            if (isset($_POST["punto_$i"])) {
                // Almacenamos el valor seleccionado (0, 1, 2, 3 o 4)
                $valor = $_POST["punto_$i"];
                $autovalores[] = $valor;
                $suma += $valor; // Sumar el valor seleccionado
            } else {
                // En caso de que no se haya seleccionado nada, almacenamos 0 por defecto
                $autovalores[] = 0;
            }
        }

        // Convertir el array de autovalores en una cadena separada por comas
        $nuevoAutovalor = implode(",", $autovalores);

        // Llamar a la función para actualizar el valor en la base de datos
        $resultado = $planData->actualizarAutovalor($idPlan, $nuevoAutovalor);

        // Calcular el potencial de mejora: 1 - (suma / 100)
        $potencialMejora = 1 - ($suma / 100);
        $potencialMejoraPorcentaje = $potencialMejora * 100; // Convertir a porcentaje

        // Almacenar el potencial de mejora en la sesión para mostrarlo en la vista
        $_SESSION['potencialMejora'] = round($potencialMejoraPorcentaje, 2); // Redondeado a 2 decimales

        if ($resultado) {
            header("Location: ../presentation/cadenaValor2.php");
            exit;
        } else {
            echo "Error al actualizar la autoevaluación.";
        }
    }

    // Procesar reflexiones si se hace clic en "Guardar Reflexión"
    if (isset($_POST['guardarReflexion'])) {
        $reflexion = $_POST['reflexion'];

        // Llamar a la función para actualizar las reflexiones
        $resultadoReflexion = $planData->actualizarReflexiones($idPlan, $reflexion);

        if ($resultadoReflexion) {
            echo "<script>alert('Reflexión guardada con éxito.'); window.location.href = '../presentation/cadenaValor2.php';</script>";
        } else {
            echo "<script>alert('Error al guardar la reflexión.'); window.location.href = '../presentation/cadenaValor2.php';</script>";
        }
        exit;
    }
} else {
    die("Solicitud no válida.");
}
