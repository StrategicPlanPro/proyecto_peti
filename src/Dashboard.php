<?php
include_once './data/neonConnection.php'; // Archivo de conexión a la base de datos

// Crear una nueva instancia de la conexión
$db = new Conexion();
$conn = $db->getConnection();  // Obtener la conexión a la base de datos

// Consulta para obtener los planes creados
$query = "SELECT id, nombreempresa, logo FROM plan";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Planes</title>
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Asegúrate de tener un archivo de estilos -->
</head>
<body>
    <h1>Dashboard de Planes</h1>

    <!-- Si hay planes creados, mostrar la tabla -->
    <?php if (count($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre del Plan</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <!-- Mostrar la imagen del plan -->
                        <td><img src="assets/uploads/<?php echo $row['logo']; ?>" alt="Logo del plan" width="100"></td>
                        <!-- Mostrar el nombre del plan -->
                        <td><?php echo htmlspecialchars($row['nombreempresa']); ?></td>
                        <td>
                            <!-- Enlaces para ver el plan y descargar el PDF -->
                            <a href="verPlan.php?id=<?php echo $row['id']; ?>">Ver Plan</a>
                            <a href="descargarPDF.php?id=<?php echo $row['id']; ?>">Descargar PDF</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <!-- Si no hay planes creados, mostrar un mensaje y la opción para crear uno -->
        <p>No hay planes creados. <a href="crearPlan.php">Crear un plan</a></p>
    <?php endif; ?>

</body>
</html>