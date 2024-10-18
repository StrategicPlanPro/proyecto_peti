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
$autovalorGuardado = $planData->obtenerAutovalorPorId($idPlan);

// Convertimos el autovalor de cadena a un array
$autovalores = $autovalorGuardado ? explode(",", $autovalorGuardado) : array_fill(0, 25, 0); // Si no hay valor guardado, usamos 0 por defecto

// Mostrar el potencial de mejora si está disponible
$potencialMejora = isset($_SESSION['potencialMejora']) ? $_SESSION['potencialMejora'] : null;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autodiagnóstico de la Cadena de Valor</title>
    <style>
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
    </style>
</head>
<body>

    <div class="container">
        <h2 class="center">Autodiagnóstico de la Cadena de Valor</h2>

        

        <form method="POST" action="../business/autodiagnostico.php">
            <table>
                <thead>
                    <tr>
                        <th>Autodiagnóstico de la Cadena de Valor Interna</th>
                        <th>En total en desacuerdo</th>
                        <th>No está de acuerdo</th>
                        <th>Está de acuerdo</th>
                        <th>Está bastante de acuerdo</th>
                        <th>En total acuerdo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    for ($i = 1; $i <= 25; $i++) {
                        // Obtener el valor seleccionado de autovalores
                        $valorSeleccionado = isset($autovalores[$i - 1]) ? $autovalores[$i - 1] : 0;

                        echo "<tr>
                            <td>Punto de evaluación $i</td>
                            <td><input type='radio' name='punto_$i' value='0' " . ($valorSeleccionado == 0 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_$i' value='1' " . ($valorSeleccionado == 1 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_$i' value='2' " . ($valorSeleccionado == 2 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_$i' value='3' " . ($valorSeleccionado == 3 ? 'checked' : '') . "></td>
                            <td><input type='radio' name='punto_$i' value='4' " . ($valorSeleccionado == 4 ? 'checked' : '') . "></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <div class="center">
                <button type="submit" class="button">Realizar Autoevaluación</button>
            </div>
        </form>

        <!-- Mostrar el potencial de mejora si está disponible -->
        <?php if ($potencialMejora !== null): ?>
            <div class="result center">
                <strong>POTENCIAL DE MEJORA DE LA CADENA DE VALOR INTERNA: <?php echo $potencialMejora; ?>%</strong>
            </div>
            <?php unset($_SESSION['potencialMejora']); // Limpiar el valor de la sesión ?>
        <?php endif; ?>
    </div>

</body>
</html>
