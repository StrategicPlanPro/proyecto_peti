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

    // Validar los datos antes de usarlos
    $fortalezas = !empty($plan['fortalezas']) ? explode("\n", $plan['fortalezas']) : [];
    $debilidades = !empty($plan['debilidades']) ? explode("\n", $plan['debilidades']) : [];
    $oportunidades = !empty($plan['oportunidades']) ? explode("\n", $plan['oportunidades']) : [];
    $amenazas = !empty($plan['amenazas']) ? explode("\n", $plan['amenazas']) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identificación de Estrategias</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Botones */
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

        /* Estilo por categoría */
        table tbody tr:nth-child(odd) td {
            background-color: #f9f9f9; /* Color claro para filas impares */
        }

        table tbody tr:nth-child(even) td {
            background-color: #ffffff; /* Color blanco para filas pares */
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
                se refleja en el siguente dibujo para identificar la estrategía más conveniente a llevar a cabo. 
                </p>

                <div class="image">
                    <img src="assets/images/idestrategia1.png" alt="Modelo Porter" class="image-external">
                </div>

                <p>
                Pasemos a repasar de forma abreviada como funciona cada una de las cinco fuerzas.
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

        <!-- Matriz Cruzada de Estrategias -->
        <h2 style="text-align: center;">Matriz de Estrategias Cruzadas</h2>
        <div class="table-container">
            <!-- Fortalezas / Oportunidades -->
            <h3>Fortalezas / Oportunidades</h3>
            <table class="fortalezas-oportunidades">
                <thead>
                    <tr>
                        <th>Fortalezas / Oportunidades</th>
                        <?php foreach ($oportunidades as $j => $oportunidad): ?>
                            <th>O<?php echo $j + 1; ?></th>  <!-- Reemplazar por código O1, O2, O3, ... -->
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fortalezas as $i => $fortaleza): ?>
                        <tr>
                            <!-- Reemplazar por código F1, F2, F3, ... -->
                            <td>F<?php echo $i + 1; ?></td>
                            <?php foreach ($oportunidades as $j => $oportunidad): ?>
                                <td>
                                    <select id="fortaleza_oportunidad_<?php echo $i . '_' . $j; ?>" class="select-strategy" onchange="calcularTotal()">
                                        <option value="0">Seleccione</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total-puntos">
                Total de puntos (Fortalezas / Oportunidades): <span id="total-fortalezas-oportunidades">0</span>
            </div>
        </div>

            <script>
                // Función para calcular el total de puntos
                function calcularTotal() {
                    let total = 0;
                    
                    // Obtener todos los selectores de la tabla
                    const selects = document.querySelectorAll('.select-strategy');
                    
                    // Recorrer cada selector y sumar los valores seleccionados
                    selects.forEach(select => {
                        total += parseInt(select.value) || 0; // Si no se selecciona un valor, se considera 0
                    });
                    
                    // Actualizar el total en la interfaz
                    document.getElementById('total-fortalezas-oportunidades').innerText = total;
                }
            </script>

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
