<?php

require_once('../data/analisis_pest.php');

session_start();
if (!isset($_SESSION['idPlan'])) {
    die("ID de plan no encontrado en la sesión.");
}

$idPlan = $_SESSION['idPlan'];

// Instancia de la clase que maneja los planes
$pest = new AnalisisPest();

$respuestasGuardadas = $pest->obtenerPest($idPlan);

$respuestas = $respuestasGuardadas ? explode(",", $respuestasGuardadas) : array_fill(0, 25, 0);

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis PEST</title>
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
                        // Generar las filas para cada enunciado
                        foreach ($enunciados as $index => $enunciado) {
                            $valorSeleccionado = isset($respuestas[$index]) ? $respuestas[$index] : 0; // Usar $respuestasGuardadas
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

            <!-- Botón para realizar el análisis -->
            <div class="center">
                <button type="submit" name="guardarAnalisis" class="button-save">Realizar Análisis PEST</button>
            </div>
        </form>

        <!-- Botones para navegación -->
        <div class="center" style="display: flex; justify-content: space-between; margin-top: 20px;">
            <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
            <a href="matriz1.php" class="btn-siguiente">Siguiente</a>
        </div>
    </div>

</body>
</html>
