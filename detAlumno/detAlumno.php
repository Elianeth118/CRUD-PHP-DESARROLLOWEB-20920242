<?php
require_once('../CONEXION.php');
require_once('../Docente/claseDocente.php');
require_once('../materia/claseMateria.php');
require_once('../Alumno/claseAlumno.php');
require_once('../DetMateria/claseDetMat.php');
require_once('claseDetAlumno.php');
require_once('index.html');


$conecta = new conexion('localhost', 'root', '', 'itvo2');
$conecta->conectar();
$objdetAlumno = new detAlumno($conecta->get_conn());

$datos = $objdetAlumno->listarPorEstado('detAlumno', 1);;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idAlumno = $_POST["idAlumno"];
    $idDetMateria = $_POST["idDetMateria"];

    $objdetAlumno->asignarMateriaAlumno($idAlumno, $idDetMateria);
}
$datos = $objdetAlumno->listarPorEstado('detAlumno', 1);

// Cambiar estado (activo o inactivo)
if (isset($_GET["cambiarEstado"]) && is_numeric($_GET["cambiarEstado"])) {
    $idCambiarEstado = $_GET["cambiarEstado"];
    
    if ($objdetAlumno->cambiarEstadoDetAlumno('detalumno', $idCambiarEstado)) {
        //echo "Estado de la materia actualizado con éxito.";
    } else {
        //echo "Error al actualizar el estado de la materia";
    }
    
$datos = $objdetAlumno->listarPorEstado('detAlumno', 1);
}
$datos = $objdetAlumno->listarPorEstado('detAlumno', 1);


?>

<html>
<head>
    <title>detAlumno</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../bootstrap/css/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="mt-3"></div>
        <h3 style="font-size: 16px; font-weight: bold;text-align: center">Asignación de Materias a Alumno</h3>
    <div class="row justify-content-center">
    <div class="col-md-6 mt-3">
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
    <div class="form-group">
        <label for="Alumno">Alumno:</label>
        <select name="idAlumno" required class="form-control">
            <option value="">Seleccione un alumno</option>
            <?php
            $alumnos = $objdetAlumno->listar('alumno');
            while ($alumno= $alumnos->fetch_assoc()) {
                $selected = ($alumno['idAlumno'] == $idAlumno) ? "selected" : "";
                echo "<option value='{$alumno['idAlumno']}' $selected>{$alumno['nombre']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="Materia">Materia:</label>
        <select name="idDetMateria" required class="form-control">
            <option value="">Seleccione una Materia</option>
            <?php
        $detMaterias = $objdetAlumno->listar('detMateria');
        while ($detMateria = $detMaterias->fetch_assoc()) {
            // Obtener información de la materia
            $materia = $objdetAlumno->obtenerMateriaPorID($detMateria['idMateria']);
            
            // Obtener información del docente
            $docente = $objdetAlumno->obtenerDocentePorID($detMateria['idDocente']);

            // Mostrar la carrera y el docente en la opción del menú desplegable
            echo "<option value='{$detMateria['idDetMateria']}'>{$materia['nomMateria']} - {$docente['nomDocente']}</option>";
        }
        ?>     
        </select>
    </div>
    <div class="form-group text-center mt-3">
    <input type="submit" value="Asignar Materia" class="btn btn-outline-primary">
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
                            <th>Materia</th>
                            <th>Estadi</th>
                            <th colspan=2>Accion</th>
                            
                        </tr>
                    </thead>
                    <tbody>
    <?php
    if ($datos->num_rows > 0) {
        while ($tupla = $datos->fetch_assoc()) {
            $estado = ($tupla['estado'] == 1) ? 'Activo' : 'Inactivo';
            $alumnoNombre = ""; // Variable para almacenar el nombre del docente
            $materiaNombre = "";
            $docenteNombre = ""; // Variable para almacenar el nombre de la materia

            // Obtener el nombre del docente asociado al ID
            $alumnos = $objdetAlumno->listar('alumno');
            while ($alumno = $alumnos->fetch_assoc()) {
                if ($alumno['idAlumno'] == $tupla['idAlumno']) {
                    $alumnoNombre = $alumno['nombre'];
                    break;
                }
            }

       // Obtener el nombre de la materia y el docente asociados al ID de detMateria
        $detMaterias = $objdetAlumno->listar('detMateria');
        while ($detMateria = $detMaterias->fetch_assoc()) {
            if ($detMateria['idDetMateria'] == $tupla['idDetMateria']) {
               // Obtener el nombre de la materia
                $materia = $objdetAlumno->obtenerMateriaPorID($detMateria['idMateria']);
                $materiaNombre = $materia['nomMateria'];
                // Obtener el nombre del docente asociado al ID
                $docentes = $objdetAlumno->listar('docente');
                while ($docente = $docentes->fetch_assoc()) {
                    if ($docente['idDocente'] == $detMateria['idDocente']) {
                        $docenteNombre = $docente['nomDocente'];
                        break;
                    }
                }

                // Verificar si se encontró un docente
                if (isset($docenteNombre)) {
                    // Mostrar el nombre del docente
                   // echo $docenteNombre;
                } else {
                    // No se encontró un docente, puedes mostrar un mensaje o dejarlo en blanco
                    echo "No se encontró un docente asociado.";
                }
            }
        }
    ?>
            <tr>
                <td><?php echo $alumnoNombre; ?></td>
                <td><?php echo "$materiaNombre (Docente: $docenteNombre)"; ?></td>
                <td><?php  echo $estado ?></td>
                <td><a type="button" class="btn btn-outline-danger"  href="<?php echo $_SERVER['PHP_SELF'] .'?cambiarEstado=' . $tupla['idDetAlumno']; ?>">Cambiar Estado</a></td>
                
            

            </tr>
            <?php
        }
    }
    ?>
</tbody>


                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
