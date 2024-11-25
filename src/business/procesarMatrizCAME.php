<?php
session_start();
require_once('../data/plan.php');

// Verificar si el usuario ha iniciado sesión y si se envió el formulario
if (!isset($_SESSION['idusuario']) || !isset($_SESSION['idPlan']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Obtener el id del usuario y del plan desde la sesión
$idusuario = $_SESSION['idusuario'];
$idPlan = $_SESSION['idPlan'];

// Crear una instancia de PlanData
$planData = new PlanData();

// Inicializar una variable para indicar si se procesó algún cambio
$cambiosRealizados = false;

// Verificar y actualizar las fortalezas si se envían
if (isset($_POST['fortalezas'])) {
    $nuevasFortalezas = $_POST['fortalezas'];
    if ($planData->actualizarFortalezas($idPlan, $nuevasFortalezas)) {
        $cambiosRealizados = true;
    }
}

// Verificar y actualizar las debilidades si se envían
if (isset($_POST['debilidades'])) {
    $nuevasDebilidades = $_POST['debilidades'];
    if ($planData->actualizarDebilidades($idPlan, $nuevasDebilidades)) {
        $cambiosRealizados = true;
    }
}

// Verificar y actualizar las amenazas si se envían
if (isset($_POST['amenazas'])) {
    $nuevasAmenazas = $_POST['amenazas'];
    if ($planData->actualizarAmenazas($idPlan, $nuevasAmenazas)) {
        $cambiosRealizados = true;
    }
}

// Verificar y actualizar las oportunidades si se envían
if (isset($_POST['oportunidades'])) {
    $nuevasOportunidades = $_POST['oportunidades'];
    if ($planData->actualizarOportunidades($idPlan, $nuevasOportunidades)) {
        $cambiosRealizados = true;
    }
}

// Establecer mensaje de éxito si hubo cambios
if ($cambiosRealizados) {
    $_SESSION['mensaje_exito'] = "Datos actualizados correctamente.";
} else {
    $_SESSION['mensaje_exito'] = "No se realizaron cambios.";
}

// Redirigir a la vista matrizCAME.php
header("Location: ../presentation/matrizCAME.php");
exit();
?>
