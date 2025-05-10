<?php
require_once '../../../fpdf184/fpdf.php';
require_once '../../../Model/Participation.php';
require_once '../../../Controller/ParticipationController.php';
require_once '../../../Model/ActiviteVirtuelle.php';
require_once '../../../Controller/ActiviteVirtuelleController.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Liste des Activites et Participants', 0, 1, 'C');
$pdf->Ln(5);

$activiteController = new ActiviteVirtuelleController();
$participationController = new ParticipationController();

$activites = $activiteController->listActiviteVirtuelle();

foreach ($activites as $a) {
    $pdf->SetFont('Arial','B',14);
    $pdf->SetTextColor(0,102,204);
    $pdf->Cell(0,10, utf8_decode("Activité : ".$a['titre']), 0, 1);

    $pdf->SetFont('Arial','',12);
    $pdf->SetTextColor(0);
    $pdf->Cell(0,8, utf8_decode("Type : ".$a['type']." | Date : ".date('d/m/Y H:i', strtotime($a['date']))), 0, 1);
    $pdf->Cell(0,8, utf8_decode("Plateforme : ".$a['plateforme']), 0, 1);
    $pdf->Ln(2);

    // En-têtes
    $pdf->SetFont('Arial','B',12);
    $pdf->SetFillColor(200,220,255);
    $pdf->Cell(10,8,'#',1,0,'C',true);
    $pdf->Cell(60,8,'Nom Utilisateur',1,0,'C',true);
    $pdf->Cell(20,8,'Age',1,0,'C',true);
    $pdf->Ln();

    $participants = $participationController->getParticipationsByActivite($a['id_activite']);
    $pdf->SetFont('Arial','',11);

    if (count($participants) > 0) {
        $i = 1;
        foreach ($participants as $p) {
            $pdf->Cell(10,8,$i++,1);
            $pdf->Cell(60,8, utf8_decode($p['username']),1);
            $pdf->Cell(20,8, $p['age'],1);
            $pdf->Ln();
        }
    } else {
        $pdf->Cell(0,8, utf8_decode("Aucun participant."),1,1);
    }

    $pdf->Ln(5);
}

$pdf->Output('I', 'activites_participants.pdf');
?>
