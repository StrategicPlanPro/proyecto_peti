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
        // Puedes redirigir a otra página o mostrar un mensaje de éxito
        echo "<script>alert('Misión guardada exitosamente.');</script>";
        $mision = $nuevaMision; // Actualizar la misión en la variable para reflejar el cambio en la página
    } else {
        // Manejar el error si no se pudo actualizar
        echo "<script>alert('Error al actualizar la misión.');</script>";
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
            <h1>Misión</h1>
            <form method="POST" action="">
                <textarea name="mision" rows="10" cols="50" placeholder="Ingrese la misión aquí..."><?php echo htmlspecialchars($mision ?? '', ENT_QUOTES) ;?></textarea>
                
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
