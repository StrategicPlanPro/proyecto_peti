<?php
session_start();

// Inicializar la sesión de productos
if (!isset($_SESSION['productos'])) {
    $_SESSION['productos'] = [];
}

// Agregar productos
if (isset($_POST['agregarProducto'])) {
    $nuevoProducto = $_POST['producto'];
    if (!empty($nuevoProducto)) {
        $_SESSION['productos'][] = $nuevoProducto;
    }
}

// Eliminar productos
if (isset($_POST['eliminarProducto'])) {
    $index = $_POST['index'];
    unset($_SESSION['productos'][$index]);
    $_SESSION['productos'] = array_values($_SESSION['productos']); // Reindexar el array
}

// Calcular ventas
$totalVentas = 0;
$ventas = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ventas'])) {
    $ventas = $_POST['ventas'];
    foreach ($ventas as $venta) {
        $totalVentas += $venta;
    }
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
    <h1>Ingreso de Productos</h1>
    <form method="POST">
        <label>Nombre del Producto:</label>
        <input type="text" name="producto" required>
        <button type="submit" name="agregarProducto">Agregar Producto</button>
    </form>

    <h2>Productos Ingresados</h2>
    <ul>
        <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
            <li>
                <?php echo $producto; ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
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
                            <td><?php echo $producto; ?></td>
                            <td><input type="number" step="0.01" name="ventas[<?php echo $index; ?>]" value="<?php echo isset($ventas[$index]) ? $ventas[$index] : 0; ?>" required></td>
                            <td><?php echo $totalVentas > 0 ? number_format(($ventas[$index] / $totalVentas) * 100, 2) . '%' : '0.00%'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="header-gray">
                        <td>Total</td>
                        <td><?php echo number_format($totalVentas, 2); ?></td>
                        <td>100%</td>
                    </tr>
                </table>

                <button type="submit">Calcular</button>
            </form>

            <h2>Tasas de Crecimiento del Mercado (TCM)</h2>
            <table>
                <tr class="header-green">
                    <th>Períodos</th>
                    <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                        <th><?php echo $producto; ?></th>
                    <?php endforeach; ?>
                </tr>
                <tr class="header-gray">
                    <th>2012</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

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
                        <td><?php echo $producto; ?></td>
                        <td>0.00%</td>
                        <td>0.00</td>
                        <td><?php echo $totalVentas > 0 ? number_format(($ventas[$index] / $totalVentas) * 100, 2) . '%' : '0.00%'; ?></td>
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
