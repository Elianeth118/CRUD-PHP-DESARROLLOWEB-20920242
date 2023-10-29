<?php
require_once('../fpdf/fpdf.php');
require_once('CONEXION.php');
require_once('DetMateria/claseDetMat.php');
require_once('detAlumno/claseDetAlumno.php');

$conecta = new conexion('localhost', 'root', '', 'itvo2');
$conecta->conectar();

if(isset($_GET['id'])){
    $idDetalle=$_GET['id'];
}else{
    $idDetalle=$_POST['id'];
}

$objdetMateria = new detMateria($conecta->get_conn());
$datosDetalle = $objdetMateria->mostrarDetalle(' and idDetMateria='.$idDetalle);

if($datosDetalle->num_rows > 0){
    while($tuplaD = $datosDetalle->fetch_assoc()){
        $docente = $tuplaD["docente"];
        $materia = $tuplaD["materia"];
    }
}

$objdetAlumno = new detAlumno($conecta->get_conn());
$datosdetAlumno=$objdetAlumno->mostrarDetalleAlumno($idDetalle);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'ACTA DE CALIFICACIONES');

$pdf->SetFont('Arial','B',14);
$pdf->Ln(10);
$pdf->Cell(40,10,"Docente: ".$docente);
$pdf->Ln(10);
$pdf->Cell(40,10, "Materia: ".$materia);

$pdf->Ln(10);
$pdf->Cell(15, 10, 'No.', 1, 0, 'C');
$pdf->Cell(40, 10, 'Nombre', 1, 0, 'C');
$pdf->Cell(40, 10, 'Carrera', 1, 0, 'C');
$pdf->Cell(10, 10, 'Calif.', 1, 0, 'C');

if($datosdetAlumno->num_rows > 0){
    $num=1;
    while($tuplaA = $datosdetAlumno->fetch_assoc()){
      $pdf->Ln(10);
        $pdf->Cell(15, 10, $num, 1, 0, 'C');
        
        $pdf->Cell(40, 10, $tuplaA['nombre'], 1, 0, 'C');
        $pdf->Cell(40, 10, $tuplaA['nomCarrera'], 1, 0, 'C');

        $num++;

    }
}


$pdf->Output();
?>