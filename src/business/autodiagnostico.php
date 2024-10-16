<?php
session_start();
require_once('../data/plan.php');

// Verificar si el usuario ha iniciado sesión y si se envió el formulario
if (!isset($_SESSION['idusuario']) || !isset($_SESSION['idPlan']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirigir al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit();
}

// Obtener el id del usuario y del plan desde la sesión
$idusuario = $_SESSION['idusuario'];
$idPlan = $_SESSION['idPlan'];

// Crear una instancia de PlanData
$planData = new PlanData();

// Obtener reflexiones, debilidades y fortalezas del plan actual
$reflexiones = $planData->obtenerReflexionesPorId($idPlan);
$debilidades = $planData->obtenerDebilidadesPorId($idPlan);
$fortalezas = $planData->obtenerFortalezasPorId($idPlan);

// Recoger los valores del formulario de autoevaluación
$autovalor = [];
for ($i = 1; $i <= 25; $i++) {
    // Guardar el valor seleccionado en el array $autovalor
    if (isset($_POST["valoracion_$i"])) {
        $autovalor[$i] = $_POST["valoracion_$i"];
    } else {
        $autovalor[$i] = 0; // Valor por defecto si no se seleccionó una opción
    }
}

// Convertir el array a formato JSON para almacenarlo en la base de datos
$autovalorJson = json_encode($autovalor);

// Llamar a la función autodiagnostico para actualizar el valor en la base de datos
if ($planData->autodiagnostico($autovalorJson, $idPlan)) {
    // Si la actualización fue exitosa, redirigir a la siguiente página o mostrar un mensaje
    echo "Autodiagnóstico guardado correctamente.<br>";
    
    // Mostrar las reflexiones, debilidades y fortalezas obtenidas
    echo "<h3>Reflexiones:</h3>";
    echo $reflexiones ? $reflexiones : "No hay reflexiones registradas.<br>";

    echo "<h3>Debilidades:</h3>";
    echo $debilidades ? $debilidades : "No hay debilidades registradas.<br>";

    echo "<h3>Fortalezas:</h3>";
    echo $fortalezas ? $fortalezas : "No hay fortalezas registradas.<br>";

    header("Location: ../presentation/matriz.php"); // Redirigir a la siguiente página
    exit();
} else {
    // Si la actualización falla, mostrar un mensaje de error
    echo "Hubo un error al guardar el autodiagnóstico.";
}
?>
