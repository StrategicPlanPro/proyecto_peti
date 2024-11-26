<?php
session_start(); // Iniciar la sesión

require_once '../data/plan.php'; // Asegúrate de incluir la clase que maneja los planes

// Verificar si el plan ha sido creado
if (!isset($_SESSION['idPlan'])) {
    // Redirigir al dashboard si no se ha creado un plan
    header("Location: ../presentation/dashboard.php");
    exit;
}

// Obtener el ID del plan desde la sesión
$idPlan = $_SESSION['idPlan'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Por Último</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .btn-volver, .btn-siguiente, .btn-guardar {
            background-color: gray;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 50px; /* Bordes más redondeados */
            transition: background-color 0.3s ease;
        }

        .btn-volver:hover, .btn-siguiente:hover, .btn-guardar:hover {
            background-color: #555; /* Cambia el color al pasar el ratón */
        }

        .btn-siguiente {
            background-color: #333; /* Color más oscuro para el botón "Siguiente" */
        }

        .button-container {
            display: flex;
            justify-content: space-between; /* Espacio entre los botones */
            margin-top: 10px; /* Margen superior */
        }

        .consultor, .redes-sociales {
            margin-top: 20px;
        }

        .consultor h3, .redes-sociales h3 {
            margin: 0;
            font-size: 1.2em;
            color: #0078D7;
        }

        .consultor p, .redes-sociales p {
            margin: 5px 0;
        }

        

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .footer img {
            max-width: 150px;
            display: block;
            margin: 0 auto 10px auto;
        }

        .footer p {
            margin: 0;
            font-size: 1.2em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-content">
            <h1>Por último...</h1>
            <p>
                Con su Plan empresarial de proyecto de inversión listo, ya sabe lo que su empresa tiene que hacer de aquí a unos años para alcanzar la misión, favorecer la visión y procurar lograr ventaja competitiva. Pero, se puede estar preguntando:
            </p>
            <ul>
                <li>¿Cómo debo llevarlo a cabo?</li>
                <li>¿Cómo puedo saber si las acciones responden a la estrategia identificada?</li>
                <li>¿Qué recursos tengo que emplear?</li>
                <li>¿Cuándo y cómo debo tomar las decisiones clave?</li>
            </ul>
            <p>
                Para ello, le proponemos que elabore y diseñe su Cuadro de Mando Integral.
            </p>
            <span class="highlight">
                Tenga presente que lo que de verdad diferencia a una empresa ganadora no es su mayor o menor habilidad para definir brillantes y extensas estrategias, sino su capacidad para llevarlas a la práctica, sabiendo saltar las barreras que habitualmente se interponen entre el diseño y su ejecución.
            </span>



            <div class="footer">
                <img src="assets/images/logo.png" alt="StrategicPlan Logo">
                <p>StrategicPlan</p>
            </div>

            <div class="button-container">
                <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
      
            </div>
        </div>

      

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
    </div>
</body>
</html>
