<?php
session_start();
require_once __DIR__ . '/../data/neonConnection.php'; // Conexión a la base de datos

// Instancia de la conexión
$conexion = new Conexion();
$pdo = $conexion->getConnection();
// Obtener idusuario e idplan
$idusuario = $_SESSION['idusuario'] ?? null;
$idplan = $_SESSION['idPlan'] ?? null;

// Inicializar productos si no están definidos en la sesión
if (!isset($_SESSION['productos']) || empty($_SESSION['productos'])) {
    // Cargar productos desde la base de datos y guardarlos en la sesión
    $_SESSION['productos'] = cargarProductosDesdeBD($pdo, $idplan);
}

// Ahora, usamos los productos desde la sesión para mostrarlos en la página.
$productos = $_SESSION['productos'];



// Limpiar productos de la sesión
if (isset($_POST['limpiarSesion'])) {
    $_SESSION['productos'] = [];
}

function obtenerVentas($pdo, $nombre, $idplan) {
    $stmt = $pdo->prepare("SELECT ventas FROM producto WHERE nombre = :nombre AND idplan = :idplan");
    $stmt->execute([':nombre' => $nombre, ':idplan' => $idplan]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? (int) $result['ventas'] : 0;
}

// Obtener ventas de los productos en la sesión
$ventas = [];
$totalVentas = 0;

foreach ($_SESSION['productos'] as $index => $producto) {
    $ventas[$index] = obtenerVentas($pdo, $producto['nombre'], $idplan);
    $totalVentas += $ventas[$index];
}

// Guardar ventas si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ventas'])) {
    guardarVentas($pdo, $_POST['ventas'], $idplan);
}

// Función para guardar ventas
function guardarVentas($pdo, $ventas, $idplan) {
    try {
        foreach ($ventas as $index => $venta) {
            $producto = $_SESSION['productos'][$index]['nombre'];
            $stmt = $pdo->prepare("UPDATE producto SET ventas = :ventas WHERE nombre = :nombre AND idplan = :idplan");
            $stmt->execute([':ventas' => $venta, ':nombre' => $producto, ':idplan' => $idplan]);
        }
        echo "Ventas guardadas correctamente.";
    } catch (PDOException $e) {
        echo "Error al guardar ventas: " . $e->getMessage();
    }
}

// Agregar producto
if (isset($_POST['agregarProducto'])) {
    $producto = trim($_POST['producto']);
    agregarProducto($pdo, $producto, $idplan);
}

function agregarProducto($pdo, $producto, $idplan) {
    
    try {
        $stmt = $pdo->prepare("INSERT INTO producto (nombre, idplan) VALUES (:nombre, :idplan)");
        if ($stmt->execute([':nombre' => $producto, ':idplan' => $idplan])) {
            $_SESSION['productos'][] = ['nombre' => $producto, 'idplan' => $idplan];
            echo "Producto agregado correctamente.";
        }
    } catch (PDOException $e) {
        echo "Error al agregar producto: " . $e->getMessage();
    }
}

// Eliminar producto
if (isset($_POST['eliminarProducto'])) {
    $index = $_POST['index'];
    eliminarProducto($pdo, $index);
}

function eliminarProducto($pdo, $index) {
    $productoData = $_SESSION['productos'][$index];
    try {
        $stmt = $pdo->prepare("DELETE FROM producto WHERE nombre = :nombre AND idplan = :idplan");
        $stmt->execute([':nombre' => $productoData['nombre'], ':idplan' => $productoData['idplan']]);

        // Eliminar de la sesión y reindexar
        unset($_SESSION['productos'][$index]);
        $_SESSION['productos'] = array_values($_SESSION['productos']);
    } catch (PDOException $e) {
        echo "Error al eliminar producto: " . $e->getMessage();
    }
}

// Función para guardar Tasas de Crecimiento del Mercado (TCM)
function guardarTcm($pdo, $tsc, $idplan) {
    try {
        foreach ($tsc as $index => $tasa) {
            $producto = $_SESSION['productos'][$index]['nombre'];
            // Actualiza los campos tsc1, tsc2, tsc3, tsc4 según el índice
            $stmt = $pdo->prepare("UPDATE producto SET tsc1 = :tsc1, tsc2 = :tsc2, tsc3 = :tsc3, tsc4 = :tsc4 WHERE nombre = :nombre AND idplan = :idplan");
            $stmt->execute([
                ':tsc1' => $tasa[0],
                ':tsc2' => $tasa[1],
                ':tsc3' => $tasa[2],
                ':tsc4' => $tasa[3],
                ':nombre' => $producto,
                ':idplan' => $idplan
            ]);
        }
        echo "Tasas de crecimiento guardadas correctamente.";
    } catch (PDOException $e) {
        echo "Error al guardar tasas de crecimiento: " . $e->getMessage();
    }
}

