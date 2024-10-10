<?php
require_once('libs/fpdf.php');
require_once('data/planData.php');

if (isset($_GET['id'])) {
    $idPlan = $_GET['id'];

    $planData = new PlanData();
    $plan = $planData->getPlanById($idPlan);

    if ($plan) {
        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Resumen Ejecutivo - ' . $plan['nombre'], 0, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Fecha: ' . $plan['fecha'], 0, 1);
        $pdf->Cell(0, 10, 'Promotores: ' . $plan['promotores'], 0, 1);

        if (!empty($plan['logo'])) {
            $pdf->Ln(10);
            $pdf->Image('uploads/' . $plan['logo'], 10, $pdf->GetY(), 50);
        }

        $pdf->Output('D', 'Resumen_' . $plan['nombre'] . '.pdf');
    } else {
        echo "<p>El plan no existe.</p>";
    }
} else {
    echo "<p>No se ha proporcionado un ID de plan.</p>";
}
?>