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
    include_once '../data/foda_puntajes.php'; // Asegúrate de que este archivo se carga correctamente

    // Obtener el idusuario de la sesión
    $idusuario = $_SESSION['idusuario'];

    // Obtener la id del plan de la sesión
    $idPlan = $_SESSION['idPlan'];

    // Crear una instancia de PlanData y EvaluacionMatrizData
    $planData = new PlanData();
    $evaluacionData = new EvaluacionMatrizData(); // Usamos la clase correcta aquí

    // Obtener el plan utilizando ambos IDs
    $plan = $planData->obtenerPlanPorId($idPlan, $idusuario);

    // Validar los datos antes de usarlos
    $fortalezas = !empty($plan['fortalezas']) ? explode("\n", $plan['fortalezas']) : [];
    $debilidades = !empty($plan['debilidades']) ? explode("\n", $plan['debilidades']) : [];
    $oportunidades = !empty($plan['oportunidades']) ? explode("\n", $plan['oportunidades']) : [];
    $amenazas = !empty($plan['amenazas']) ? explode("\n", $plan['amenazas']) : [];

    // Fortalezas y Oportunidades predefinidas para la matriz cruzada
    $fortalezasPredeterminadas = ['F1', 'F2', 'F3', 'F4']; // Fortalezas predeterminadas
    $oportunidadesPredeterminadas = ['O1', 'O2', 'O3', 'O4']; // Oportunidades predeterminadas
    $amenazasPredeterminadas = ['A1', 'A2', 'A3', 'A4']; // Amenazas predeterminadas

    // Obtener los puntajes guardados
    $puntajesGuardados = $evaluacionData->obtenerPuntajes($idPlan);
    $puntajeFinalGuardado = isset($puntajesGuardados['puntaje_final']) ? $puntajesGuardados['puntaje_final'] : 0;
    $puntajesGuardados = isset($puntajesGuardados['puntajes']) ? json_decode($puntajesGuardados['puntajes'], true) : [];

    // Obtener los puntajes para la segunda matriz
    $puntajesGuardados2 = $evaluacionData->obtenerPuntajes2($idPlan);
    $puntajeFinalGuardado2 = isset($puntajesGuardados2['puntaje_final2']) ? $puntajesGuardados2['puntaje_final2'] : 0;
    $puntajesGuardados2 = isset($puntajesGuardados2['puntajes2']) ? json_decode($puntajesGuardados2['puntajes2'], true) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identificación de Estrategias</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Estilo para los botones y la tabla */
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

        /* Contenedor de la tabla */
        .table-container {
            margin: 20px auto;
            text-align: center;
            width: 90%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        table tbody tr:nth-child(odd) td {
            background-color: #f9f9f9;
        }

        table tbody tr:nth-child(even) td {
            background-color: #ffffff;
        }

        table th:nth-child(1) {
            background-color: #ffe4c4; /* Fortalezas */
        }

        table th:nth-child(2) {
            background-color: #d9fdd3; /* Debilidades */
        }

        table th:nth-child(3) {
            background-color: #ffdab9; /* Oportunidades */
        }

        table th:nth-child(4) {
            background-color: #d3e5ff; /* Amenazas */
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
                se refleja en el siguiente dibujo para identificar la estrategia más conveniente a llevar a cabo.
            </p>

            <div class="image">
                <img src="assets/images/idestrategia1.png" alt="Modelo Porter" class="image-external">
            </div>

            <p>
                A continuación se presentará la **Matriz Cruzada** con las **Fortalezas** en las filas y las **Oportunidades** en las columnas.
                El usuario podrá asignar un puntaje entre 1 y 4 para cada relación entre Fortaleza y Oportunidad.
            </p>
        </div>

        <!-- Título de la tabla -->
        <h2 style="text-align: center;">Matriz de Factores Internos y Externos</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Fortalezas</th>
                        <th>Debilidades</th>
                        <th>Oportunidades</th>
                        <th>Amenazas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Determinar el número máximo de filas
                    $maxRows = max(count($fortalezas), count($debilidades), count($oportunidades), count($amenazas));

                    // Llenar la tabla dinámicamente
                    for ($i = 0; $i < $maxRows; $i++) {
                        echo "<tr>";
                        echo "<td>" . ($fortalezas[$i] ?? '') . "</td>";
                        echo "<td>" . ($debilidades[$i] ?? '') . "</td>";
                        echo "<td>" . ($oportunidades[$i] ?? '') . "</td>";
                        echo "<td>" . ($amenazas[$i] ?? '') . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Título de la tabla de la matriz cruzada -->
        <h2 style="text-align: center;">Matriz de Fortalezas y Oportunidades</h2>
        <div class="table-container">
            <form action="../business/guardar_puntajes.php" method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>Fortalezas</th>
                            <?php
                            // Mostrar las Oportunidades en el encabezado de las columnas (predefinidas)
                            foreach ($oportunidadesPredeterminadas as $oportunidad) {
                                echo "<th>$oportunidad</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Mostrar las Fortalezas en las filas (predefinidas)
                        foreach ($fortalezasPredeterminadas as $fortaleza) {
                            echo "<tr>";
                            echo "<td>$fortaleza</td>";
                            // Mostrar las celdas para puntuar
                            foreach ($oportunidadesPredeterminadas as $oportunidad) {
                                // Recuperar el puntaje guardado si existe
                                $puntaje = isset($puntajesGuardados[$fortaleza][$oportunidad]) ? $puntajesGuardados[$fortaleza][$oportunidad] : '';
                                echo "<td><input type='number' name='puntaje[$fortaleza][$oportunidad]' min='1' max='4' value='$puntaje' required></td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Mostrar el puntaje final -->
                <div class="puntaje-final">
                    <label for="puntaje_final">Puntaje Final:</label>
                    <input type="number" id="puntaje_final" name="puntaje_final" value="<?= $puntajeFinalGuardado ?>" readonly>
                </div>
                <br>
                <br>

                <h2 style="text-align: center;">Matriz de Fortalezas y Amenazas</h2>
                <div class="table-container">
                    <form action="../business/guardar_puntajes.php" method="POST">
                        <table>
                            <thead>
                                <tr>
                                    <th>Fortalezas</th>
                                    <?php
                                    // Mostrar las Amenazas en el encabezado de las columnas
                                    foreach ($amenazasPredeterminadas as $amenaza) {
                                        echo "<th>$amenaza</th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Mostrar las Fortalezas en las filas
                                foreach ($fortalezasPredeterminadas as $fortaleza) {
                                    echo "<tr>";
                                    echo "<td>$fortaleza</td>";
                                    // Mostrar las celdas para puntuar
                                    foreach ($amenazasPredeterminadas as $amenaza) {
                                        // Recuperar el puntaje guardado si existe
                                        $puntaje = isset($puntajesGuardados2[$fortaleza][$amenaza]) ? $puntajesGuardados2[$fortaleza][$amenaza] : '';
                                        echo "<td><input type='number' name='puntaje2[$fortaleza][$amenaza]' min='1' max='4' value='$puntaje' required></td>";
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>

                        <!-- Mostrar el puntaje final -->
                        <div class="puntaje-final">
                            <label for="puntaje_final2">Puntaje Final:</label>
                            <input type="number" id="puntaje_final2" name="puntaje_final2" value="<?= $puntajeFinalGuardado2 ?>" readonly>
                        </div>

                        <div class="button-container">
                            <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
                            <button type="submit" class="btn-siguiente">Guardar Puntajes</button>
                        </div>
                    </form>
                </div>

            </form>
        </div>
    </div>
    <div class="info-content">
        <?php include('aside.php'); ?>
    </div>
</div>

</body>
</html>
