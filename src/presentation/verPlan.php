<?php
include_once '../data/plan.php';
session_start();

// Verifica si el ID del plan ha sido pasado como parámetro en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID del plan no proporcionado.");
}

// Obtener el idusuario de la sesión
$idusuario = $_SESSION['idusuario'];

// Obtener el ID del plan desde la URL
$idplan = $_GET['id'];

$planData = new PlanData();
$plan = $planData->obtenerPlanPorId($idplan, $idusuario); // Método que debes implementar

if (!$plan) {
    die("Error: Plan no encontrado o no tienes permiso para acceder a este plan.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Plan</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .plan-details {
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        img {
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
            border: 1px solid #ddd;
            padding: 5px;
            background-color: #fff;
        }
        .button {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h1>Detalles del Plan</h1>
    <div class="plan-details">
        <img src="assets/uploads/<?php echo htmlspecialchars($plan['logo']); ?>" alt="Logo del plan">
        <h2><?php echo htmlspecialchars($plan['nombreempresa']); ?></h2>
        <p><strong>Fecha:</strong> <?php echo htmlspecialchars($plan['fecha']); ?></p>
        <p><strong>Promotores:</strong> <?php echo htmlspecialchars($plan['promotores']); ?></p>
        <!-- Aquí puedes agregar más campos si es necesario -->
        
        <button class="button" onclick="window.location.href='dashboard.php'">Volver al Dashboard</button>
    </div>
</body>
</html>
