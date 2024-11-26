<?php
session_start();
require_once('../data/plan.php');

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idusuario']) || !isset($_SESSION['idPlan'])) {
    header("Location: login.php");
    exit();
}

// Obtener el id del usuario y del plan desde la sesión
$idPlan = $_SESSION['idPlan'];
$planData = new PlanData();

// Obtener fortalezas, debilidades, oportunidades y amenazas
$fortalezas = $planData->obtenerFortalezasPorId($idPlan);
$debilidades = $planData->obtenerDebilidadesPorId($idPlan);
$amenazas = $planData->obtenerAmenazasPorId($idPlan);
$oportunidades = $planData->obtenerOportunidadesPorId($idPlan);

// Manejo de formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $nuevasFortalezas = $_POST['fortalezas'] ?? '';
    $nuevasDebilidades = $_POST['debilidades'] ?? '';
    $nuevasAmenazas = $_POST['amenazas'] ?? '';
    $nuevasOportunidades = $_POST['oportunidades'] ?? '';

    $exito = $planData->actualizarFortalezas($idPlan, $nuevasFortalezas) &&
             $planData->actualizarDebilidades($idPlan, $nuevasDebilidades) &&
             $planData->actualizarAmenazas($idPlan, $nuevasAmenazas) &&
             $planData->actualizarOportunidades($idPlan, $nuevasOportunidades);

    if ($exito) {
        echo "<script>alert('Datos actualizados correctamente.');</script>";
    } else {
        echo "<script>alert('Error al actualizar los datos.');</script>";
    }

    // Recargar valores actualizados
    $fortalezas = $nuevasFortalezas;
    $debilidades = $nuevasDebilidades;
    $amenazas = $nuevasAmenazas;
    $oportunidades = $nuevasOportunidades;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matriz CAME</title>
    <link rel="stylesheet" href="assets/css/styles.css">
   
    <style>
        .btn-volver, .btn-siguiente {
            background-color: gray;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 50px; /* Bordes más redondeados */
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

     /* Estilo para aumentar el tamaño de los labels */
     label {
        font-size: 1.2em; /* Tamaño del texto */
        font-weight: bold; /* Para que el texto sea más visible */
    }

    /* Estilo para los cuadros de texto */
    textarea {
        width: 100%; /* Para que ocupe todo el espacio disponible */
        font-size: 1em; /* Ajusta el tamaño del texto dentro del cuadro */
        padding: 10px; /* Espaciado interno para mejor legibilidad */
        border: 1px solid #ccc; /* Bordes del cuadro */
        border-radius: 5px; /* Bordes redondeados */
        resize: vertical; /* Permite cambiar solo el alto del cuadro */
        box-sizing: border-box; /* Asegura que el padding no rompa el diseño */
    }

    /* Opcional: mejorar el enfoque del cuadro */
    textarea:focus {
        border-color: #555; /* Cambia el color del borde al enfocar */
        outline: none; /* Elimina el borde adicional del navegador */
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-content">
            <h1>Matriz CAME</h1>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="fortalezas">Fortalezas:</label><br>
                    <textarea name="fortalezas" id="fortalezas"><?php echo htmlspecialchars($fortalezas); ?></textarea>
                </div><br>
                <div class="form-group">
                    <label for="debilidades">Debilidades:</label><br>
                    <textarea name="debilidades" id="debilidades"><?php echo htmlspecialchars($debilidades); ?></textarea>
                </div><br>
                <div class="form-group">
                    <label for="oportunidades">Oportunidades:</label><br>
                    <textarea name="oportunidades" id="oportunidades"><?php echo htmlspecialchars($oportunidades); ?></textarea>
                </div><br>
                <div class="form-group">
                    <label for="amenazas">Amenazas:</label><br>
                    <textarea name="amenazas" id="amenazas"><?php echo htmlspecialchars($amenazas); ?></textarea>
                </div><br><br>

                <input type="submit" name="guardar" value="Guardar" class="btn-guardar">
            </form>

            <div class="button-container">
                <a href="dashboard.php" class="btn btn-volver">Volver al Dashboard</a>
                <a href="siguientePaso.php" class="btn btn-siguiente">Siguiente</a>
            </div>
        </div>

        <div class="info-content">
            <?php include('aside.php'); ?>
        </div>
    </div>
</body>
</html>
