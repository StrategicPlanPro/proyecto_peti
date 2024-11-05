<?php
session_start(); // Iniciar la sesión

require_once '../data/plan.php'; // Asegúrate de incluir la clase que maneja los planes

// Verificar si el plan ha sido creado
if (!isset($_SESSION['idPlan'])) {
    // Redirigir al dashboard si no se ha creado un plan
    header("Location: ../presentation/dashboard.php");
    exit;
}

// Obtener la misión del plan utilizando la ID almacenada en la sesión
$idPlan = $_SESSION['idPlan'];
$planData = new PlanData();
$mision = $planData->obtenerMisionPorId($idPlan); // Obtener la misión actual

// Manejo de la actualización de la misión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $nuevaMision = $_POST['mision'] ?? ''; // Obtener la nueva misión desde el formulario
    $resultado = $planData->actualizarMision($idPlan, $nuevaMision); // Actualizar misión en la base de datos

    // Verificar si la actualización fue exitosa
    if ($resultado) {
        echo "<script>alert('Misión guardada exitosamente.');</script>";
        $mision = $nuevaMision; // Actualizar la misión en la variable para reflejar el cambio en la página
    } else {
        echo "<script>alert('Error al actualizar la misión.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Misión</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .btn-volver, .btn-siguiente, .btn-guardar {
            background-color: gray;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }

        .btn-volver:hover, .btn-siguiente:hover, .btn-guardar:hover {
            background-color: #555; /* Cambia el color al pasar el ratón */
        }

        .btn-siguiente {
            background-color: #333; /* Color más oscuro para el botón "Siguiente" */
        }

        .button-container {
            display: flex;
            justify-content: space-between; /* Espacio entre los botones */
            margin-top: 10px; /* Margen superior */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-content">
            <h1>Misión</h1>
            <form method="POST" action="">
                <textarea name="mision" rows="10" cols="50" placeholder="Ingrese la misión aquí..."><?php echo htmlspecialchars($mision ?? '', ENT_QUOTES); ?></textarea>
                
                <br><br>
                <input type="submit" name="guardar" value="Guardar" class="btn-guardar">
            </form>
            <div class="button-container">
                <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
                <a href="vision.php" class="btn-siguiente">Siguiente</a> <!-- Botón siguiente -->
            </div>
        </div>

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
    </div>
</body>
</html>
