<?php
require('../libs/fpdf/fpdf.php');
require_once('../data/plan.php'); // Incluye tu clase de PlanData

// Iniciar sesión y verificar si hay un plan creado
session_start();
if (!isset($_SESSION['idPlan'])) {
    header("Location: ../presentation/dashboard.php");
    exit;
}

// Obtener la ID del plan desde la sesión
$idPlan = $_SESSION['idPlan'];

// Crear una instancia de PlanData y obtener los datos del plan
$planData = new PlanData();
$plan = $planData->obtenerPlanPorIdMango($idPlan); // Asegúrate de tener un método para obtener el plan completo

// Crear un nuevo PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Título
$pdf->Cell(190, 10, 'Datos del Plan Estrategico', 0, 1, 'C');
$pdf->Ln(10); // Salto de línea

// Añadir los campos del plan, excepto las IDs
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Nombre Empresa:');
$pdf->Cell(150, 10, $plan['nombreempresa'], 0, 1);

$pdf->Cell(40, 10, 'Fecha:');
$pdf->Cell(150, 10, $plan['fecha'], 0, 1);

$pdf->Cell(40, 10, 'Promotores:');
$pdf->Cell(150, 10, $plan['promotores'], 0, 1);

$pdf->Cell(40, 10, 'Mision:');
$pdf->MultiCell(150, 10, $plan['mision'], 0, 1);

$pdf->Cell(40, 10, 'Vision:');
$pdf->MultiCell(150, 10, $plan['vision'], 0, 1);

$pdf->Cell(40, 10, 'Valores:');
$pdf->MultiCell(150, 10, $plan['valores'], 0, 1);

$pdf->Cell(40, 10, 'Objetivos Generales:');
$pdf->MultiCell(150, 10, $plan['objetivosgenerales'], 0, 1);

$pdf->Cell(40, 10, 'Objetivos Especificos:');
$pdf->MultiCell(150, 10, $plan['objetivosespecificos'], 0, 1);

// Y así con el resto de los campos (fortalezas, debilidades, oportunidades, etc.)
$pdf->Cell(40, 10, 'Fortalezas:');
$pdf->MultiCell(150, 10, $plan['fortalezas'], 0, 1);

$pdf->Cell(40, 10, 'Debilidades:');
$pdf->MultiCell(150, 10, $plan['debilidades'], 0, 1);

// Generar el PDF
$pdf->Output('D', 'PlanEstrategico.pdf'); // Forzar descarga con nombre "PlanEstrategico.pdf"
?>
