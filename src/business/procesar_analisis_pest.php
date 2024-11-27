<?php
// Iniciar sesión para obtener el idPlan
session_start();

// Verificar si se recibieron datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Asegurarse de que idPlan esté en la sesión
    if (!isset($_SESSION['idPlan'])) {
        die("ID de plan no encontrado en la sesión.");
    }

    $idPlan = $_SESSION['idPlan'];

    // Recoger las respuestas del formulario
    $respuestas = [];
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'respuesta_') === 0) {
            $respuestas[] = $value;
        }
    }

    // Generar el string para el análisis PEST
    $pestResultados = implode(',', $respuestas);

    // Las conclusiones pueden ser obtenidas de otros inputs o generadas según el análisis
    $conclusionEconomico = "Conclusión sobre el factor económico"; // Aquí podrías generar una conclusión basada en las respuestas
    $conclusionPolitico = "Conclusión sobre el factor político";
    $conclusionSocial = "Conclusión sobre el factor social";
    $conclusionTecnologico = "Conclusión sobre el factor tecnológico";
    $conclusionAmbiental = "Conclusión sobre el factor ambiental";

    // Incluir el archivo donde se encuentran las funciones de actualización
    require_once('../data/analisis_pest.php');

    // Instanciar la clase AnalisisPest
    $pest = new AnalisisPest();

    // Llamar a la función que actualiza o inserta el análisis PEST
    $resultado = $pest->actualizarPest($idPlan, $pestResultados, $conclusionEconomico, $conclusionPolitico, $conclusionSocial, $conclusionTecnologico, $conclusionAmbiental);

    // Verificar el resultado
    if ($resultado) {
        // Redirigir o mostrar un mensaje de éxito
        echo "Análisis PEST actualizado correctamente.";
    } else {
        // Manejar el error si algo salió mal
        echo "Error al actualizar el análisis PEST.";
    }
} else {
    // Si el formulario no fue enviado, redirigir o mostrar un error
    echo "No se recibieron datos del formulario.";
}
?>
