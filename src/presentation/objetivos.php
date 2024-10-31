<?php
session_start(); // Iniciar la sesión

require_once '../data/plan.php'; // Asegúrate de incluir la clase que maneja los planes

// Verificar si el plan ha sido creado
if (!isset($_SESSION['idPlan'])) {
    // Redirigir al dashboard si no se ha creado un plan
    header("Location: ../presentation/dashboard.php");
    exit;
}

// Obtener los objetivos del plan utilizando la ID almacenada en la sesión
$idPlan = $_SESSION['idPlan'];
$planData = new PlanData();
$objetivos = $planData->obtenerObjetivosPorId($idPlan); // Obtener los objetivos actuales

// Manejo de la actualización de los objetivos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $objetivosGenerales = $_POST['objetivosgenerales'] ?? ''; // Obtener los objetivos generales desde el formulario
    $objetivosEspecificos = $_POST['objetivosespecificos'] ?? ''; // Obtener los objetivos específicos desde el formulario
    $resultado = $planData->actualizarObjetivos($idPlan, $objetivosGenerales, $objetivosEspecificos); // Actualizar objetivos en la base de datos

    // Verificar si la actualización fue exitosa
    if ($resultado) {
        echo "<script>alert('Objetivos guardados exitosamente.');</script>";
        $objetivos['objetivosgenerales'] = $objetivosGenerales; // Actualizar los objetivos en la variable
        $objetivos['objetivosespecificos'] = $objetivosEspecificos;
    } else {
        echo "<script>alert('Error al actualizar los objetivos.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objetivos</title>
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
            <h1>Objetivos</h1>
            <form method="POST" action="">
                <label for="objetivosgenerales">Objetivos Generales:</label><br>
                <textarea name="objetivosgenerales" rows="5" cols="50" placeholder="Ingrese los objetivos generales aquí..."><?php echo htmlspecialchars($objetivos['objetivosgenerales'] ?? '', ENT_QUOTES); ?></textarea>
                <br><br>

                <label for="objetivosespecificos">Objetivos Específicos:</label><br>
                <textarea name="objetivosespecificos" rows="5" cols="50" placeholder="Ingrese los objetivos específicos aquí..."><?php echo htmlspecialchars($objetivos['objetivosespecificos'] ?? '', ENT_QUOTES); ?></textarea>
                <br><br>

                <input type="submit" name="guardar" value="Guardar" class="btn-guardar">
                <a href="dashboard.php" class="btn-volver">Volver al Dashboard</a>
            </form>
        </div>

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
    </div>
</body>
</html>
