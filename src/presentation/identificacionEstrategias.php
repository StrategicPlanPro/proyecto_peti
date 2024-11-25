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

    // Obtener la id del plan de la sesión
    $idPlan = $_SESSION['idPlan'];

    // Crear una instancia de PlanData
    $planData = new PlanData();

    // Obtener el plan utilizando ambos IDs
    $plan = $planData->obtenerPlanPorId($idPlan, $idusuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identificación de Estrategias</title>
    <link rel="stylesheet" href="assets/css/styles.css">
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
        }

        .btn-volver:hover, .btn-siguiente:hover {
            background-color: #555;
        }

        .btn-siguiente {
            background-color: #333;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding: 10px;
        }
            
    </style>
</head>
<body>

    <div class="container2">
        <div class="form-content2">
            <h1 style="text-align: center;">Identificación de Estrategias</h1>
            <div class="content">
                <p>
                Tras el análisis realizado habiéndose identificado las oportunidades, amenazas, fortalezas y debilidades, es momento de identificar 
                la estrategia que debe seguir en su empresa para el logro de sus objetivos empresariales. Se trata de realizar una Matriz Cruzada tal y como 
                se refleja en el siguente dibujo para identificar la estrategía más conveniente a llevar a cabo. 
                </p>

                <div class="image">
                    <img src="assets/images/idestrategia1.png" alt="Modelo Porter" class="image-external">
                </div>

                <p>
                Pasemos a repasar de forma abreviada como funciona cada una de las cinco fuerzas.
                </p>
                
            </div>

            <!-- Contenedor de los botones -->
            <div class="button-container">
                <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
                <a href="matrizCAME.php" class="btn-siguiente">Siguiente</a>
            </div>
        </div>
        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
    </div>
</body>
</html>