<?php
    // Incluir archivos de la carpeta Business
    require_once '../business/procesarMatriz.php';

    // Llamar a la función para cargar fortalezas y debilidades
    $fortalezasDebilidades = cargarFortalezasYDebilidades($pdo, $idplan);
    $fortalezas = $fortalezasDebilidades['fortalezas'];
    $debilidades = $fortalezasDebilidades['debilidades'];

    // Llamar a la función para cargar los productos desde la base de datos
    $productos = cargarProductosDesdeBD($pdo, $idplan);
    $_SESSION['productos'] = cargarProductosDesdeBD($pdo, $idplan);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Matriz B.C.G</title>
    <link rel="stylesheet" href="assets/css/estilosmatriz.css">
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
    <style>
        .info-content {
            width: 200px;  /* Reducir el ancho */
            background: linear-gradient(135deg, #f76a6a, #f85b6b, #f91c6d);
            border-radius: 10px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: absolute; /* Posicionarlo de forma absoluta */
            top: 50px; /* Ajustar según la altura necesaria */
            right: 20px; /* Mantener a la derecha */
        }

        .info-content h2 {
            text-align: center;
            font-size: 18px; /* Ajustar tamaño de fuente */
            margin-bottom: 15px;
        }

        .info-content ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .info-content ul li {
            margin-bottom: 10px;
        }

        .info-content ul li a {
            text-decoration: none;
            font-size: 14px; /* Tamaño reducido */
            color: white; /* Color del texto blanco */
            background: none; /* Sin fondo */
            padding: 5px 0; /* Solo agregar espacio vertical, no fondo */
            font-weight: normal;
            display: block;
            transition: color 0.3s ease;
        }

        .info-content ul li a:hover {
            color: #ffd1d1; /* Efecto de hover */
        }

    </style>
</head>
    <body>
        <?php
            $productos = cargarProductosDesdeBD($pdo, $idplan);
            $_SESSION['productos'] = cargarProductosDesdeBD($pdo, $idplan);  
        ?>

        <!-- PRIMERA PARTE: Ingreso de Productos -->
        <div class="primeraparte-container">
            <h1>Productos</h1>
            <form method="POST">
                <label for="producto">Nombre del Producto:</label>
                <input type="text" id="producto" name="producto" required>
                <button type="submit" name="agregarProducto">Agregar Producto</button>
            </form>

            <form method="POST" style="margin-top: 10px;">
                <button type="submit" name="limpiarSesion">Limpiar Productos de Sesión</button>
            </form>
        </div>

        <!-- SEGUNDA PARTE: Productos Ingresados -->
        <div class="segundaparte-container">
            <h4>Productos Ingresados</h4>
            <ul>
                <?php foreach ($productos as $index => $producto): ?>
                    <li>
                        <?= htmlspecialchars($producto['nombre']); ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="index" value="<?= $index; ?>">
                            <button type="submit" name="eliminarProducto" class="delete-button">Eliminar</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if (count($_SESSION['productos']) > 0): ?>
            <!-- TERCERA PARTE: Previsión de Ventas -->
            <div class="terceraparte-container">
                <h4>Previsión de Ventas</h4>
                <form method="POST">
                    <table>
                        <tr class="header-green">
                            <th>Productos</th>
                            <th>Ventas</th>
                            <th>% Ventas Total</th>
                        </tr>

                        <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td>
                                    <input type="number" step="10" name="ventas[<?php echo $index; ?>]" 
                                        value="<?php echo $ventas[$index]; ?>" required>
                                </td>
                                <td>
                                    <?php 
                                    try {
                                        $porcentaje = ($totalVentas > 0 && isset($ventas[$index]) && $ventas[$index] !== null) 
                                            ? ($ventas[$index] / $totalVentas) * 100 
                                            : 0;
                                    } catch (Exception $e) {
                                        $porcentaje = 0;
                                    }
                                    echo number_format($porcentaje, 2) . '%'; 
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <tr class="header-gray">
                            <td>Total</td>
                            <td><?php echo number_format($totalVentas, 2); ?></td>
                            <td>100%</td>
                        </tr>
                    </table>
                    <button type="submit">Ingresar ventas</button>
                </form>
            </div>

            <!-- CUARTA PARTE: Tasas de Crecimiento del Mercado (TCM) -->
            <div class="cuartaparte-container">
                <h4>Tasas de Crecimiento del Mercado (TCM)</h4>
                <form action="" method="POST"> 
                    <table>
                        <tr class="header-green">
                            <th>Períodos</th>
                            <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                <th><?php echo htmlspecialchars($producto['nombre']); ?></th>
                            <?php endforeach; ?>
                        </tr>

                        <?php 
                        $periodos = ['2019 - 2020', '2020 - 2021', '2021 - 2022', '2022 - 2023']; 
                        $columnas = ['tsc1', 'tsc2', 'tsc3', 'tsc4']; 
                        ?>

                        <?php foreach ($periodos as $i => $periodo): ?>
                            <tr class="header-gray">
                                <th><?php echo $periodo; ?></th>
                                <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                    <td>
                                        <input 
                                            type="number" 
                                            step="0.01" 
                                            name="<?php echo $columnas[$i]; ?>[<?php echo $index; ?>]" 
                                            placeholder="0.00" 
                                            value="<?php echo htmlspecialchars($producto[$columnas[$i]] ?? ''); ?>" 
                                            required>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <button type="submit" name="guardarTcm">Ingresar TCM</button>
                </form>
            </div>
                                       
            <!-- QUINTA PARTE: Participación Relativa del Mercado (PRM) -->
            <div class="quintaparte-container">
                <h2>Participación Relativa del Mercado (PRM)</h2>
                <table>
                    <tr class="header-red">
                        <th>Producto</th>
                        <th>TCM</th>
                        <th>PRM</th>
                        <th>% SVTAS</th>
                    </tr>
                    <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td>
                                <?php
                                $stmt = $pdo->prepare("SELECT tsc1, tsc2, tsc3, tsc4 FROM producto WHERE nombre = :nombre AND idplan = :idplan");
                                $stmt->execute([':nombre' => $producto['nombre'], ':idplan' => $idplan]);
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                $tcmTotal = $row ? array_sum($row) : 0;
                                $tcmPromedio = $tcmTotal / 4;
                                echo number_format($tcmPromedio, 2) . '%';
                                ?>
                            </td>
                            <td>0.00</td>
                            <td>
                                <?php 
                                // Verificar si el índice existe y si hay ventas totales para evitar el error
                                if (isset($ventas[$index]) && $totalVentas > 0) {
                                    echo number_format(($ventas[$index] / $totalVentas) * 100, 2) . '%';
                                } else {
                                    echo '0.00%';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- SEXTA PARTE: Evolución de la Demanda Global del Sector -->
            <div class="sextaparte-container">
                <h4>Evolución de la Demanda Global del Sector</h4>
                <form action="" method="POST">
                    <table>
                        <tr class="header-green">
                            <th>Años</th>
                            <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                <th><?php echo htmlspecialchars($producto['nombre']); ?></th>
                            <?php endforeach; ?>
                        </tr>

                        <?php 
                        $años = ['dgs1', 'dgs2', 'dgs3', 'dgs4', 'dgs5']; 
                        $nombresAños = ['2019', '2020', '2021', '2022', '2023'];
                        ?>

                        <?php foreach ($nombresAños as $i => $año): ?>
                            <tr class="header-gray">
                                <th><?php echo $año; ?></th>
                                <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                    <td>
                                        <input 
                                            type="number" 
                                            step="1" 
                                            name="<?php echo $años[$i]; ?>[<?php echo $index; ?>]" 
                                            placeholder="0.00" 
                                            value="<?php echo htmlspecialchars($producto[$años[$i]] ?? ''); ?>" 
                                            required>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <button type="submit" name="guardarDgs">Guardar Demanda</button>
                </form>
            </div>

            <!-- SÉPTIMA PARTE: Niveles de Venta de los Competidores de Cada Producto -->
            <div class="septimaparte-container">
                <h4>Niveles de Venta de los Competidores de Cada Producto</h4>
                <form action="" method="POST">
                    <table>
                        <tr class="header-yellow">
                            <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                <th colspan="2" style="text-align: center;">
                                    <?php echo htmlspecialchars($producto['nombre']); ?> (<?php echo htmlspecialchars($producto['ventas'] ?? 0); ?>)
                                </th>
                            <?php endforeach; ?>
                        </tr>

                        <tr>
                            <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                <th>Competidor</th>
                                <th>Ventas</th>
                            <?php endforeach; ?>
                        </tr>

                        <?php for ($competidor = 1; $competidor <= 9; $competidor++): ?>
                            <tr>
                                <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                    <td>CP<?php echo $competidor; ?>-<?php echo $index + 1; ?></td>
                                    <td>
                                        <input type="number" step="1" 
                                            name="niveles_ventas[<?php echo $index; ?>][CP<?php echo $competidor; ?>]" 
                                            placeholder="0" 
                                            value="<?php echo htmlspecialchars($producto['compe' . $competidor] ?? 0); ?>">
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endfor; ?>

                        <tr class="header-gray">
                            <th>Mayor</th>
                            <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                <td colspan="2" style="text-align: center;">
                                    <span id="mayor-text-<?php echo $index; ?>">
                                        <?php echo isset($producto['mayor']) ? htmlspecialchars($producto['mayor']) : 'N/A'; ?>
                                    </span>
                                    <input type="hidden" id="mayor-<?php echo $index; ?>" name="mayor[<?php echo $index; ?>]" value="<?php echo isset($producto['mayor']) ? htmlspecialchars($producto['mayor']) : '0'; ?>">
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </table>
                    <button type="submit" name="guardarCompetencia">Guardar Niveles de Competencia</button>
                </form>
            </div>

            <!-- MATRIZ BCG -->
            <div class="matrizbcg-container">
                <h4>Matriz BCG</h4>
                <form method="POST">
                    <button type="submit" name="generarMatrizBCG">Generar Matriz BCG</button>
                </form>

                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generarMatrizBCG'])): ?>
                    <?php
                        $resultados = generarMatrizBCG($pdo, $idplan);
                        $clasificacion = $resultados['clasificacion'];
                        $decisiones = $resultados['decisiones'];
                    ?>
                    <table border="1">
                        <tr class="header-blue">
                            <th>Producto(s)</th>
                            <th>Clasificación</th>
                            <th>Decisión Estratégica</th>
                        </tr>
                        <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                            <tr class="product-<?php echo ($index + 1); ?>">
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($clasificacion[$index]); ?></td>
                                <td><?php echo htmlspecialchars($decisiones[$index]); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <div class="fortalezas-debilidades-container">
            <form method="POST">
                <label for="fortalezas">Fortalezas:</label><br>
                <textarea name="fortalezas" id="fortalezas" rows="4" cols="50"><?php echo htmlspecialchars($fortalezas); ?></textarea><br>

                <label for="debilidades">Debilidades:</label><br>
                <textarea name="debilidades" id="debilidades" rows="4" cols="50"><?php echo htmlspecialchars($debilidades); ?></textarea><br>

                <input type="submit" name="guardarFortalezasDebilidades" value="Guardar cambios">
            </form>
        </div>

        <?php
            // Guardar las fortalezas y debilidades si el formulario es enviado
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarFortalezasDebilidades'])) {
                $nuevasFortalezas = $_POST['fortalezas'];
                $nuevasDebilidades = $_POST['debilidades'];

                try {
                    // Actualizar fortalezas y debilidades en la base de datos
                    $stmt = $pdo->prepare("UPDATE plan SET fortalezas = :fortalezas, debilidades = :debilidades WHERE idplan = :idplan");
                    $stmt->execute([
                        ':fortalezas' => $nuevasFortalezas,
                        ':debilidades' => $nuevasDebilidades,
                        ':idplan' => $idplan
                    ]);

                    echo "<script>alert('Fortalezas y debilidades actualizadas correctamente.');</script>";
                } catch (PDOException $e) {
                    echo "<script>alert('Error al guardar fortalezas y debilidades: " . addslashes($e->getMessage()) . "');</script>";
                }
            }
        ?>  
        <!-- BOTONES -->
        <div>
            <button class="back-button" onclick="window.location.href='dashboard.php'">Volver</button>
            <button class="next-button" onclick="window.location.href='matrizPorter1.php'">Siguiente</button>
        </div>
    </body>
</html>