<?php
require('../libs/fpdf/fpdf.php');
require_once('../data/plan.php'); // Incluye tu clase de PlanData

// Desactivar la visualización de advertencias y errores
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE); // Ocultar advertencias y notificaciones deprecadas

// Verificar si se ha pasado la ID del plan por GET
if (!isset($_GET['id'])) {
    header("Location: ../presentation/dashboard.php");
    exit;
}

// Obtener la ID del plan desde la URL
$idPlan = $_GET['id'];

// Crear una instancia de PlanData y obtener los datos del plan
$planData = new PlanData();
$plan = $planData->obtenerPlanPorIdMango($idPlan); // Método para obtener los datos del plan por su ID

// Verificar si se obtuvo un plan válido
if (!$plan) {
    echo "No se encontró el plan.";
    exit;
}

// Crear un nuevo PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Título
$pdf->Cell(190, 10, 'Datos del Plan Estrategico', 0, 1, 'C');
$pdf->Ln(10); // Salto de línea

// Añadir los campos del plan, verificando si no son nulos o vacíos
$pdf->SetFont('Arial', '', 12);

// Nombre Empresa
$pdf->Cell(40, 10, 'Nombre Empresa:');
$pdf->Cell(150, 10, !empty($plan['nombreempresa']) ? $plan['nombreempresa'] : 'N/A', 0, 1);

// Fecha
$pdf->Cell(40, 10, 'Fecha:');
$pdf->Cell(150, 10, !empty($plan['fecha']) ? $plan['fecha'] : 'N/A', 0, 1);

// Promotores
$pdf->Cell(40, 10, 'Promotores:');
$pdf->MultiCell(150, 10, !empty($plan['promotores']) ? $plan['promotores'] : 'N/A', 0, 1);

// Misión
$pdf->Cell(40, 10, 'Mision:');
$pdf->MultiCell(150, 10, !empty($plan['mision']) ? $plan['mision'] : 'N/A', 0, 1);

// Visión
$pdf->Cell(40, 10, 'Vision:');
$pdf->MultiCell(150, 10, !empty($plan['vision']) ? $plan['vision'] : 'N/A', 0, 1);

// Valores
$pdf->Cell(40, 10, 'Valores:');
$pdf->MultiCell(150, 10, !empty($plan['valores']) ? $plan['valores'] : 'N/A', 0, 1);

// Objetivos Generales
$pdf->Cell(40, 10, 'Objetivos Generales:');
$pdf->MultiCell(150, 10, !empty($plan['objetivosgenerales']) ? $plan['objetivosgenerales'] : 'N/A', 0, 1);

// Objetivos Específicos
$pdf->Cell(40, 10, 'Objetivos Especificos:');
$pdf->MultiCell(150, 10, !empty($plan['objetivosespecificos']) ? $plan['objetivosespecificos'] : 'N/A', 0, 1);

// Fortalezas
$pdf->Cell(40, 10, 'Fortalezas:');
$pdf->MultiCell(150, 10, !empty($plan['fortalezas']) ? $plan['fortalezas'] : 'N/A', 0, 1);

// Debilidades
$pdf->Cell(40, 10, 'Debilidades:');
$pdf->MultiCell(150, 10, !empty($plan['debilidades']) ? $plan['debilidades'] : 'N/A', 0, 1);

// Generar el PDF y forzar la descarga
$pdf->Output('D', 'PlanEstrategico.pdf'); // Forzar descarga con nombre "PlanEstrategico.pdf"
?>
