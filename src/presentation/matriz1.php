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

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/indexStyle.css?v=1">
    <!--<link rel="stylesheet" href="assets/css/styles.css">-->

    <title>ANÁLISIS INTERNO: MATRIZ DE CRECIMIENTO - PARTICIPACIÓN BCG</title>
</head>
<body>

    <div class="container">
        <div class="content">
            <h1 style="font-weight: bold;">
                ANÁLISIS INTERNO: MATRIZ DE CRECIMIENTO - PARTICIPACIÓN BCG
            </h1>
            <p>
                Toda empresa debe analizar de forma periódica su cartera de productos y servicios.
            </p>
            <p>
                La <strong>Matriz de crecimiento - participación</strong>, conocida como <strong>Matriz BCG</strong>, es un método gráfico de análisis de cartera de negocios desarrollado por The Boston Consulting Group en la década de 1970. Su finalidad es ayudar a priorizar recursos entre distintas áreas de negocios o Unidades Estratégicas de Análisis (UEA), es decir, determinar en qué negocios se debe invertir, desinvertir o incluso abandonar.
            </p>
            <p>
                Se trata de una sencilla matriz con cuatro cuadrantes, cada uno de los cuales propone una estrategia diferente para una unidad de negocio. Cada cuadrante viene representado por una figura o icono.
            </p>
            <p>
                El eje vertical de la matriz define el crecimiento en el mercado, y el horizontal la cuota de mercado.
            </p>

            <h3>CUADRO RESUMEN DE LAS PRINCIPALES CARACTERÍSTICAS</h3>
            <table border="1" cellspacing="0" cellpadding="10" style="width: 100%; text-align: center;">
                <thead>
                    <tr>
                        <th>Características</th>
                        <th>Estrella</th>
                        <th>Incógnita</th>
                        <th>Vaca</th>
                        <th>Perro</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Cuota de mercado</td>
                        <td>Alta</td>
                        <td>Baja</td>
                        <td>Alta</td>
                        <td>Baja</td>
                    </tr>
                    <tr>
                        <td>Crecimiento del mercado</td>
                        <td>Alto</td>
                        <td>Alto</td>
                        <td>Bajo</td>
                        <td>Bajo</td>
                    </tr>
                    <tr>
                        <td>Estrategia en función de participación en el mercado</td>
                        <td>Crecer o mantenerse</td>
                        <td>Crecer</td>
                        <td>Mantenerse</td>
                        <td>Cosechar o desinvertir</td>
                    </tr>
                    <tr>
                        <td>Inversión requerida</td>
                        <td>Alta</td>
                        <td>Muy alta</td>
                        <td>Baja</td>
                        <td>Baja, desinvertir</td>
                    </tr>
                    <tr>
                        <td>Rentabilidad</td>
                        <td>Alta</td>
                        <td>Baja o negativa</td>
                        <td>Alta</td>
                        <td>Muy baja, negativa</td>
                    </tr>
                    <tr>
                        <td>Decisión estratégica</td>
                        <td>Potenciar</td>
                        <td>Evaluar</td>
                        <td>Mantener</td>
                        <td>Reestructurar o desinvertir</td>
                    </tr>
                </tbody>
            </table>

            <p>
                La situación idónea es tener una cartera equilibrada, es decir, productos y/o servicios con diferentes índices de crecimiento y diferentes cuotas o niveles de participación en el mercado.
            </p>
            
            <div class="btn-container">
                <a href="matriz.php" class="btn">Autodiagnóstico BCG</a>
            </div>

            
            
        </div>
        <div class="info-content">
                <?php include('aside.php'); ?>
        </div>
        
        
    </div>
    

</body>
</html>
