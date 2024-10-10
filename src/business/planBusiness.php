<?php
require_once('../data/planData.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crearPlan'])) {
    $nombreEmpresa = $_POST['nombreEmpresa'];
    $fecha = $_POST['fecha'];
    $promotores = $_POST['promotores'];

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['logo']['tmp_name'];
        $fileName = $_FILES['logo']['name'];
        $uploadFileDir = '../presentation/assets/uploads/';
        $dest_path = $uploadFileDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $logo = $dest_path;
        } else {
            die("Error al subir el logo.");
        }
    } else {
        die("Logo no subido correctamente.");
    }

    $planData = new PlanData();
    $resultado = $planData->crearPlan($nombreEmpresa, $fecha, $promotores, $logo);

    if ($resultado) {
        echo "Plan creado exitosamente.";
    } else {
        echo "Hubo un error al crear el plan.";
    }
}

