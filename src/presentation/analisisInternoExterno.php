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
$idusuario = $_SESSION['idusuario'];  // idusuario en lugar de idUsuario

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
    <title>Análisis Interno y Externo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            display: flex;
            justify-content: space-between;
            margin: 50px auto;
            max-width: 1000px; /* Hacemos más pequeño el contenedor */
            background-color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            height: 700px; /* Altura limitada para agregar scroll */
            overflow: hidden;
        }
        .form-content, .info-content {
            padding: 20px;
        }
        .form-content {
            width: 60%;
            overflow-y: auto; /* Agrega scroll en la parte del contenido */
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
        .box {
            width: 200px;
            border: 2px solid #5DBCD2;
            padding: 10px;
            text-align: center;
        }
        .image {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .image img {
            max-width: 40%;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .image-external {
            max-width: 35%;
            height: auto;
        }
        .foda-image img {
            max-width: 40%;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .btn-volver {
            background-color: gray;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            margin-top: 20px;
            display: inline-block;
        }
        /* Estilo para el scroll */
        .form-content {
            height: 100%;
            overflow-y: scroll; /* Agregamos el scroll vertical */
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="form-content">
            <h1>Análisis Interno y Externo</h1>
            <div class="content">
                <p>
                    Fijados los objetivos estratégicos se debe analizar las distintas estrategias para lograrlos.
                    De esta forma, las estrategias son los caminos, vías o enfoques para alcanzar los objetivos. Responden a la pregunta <strong>¿cómo?</strong>.
                </p>
                <p>
                    Para determinar la estrategia, podríamos basarnos en el conjunto de estrategias genéricas y específicas que diferentes profesionales proponen al respecto.
                    Esta guía, lejos de rozar la teoría, propone llevar a cabo un análisis interno y externo de su empresa para obtener una matriz cruzada y identificar la estrategia más conveniente a seguir.
                    Este análisis le permitirá detectar por un lado los factores de éxito (fortalezas y oportunidades), y por otro lado, las debilidades y amenazas que una empresa debe gestionar.
                </p>

                <div class="image">
                    <img src="assets/images/analisisExterno.png" alt="Diagrama FODA" class="image-external">
                </div>

                <div class="image">
                    <img src="assets/images/analisisInterno.png" alt="Diagrama FODA">
                </div>

                <h3>Oportunidades:</h3>
                <p>
                    Aquellos aspectos que pueden presentar una posibilidad para mejorar la rentabilidad de la empresa, aumentar la cifra de negocio y fortalecer la ventaja competitiva.
                </p>
                <p>
                    <strong>Ejemplos:</strong> Fuerte crecimiento, desarrollo de la externalización, nuevas tecnologías, seguridad de la distribución, atender a grupos adicionales de clientes, crecimiento rápido del mercado, etc.
                </p>

                <h3>Amenazas:</h3>
                <p>
                    Son fuerzas y presiones del mercado-entorno que pueden impedir y dificultar el crecimiento de la empresa, la ejecución de la estrategia, reducir su eficacia o incrementar los riesgos en relación con el entorno y sector de actividad.
                </p>
                <p>
                    <strong>Ejemplos:</strong> Competencia en el mercado, aparición de nuevos competidores, reglamentación, monopolio en una materia prima, cambio en las necesidades de los consumidores, creciente poder de negociación de clientes y/o proveedores, etc.
                </p>

                <h3>Fortalezas:</h3>
                <p>
                    Son capacidades, recursos, posiciones alcanzadas, ventajas competitivas que posee la empresa y que le ayudarán a aprovechar las oportunidades del mercado.
                </p>
                <p>
                    <strong>Ejemplos:</strong> Buena implantación en el territorio, notoriedad de la marca, capacidad de innovación, recursos financieros adecuados, ventajas en costes, líder en el mercado, buena imagen entre los consumidores, etc.
                </p>

                <h3>Debilidades:</h3>
                <p>
                    Son todos aquellos aspectos que limitan o reducen la capacidad de desarrollo de la empresa. Constituyen dificultades para la organización y deben, por tanto, ser controladas y superadas.
                </p>
                <p>
                    <strong>Ejemplos:</strong> Precios elevados, productos en el final de su ciclo de vida, deficiente control de riesgos, recursos humanos poco cualificados, débil imagen en el mercado, red de distribución débil, no hay dirección estratégica clara, etc.
                </p>

                <h3>Análisis FODA</h3>
                <p>
                    Para elaborar el análisis FODA de su empresa, le proponemos que utilice distintos instrumentos para el análisis tanto interno como externo.
                </p>

                <div class="image">
                    <img src="assets/images/FODA.png" alt="Diagrama FODA">
                </div>
            </div>
        </div>

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
    </div>
</body>
</html>
