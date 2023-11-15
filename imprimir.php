<?php
require_once('../fpdf/fpdf.php');
require_once('CONEXION.php');
require_once('DetMateria/claseDetMat.php');
require_once('detAlumno/claseDetAlumno.php');
require_once('calificacion/claseCalificacion.php');

$conecta = new conexion('localhost', 'root', '', 'bditvo');
$conecta->conectar();

if(isset($_GET['id'])){
    $idDetalle=$_GET['id'];
}else{
    $idDetalle=$_POST['id'];
}

$objdetMateria = new detMateria($conecta->get_conn());
$objdetAlumno = new detAlumno($conecta->get_conn());
$datosDetalle = $objdetMateria->mostrarDetalle(' and idDetMateria='.$idDetalle);
$datosCalificacion=$objdetAlumno->mostrarDetalleAlumnoConCalificacion($idDetalle);




if($datosDetalle->num_rows > 0){
    while($tuplaD = $datosDetalle->fetch_assoc()){
        $docente = $tuplaD["docente"];
        $materia = $tuplaD["materia"];
    }
}


$datosdetAlumno=$objdetAlumno->mostrarDetalleAlumno($idDetalle);
$datosCalificacion=$objdetAlumno->mostrarDetalleAlumnoConCalificacion($idDetalle);


$pdf = new FPDF();
$pdf->AddPage();
$pdf->Image('pleca-ITVO.png',10,10,60);
$pdf->Image('SEP.png',120,10,60);
$pdf->Ln(20);
$pdf->SetFont('Arial','B',14);

$pdf->Cell(40,10,'ACTA DE CALIFICACIONES');

$pdf->SetFont('Arial','B',12);
$pdf->Ln(10);
$pdf->Cell(40,10,"Docente: ".$docente);
$pdf->Ln(10);
$pdf->Cell(40,10, "Materia: ".$materia);
$pdf->Ln(10);


$pdf->Ln(10);
$pdf->Cell(15, 10, 'No.', 1, 0, 'C');
$pdf->Cell(75, 10, 'Nombre', 1, 0, 'C');
$pdf->Cell(75, 10, 'Carrera', 1, 0, 'C');
$pdf->Cell(15, 10, 'Calif.', 1, 0, 'C');

if($datosCalificacion->num_rows > 0){
    $num=1;
    while($tuplaA = $datosCalificacion->fetch_assoc()){
      $pdf->Ln(10);
      $pdf->SetFont('Arial','','12');
        $pdf->Cell(15, 10, $num, 1, 0, 'C');
        
        $pdf->Cell(75, 10,utf8_decode( $tuplaA['nombre']), 1, 0, 'C');
        $pdf->Cell(75, 10, $tuplaA['nomCarrera'], 1, 0, 'C');
        $pdf->Cell(15, 10, $tuplaA['calificacion'], 1, 0, 'C'); 

        $num++;

    }
}


$pdf->Output();
?>