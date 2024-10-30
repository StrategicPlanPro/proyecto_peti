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
/* Contenedor General */
.general-container {
    width: 90%; /* Ajustar el ancho al 90% del viewport */
    margin: 0 auto; /* Centrar el contenedor */
    padding: 20px; /* Espacio interno */
    background-color: #f9f9f9; /* Color de fondo suave */
    border-radius: 12px; /* Bordes redondeados */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Sombra suave */
    display: flex;
    flex-wrap: wrap; /* Permite flexibilidad en múltiples filas */
    gap: 10px; /* Espacio reducido entre los elementos hijos */
}

/* Contenedor para organizar la Primera y Cuarta Parte en una columna */
.primera-cuarta-columna {
    display: flex;
    flex-direction: column; /* Organiza los contenedores en columna */
    gap: 15px; /* Espacio entre los contenedores de la primera y cuarta parte */
}

/* Contenedor para organizar la Segunda, Tercera y Quinta Parte en una fila */
.segunda-tercera-quinta-columnas {
    display: flex;
    flex-direction: row; /* Alinea las partes horizontalmente */
    gap: 10px; /* Espacio reducido entre los contenedores */
}

/* Nueva clase para agrupar CUARTA y QUINTA PARTE en una fila */
.cuarta-quinta-row {
    display: flex;
    flex-direction: row; /* Organiza la CUARTA y QUINTA PARTE en una fila */
    gap: 10px; /* Espacio entre las dos partes */
}

/* Nueva clase para agrupar QUINTA y SEXTA PARTE en una fila */
.quinta-sexta-row {
    display: flex;
    flex-direction: row; /* Organiza la QUINTA y SEXTA PARTE en una fila */
    gap: 10px; /* Espacio entre las dos partes */
}

/* Contenedor para la Primera Parte: Ingreso de Productos */
.primeraparte-container {
    width: 250px; /* Tamaño ajustado del contenedor */
    background-color: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    height: 220px; /* Altura fija para que coincida con otros contenedores */
}

/* Alineación de campos */
.primeraparte-container form {
    display: flex;
    flex-direction: column;
    align-items: flex-end; /* Alineación a la derecha */
}

/* Ajustes de estilo para etiquetas e inputs */
.primeraparte-container label {
    font-size: 12px; /* Reducir el tamaño de la etiqueta */
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
    text-align: left; /* Alinear el texto a la izquierda */
    width: 100%;
}

.primeraparte-container input[type="text"] {
    width: 100%;
    padding: 4px; /* Reducir el padding del campo de texto */
    margin-bottom: 10px; /* Espacio entre campos */
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 12px; /* Reducir el tamaño de texto */
}

/* Botones */
.primeraparte-container button {
    width: auto;
    padding: 6px; /* Reducir el padding de los botones */
    margin-top: 5px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px; /* Reducir el tamaño de texto */
    transition: background-color 0.3s ease; /* Transición suave */
}

.primeraparte-container button:hover {
    background-color: #0056b3; /* Cambio de color al pasar el cursor */
}

/* Contenedor para la Segunda Parte: Productos Ingresados */
.segundaparte-container {
    width: 180px; /* Ancho reducido */
    height: 220px; /* Altura fija para igualar la Primera Parte */
    overflow-y: auto; /* Scroll para ver mejor los productos */
    background-color: white;
    padding: 8px; /* Reducir el padding */
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Ajustes de estilo para lista e inputs en Segunda Parte */
.segundaparte-container h2,
.terceraparte-container h2 {
    font-size: 12px; /* Tamaño de fuente reducido */
    color: #555;
    margin-bottom: 6px;
}

.segundaparte-container ul {
    padding: 0;
    margin: 0;
    list-style: none;
}

.segundaparte-container li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f9f9f9;
    padding: 4px 6px; /* Padding reducido */
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 4px; /* Margen entre elementos reducido */
}

