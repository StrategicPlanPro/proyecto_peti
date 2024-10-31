<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idusuario'])) {
    header("Location: login.php");
    exit();
}

include_once '../data/plan.php';

$idusuario = $_SESSION['idusuario'];
$planData = new PlanData();
$planes = $planData->obtenerPlanesPorUsuario($idusuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Planes</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* General page styling */
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

        /* Main container styling */
        .container {
            background-color: #ffffff;
            width: 80%;
            max-width: 900px;
            padding: 20px 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
            text-align: center;
            box-sizing: border-box;
        }

        /* Header and title */
        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        /* Header bar with logout button */
        .header {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 10px;
        }

        /* Button styling */
        .button {
            background: linear-gradient(to right, #ff6b6b, #ff7e7e);
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 25px; /* Rounded corners for buttons */
            transition: background-color 0.3s ease, color 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        .button:hover {
            background-color: #ff00ff; /* Fuchsia on hover */
        }
        .button:active {
            background-color: #800080; /* Purple on click */
        }
        .logout-button {
            background: linear-gradient(to right, #ff4e50, #ff6a6b);
        }
        .logout-button:hover {
            background-color: #e0e0e0; /* Light gray on hover for logout */
            color: #000000; /* Black text on hover */
        }
        .logout-button:active {
            background-color: #d0d0d0; /* Slightly darker gray on click for logout */
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #ddd;
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
            background-color: #f9f9f9;
        }
        .create-plan-button {
            margin: 20px 0;
            display: inline-block;
        }

        /* Message styling */
        .empty-message {
            color: #777;
            font-size: 16px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <button class="button logout-button" onclick="logout()">Cerrar Sesión</button>
        </div>

        <h1>Dashboard de Planes</h1>
        <button class="button create-plan-button" onclick="window.location.href='datosIniciales.php'">Crear Plan</button>

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
                            <td>
                                <?php if (!empty($plan['logo'])): ?>
                                    <img src="assets/uploads/<?php echo htmlspecialchars($plan['logo']); ?>" alt="Logo del plan" style="max-width: 80px; max-height: 80px; padding: 5px; border: 1px solid #ddd;">
                                <?php else: ?>
                                    <p>No Image</p>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($plan['nombreempresa']); ?></td>
                            <td>
                                <button class="button" onclick="redirectToDatosIniciales(<?php echo $plan['idplan']; ?>)">Ver Plan</button>
                                <button class="button" onclick="window.location.href='../business/descargarPDF.php?id=<?php echo $plan['idplan']; ?>'">Descargar PDF</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty-message">No hay planes creados.</p>
        <?php endif; ?>
    </div>

    <script>
        function logout() {
            window.location.href = '../business/cerrarSesion.php';
        }

        function redirectToDatosIniciales(idPlan) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../business/almacenarIdPlan.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    window.location.href = 'datosIniciales2.php';
                }
            };
            xhr.send('idPlan=' + encodeURIComponent(idPlan));
        }
    </script>
</body>
</html>
