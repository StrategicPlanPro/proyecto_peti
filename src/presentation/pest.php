<?php

require_once('../data/analisis_pest.php');

session_start();
if (!isset($_SESSION['idPlan'])) {
    die("ID de plan no encontrado en la sesión.");
}

$idPlan = $_SESSION['idPlan'];

$pest = new AnalisisPest();

$respuestasGuardadas = $pest->obtenerPest($idPlan);
$respuestas = $respuestasGuardadas ? explode(",", $respuestasGuardadas) : array_fill(0, 25, 0);

    // Calcular los puntajes de cada factor basados en las respuestas
    $puntajesCalculados = [
        'social' => 0,
        'ambiental' => 0,
        'politico' => 0,
        'economico' => 0,
        'tecnologico' => 0
    ];
    
    // Asumiendo que las respuestas están ordenadas según los factores
    // Esto depende de cómo se organice la respuesta en el formulario
    $puntajesCalculados['social'] = (array_sum(array_slice($respuestas, 0, 5)) / 5) * 25; // Social: 5 respuestas
    $puntajesCalculados['ambiental'] = (array_sum(array_slice($respuestas, 5, 5)) / 5) * 25; // Ambiental: 5 respuestas
    $puntajesCalculados['politico'] = (array_sum(array_slice($respuestas, 10, 5)) / 5) * 25; // Político: 5 respuestas
    $puntajesCalculados['economico'] = (array_sum(array_slice($respuestas, 15, 5)) / 5) * 25; // Económico: 5 respuestas
    $puntajesCalculados['tecnologico'] = (array_sum(array_slice($respuestas, 20, 5)) / 5) * 25; // Tecnológico: 5 respuestas

// Enunciados del análisis PEST
$enunciados = [
    // Económico
    "Los cambios en la composición étnica de los consumidores de nuestro mercado está teniendo un notable impacto.",
    "El envejecimiento de la población tiene un importante impacto en la demanda.",
    "Las variaciones en el nivel de riqueza de la población impactan considerablemente en la demanda de los productos/servicios del sector.",
    "La legislación fiscal afecta muy considerablemente a la economía de las empresas del sector.",
    "Las expectativas de crecimiento económico generales afectan crucialmente al mercado.",
    // Político
    "La legislación laboral afecta muy considerablemente a la operativa del sector.",
    "Las subvenciones otorgadas por las Administraciones Públicas son claves en el desarrollo competitivo del mercado.",
    "El impacto que tiene la legislación de protección al consumidor es muy importante.",
    "La normativa autonómica tiene un impacto considerable en el funcionamiento del sector.",
    "Las Administraciones Públicas están incentivando el esfuerzo tecnológico de las empresas de nuestro sector.",
    // Social
    "Los nuevos estilos de vida y tendencias originan cambios en la oferta de nuestro sector.",
    "El envejecimiento de la población tiene un importante impacto en la oferta del sector donde operamos.",
    "La globalización permite a nuestra industria gozar de importantes oportunidades en nuevos mercados.",
    "La situación del empleo es fundamental para el desarrollo económico de nuestra empresa.",
    "Los clientes de nuestro mercado exigen que seamos socialmente responsables, en el plano medioambiental.",
    // Tecnológico
    "Internet, el comercio electrónico, el wireless y otras NTIC están impactando en la demanda de nuestros productos/servicios.",
    "El empleo de NTIC´s es generalizado en el sector.",
    "En nuestro sector, es de gran importancia ser pionero o referente en el empleo de aplicaciones tecnológicas.",
    "En el sector donde operamos, para ser competitivos, es condición innovar constantemente.",
    "Los recursos tecnológicos son una ventaja competitiva clave.",
    // Ambiental
    "La legislación medioambiental afecta al desarrollo de nuestro sector.",
    "En nuestro sector, las políticas medioambientales son una fuente de ventajas competitivas.",
    "La creciente preocupación social por el medio ambiente impacta notablemente en la demanda de productos/servicios.",
    "El factor ecológico es una fuente de diferenciación clara en el sector."
];

// Lógica para generar conclusiones
function generarConclusion($respuestas, $inicio, $cantidad) {
    $rangos = array_slice($respuestas, $inicio, $cantidad);

    $deAcuerdo = count(array_filter($rangos, fn($v) => $v == 2));
    $bastanteDeAcuerdo = count(array_filter($rangos, fn($v) => $v == 3));
    $enTotalAcuerdo = count(array_filter($rangos, fn($v) => $v == 4));

    // Evaluar conclusiones según las combinaciones especificadas
    if ($bastanteDeAcuerdo >= 3 || $enTotalAcuerdo >= 2) {
        return "HAY UN NOTABLE IMPACTO DEL FACTOR EN EL FUNCIONAMIENTO DE LA EMPRESA.";
    } elseif ($deAcuerdo >= 2 && $bastanteDeAcuerdo >= 2) {
        return "NO HAY UN NOTABLE IMPACTO DEL FACTOR EN EL FUNCIONAMIENTO DE LA EMPRESA.";
    } else {
        return "IMPACTO DEL FACTOR NO CLARAMENTE DEFINIDO.";
    }
}

// Generar conclusiones para cada factor
$conclusiones = [
    "Económico" => generarConclusion($respuestas, 0, 5),
    "Político" => generarConclusion($respuestas, 5, 5),
    "Social" => generarConclusion($respuestas, 10, 5),
    "Tecnológico" => generarConclusion($respuestas, 15, 5),
    "Ambiental" => generarConclusion($respuestas, 20, 5)
];
// Obtener los puntajes de los factores (si existen)
$puntajes = [
    'social' => $_POST['puntaje_social'] ?? 0,
    'ambiental' => $_POST['puntaje_ambiental'] ?? 0,
    'politico' => $_POST['puntaje_politico'] ?? 0,
    'economico' => $_POST['puntaje_economico'] ?? 0,
    'tecnologico' => $_POST['puntaje_tecnologico'] ?? 0
];

