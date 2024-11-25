<?php
session_start();
require_once('../data/plan.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idusuario']) || !isset($_SESSION['idPlan'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Obtener el id del usuario y del plan desde la sesión
$idusuario = $_SESSION['idusuario'];
$idPlan = $_SESSION['idPlan'];

// Crear una instancia de PlanData
$planData = new PlanData();

// Obtener las fortalezas, debilidades, amenazas y oportunidades del plan
$fortalezas = $planData->obtenerFortalezasPorId($idPlan);
$debilidades = $planData->obtenerDebilidadesPorId($idPlan);
$amenazas = $planData->obtenerAmenazasPorId($idPlan);
$oportunidades = $planData->obtenerOportunidadesPorId($idPlan);

// Mostrar mensaje de éxito si se redirige desde el procesamiento
$mensaje = '';
if (isset($_SESSION['mensaje_exito'])) {
    $mensaje = $_SESSION['mensaje_exito'];
    unset($_SESSION['mensaje_exito']); // Limpiar el mensaje después de mostrarlo
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriz CAME</title>
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
        .mensaje {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #e7f3e7;
            border: 1px solid #c1e1c1;
            border-radius: 4px;
            color: #2e7d32;
            font-weight: bold;
        }
        form {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        .matriz-item {
            background-color: #e8eaf6;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
        }
        .matriz-item h2 {
            margin-top: 0;
            font-size: 20px;
            color: #3f51b5;
        }
        .matriz-item textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        .button-container button:hover {
            background-color: #45a049;
        }
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .navigation-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 8px;
        }
        .btn-volver {
            background-color: #2196F3;
            color: white;
        }
        .btn-volver:hover {
            background-color: #1E88E5;
        }
        .btn-siguiente {
            background-color: #FF9800;
            color: white;
        }
        .btn-siguiente:hover {
            background-color: #FB8C00;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Matriz CAME</h1>

    <!-- Mensaje de éxito -->
    <?php if ($mensaje): ?>
        <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <form method="POST" action="../business/procesarMatrizCAME.php">
        <!-- Fortalezas -->
        <div class="matriz-item">
            <h2>Fortalezas</h2>
            <textarea name="fortalezas"><?php echo $fortalezas ? htmlspecialchars($fortalezas) : ''; ?></textarea>
        </div>

        <!-- Debilidades -->
        <div class="matriz-item">
            <h2>Debilidades</h2>
            <textarea name="debilidades"><?php echo $debilidades ? htmlspecialchars($debilidades) : ''; ?></textarea>
        </div>

        <!-- Oportunidades -->
        <div class="matriz-item">
            <h2>Oportunidades</h2>
            <textarea name="oportunidades"><?php echo $oportunidades ? htmlspecialchars($oportunidades) : ''; ?></textarea>
        </div>

        <!-- Amenazas -->
        <div class="matriz-item">
            <h2>Amenazas</h2>
            <textarea name="amenazas"><?php echo $amenazas ? htmlspecialchars($amenazas) : ''; ?></textarea>
        </div>

        <!-- Botones -->
        <div class="button-container">
            <button type="submit">Guardar Cambios</button>
        </div>
    </form>

    <!-- Navegación -->
    <div class="navigation-buttons">
        <button class="btn-volver" onclick="window.location.href='dashboard.php';">Volver</button>
        <button class="btn-siguiente" onclick="window.location.href='final.php';">Siguiente</button>
    </div>
</div>

</body>
</html>
