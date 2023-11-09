<?php
require_once('CONEXION.php');
require_once('Alumno/claseAlumno.php');
require_once('detAlumno/claseDetAlumno.php');
require_once('DetMateria/claseDetMat.php');
require_once('calificacion/claseCalificacion.php');

$conecta = new conexion('localhost', 'root', '', 'bditvo');
$conecta->conectar();
$objdetAlumno = new detAlumno($conecta->get_conn());
$objdetMateria=new detMateria($conecta->get_conn());
$objAlumno=new alumno($conecta->get_conn());
$objCalificacion=new calificacion($conecta->get_conn());



if(isset($_GET['id'])){
    $idDetalle=$_GET['id'];
}else{
    $idDetalle=$_POST['id'];
}
$datosDetalle=$objdetMateria->mostrarDetalle(' and idDetMateria='.$idDetalle);
$datosAlumno=$objAlumno->listar('alumno');

$datosdetAlumno=$objdetAlumno->mostrarDetalleAlumno($idDetalle);

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $idCambiarEstado = $_GET["id"];
    
    if ($objCalificacion->cambiarEstadoCali('calificacion',$idCambiarEstado)) {
        $datosCalificacion=$objdetAlumno->mostrarDetalleAlumnoConCalificacion($idDetalle);
        
    } else {
        // Error al actualizar el estado de detAlumno.
        $datosCalificacion=$objdetAlumno->mostrarDetalleAlumnoConCalificacion($idDetalle);
    }
    $datosCalificacion=$objdetAlumno->mostrarDetalleAlumnoConCalificacion($idDetalle);
}

//Insertar calificación
$calificacion = '';
$idDetAlumno = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idDetAlumno = $_POST['idDetAlumno'];
    $calificacion = $_POST['calificacion'];

    
    if($objCalificacion->insertarCalificacion($idDetAlumno, $calificacion)) {
    
    } else {
    
        echo "Error al insertar la calificación.";
    }
}
$datosdetAlumno=$objdetAlumno->mostrarDetalleAlumno($idDetalle);
$datosdetAlum=$objdetAlumno->listar('detalumno');
$datos = $objdetMateria->listar('detMateria');
$datosCalificacion=$objdetAlumno->mostrarDetalleAlumnoConCalificacion($idDetalle);

?>
<html>
<head>
    <title>Calificaciones</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/css/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
</head>
<body>

<div class="Encabezado">
<h1>Insertar Calificaciones:</h1>

<?php
if($datosDetalle->num_rows>0) {
    while($tuplaD=$datosDetalle->fetch_assoc()) {
    ?> 
        <p>Docente: <?php echo $tuplaD['docente']; ?></p>
        <p>Materia: <?php echo $tuplaD['materia'];?></p>
    <?php
    }
}
?>
</div>

<div class="mt-3"></div>
    <h3 style="font-size: 16px; font-weight: bold;text-align: center">Asignación de Materias a Alumno</h3>
    <div class="row justify-content-center">
    <div class="col-md-6 mt-3">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="idDetAlumno" value="<?php echo $idDetAlumno; ?>">
    <input type="hidden" name="idCalificacion" value="<?php echo $tuplaA['idCalificacion']; ?>">

    <div class="form-group">
    <label for="Alumno">Alumno:</label>
    <select id="idDetAlumno" name="idDetAlumno" required class="form-select">
        <option value="">Seleccione un Alumno</option>
        <?php
        if ($datosdetAlumno->num_rows > 0) {
            while ($tuplaA = $datosdetAlumno->fetch_assoc()) {
                ?>
                <option value="<?php echo $tuplaA['idDetAlumno']; ?>"><?php echo $tuplaA['nombre']; ?></option>
                <?php
            }
        }
        ?>
    </select>
</div>
    <div class="form-group">
        <label for="calificacion">Calificación:</label>
                    <input type="text" name="calificacion" required class="form-control" value="<?php echo isset($tuplaA['calificacion']) ? $tuplaA['calificacion'] : ''; ?>">
                </div>
    <input type="hidden" name="id" value="<?php echo $idDetalle; ?>">
    <div class="form-group text-center mt-3">
    <input type="submit" value="Asignar" class="btn btn-outline-primary">
    </div>
    </div>
</form>
<div class="mt-3"></div>
        </div>
    </div>
</div>
<br>
<div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="table-responsive">
<table class="table table-sm table-bordered">
        <thead class="table-primary">
            <tr>
                            <th>Alumno</th>
                            <th>calificacion</th>
                            <th colspan=2>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
if ($datosCalificacion && $datosCalificacion->num_rows > 0) {
    while ($tuplaA = $datosCalificacion->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $tuplaA['nombre']; ?></td>
            <td><?php echo $tuplaA['calificacion']; ?></td>
            <td><a type="button" class="btn btn-outline-danger"  href="<?php echo $_SERVER['PHP_SELF'] .'?id=' . $tuplaA['idCalificacion']; ?>">Eliminar <i class="fa fa-trash"></i></a></td>
        </tr>
        <?php
    }
} else {
    // Manejar el caso en el que no hay resultados.
    echo "No hay datos de calificación disponibles.";
    $datosCalificacion=$objdetAlumno->mostrarDetalleAlumnoConCalificacion($idDetalle);
}
?>
</tbody>
                </table>
                </div>
        </div>
    </div>
    <div class="form-group text-center mt-3">
    <button  class="btn btn-outline-primary"  onclick="window.location.href='detMateria/detMateria.php'" > Regresar</button>

    </div>
</body>
</html>