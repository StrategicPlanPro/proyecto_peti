<?php
session_start(); // Iniciar la sesión

require_once '../data/plan.php'; // Asegúrate de incluir la clase que maneja los planes

// Verificar si el plan ha sido creado
if (!isset($_SESSION['idPlan'])) {
    // Redirigir al dashboard si no se ha creado un plan
    header("Location: ../presentation/dashboard.php");
    exit;
}

// Obtener los valores del plan utilizando la ID almacenada en la sesión
$idPlan = $_SESSION['idPlan'];
$planData = new PlanData();
$valores = $planData->obtenerValoresPorId($idPlan); // Obtener los valores actuales

// Manejo de la actualización de los valores
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $nuevosValores = $_POST['valores'] ?? ''; // Obtener los nuevos valores desde el formulario
    $resultado = $planData->actualizarValores($idPlan, $nuevosValores); // Actualizar valores en la base de datos

    // Verificar si la actualización fue exitosa
    if ($resultado) {
        echo "<script>alert('Valores guardados exitosamente.');</script>";
        $valores = $nuevosValores; // Actualizar los valores en la variable para reflejar el cambio en la página
    } else {
        echo "<script>alert('Error al actualizar los valores.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valores</title>
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
            <h1>Valores</h1>
            <form method="POST" action="">
                <textarea name="valores" rows="10" cols="50" placeholder="Ingrese los valores aquí..."><?php echo htmlspecialchars($valores ?? '', ENT_QUOTES); ?></textarea>
                <br><br>
                <input type="submit" name="guardar" value="Guardar" class="btn-guardar">
                <a href="dashboard.php" class="btn-volver">Volver</a>
            </form>
        </div>

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
    </div>
</body>
