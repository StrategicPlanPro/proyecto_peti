
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
            background-color: #d43f3f; /* Color rojo más oscuro en hover */
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
        .button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        .result {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
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
                        // Obtener el valor seleccionado de autovalores
                        $valorSeleccionado = isset($autovalores[$i]) ? $autovalores[$i] : 0;
                        $pregunta = $preguntas[$i]; // Obtener la pregunta correspondiente

                        echo "<tr>
                            <td>$pregunta</td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='0' " . ($valorSeleccionado == 0 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='1' " . ($valorSeleccionado == 1 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='2' " . ($valorSeleccionado == 2 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='3' " . ($valorSeleccionado == 3 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_" . ($i + 1) . "' value='4' " . ($valorSeleccionado == 4 ? 'checked' : '') . "></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <!-- Título y Cuadro de texto para Conclusiones -->
            <div class="center">
                <label class="textarea-title">Conclusiones</label>
                <textarea class="conclusion-textarea" name="conclusion" placeholder="Conclusiones sobre el autodiagnóstico..."><?php echo isset($conclusiones) ? htmlspecialchars($reflexionesGuardadas) : ''; ?></textarea>
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


            <!-- Botón para guardar la autoevaluación -->
            <div class="center">
                <button type="submit" name="guardarEvaluacionPorter" class="button-save">Realizar Evaluación Porter</button>
                <button type="submit" name="guardarConclusion" class="button-save">Guardar Datos</button>
            </div>
        </form>

            <div class="center" style="display: flex; justify-content: space-between; margin-top: 20px;">
            <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
            <a href="pest.php" class="btn-siguiente">Siguiente</a>
        </div>
    </div>

</body>
</html>
