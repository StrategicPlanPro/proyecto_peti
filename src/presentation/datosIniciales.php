<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Plan Ejecutivo</title>
    <link rel="stylesheet" href="assets/css/styles.css">
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
        </div>

        <div class="info-content">
            <?php include('aside.php'); ?> <!-- Aquí incluimos el aside -->
        </div>
    </div>

</body>

</html>
