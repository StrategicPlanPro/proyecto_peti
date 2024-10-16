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

// Obtener el autodiagnóstico (autovalor) existente del plan
$autovalorExistente = $planData->obtenerAutovalorPorId($idPlan);

// Si ya existe un autodiagnóstico guardado, mostrarlo o procesarlo como desees
if ($autovalorExistente) {
    echo "Autodiagnóstico existente encontrado.<br>";
    // Convertir el JSON almacenado en un array para usarlo si es necesario
    $autovalorExistenteArray = json_decode($autovalorExistente, true);
} else {
    echo "No se encontró autodiagnóstico previo. Se procederá a guardar uno nuevo.<br>";
    $autovalorExistenteArray = []; // Si no existe, se inicializa un array vacío
}

// Recoger los valores del formulario de autoevaluación (sobreescribiendo o creando nuevos valores)
$autovalor = [];
for ($i = 1; $i <= 25; $i++) {
    // Guardar el valor seleccionado en el array $autovalor o usar el valor existente
    if (isset($_POST["valoracion_$i"])) {
        $autovalor[$i] = $_POST["valoracion_$i"];
    } else {
        // Si no se seleccionó una opción, utilizar el valor existente o asignar 0
        $autovalor[$i] = isset($autovalorExistenteArray[$i]) ? $autovalorExistenteArray[$i] : 0;
    }
}

// Convertir el array a formato JSON para almacenarlo en la base de datos
$autovalorJson = json_encode($autovalor);

// Llamar a la función actualizarAutovalor para actualizar el valor en la base de datos
if ($planData->actualizarAutovalor($idPlan, $autovalorJson)) {
    echo "Autodiagnóstico guardado correctamente.<br>";

    // Actualizar las reflexiones si se enviaron
    if (isset($_POST['reflexiones'])) {
        $nuevasReflexiones = $_POST['reflexiones'];
        if ($planData->actualizarReflexiones($idPlan, $nuevasReflexiones)) {
            echo "Reflexiones actualizadas correctamente.<br>";
        } else {
            echo "Error al actualizar las reflexiones.<br>";
        }
    }

    // Actualizar las debilidades si se enviaron
    if (isset($_POST['debilidades'])) {
        $nuevasDebilidades = $_POST['debilidades'];
        if ($planData->actualizarDebilidades($idPlan, $nuevasDebilidades)) {
            echo "Debilidades actualizadas correctamente.<br>";
        } else {
            echo "Error al actualizar las debilidades.<br>";
        }
    }

    // Actualizar las fortalezas si se enviaron
    if (isset($_POST['fortalezas'])) {
        $nuevasFortalezas = $_POST['fortalezas'];
        if ($planData->actualizarFortalezas($idPlan, $nuevasFortalezas)) {
            echo "Fortalezas actualizadas correctamente.<br>";
        } else {
            echo "Error al actualizar las fortalezas.<br>";
        }
    }

    // Redirigir a la siguiente página
    header("Location: ../presentation/matriz.php");
    exit();

} else {
    // Si la actualización falla, mostrar un mensaje de error
    echo "Hubo un error al guardar el autodiagnóstico.";
}
?>
