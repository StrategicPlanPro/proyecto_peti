<?php
include_once '../data/plan.php';
session_start();

// Obtener el idusuario de la sesión
$idusuario = $_SESSION['idusuario'];  // idusuario en lugar de idUsuario

$planData = new PlanData();
$planes = $planData->obtenerPlanesPorUsuario($idusuario);  // Pasar idusuario
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Planes</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f8f8;
            color: #333;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        img {
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
            border: 1px solid #ddd;
            padding: 5px;
            background-color: #fff;
        }
        .empty-message {
            text-align: center;
            margin-top: 50px;
        }
        .create-link {
            color: #007bff;
            font-weight: bold;
            font-size: 16px;
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
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h1>Dashboard de Planes</h1>

    <!-- Si hay planes creados, mostrar la tabla -->
    <?php if (count($planes) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Nombre del Plan</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($planes as $plan): ?>
                    <tr>
                        <!-- Mostrar la imagen del plan -->
                        <td>
                            <?php if (!empty($plan['logo'])): ?>
                                <img src="assets/uploads/<?php echo htmlspecialchars($plan['logo']); ?>" alt="Logo del plan">
                            <?php else: ?>
                                <p>No Image</p>
                            <?php endif; ?>
                        </td>
                        <!-- Mostrar el nombre del plan -->
                        <td><?php echo htmlspecialchars($plan['nombreempresa']); ?></td>
                        <td>
                            <button class="button" onclick="window.location.href='verPlan.php?id=<?php echo $plan['idplan']; ?>'">Ver Plan</button>
                            <button class="button" onclick="window.location.href='../business/procesarPlan.php?id=<?php echo $plan['idplan']; ?>'">Descargar PDF</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-message">No hay planes creados. <a class="create-link" href="datosIniciales.php">Crear un plan</a></p>
    <?php endif; ?>
</body>
</html>
