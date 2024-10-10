<?php
require_once 'data/planData.php';

$planData = new PlanData();
$planes = $planData->getPlanes();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="presentation/assets/css/styles.css">
    <title>Dashboard</title>
</head>
<body>
    <?php include 'aside.php'; ?>
    
    <div class="dashboard">
        <h2>Planes Creados</h2>
        <?php if(count($planes) > 0): ?>
            <ul>
                <?php foreach($planes as $plan): ?>
                    <li>
                        <h3><?php echo $plan['nombre']; ?></h3>
                        <img src="presentation/assets/uploads/<?php echo $plan['logo']; ?>" alt="Imagen del plan">
                        <a href="verPlan.php?id=<?php echo $plan['id']; ?>">Ver plan</a>
                        <a href="descargarPDF.php?id=<?php echo $plan['id']; ?>">Descargar PDF</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No hay planes creados aún.</p>
        <?php endif; ?>
        <a href="crearPlan.php" class="btn">Crear Nuevo Plan</a>
    </div>
</body>
</html>