<?php

require_once('../data/plan.php'); // Asegúrate de que esto apunte al archivo correcto donde tienes la clase PlanData

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Iniciar la sesión si no está iniciada ya
    session_start();

    // Verificar si existe la sesión con el ID del plan
    if (!isset($_SESSION['idPlan'])) {
        die("ID de plan no encontrado en la sesión.");
    }

    $idPlan = $_SESSION['idPlan'];

    // Crear un array para almacenar los valores seleccionados en el formulario
    $autovalores = [];

    // Recorremos los 25 puntos de evaluación
    for ($i = 1; $i <= 25; $i++) {
        if (isset($_POST["punto_$i"])) {
            // Almacenamos el valor seleccionado (0, 1, 2, 3 o 4)
            $autovalores[] = $_POST["punto_$i"];
        } else {
            // En caso de que no se haya seleccionado nada, almacenamos un valor por defecto (0)
            $autovalores[] = 0;
        }
    }

    // Convertir el array de autovalores en una cadena separada por comas
    $nuevoAutovalor = implode(",", $autovalores);

    // Instancia de la clase que maneja la actualización
    $planData = new PlanData();

    // Llamar a la función para actualizar el valor en la base de datos
    $resultado = $planData->actualizarAutovalor($idPlan, $nuevoAutovalor);

    if ($resultado) {
        // Mostrar un mensaje de éxito y redirigir a cadenaValor2.php
        echo "<script>
            alert('Los datos se han guardado con éxito.');
            window.location.href = '../presentation/cadenaValor2.php'; // Redirigir a cadenaValor2.php
        </script>";
        exit;
    } else {
        echo "Error al actualizar la autoevaluación.";
    }
} else {
    // Mostrar un error si la solicitud no es POST
    die("Solicitud no válida.");
}
?>
