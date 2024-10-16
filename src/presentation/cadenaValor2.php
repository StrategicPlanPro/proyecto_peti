<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idusuario']) || !isset($_SESSION['idPlan'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

include_once '../data/plan.php';

// Obtener el idusuario de la sesión
$idusuario = $_SESSION['idusuario'];
$idPlan = $_SESSION['idPlan'];

// Crear una instancia de PlanData
$planData = new PlanData();

// Obtener reflexiones, debilidades y fortalezas del plan actual
$reflexiones = $planData->obtenerReflexionesPorId($idPlan);
$debilidades = $planData->obtenerDebilidadesPorId($idPlan);
$fortalezas = $planData->obtenerFortalezasPorId($idPlan);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadena de Valor 2</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .observaciones {
            margin-bottom: 20px;
        }
        .observaciones textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .strengths-weaknesses {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .strengths-weaknesses div {
            width: 48%;
        }
        .strengths-weaknesses div table {
            width: 100%;
        }
        .strengths-weaknesses div table th {
            text-align: left;
            background-color: #e2e2e2;
        }
        .strengths-weaknesses div table td {
            height: 40px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Autoevaluación de la Cadena de Valor Interna</h1>

        <!-- Formulario para enviar los datos al archivo autodiagnostico.php -->
        <form method="POST" action="../business/autodiagnostico.php">
            <!-- Tabla de Autoevaluación -->
            <table>
                <thead>
                    <tr>
                        <th>Autodiagnóstico de la Cadena de Valor Interna</th>
                        <th>Valoración</th>
                        <th>0</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Añadir las filas de preguntas de autoevaluación aquí -->
                    <?php for($i = 1; $i <= 25; $i++): ?>
                    <tr>
                        <td>Punto de evaluación <?php echo $i; ?></td>
                        <td>Valoración</td>
                        <td><input type="radio" name="valoracion_<?php echo $i; ?>" value="0"></td>
                        <td><input type="radio" name="valoracion_<?php echo $i; ?>" value="1"></td>
                        <td><input type="radio" name="valoracion_<?php echo $i; ?>" value="2"></td>
                        <td><input type="radio" name="valoracion_<?php echo $i; ?>" value="3"></td>
                        <td><input type="radio" name="valoracion_<?php echo $i; ?>" value="4"></td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

            <!-- Área de Observaciones -->
            <div class="observaciones">
                <label for="observaciones">Reflexione sobre el resultado obtenido:</label>
                <textarea id="observaciones" name="observaciones" placeholder="Anote aquellas observaciones que puedan ser de su interés."><?php echo $reflexiones; ?></textarea>
            </div>

            <!-- Fortalezas y Debilidades -->
            <div class="strengths-weaknesses">
                <div class="fortalezas">
                    <table>
                        <thead>
                            <tr>
                                <th>FORTALEZAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><textarea name="fortalezas" rows="3" style="width: 100%;" placeholder="Ingrese las fortalezas..."><?php echo $fortalezas; ?></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="debilidades">
                    <table>
                        <thead>
                            <tr>
                                <th>DEBILIDADES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><textarea name="debilidades" rows="3" style="width: 100%;" placeholder="Ingrese las debilidades..."><?php echo $debilidades; ?></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Botón para enviar el formulario -->
            <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 8px; font-size: 16px;">
                Guardar Autoevaluación
            </button>
        </form>

    </div>

</body>
</html>
