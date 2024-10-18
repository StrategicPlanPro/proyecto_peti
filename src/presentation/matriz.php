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
    $stmt = $pdo->prepare("SELECT nombre, ventas, tsc1, tsc2, tsc3, tsc4 FROM producto WHERE idplan = :idplan");
    $stmt->execute([':idplan' => $idplan]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Matriz B.C.G</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        form {
            margin: 20px auto;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        ul {
            list-style-type: none;
            padding: 0;
            max-width: 400px;
            margin: 20px auto;
        }

        li {
            background-color: #fff;
            margin: 5px 0;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        li form {
            display: inline;
        }

        .table-container {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #e0e0e0;
            font-weight: bold;
            color: #333;
        }

        .row-highlight {
            background-color: #f9f9f9;
        }

        .header-green {
            background-color: #d4edda;
            color: #155724;
        }

        .header-gray {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .header-blue {
            background-color: #cce5ff;
            color: #004085;
        }

        .header-red {
            background-color: #f8d7da;
            color: #721c24;
        }

        .header-yellow {
            background-color: #fff3cd;
            color: #856404;
        }

        /* Colores de los productos */
        .product-1 { background-color: #b3cde0; }
        .product-2 { background-color: #fbb4ae; }
        .product-3 { background-color: #ccebc5; }
        .product-4 { background-color: #decbe4; }
        .product-5 { background-color: #fed9a6; }

    </style>
</head>
<body>

    <?php
        
        $productos = cargarProductosDesdeBD($pdo, $idplan);
         var_dump($idplan);
        $_SESSION['productos'] = cargarProductosDesdeBD($pdo, $idplan);  
    ?>
    
                <br><br>
    <h1>Ingreso de Productos</h1>
    <form method="POST">
        <label>Nombre del Producto:</label>
        <input type="text" name="producto" required>
        
        <button type="submit" name="agregarProducto">Agregar Producto</button>
    </form>

    <form method="POST">
        <button type="submit" name="limpiarSesion">Limpiar Productos de Sesión</button>
    </form>
    
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

    <?php if (count($_SESSION['productos']) > 0): ?>
        <div class="table-container">
        <form method="POST">
            <h1>Previsión de Ventas</h1>
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
                                $porcentaje = 0; // Si ocurre cualquier error, asignamos 0 al porcentaje
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
        <h2>Tasas de Crecimiento del Mercado (TCM)</h2>
        <form action="" method="POST"> <!-- Cambia la acción del formulario para que apunte al mismo archivo -->
            <table>
                <tr class="header-green">
                    <th>Períodos</th>
                    <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                        <th><?php echo htmlspecialchars($producto['nombre']); ?></th>
                    <?php endforeach; ?>
                </tr>
                <tr class="header-gray">
                    <th>2019 - 2020</th>
                    <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                        <td>
                            <input type="number" step="0.01" name="tsc1[<?php echo $index; ?>]" placeholder="0.00" required>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <tr class="header-gray">
                    <th>2020 - 2021</th>
                    <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                        <td>
                            <input type="number" step="0.01" name="tsc2[<?php echo $index; ?>]" placeholder="0.00" required>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <tr class="header-gray">
                    <th>2021 - 2022</th>
                    <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                        <td>
                            <input type="number" step="0.01" name="tsc3[<?php echo $index; ?>]" placeholder="0.00" required>
                        </td>
                    <?php endforeach; ?>
                </tr>
                <tr class="header-gray">
                    <th>2022 - 2023</th>
                    <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                        <td>
                            <input type="number" step="0.01" name="tsc4[<?php echo $index; ?>]" placeholder="0.00" required>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </table>
            <button type="submit" name="guardarTcm">Ingresar TCM</button> <!-- Asegúrate de incluir el nombre del botón -->
        </form>


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
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td> <!-- Acceder al nombre del producto -->
                    <td>0.00%</td>
                    <td>0.00</td>
                    <td><?php 
                        try {
                            if ($totalVentas > 0 && isset($ventas[$index]) && $ventas[$index] !== null) {
                                echo number_format(($ventas[$index] / $totalVentas) * 100, 2) . '%';
                            } else {
                                echo '0.00%';
                            }
                        } catch (Exception $e) {
                            echo '0.00%'; // Si ocurre un error, mostramos 0.00%
                        }                   
                    ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

            <h2>Niveles de Venta de los Competidores</h2>
            <table>
                <tr class="header-yellow">
                    <th>Empresa</th>
                    <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                        <th>Competidor</th>
                        <th>Ventas</th>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td>CP1</td>
                    <td>CP2</td>
                    <td>CP3</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    <?php endif; ?>
</body>
</html>