// Recuperar oportunidades y amenazas desde la base de datos
$oportunidades = "";  // Valor predeterminado vacío
$amenazas = "";  // Valor predeterminado vacío

// Método para obtener las oportunidades y amenazas de la base de datos
$oportunidadesAmenazas = $pest->obtenerOportunidadesAmenazas($idPlan);
if ($oportunidadesAmenazas) {
    $oportunidades = $oportunidadesAmenazas['oportunidades'];
    $amenazas = $oportunidadesAmenazas['amenazas'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis PEST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Estilos del formulario */
        .btn-volver, .btn-siguiente {
            background-color: gray;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            border-radius: 25px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .btn-volver:hover, .btn-siguiente:hover {
            background-color: #555;
        }
        .btn-siguiente {
            background-color: #333;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 100%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .center {
            text-align: center;
            margin-top: 20px;
        }
        .textarea-title {
            font-size: 16px;
            margin-top: 20px;
            font-weight: bold;
            color: #333;
        }
        .textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="center">Análisis PEST</h2>

    <form method="POST" action="../business/procesar_analisis_pest.php">
        <input type="hidden" name="conclusion_economico" value="<?php echo $conclusiones['Económico']; ?>">
        <input type="hidden" name="conclusion_politico" value="<?php echo $conclusiones['Político']; ?>">
        <input type="hidden" name="conclusion_social" value="<?php echo $conclusiones['Social']; ?>">
        <input type="hidden" name="conclusion_tecnologico" value="<?php echo $conclusiones['Tecnológico']; ?>">
        <input type="hidden" name="conclusion_ambiental" value="<?php echo $conclusiones['Ambiental']; ?>">
        <table>
            <thead>
            <tr>
                <th>Enunciado</th>
                <th>En total en desacuerdo</th>
                <th>No está de acuerdo</th>
                <th>Está de acuerdo</th>
                <th>Está bastante de acuerdo</th>
                <th>En total acuerdo</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($enunciados as $index => $enunciado) {
                $valorSeleccionado = isset($respuestas[$index]) ? $respuestas[$index] : 0;
                echo "<tr>
                        <td>$enunciado</td>
                        <td><input type='radio' name='respuesta_" . ($index + 1) . "' value='0' " . ($valorSeleccionado == 0 ? 'checked' : '') . "></td>
                        <td><input type='radio' name='respuesta_" . ($index + 1) . "' value='1' " . ($valorSeleccionado == 1 ? 'checked' : '') . "></td>
                        <td><input type='radio' name='respuesta_" . ($index + 1) . "' value='2' " . ($valorSeleccionado == 2 ? 'checked' : '') . "></td>
                        <td><input type='radio' name='respuesta_" . ($index + 1) . "' value='3' " . ($valorSeleccionado == 3 ? 'checked' : '') . "></td>
                        <td><input type='radio' name='respuesta_" . ($index + 1) . "' value='4' " . ($valorSeleccionado == 4 ? 'checked' : '') . "></td>
                    </tr>";
            }
            ?>
            </tbody>
        </table>

        <div class="center">
            <button type="submit" name="guardarAnalisis" class="button-save">Realizar Análisis PEST</button>
        </div>

    <h3 class="center">Conclusiones</h3>
    <ul>
        <?php
        foreach ($conclusiones as $factor => $conclusion) {
            echo "<li><strong>$factor:</strong> $conclusion</li>";
        }
        ?>
    </ul>

    <div class="center">
        <button type="submit" name="guardarConclusiones" class="button-save">Guardar Conclusiones</button>
    </div>
    
    <div class="mt-4">
        <h4>Gráfico de Impacto de los Factores Externos</h4>
        <canvas id="impactoFactores" width="400" height="200"></canvas>
    </div>

    <div class="mt-4">
    <h4>Oportunidades</h4>
        <textarea name="oportunidades" class="textarea" placeholder="Escribe las oportunidades aquí..."><?php echo htmlspecialchars($oportunidades); ?></textarea>
    </div>

    <div class="mt-4">
        <h4>Amenazas</h4>
        <textarea name="amenazas" class="textarea" placeholder="Escribe las amenazas aquí..."><?php echo htmlspecialchars($amenazas); ?></textarea>
    </div>

    <div class="center mt-4">
        <button type="submit" name="guardarOportunidadesAmenazas" class="btn-siguiente">Guardar Oportunidades y Amenazas</button>
    </div>
</form>

    <div class="center">
        <a href="dashboard.php" class="btn-volver">Volver</a>
    </div>
</div>

<script>
    // Crear el gráfico de barras con Chart.js
    var ctx = document.getElementById('impactoFactores').getContext('2d');
    var impactoFactores = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Social', 'Ambiental', 'Político', 'Económico', 'Tecnológico'],
            datasets: [{
                label: 'Nivel de Impacto de Factores Externos',
                data: [
                    <?= $puntajesCalculados['social'] ?>,
                    <?= $puntajesCalculados['ambiental'] ?>,
                    <?= $puntajesCalculados['politico'] ?>,
                    <?= $puntajesCalculados['economico'] ?>,
                    <?= $puntajesCalculados['tecnologico'] ?>
                ],
                backgroundColor: ['#007bff', '#28a745', '#dc3545', '#ffc107', '#6f42c1'],
                borderColor: ['#0056b3', '#218838', '#c82333', '#e0a800', '#5a3e93'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
</body>
</html>
