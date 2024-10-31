<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['idusuario']) || !isset($_SESSION['idPlan'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
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
            <h1>Crear un nuevo plan ejecutivo</h1>
            <form action="../business/planBusiness.php" method="POST" enctype="multipart/form-data">
                <label for="nombreEmpresa">Nombre de la Empresa:</label>
                <input type="text" id="nombreEmpresa" name="nombreEmpresa" required>

                <label for="fecha">Fecha de Elaboración:</label>
                <input type="date" id="fecha" name="fecha" required>

                <label for="promotores">Emprendedores / Promotores:</label>
                <input type="text" id="promotores" name="promotores" required>

                <label for="logo">Subir Logo:</label>
                <input type="file" id="logo" name="logo" accept="image/*" required>

                <input type="submit" name="crearPlan" value="Crear Plan">
            </form>
            <br>
            <a href="../presentation/dashboard.php" class="btn-volver">Volver al Dashboard</a>
        </div>
    </div>

</body>

</html>