/* Botones */
.segundaparte-container button {
    padding: 3px 5px; /* Padding reducido */
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 10px; /* Fuente más pequeña */
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.segundaparte-container button:hover {
    background-color: #c0392b; /* Cambio de color al pasar el cursor */
}

/* Ajuste manual de la Segunda Parte */
.segundaparte-container.manual-position-left {
    position: relative;
    left: 380px; /* Ajusta este valor según sea necesario */
    top: -910px; /* Ajusta este valor según la altura requerida */
}

/* Contenedor para la Tercera Parte: Previsión de Ventas */
.terceraparte-container {
    width: 300px; /* Ancho reducido */
    height: 220px; /* Altura fija */
    overflow-y: auto; /* Scroll vertical */
    background-color: white;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Ajuste manual de la Tercera Parte */
.terceraparte-container.manual-position-left-tercera {
    position: relative;
    left: 380px; /* Ajusta este valor según sea necesario */
    top: -910px; /* Ajusta este valor según la altura requerida */
}

/* Estilo para el título de la Tercera Parte */
.terceraparte-container h2 {
    font-size: 14px; /* Tamaño de fuente reducido */
    color: #555;
    margin-bottom: 8px;
}

/* Estilo de la tabla de Previsión de Ventas */
.terceraparte-container table {
    width: 100%; /* Ancho total de la tabla */
    font-size: 11px; /* Tamaño de fuente reducido */
    border-collapse: collapse;
}

.terceraparte-container th, .terceraparte-container td {
    padding: 5px; /* Padding reducido */
    border: 1px solid #ddd; /* Borde para las celdas */
    text-align: center;
}

.terceraparte-container th {
    background-color: #007bff;
    color: white;
}

.terceraparte-container tr.header-gray {
    background-color: #f2f2f2;
}

/* CUARTA PARTE: Tasas de Crecimiento del Mercado */
.cuartaparte-container {
    width: 250px; /* Ajustar el tamaño manual del contenedor */
    height: 180px; /* Ajustar la altura manual para que se vea más compacto */
    background-color: #ffffff;
    padding: 10px; /* Padding reducido */
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    overflow-y: auto; /* Scroll en caso de exceder la altura */
}

/* Encabezado de la Cuarta Parte */
.cuartaparte-container h2 {
    font-size: 12px;
    color: #c4e17f;
    margin-bottom: 6px;
}

/* Tabla de la Cuarta Parte */
.cuartaparte-container table {
    width: 100%;
    font-size: 11px; /* Tamaño de fuente más pequeño */
    border-collapse: collapse; /* Colapsar bordes para un mejor estilo */
}

.cuartaparte-container th,
.cuartaparte-container td {
    padding: 5px;
    text-align: center;
    border: 1px solid #ddd; /* Añadir bordes a las celdas */
}

.cuartaparte-container th {
    background-color: #c4e17f;
    color: #333;
}

.cuartaparte-container .header-gray {
    background-color: #f0f0f0;
}

/* Botón para guardar TCM */
.cuartaparte-container button {
    width: 100%;
    padding: 5px; /* Reducir padding para ajustar el tamaño del botón */
    background-color: #77aaff;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 11px; /* Reducir tamaño de fuente */
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cuartaparte-container button:hover {
    background-color: #609fda;
}

/* QUINTA PARTE: Participación Relativa del Mercado */
.quintaparte-container {
    width: 200px; /* Ancho ajustado del contenedor */
    height: 180px; /* Altura limitada para una apariencia compacta */
    background-color: #ffffff;
    padding: 10px; /* Espacio interno ajustado */
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    overflow-y: auto; /* Scroll vertical en caso de exceder la altura */
}

/* Estilo del encabezado de la Quinta Parte */
.quintaparte-container h2 {
    font-size: 12px; /* Fuente más pequeña */
    color: #89c2d9;
    margin-bottom: 6px;
}

/* Tabla de la Quinta Parte */
.quintaparte-container table {
    width: 100%;
    font-size: 11px; /* Tamaño de fuente reducido */
    border-collapse: collapse; /* Colapsar bordes para un estilo limpio */
}

.quintaparte-container th,
.quintaparte-container td {
    padding: 5px;
    text-align: center;
    border: 1px solid #ddd; /* Añadir bordes a las celdas */
}

.quintaparte-container th {
    background-color: #89c2d9; /* Color del encabezado */
    color: #333;
}

.quintaparte-container .header-gray {
    background-color: #f0f0f0;
}

/* Botón para guardar PRM */
.quintaparte-container button {
    width: 100%;
    padding: 5px;
    background-color: #ffa69e;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 11px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.quintaparte-container button:hover {
    background-color: #ff8c76;
}

/* Contenedor para la Sexta Parte: Evolución de la Demanda Global del Sector */
.sextaparte-container {
    width: 300px; /* Ancho ajustado del contenedor */
    max-height: 180px; /* Altura limitada para una apariencia compacta */
    background-color: #ffffff;
    padding: 10px; /* Espacio interno ajustado */
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    overflow-y: auto; /* Scroll vertical en caso de exceder la altura */
}

/* Estilo de la tabla de la Sexta Parte */
.sextaparte-container table {
    width: 100%; /* Ancho total de la tabla */
    font-size: 11px; /* Tamaño de fuente reducido */
    border-collapse: collapse; /* Colapsar bordes para un estilo limpio */
}

.sextaparte-container th,
.sextaparte-container td {
    padding: 5px; /* Espaciado interno */
    text-align: center;
    border: 1px solid #ddd; /* Bordes de las celdas */
}

/* Encabezado de la tabla en la Sexta Parte */
.sextaparte-container th {
    background-color: #77aaff; /* Color de fondo para el encabezado */
    color: white; /* Color del texto en el encabezado */
}

/* Filas con estilo alternativo para la Sexta Parte */
.sextaparte-container .header-gray {
    background-color: #f2f2f2; /* Color de fondo para filas alternas */
}

/* Contenedor para la Séptima Parte: Niveles de Venta de los Competidores de Cada Producto */
.septimaparte-container {
    width: 350px; /* Ancho ajustado del contenedor (aumentado de 250px a 350px) */
    max-height: 180px; /* Altura limitada para una apariencia compacta */
    background-color: #ffffff;
    padding: 10px; /* Espacio interno ajustado */
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    overflow-y: auto; /* Scroll vertical en caso de exceder la altura */
    margin-top: 15px; /* Espacio desde el contenedor superior */
    position: relative; /* Para permitir ajustes de posición manual */
    top: 20px; /* Ajusta este valor para mover el contenedor manualmente */
    left: 0; /* Ajusta este valor para mover el contenedor manualmente a la izquierda o derecha */
}

/* Estilo de la tabla de la Séptima Parte */
.septimaparte-container table {
    width: 100%; /* Ancho total de la tabla */
    font-size: 10px; /* Tamaño de fuente más pequeño */
    border-collapse: collapse; /* Colapsar bordes para un estilo limpio */
}

.septimaparte-container th,
.septimaparte-container td {
    padding: 2px; /* Padding reducido para hacer la tabla más pequeña */
    text-align: center;
    border: 1px solid #ddd; /* Bordes de las celdas */
}

/* Encabezado de la tabla en la Séptima Parte */
.septimaparte-container th {
    background-color: #ffa07a; /* Color de fondo para el encabezado */
    color: white; /* Color del texto en el encabezado */
}

/* Filas con estilo alternativo para la Séptima Parte */
.septimaparte-container .header-gray {
    background-color: #f2f2f2; /* Color de fondo para filas alternas */
}

.matrizbcg-container {
    width: 350px; /* Ajusta el ancho según tus necesidades */
    background-color: #ffffff; /* Color de fondo */
    padding: 10px; /* Espacio interno */
    border-radius: 8px; /* Bordes redondeados */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Sombra suave */
    margin-left: 380px; /* Espacio desde el contenedor de la Séptima Parte */
    position: relative; /* Para permitir ajustes de posición manual */
    top: -190px; /* Ajusta este valor para mover el contenedor hacia arriba o abajo */
}



    </style>
</head>
<body>
    <?php
        $productos = cargarProductosDesdeBD($pdo, $idplan);
        $_SESSION['productos'] = cargarProductosDesdeBD($pdo, $idplan);  
    ?>

    <div class="general-container">
        <div class="primera-cuarta-columna">
            <!-- PRIMERA PARTE -->
            <div class="primeraparte-container">
                <h1 style="font-size: 16px; margin-bottom: 8px;">Productos</h1>

                <form method="POST">
                    <label for="producto">Nombre del Producto:</label>
                    <input type="text" id="producto" name="producto" required>
                    <button type="submit" name="agregarProducto">Agregar Producto</button>
                </form>

                <form method="POST" style="margin-top: 10px;">
                    <button type="submit" name="limpiarSesion">Limpiar Productos de Sesión</button>
                </form>
            </div>

            <?php if (count($_SESSION['productos']) > 0): ?>
                <div class="cuarta-quinta-row">
                <!-- CUARTA PARTE -->
                <div class="cuartaparte-container">
                <h2>Tasas de Crecimiento del Mercado (TCM)</h2>
                    <form action="" method="POST"> 
                        <table>
                            <tr class="header-green">
                                <th>Períodos</th>
                                <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                    <th><?php echo htmlspecialchars($producto['nombre']); ?></th>
                                <?php endforeach; ?>
                            </tr>

                            <?php 
                            // Crear un array con los años y los índices de las columnas
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
                <?php endif; ?>

                <!-- QUINTA Y SEXTA PARTE -->
                <div class="quinta-sexta-row">
                    <!-- QUINTA PARTE -->
                    <?php if (count($_SESSION['productos']) > 0): ?>
                    <div class="quintaparte-container">
                    <h2>Participación Relativa del Mercado (PRM)</h2>
                        <table>
                            <tr class="header-red">
                                <th>Producto</th>
                                <th>TCM</th> <!-- Columna para la suma de TCM dividida entre los periodos -->
                                <th>PRM</th>
                                <th>% SVTAS</th>
                            </tr>
                            <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                <tr class="product-<?php echo ($index + 1); ?>">
                                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td> <!-- Nombre del producto -->
                                    <td>
                                        <?php
                                        // Realizamos una consulta para obtener los valores de TCM del producto
                                        $stmt = $pdo->prepare("SELECT tsc1, tsc2, tsc3, tsc4 FROM producto WHERE nombre = :nombre AND idplan = :idplan");
                                        $stmt->execute([
                                            ':nombre' => $producto['nombre'],
                                            ':idplan' => $idplan
                                        ]);
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                        // Calculamos la suma de los TCM
                                        if ($row) {
                                            $tcmTotal = $row['tsc1'] + $row['tsc2'] + $row['tsc3'] + $row['tsc4'];
                                            $tcmPromedio = $tcmTotal / 4; // Dividimos entre 4 (número de periodos)
                                            echo number_format($tcmPromedio, 2) . '%'; // Mostramos el valor promedio con 2 decimales
                                        } else {
                                            echo '0.00%'; // Si no se encuentran valores, mostramos 0
                                        }
                                        ?>
                                    </td>
                                    <td>0.00</td> <!-- Aquí se completaría la columna PRM -->
                                    <td>
                                        <?php 
                                            try {
                                                if ($totalVentas > 0 && isset($ventas[$index]) && $ventas[$index] !== null) {
                                                    echo number_format(($ventas[$index] / $totalVentas) * 100, 2) . '%';
                                                } else {
                                                    echo '0.00%';
                                                }
                                            } catch (Exception $e) {
                                                echo '0.00%'; // Si ocurre un error, mostramos 0.00%
                                            }                   
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <?php endif; ?>

                    <!-- SEXTA PARTE -->
                    <?php if (count($_SESSION['productos']) > 0): ?>
                    <div class="sextaparte-container">
                        <h4>Evolución de la Demanda Global del Sector</h4>
                        <form action="" method="POST">
                        <table>
                        <tr class="header-green">
                            <th>Años</th>
                            <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                                <th><?php echo htmlspecialchars($producto['nombre']); ?></th> <!-- Nombres de los productos -->
                            <?php endforeach; ?>
                        </tr>

                        <?php 
                        // Crear un array con los años y los índices de las columnas
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
                    </div>
                    <?php endif; ?>
                </div>
                </div>

            <!-- SÉPTIMA PARTE -->
            <?php if (count($_SESSION['productos']) > 0): ?>
            <div class="septimaparte-container">
                <h4>Niveles de Venta de los Competidores de Cada Producto</h4>
                <form action="" method="POST">
                    <table>
                        <!-- Cabecera: Empresa y Nombres de Productos -->
                        <tr class="header-yellow">
                            <th>EMPRESA</th>
                            <!-- Aquí se repiten las columnas por cada producto -->
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
                                            name="ventas[<?php echo $index; ?>][CP<?php echo $competidor; ?>]" 
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
                <?php endif; ?>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Recorre todos los productos para calcular el mayor valor de ventas
                    <?php foreach ($_SESSION['productos'] as $index => $producto): ?>
                    const ventasInputs<?php echo $index; ?> = document.querySelectorAll('input[name^="ventas[<?php echo $index; ?>]"]');
                    const mayorInput<?php echo $index; ?> = document.getElementById('mayor-<?php echo $index; ?>');
                    const mayorText<?php echo $index; ?> = document.getElementById('mayor-text-<?php echo $index; ?>');

                    ventasInputs<?php echo $index; ?>.forEach(input => {
                        input.addEventListener('input', function() {
                            let maxValue = 0;
                            ventasInputs<?php echo $index; ?>.forEach(input => {
                                const value = parseInt(input.value) || 0;
                                if (value > maxValue) {
                                    maxValue = value;
                                }
                            });
                            mayorInput<?php echo $index; ?>.value = maxValue;
                            mayorText<?php echo $index; ?>.textContent = maxValue;
                        });
                    });
                    <?php endforeach; ?>
                });
             </script>
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
            </div>

        </div>

        <div class="segunda-tercera-quinta-columnas">
            <!-- SEGUNDA PARTE -->
            <div class="segundaparte-container manual-position-left">
                <h2 style="font-size: 12px; margin-bottom: 6px;">Productos Ingresados</h2> <!-- Título ajustado -->

                <ul style="padding: 0; margin: 0; list-style: none;">
                    <?php foreach ($productos as $index => $producto): ?>
                        <li style="display: flex; justify-content: space-between; align-items: center; background-color: #f9f9f9; padding: 4px 6px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 4px;">
                            <?= htmlspecialchars($producto['nombre']); ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="index" value="<?= $index; ?>">
                                <button type="submit" name="eliminarProducto" style="padding: 3px 5px; font-size: 10px; background-color: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">Eliminar</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if (count($_SESSION['productos']) > 0): ?>
                <!-- TERCERA PARTE -->
                <div class="terceraparte-container manual-position-left-tercera">
                <h4>Previsión de Ventas</h4>
                    <table>
                        
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
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>