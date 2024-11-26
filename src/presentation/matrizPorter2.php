<?php

require_once('../data/plan.php'); // Asegúrate de que esto apunte al archivo correcto donde tienes la clase PlanData

session_start();
if (!isset($_SESSION['idPlan'])) {
    die("ID de plan no encontrado en la sesión.");
}

$idPlan = $_SESSION['idPlan'];

// Instancia de la clase que maneja los planes
$planData = new PlanData();

// Obtener el autovalor guardado en la base de datos
$valorPorterGuardado = $planData->obtenerValorPorterPorId($idPlan);

// Obtener las fortalezas guardadas
$oportunidadesGuardadas = $planData->obtenerOportunidadesPorId($idPlan);

// Obtener las debilidades guardadas
$amenazasGuardadas = $planData->obtenerAmenazasPorId($idPlan);

// Convertimos el autovalor de cadena a un array
$valoresporter = $valorPorterGuardado ? explode(",", $valorPorterGuardado) : array_fill(0, 17, 0); // Si no hay valor guardado, usamos 0 por defecto

// Array de preguntas obtenidas de la imagen
$preguntas = [
    " Crecimiento",
    " Naturaleza de los competidores",
    " Exceso de capacidad productiva",
    " Rentabilidad media del sector",
    " Diferenciación del producto",
    " Barreras de salida",
    " Economías de escala",
    " Necesidad de capital",
    " Acceso a la tecnología",
    " Reglamentos o leyes limitativos",
    " Trámites burocráticos",
    " Reacción esperada actuales competidores",
    " Número de clientes ",
    " Posibilidad de integración ascendente",
    " Rentabilidad de los clientes",
    " Coste de cambio de proveedor para cliente",
    " Disponibilidad de Productos Sustitutivos",
];

// Calcular el total de los puntos
$totalPuntos = array_sum($valoresporter);

// Determinar la conclusión según el total de puntos
$conclusion = '';
if ($totalPuntos < 30) {
    $conclusion = "Estamos en un mercado altamente competitivo, en el que es muy difícil hacerse un hueco en el mercado.";
} elseif ($totalPuntos >= 30 && $totalPuntos < 45) {
    $conclusion = "Estamos en un mercado de competitividad relativamente alta, pero con ciertas modificaciones en el producto y la política comercial de la empresa, podría encontrarse un nicho de mercado.";
} elseif ($totalPuntos >= 45 && $totalPuntos < 60) {
    $conclusion = "La situación actual del mercado es favorable a la empresa.";
} else {
    $conclusion = "Estamos en una situación excelente para la empresa.";
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autodiagnóstico de Porter</title>
    <style>
        .btn-volver, .btn-siguiente {
            background-color: gray;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            border-radius: 25px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .btn-volver:hover, .btn-siguiente:hover {
            background-color: #555;
        }

        .btn-siguiente {
            background-color: #333;
        }

        .button-save {
            background-color: #ff4d4d; 
            color: white;
            border: none;
            padding: 10px 20px; 
            text-align: center;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px; 
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .button-save:hover {
            background-color: #d43f3f;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .center {
            text-align: center;
            margin-top: 20px;
        }
        .textarea-title {
            font-size: 16px;
            margin-top: 20px;
            font-weight: bold;
            color: #333;
        }
        .reflexion-textarea, .oportunidades-textarea, .amenazas-textarea, .conclusion-textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
    <script>
        // Función para actualizar la conclusión basada en el puntaje
        function actualizarConclusion() {
            const radios = document.querySelectorAll('input[type="radio"]:checked');
            let total = 0;

            radios.forEach(radio => {
                total += parseInt(radio.value);
            });

            const conclusionTextarea = document.getElementById('conclusion');
            if (total < 30) {
                conclusionTextarea.value = "Estamos en un mercado altamente competitivo, en el que es muy difícil hacerse un hueco en el mercado.";
            } else if (total >= 30 && total < 45) {
                conclusionTextarea.value = "Estamos en un mercado de competitividad relativamente alta, pero con ciertas modificaciones en el producto y la política comercial de la empresa, podría encontrarse un nicho de mercado.";
            } else if (total >= 45 && total < 60) {
                conclusionTextarea.value = "La situación actual del mercado es favorable a la empresa.";
            } else {
                conclusionTextarea.value = "Estamos en una situación excelente para la empresa.";
            }
        }
    </script>
</head>
<body>

    <div class="container">
        <h2 class="center">Autodiagnóstico Porter</h2>

        <form method="POST" action="../business/autodiagnosticoPorter.php">
            <table>
                <thead>
                    <tr>
                        <th>Autodiagnóstico Porter</th>
                        <th>Nada</th>
                        <th>Poco</th>
                        <th>Medio</th>
                        <th>Alto</th>
                        <th>Muy alto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    for ($i = 0; $i < 17; $i++) {
                        $pregunta = $preguntas[$i]; // Obtener la pregunta correspondiente
                        $valorSeleccionado = isset($valoresporter[$i]) ? $valoresporter[$i] : 0; // Obtener el valor guardado para cada pregunta

                        // Generamos los radio buttons, con el valor seleccionado de la base de datos
                        echo "<tr>
                            <td>$pregunta</td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='1' " . ($valorSeleccionado == 1 ? 'checked' : '') . " onchange='actualizarConclusion()'></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='2' " . ($valorSeleccionado == 2 ? 'checked' : '') . " onchange='actualizarConclusion()'></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='3' " . ($valorSeleccionado == 3 ? 'checked' : '') . " onchange='actualizarConclusion()'></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='4' " . ($valorSeleccionado == 4 ? 'checked' : '') . " onchange='actualizarConclusion()'></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='5' " . ($valorSeleccionado == 5 ? 'checked' : '') . " onchange='actualizarConclusion()'></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Área de texto para conclusiones -->
            <div class="center">
                <label class="textarea-title">Conclusiones</label>
                <textarea id="conclusion" class="conclusion-textarea" name="conclusion" readonly><?php echo htmlspecialchars($conclusion); ?></textarea>
            </div>

            <!-- Título y Cuadro de texto para oportunidades -->
            <div class="center">
                <label class="textarea-title">Oportunidades</label>
                <textarea class="oportunidades-textarea" name="oportunidades" placeholder="Escribe tus oportunidades sobre el autodiagnóstico..."><?php echo isset($oportunidadesGuardadas) ? htmlspecialchars($oportunidadesGuardadas) : ''; ?></textarea>
            </div>

            <!-- Título y Cuadro de texto para amenazas -->
            <div class="center">
                <label class="textarea-title">Amenazas</label>
                <textarea class="amenazas-textarea" name="amenazas" placeholder="Escribe las amenazas..."><?php echo isset($amenazasGuardadas) ? htmlspecialchars($amenazasGuardadas) : ''; ?></textarea>
            </div>

            <div class="center">
                <button type="submit" name="guardarEvaluacionPorter" class="button-save">Guardar Evaluación</button>
            </div>
        </form>

        <div class="center" style="display: flex; justify-content: space-between; margin-top: 20px;">
            <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
            <a href="pest.php" class="btn-siguiente">Siguiente</a>
        </div>
    </div>

</body>
</html>