// Guardar TCM si se envía el formulario

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarTcm'])) {
    $tcm = [];
    for ($i = 0; $i < count($_SESSION['productos']); $i++) {
        $tcm[$i] = [
            $_POST['tsc1'][$i] ?? 0, // TCM para 2019-2020
            $_POST['tsc2'][$i] ?? 0, // TCM para 2020-2021
            $_POST['tsc3'][$i] ?? 0, // TCM para 2021-2022
            $_POST['tsc4'][$i] ?? 0  // TCM para 2022-2023
        ];
    }
    guardarTcm($pdo, $tcm, $idplan);
}

function cargarProductosDesdeBD($pdo, $idplan) {
    $stmt = $pdo->prepare("SELECT * FROM producto WHERE idplan = :idplan");
    $stmt->execute([':idplan' => $idplan]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para guardar Demanda Global del Sector (DGS)
function guardarDgs($pdo, $dgs, $idplan) {
    try {
        foreach ($dgs as $index => $demanda) {
            $producto = $_SESSION['productos'][$index]['nombre'];
            // Actualiza los campos dgs1, dgs2, dgs3, dgs4, dgs5 según el índice
            $stmt = $pdo->prepare("UPDATE producto SET dgs1 = :dgs1, dgs2 = :dgs2, dgs3 = :dgs3, dgs4 = :dgs4, dgs5 = :dgs5 WHERE nombre = :nombre AND idplan = :idplan");
            $stmt->execute([
                ':dgs1' => $demanda[0], // Demanda Global para 2019
                ':dgs2' => $demanda[1], // Demanda Global para 2020
                ':dgs3' => $demanda[2], // Demanda Global para 2021
                ':dgs4' => $demanda[3], // Demanda Global para 2022
                ':dgs5' => $demanda[4], // Demanda Global para 2023
                ':nombre' => $producto,
                ':idplan' => $idplan
            ]);
        }
        echo "Demanda global guardada correctamente.";
    } catch (PDOException $e) {
        echo "Error al guardar la demanda global del sector: " . $e->getMessage();
    }
}

// Guardar DGS si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarDgs'])) {
    $dgs = [];
    for ($i = 0; $i < count($_SESSION['productos']); $i++) {
        $dgs[$i] = [
            $_POST['dgs1'][$i] ?? 0, // Demanda Global para 2019
            $_POST['dgs2'][$i] ?? 0, // Demanda Global para 2020
            $_POST['dgs3'][$i] ?? 0, // Demanda Global para 2021
            $_POST['dgs4'][$i] ?? 0, // Demanda Global para 2022
            $_POST['dgs5'][$i] ?? 0  // Demanda Global para 2023
        ];
    }
    guardarDgs($pdo, $dgs, $idplan);
}

// Función para guardar Niveles de Competencia (compe)
function guardarCompetencia($pdo, $competencia, $idplan) {
    try {
        foreach ($competencia as $index => $data) {
            $niveles = $data['niveles'];
            $mayor = $data['mayor'];
            $producto = $_SESSION['productos'][$index]['nombre'];
            
            // Actualiza los campos compe1, compe2, ..., compe9 y el campo "mayor"
            $stmt = $pdo->prepare("
                UPDATE producto 
                SET compe1 = :compe1, compe2 = :compe2, compe3 = :compe3, compe4 = :compe4, 
                    compe5 = :compe5, compe6 = :compe6, compe7 = :compe7, compe8 = :compe8, compe9 = :compe9, 
                    mayor = :mayor
                WHERE nombre = :nombre AND idplan = :idplan
            ");
            $stmt->execute([
                ':compe1' => $niveles[0],
                ':compe2' => $niveles[1],
                ':compe3' => $niveles[2],
                ':compe4' => $niveles[3],
                ':compe5' => $niveles[4],
                ':compe6' => $niveles[5],
                ':compe7' => $niveles[6],
                ':compe8' => $niveles[7],
                ':compe9' => $niveles[8],
                ':mayor'  => $mayor,
                ':nombre' => $producto,
                ':idplan' => $idplan
            ]);
        }
        echo "Niveles de competencia y valor mayor guardados correctamente.";
    } catch (PDOException $e) {
        echo "Error al guardar niveles de competencia: " . $e->getMessage();
    }
}

// Guardar competencia si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarCompetencia'])) {
    $competencia = [];
    for ($i = 0; $i < count($_SESSION['productos']); $i++) {
        // Recolectar los niveles de ventas de los competidores
        $nivelesVentas = [
            $_POST['niveles_ventas'][$i]['CP1'] ?? 0, // Nivel de ventas para CP1
            $_POST['niveles_ventas'][$i]['CP2'] ?? 0, // Nivel de ventas para CP2
            $_POST['niveles_ventas'][$i]['CP3'] ?? 0, // Nivel de ventas para CP3
            $_POST['niveles_ventas'][$i]['CP4'] ?? 0, // Nivel de ventas para CP4
            $_POST['niveles_ventas'][$i]['CP5'] ?? 0, // Nivel de ventas para CP5
            $_POST['niveles_ventas'][$i]['CP6'] ?? 0, // Nivel de ventas para CP6
            $_POST['niveles_ventas'][$i]['CP7'] ?? 0, // Nivel de ventas para CP7
            $_POST['niveles_ventas'][$i]['CP8'] ?? 0, // Nivel de ventas para CP8
            $_POST['niveles_ventas'][$i]['CP9'] ?? 0  // Nivel de ventas para CP9
        ];

        // Calcular el valor "mayor" (el máximo nivel de ventas)
        $mayor = max($nivelesVentas);

        // Almacenar tanto los niveles de competencia como el valor "mayor" en el array $competencia
        $competencia[$i] = [
            'niveles' => $nivelesVentas,
            'mayor' => $mayor
        ];
    }

    // Guardar los niveles de competencia y el valor "mayor" en la base de datos
    guardarCompetencia($pdo, $competencia, $idplan);
}

// Función para clasificar productos en la matriz BCG basada únicamente en la Demanda Global
// Función para clasificar productos en la matriz BCG

// echo "cuota".$cuotaMercado." ; ";
// echo "crecimiento".$crecimientoMercado." ; ";
// Función para clasificar productos en la matriz BCG
function generarMatrizBCG($pdo, $idplan) {
    $productos = $_SESSION['productos'];
    $clasificacion = [];
    $decisiones = []; // Array para almacenar las decisiones estratégicas

    foreach ($productos as $index => $producto) {
        // Obtener ventas y competidores
        $stmt = $pdo->prepare("
            SELECT ventas, compe1, compe2, compe3, compe4, compe5, compe6, compe7, compe8, compe9, tsc1, tsc2, tsc3, tsc4
            FROM producto 
            WHERE nombre = :nombre AND idplan = :idplan
        ");
        $stmt->execute([':nombre' => $producto['nombre'], ':idplan' => $idplan]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);

        // Cálculo de la cuota de mercado
        $ventas = $datos['ventas'];
        $ventasCompetidores = $datos['compe1'] + $datos['compe2'] + $datos['compe3'] + $datos['compe4'] + 
                              $datos['compe5'] + $datos['compe6'] + $datos['compe7'] + 
                              $datos['compe8'] + $datos['compe9'];
        $cuotaMercado = ($ventas / ($ventas + $ventasCompetidores)) * 100; // Expresado como porcentaje

        // Cálculo del crecimiento del mercado
        $crecimientoMercado = ($datos['tsc1'] + $datos['tsc2'] + $datos['tsc3'] + $datos['tsc4']) / 4; // Promedio de tsc

        // Clasificar el producto en la matriz BCG y asignar decisión estratégica
        if ($cuotaMercado > 50) { // Cuota de mercado alta
            if ($crecimientoMercado > 50) { // Alto crecimiento
                $clasificacion[$index] = 'Estrella'; // Potenciar
                $decisiones[$index] = 'Potenciar';
            } else { // Bajo crecimiento
                $clasificacion[$index] = 'Vaca'; // Mantener
                $decisiones[$index] = 'Mantener';
            }
        } else { // Cuota de mercado baja
            if ($crecimientoMercado > 50) { // Alto crecimiento
                $clasificacion[$index] = 'Incógnita'; // Evaluar
                $decisiones[$index] = 'Evaluar';
            } else { // Bajo crecimiento
                $clasificacion[$index] = 'Perro'; // Reestructurar o desinvertir
                $decisiones[$index] = 'Reestructurar o desinvertir';
            }
        }
    }

    return ['clasificacion' => $clasificacion, 'decisiones' => $decisiones]; // Retorna ambas clasificaciones y decisiones
}



// Llamada a la función para mostrar la tabla de la matriz BCG
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generarMatrizBCG'])) {
    $resultados = generarMatrizBCG($pdo, $idplan);
    $clasificacion = $resultados['clasificacion'];
    $decisiones = $resultados['decisiones']; // Agregar la obtención de decisiones
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Matriz B.C.G</title>
    <style>
/* General Body Styling */
body {
    font-family: 'Lato', sans-serif;
    background-color: #f0f4f8; /* Fondo pastel claro */
    margin: 0;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 15px; /* Espacio entre los contenedores principales */
}

/* Primera Parte: Ingreso de Productos */
.main-container-ingreso-productos {
    display: flex;
    flex-direction: column;
}

.ingreso-productos {
    width: 180px;
    background-color: #ffffff;
    padding: 10px;
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    height: auto;
    display: inline-block;
}

.ingreso-productos h1 {
    font-size: 18px;
    color: #77aaff; /* Azul pastel */
}

.ingreso-productos button {
    background-color: #a2d9a7; /* Verde pastel */
    color: white;
    border: none;
    border-radius: 4px;
    padding: 6px;
    transition: background-color 0.3s ease;
}

.ingreso-productos button:hover {
    background-color: #90c997; /* Verde pastel más oscuro */
}

/* Segunda Parte: Productos Ingresados */
.main-container-productos-ingresados {
    display: flex;
    flex-direction: column;
}

.productos-ingresados {
    width: 200px;
    max-height: 200px;
    overflow-y: auto;
    background-color: #ffffff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.productos-ingresados h2 {
    font-size: 16px;
    color: #ffa69e; /* Rosa pastel */
}

.productos-ingresados button {
    background-color: #ffcc80; /* Amarillo pastel */
    color: black;
    border: none;
    border-radius: 4px;
    padding: 6px;
    transition: background-color 0.3s ease;
}

.productos-ingresados button:hover {
    background-color: #ffbb66; /* Amarillo pastel más oscuro */
}

/* Tercera Parte: Previsión de Ventas */
.main-container-prevision-ventas {
    display: flex;
    flex-direction: column;
}

.prevision-ventas {
    width: 350px;
    background-color: #ffffff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}

.prevision-ventas h2 {
    font-size: 16px;
    color: #ff9999; /* Rojo pastel */
}

/* Cuarta Parte: Tasas de Crecimiento del Mercado */
.main-container-crecimiento-mercado {
    display: flex;
    flex-direction: column;
}

.crecimiento-mercado {
    width: 200px;
    background-color: #ffffff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    margin-top: 10px;
}

.crecimiento-mercado h2 {
    font-size: 16px;
    color: #c4e17f; /* Verde pastel suave */
}

/* Quinta Parte: Participación Relativa del Mercado */
.main-container-participacion-mercado {
    display: flex;
    flex-direction: column;
}

.participacion-mercado {
    width: 350px;
    background-color: #ffffff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}

.participacion-mercado h2 {
    font-size: 16px;
    color: #89c2d9; /* Azul pastel claro */
}

/* Sexta Parte: Evolución de la Demanda */
.main-container-evolucion-demanda {
    display: flex;
    flex-direction: column;
}

.evolucion-demanda {
    width: 200px;
    background-color: #ffffff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}

.evolucion-demanda h2 {
    font-size: 16px;
    color: #d4a5a5; /* Púrpura pastel suave */
}

/* Séptima Parte: Niveles de Venta de los Competidores */
.main-container-niveles-venta {
    display: flex;
    flex-direction: column;
}

.niveles-venta {
    width: 550px;
    background-color: #ffffff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}

.niveles-venta h2 {
    font-size: 16px;
    color: #f9c6c6; /* Naranja pastel suave */
}

/* Tablas generales */
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    padding: 8px;
    border: 1px solid #ddd;
    text-align: center;
}

th {
    background-color: #a6dcef; /* Azul pastel para cabeceras */
    color: #333;
}


    </style>
</head>
<body>
    <?php
        $productos = cargarProductosDesdeBD($pdo, $idplan);
        $_SESSION['productos'] = cargarProductosDesdeBD($pdo, $idplan);  
    ?>

    <div class="main-container-ingreso-productos">
        <div class="ingreso-productos">
            <h1 style="font-size: 18px; margin-bottom: 10px;">Ingreso de Productos</h1> <!-- Título con tamaño reducido -->
            
            <form method="POST">
                <label for="producto">Nombre del Producto:</label> <!-- Añadir "for" para mejor accesibilidad -->
                <input type="text" id="producto" name="producto" required> <!-- Añadir "id" al input para vinculación accesible -->
                <button type="submit" name="agregarProducto">Agregar Producto</button>
            </form>

            <form method="POST" style="margin-top: 10px;"> <!-- Separar los formularios con un margen superior -->
                <button type="submit" name="limpiarSesion">Limpiar Productos de Sesión</button>
            </form>
         </div>
    </div>

    <div class="main-container-productos-ingresados">
        <div class="productos-ingresados">
            <h2>Productos Ingresados</h2>
            <ul>
                <?php foreach ($productos as $index => $producto): ?>
                    <li>
                        <?= htmlspecialchars($producto['nombre']); ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="index" value="<?= $index; ?>">
                            <button type="submit" name="eliminarProducto">Eliminar</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <?php if (count($_SESSION['productos']) > 0): ?>
        <div class="main-container-prevision-ventas">
            <div class="prevision-ventas">
                <h2>Previsión de Ventas</h2>
                <form method="POST">
                    <table>
                        <tr class="header-green">
                            <th>Productos</th>
                            <th>Ventas</th>
                            <th>% Ventas Total</th>
                        </tr>
                        
                        <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                            <tr class="product-<?php echo ($index + 1); ?>">
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
        </div>

        <div class="main-container-crecimiento-mercado">
            <div class="crecimiento-mercado">
                <h2>Tasas de Crecimiento del Mercado (TCM)</h2>
                <form method="POST"> 
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
                                        <input type="number" step="0.01" 
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
        </div>

        <div class="main-container-participacion-mercado">
            <div class="participacion-mercado">
                <h2>Participación Relativa del Mercado (PRM)</h2>
                <table>
                    <tr class="header-red">
                        <th>Producto</th>
                        <th>TCM</th>
                        <th>PRM</th>
                        <th>% SVTAS</th>
                    </tr>
                    <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                        <tr class="product-<?php echo ($index + 1); ?>">
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td>
                                <?php
                                $stmt = $pdo->prepare("SELECT tsc1, tsc2, tsc3, tsc4 FROM producto WHERE nombre = :nombre AND idplan = :idplan");
                                $stmt->execute([':nombre' => $producto['nombre'], ':idplan' => $idplan]);
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($row) {
                                    $tcmTotal = $row['tsc1'] + $row['tsc2'] + $row['tsc3'] + $row['tsc4'];
                                    $tcmPromedio = $tcmTotal / 4;
                                    echo number_format($tcmPromedio, 2) . '%';
                                } else {
                                    echo '0.00%';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $stmt = $pdo->prepare("SELECT ventas, mayor FROM producto WHERE nombre = :nombre AND idplan = :idplan");
                                $stmt->execute([':nombre' => $producto['nombre'], ':idplan' => $idplan]);
                                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($data && $data['mayor'] > 0) {
                                    $prm = $data['ventas'] / $data['mayor'];
                                    echo number_format($prm, 2);
                                } else {
                                    echo '0.00';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                    if ($totalVentas > 0 && isset($ventas[$index]) && $ventas[$index] !== null) {
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
        </div>

        <div class="main-container-evolucion-demanda">
            <div class="evolucion-demanda">
                <h2>Evolución de la Demanda Global del Sector</h2>
                <form method="POST">
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
                                        <input type="number" step="1" 
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
        </div>

        <div class="main-container-niveles-venta">
            <div class="niveles-venta">
                <h2>Niveles de Venta de los Competidores de Cada Producto</h2>
                <form method="POST">
                    <table>
                        <tr class="header-yellow">
                            <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                <th colspan="2" style="text-align: center;">
                                <?php echo htmlspecialchars($producto['nombre']); ?> (<?php echo htmlspecialchars($producto['ventas'] ?? 0); ?>)
                                </th>
                            <?php endforeach; ?>
                        </tr>
                        <!-- Subcabecera: Competidor y Ventas -->
                        <tr>
                            <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                <th>Competidor</th>
                                <th>Ventas</th>
                            <?php endforeach; ?>
                        </tr>

                        <!-- Filas de Competidores: 9 Competidores por producto -->
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

                        <!-- Fila para mostrar el valor "Mayor" de cada producto -->
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
        </div>
    <?php endif; ?>

    <h2>Matriz BCG</h2>
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
                <th>Producto</th>
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
</body>

</html>