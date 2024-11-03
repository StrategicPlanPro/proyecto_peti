<?php
session_start(); // Iniciar la sesión

require_once '../data/plan.php'; // Asegúrate de incluir la clase que maneja los planes

// Verificar si el plan ha sido creado
if (!isset($_SESSION['idPlan'])) {
    // Redirigir al dashboard si no se ha creado un plan
    header("Location: ../presentation/dashboard.php");
    exit;
}

// Obtener la visión del plan utilizando la ID almacenada en la sesión
$idPlan = $_SESSION['idPlan'];
$planData = new PlanData();
$vision = $planData->obtenerVisionPorId($idPlan); // Obtener la visión actual

// Manejo de la actualización de la visión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $nuevaVision = $_POST['vision'] ?? ''; // Obtener la nueva visión desde el formulario
    $resultado = $planData->actualizarVision($idPlan, $nuevaVision); // Actualizar visión en la base de datos

    // Verificar si la actualización fue exitosa
    if ($resultado) {
        echo "<script>alert('Visión guardada exitosamente.');</script>";
        $vision = $nuevaVision; // Actualizar la visión en la variable para reflejar el cambio en la página
    } else {
        echo "<script>alert('Error al actualizar la visión.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visión</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .btn-volver, .btn-guardar {
            background-color: gray;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-content">
            <h1>Visión</h1>
            <form method="POST" action="">
                <textarea name="vision" rows="10" cols="50" placeholder="Ingrese la visión aquí..."><?php echo htmlspecialchars($vision ?? '', ENT_QUOTES); ?></textarea>
                <br><br>
                <input type="submit" name="guardar" value="Guardar" class="btn-guardar">
                
            </form>
            <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
        </div>

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
    </div>
</body>
</html>