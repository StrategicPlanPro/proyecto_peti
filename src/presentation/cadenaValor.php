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
    <title>Cadena de Valor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            display: flex;
            justify-content: space-between;
            margin: 50px auto;
            max-width: 1000px;
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            height: 700px; /* Dejamos que el contenedor crezca según el contenido */
            overflow: hidden;
        }
        .form-content, .info-content {
            padding: 20px;
        }
        .form-content {
            width: 60%;
            overflow-y: auto;
            height: auto; /* Cambiamos el valor de '100%' a 'auto' */
            max-height: 100%; /* Limitar la altura máxima para que no exceda el área visible */
            padding-bottom: 20px; /* Agregamos espacio debajo del contenido */
        }
        .info-content {
            width: 40%;
            background: linear-gradient(135deg, #5DBCD2, #f093fb);
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
            color: white;
        }
        .form-content h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #5DBCD2;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 24px;
        }
        .content {
            margin: 20px;
        }
        .content p {
            text-align: justify;
        }
        .image {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .image img {
            max-width: 80%;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        ul li {
            margin-bottom: 10px;
        }
        .form-content {
            height: 100%;
            overflow-y: scroll;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="form-content">
            <h1>Cadena de Valor</h1>
            <div class="content">
                <p>
                    Todas las actividades de una empresa forman la cadena de valor.
                </p>
                <p>
                    La Cadena de Valor es una herramienta que permite a la empresa identificar aquellas actividades o fases que pueden aportarle un mayor valor añadido al producto final. Intenta buscar fuentes de ventaja competitiva.
                </p>
                <p>
                    La empresa está formada por una secuencia de actividades diseñadas para añadir valor al producto o servicio según las distintas fases, hasta que se llega al cliente final.
                </p>
                <p>
                    Una cadena de valor genérica está constituida por tres elementos básicos:
                </p>

                <div class="image">
                    <img src="assets/images/cadenaValor.png" alt="Cadena de Valor">
                </div>

                <p><strong>Las Actividades Primarias</strong> son aquellas que tienen que ver con el producto/servicio, su producción, logística, comercialización, etc.</p>

                <ul>
                    <li>• Logística de entrada (recepción, almacenamiento, manipulación de materiales, inspección interna, devoluciones, inventarios, etc.)</li>
                    <li>• Operaciones (proceso de fabricación, ensamblaje, mantenimiento de equipos, mecanización, embalaje, etc.)</li>
                    <li>• Logística de salida (gestión de pedidos, almacenamiento de producto terminado, transporte, etc.)</li>
                    <li>• Marketing y ventas (comercialización, selección del canal de distribución, publicidad, promoción, política de precios, etc.)</li>
                    <li>• Servicios (reparación de productos, instalación, mantenimiento, servicios postventa, reclamaciones, reajustes del producto, etc.)</li>
                </ul>

                <p><strong>Las Actividades de Soporte</strong> o apoyo a las actividades primarias son:</p>

                <ul>
                    <li>• Infraestructura empresarial (administración, finanzas, contabilidad, calidad, relaciones públicas, asesoría legal, gerencia, etc.)</li>
                    <li>• Gestión de los recursos humanos (selección, contratación, formación, incentivos, etc.)</li>
                    <li>• Desarrollo tecnológico (telecomunicaciones, automatización, desarrollo de procesos e ingeniería, diseño, etc.)</li>
                    <li>• Abastecimiento (compras de materias primas, consumibles, equipos, servicios, etc.)</li>
                </ul>

                <p><strong>El Margen</strong> es la diferencia entre el valor total obtenido y los costes incurridos por la empresa para desempeñar las actividades generadoras de valor.</p>

                <!-- Diagrama del margen -->
                <div class="image">
                    <img src="assets/images/margen.png" alt="Diagrama del Margen">
                </div>

                <p>Cada eslabón de la cadena puede ser fuente de ventaja competitiva, ya sea porque se optimice (excelencia en la ejecución de una actividad) y/o mejore su coordinación con otra actividad.</p>
            
                <!-- Botón Siguiente alineado a la izquierda -->
                <div style="text-align: left; margin-top: 20px;">
                    <a href="cadenaValor2.php" style="padding: 10px 20px; background-color: #f76a6a; color: white; border: none; border-radius: 8px; font-size: 16px; text-decoration: none; display: inline-block;">
                        Siguiente
                    </a>
                </div>

            </div>
        </div>

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
    </div>
</body>
</html>
