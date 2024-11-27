<?php
    include_once '../data/foda_puntajes.php'; // Asegúrate de incluir la clase de puntajes
    // Asegurarse de que los datos estén disponibles
    if (isset($_POST['fortaleza_oportunidad'], $_POST['fortaleza_nombre'], $_POST['oportunidad_nombre'], $_POST['total_puntaje'])) {
        // Obtener el plan_id desde la sesión o desde un parámetro
        session_start();
        $plan_id = $_SESSION['idPlan'];

        // Instanciar la clase FodaPuntajes
        $fodaPuntajes = new FodaPuntajes();

        // Guardar los puntajes individuales (de la matriz)
        foreach ($_POST['fortaleza_oportunidad'] as $i => $row) {
            foreach ($row as $j => $puntaje) {
                $fortaleza_nombre = $_POST['fortaleza_nombre'][$i][$j];
                $oportunidad_nombre = $_POST['oportunidad_nombre'][$i][$j];

                // Verificar si fortaleza_nombre y oportunidad_nombre no están vacíos
                if (empty($fortaleza_nombre) || empty($oportunidad_nombre) || $puntaje == 0) {
                    // Si el nombre de fortaleza o oportunidad está vacío, o el puntaje es 0, continuar con el siguiente registro
                    continue;
                }

                // Guardar el puntaje si todo es válido
                $fodaPuntajes->guardarPuntaje($plan_id, $fortaleza_nombre, $oportunidad_nombre, $puntaje);
            }
        }

        // Guardar el puntaje total (si es necesario)
        $total_puntaje = $_POST['total_puntaje']; // El puntaje total enviado por el formulario
        // Aquí puedes hacer algo con el puntaje total, como guardarlo en una tabla o actualizar un campo en la tabla principal
        // Por ejemplo, guardar el puntaje total en una tabla específica:
        $fodaPuntajes->guardarPuntajeTotal($plan_id, $total_puntaje);

        // Redirigir a la siguiente página o mostrar un mensaje de éxito
        header("Location: siguiente_pagina.php");
        exit();
    }
?>