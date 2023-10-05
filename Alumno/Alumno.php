<?php
require_once('../CONEXION.php');
require_once('claseAlumno.php');
require_once('index.html');


$conecta = new conexion('localhost', 'root', '', 'itvo2');
$conecta->conectar();
$objAlumno=new alumno($conecta->get_conn());

$datos = $objAlumno->listar('alumno');
//$idCarrera = isset($_POST['idCarrera']) ? $_POST['idCarrera'] : '';
$idCarrera = isset($_POST['idCarrera']) ? intval($_POST['idCarrera']) : 0;


// Cambiar estado (activo o inactivo)
if (isset($_GET["cambiarEstado"]) && is_numeric($_GET["cambiarEstado"])) {
    $idCambiarEstado = $_GET["cambiarEstado"];
    
    if ($objAlumno->cambiarEstadoAlumno('alumno', $idCambiarEstado)) {
        //echo "Estado de la materia actualizado con éxito.";
    } else {
        //echo "Error al actualizar el estado de la materia";
    }
    $datos = $objAlumno->listar('alumno');
}



// insertar o editar
$nombreEditar = "";
$edadEditar = "";
$grupoEditar = "";
$idCarreaEditar = "";
if (isset($_GET["idEditar"]) && is_numeric($_GET["idEditar"])) {
    $idEditar = $_GET["idEditar"];
    $datosEditar = $objAlumno->obtenerDatosAlumno("alumno", $idEditar);

 
    if ($datosEditar) {
        $nombreEditar = $datosEditar['nombre'];
        $edadEditar = $datosEditar['edad'];
        $grupoEditar = $datosEditar['grupo'];
        $idCarreraEditar = $datosEditar['idCarrera'];
       
       
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["Nombre"];
    $edad = $_POST["Edad"];
    $grupo = $_POST["Grupo"];
    $idEditar = $_POST["idEditar"];

    if (empty($idEditar)) {
        $objAlumno->insertar($nombre, $edad, $grupo,$idCarrera);
    } else {
        $datosEditar =$objAlumno->obtenerDatosAlumno("alumno", $idEditar);

        if ($datosEditar) {
            $nombreEditar = $datosEditar['nombre'];
            $edadEditar = $datosEditar['edad'];
            $grupoEditar = $datosEditar['grupo'];
            $idCarreraEditar = $datosEditar['idCarrera'];
        }

        $objAlumno->editar("alumno", $idEditar, $nombre, $edad, $grupo,$idCarrera);

        $nombreEditar = "";
        $edadEditar = "";
        $grupoEditar = "";
        $idCarreaEditar = "";
      
    }

    $datos = $objAlumno->listar('alumno');
}


?>

<html>
<head>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../bootstrap/css/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container text-center">
    <form class="mw-md-xl mx-auto mt-3"  action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <div class="row align-items-center">
            <div class="col-5">
                <input type="hidden" name="idEditar" value="<?php echo isset($_GET['idEditar']) ? $_GET['idEditar'] : ''; ?>">
                <label  for="Nombre">Nombre:</label>
                <input type="text" name="Nombre" required class="form-control " value="<?php echo $nombreEditar; ?>" ><br><br>

                <label for="Edad">Edad:</label>
                <input type="text" name="Edad" required class="form-control" value="<?php echo $edadEditar; ?>" ><br><br>

                <label for="Grupo">Grupo:</label>
                <input type="text" name="Grupo" required class="form-control" value="<?php echo $grupoEditar; ?>" ><br><br>
                <label for="Carrera">Carrera:</label>
<select name="idCarrera" required class="form-control">
    <option value="">Seleccione una carrera</option>
    <?php
    $carreras = $objAlumno->listar('carrera');
    while ($carrera = $carreras->fetch_assoc()) {
        $selected = ($carrera['idCarrera'] == $idCarreraEditar) ? "selected" : "";
        echo "<option value='{$carrera['idCarrera']}' $selected>{$carrera['nomCarrera']}</option>";
    }
    ?>
</select><br><br>

                <input type="submit" value="Insertar" class="btn btn-outline-primary">
            </div>
        </div>
    </form>
    <br></br>

    <table  class="table table-primary table-striped table-hover">
        <tr>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Grupo</th>
            <th>Carrera</th>
            <th>Estado</th>
            <th colspan=2 >Acción:</th>
        </tr>
        <?php
        if($datos->num_rows > 0){
            while($tupla = $datos->fetch_assoc()){
                $estado = ($tupla['estado'] == 1) ? 'Activo' : 'Inactivo';
                $carreraNombre = ""; // Variable para almacenar el nombre de la carrera
                // Obtener el nombre de la carrera asociada al ID
                $carreras = $objAlumno->listar('carrera');
                while ($carrera = $carreras->fetch_assoc()) {
                    if ($carrera['idCarrera'] == $tupla['idCarrera']) {
                        $carreraNombre = $carrera['nomCarrera'];
                        break; // Salir del bucle una vez que se ha encontrado el nombre
                    }
                }
                ?>
                <tr>
                    <td><?php  echo $tupla['nombre'];?></td>
                    <td><?php  echo $tupla['edad'];?></td>
                    <td><?php  echo $tupla['grupo'];?></td>
                    <td><?php  echo $carreraNombre;?></td>
                    <td><?php  echo $estado ?></td>
                    <td><a href="<?php echo $_SERVER['PHP_SELF'] .'?cambiarEstado=' . $tupla['idAlumno']; ?>">Cambiar Estado</a></td>
                    
                    <td><a href="<?php echo $_SERVER['PHP_SELF'] . '?idEditar=' . $tupla['idAlumno'] . '&idCarrera=' . $tupla['idCarrera']; ?>">Editar</a></td>

                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>
</body>
</html>