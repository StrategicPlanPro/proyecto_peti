<?php
require_once('../data/plan.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crearPlan'])) {
    $nombreEmpresa = $_POST['nombreEmpresa'];
    $fecha = $_POST['fecha'];
    $promotores = $_POST['promotores'];

    // Verificar si el archivo de logo fue subido correctamente
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['logo']['tmp_name'];
        $fileName = $_FILES['logo']['name'];

        // Definir la carpeta de destino
        $uploadFileDir = '../presentation/assets/uploads/';
        
        // Mover el archivo a la carpeta de destino
        $dest_path = $uploadFileDir . $fileName;

        // Verificar si el archivo se movió correctamente
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Guardar solo el nombre del archivo (nombre + extensión)
            $logo = $fileName;
        } else {
            die("Error al subir el logo.");
        }
    } else {
        die("Logo no subido correctamente.");
    }

    // Crear instancia de PlanData y llamar a crearPlan
    $planData = new PlanData();
    $resultado = $planData->crearPlan($nombreEmpresa, $fecha, $promotores, $logo);

    // Verificar el resultado
    if ($resultado) {
        echo "Plan creado exitosamente.";
    } else {
        echo "Hubo un error al crear el plan.";
    }
}
