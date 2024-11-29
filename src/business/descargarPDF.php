<?php
require('../libs/fpdf/fpdf.php');
require_once('../data/plan.php');

// Desactivar la visualización de advertencias y errores
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

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

// Configurar fuente con soporte para caracteres especiales
$pdf->AddFont('Courier', '', 'courier.php'); // Asegúrate de tener los archivos de la fuente en tu directorio
$pdf->SetFont('Courier', '', 12);

// Título
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, utf8_decode('Datos del Plan Estratégico'), 0, 1, 'C');
$pdf->Ln(10);

// Función para agregar una celda con texto desplazado
function agregarCampo($pdf, $titulo, $contenido) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(50, 10, utf8_decode($titulo), 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetX(60); // Ajustar margen para contenido
    $pdf->MultiCell(130, 10, utf8_decode(!empty($contenido) ? $contenido : 'N/A'), 0, 1);
}

// Añadir los campos del plan
agregarCampo($pdf, 'Nombre Empresa:', $plan['nombreempresa']);
agregarCampo($pdf, 'Fecha:', $plan['fecha']);
agregarCampo($pdf, 'Promotores:', $plan['promotores']);
agregarCampo($pdf, 'Misión:', $plan['mision']);
agregarCampo($pdf, 'Visión:', $plan['vision']);
agregarCampo($pdf, 'Valores:', $plan['valores']);
agregarCampo($pdf, 'Objetivos Generales:', $plan['objetivosgenerales']);
agregarCampo($pdf, 'Objetivos Específicos:', $plan['objetivosespecificos']);
agregarCampo($pdf, 'Fortalezas:', $plan['fortalezas']);
agregarCampo($pdf, 'Debilidades:', $plan['debilidades']);
agregarCampo($pdf, 'Amenazas:', $plan['amenazas']);
agregarCampo($pdf, 'Oportunidades:', $plan['oportunidades']);
agregarCampo($pdf, 'Unidades Estratégicas:', $plan['unidadesestrategicas']);
agregarCampo($pdf, 'Estrategia:', $plan['estrategia']);
agregarCampo($pdf, 'Acciones Competitivas:', $plan['accionescompetitivas']);
agregarCampo($pdf, 'Conclusiones:', $plan['conclusiones']);

// Generar el PDF y forzar la descarga
$pdf->Output('D', 'PlanEstrategico.pdf');
?>
