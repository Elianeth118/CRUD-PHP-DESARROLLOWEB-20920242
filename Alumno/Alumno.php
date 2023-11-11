<?php
require_once('../CONEXION.php');
require_once('claseAlumno.php');
require_once('index.html');


$conecta = new conexion('localhost', 'root', '', 'bditvo');
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
    <link rel="stylesheet" type="text/css" href="../css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="mt-3"></div>
        <h3 style="font-size: 16px; font-weight: bold;text-align: center">Registro de alumnos</h3>
<div class="mt-3"></div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-3">
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <input type="hidden" name="idEditar" value="<?php echo isset($_GET['idEditar']) ? $_GET['idEditar'] : ''; ?>">
                <div class="form-group">
                    <label for="Nombre">Nombre:</label>
                    <input type="text" name="Nombre" required class="form-control" value="<?php echo $nombreEditar; ?>">
                </div>
                <div class="form-group">
                    <label for="Edad">Edad:</label>
                    <input type="text" name="Edad" required class="form-control" value="<?php echo $edadEditar; ?>">
                </div>
                <div class="form-group">
                    <label for="Grupo">Grupo:</label>
                    <input type="text" name="Grupo" required class="form-control" value="<?php echo $grupoEditar; ?>">
                </div>
                <div class="form-group">
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
                    </select>
                </div>
                <div class="form-group text-center mt-3">
                <input type="submit" value="Insertar" class="btn btn-outline-primary">
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
                            <th class="fond">Nombre</th>
                            <th class="fond">Edad</th>
                            <th  class="fond">Grupo</th>
                            <th class="fond">Carrera</th>
                            <th class="fond">Estado</th>
                            <th class="fond" colspan="2">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($datos->num_rows > 0) {
                            while($tupla = $datos->fetch_assoc()){
                                $estado = ($tupla['estado'] == 1) ? 'Activo' : 'Inactivo';
                                $carreraNombre = ""; // Variable para almacenar el nombre de la carrera
                                // Obtener el nombre de la carrera asociada al ID
                                $carreras = $objAlumno->listar('carrera');
                                while ($carrera = $carreras->fetch_assoc()) {
                                    if ($carrera['idCarrera'] == $tupla['idCarrera']) {
                                        $carreraNombre = $carrera['nomCarrera'];
                                        break;
                          
                                }
                                }
                            ?>
                            <tr>
                                <td><?php  echo $tupla['nombre'];?></td>
                                <td><?php  echo $tupla['edad'];?></td>
                                <td><?php  echo $tupla['grupo'];?></td>
                                <td><?php  echo $carreraNombre;?></td>
                                <td><?php  echo $estado ?></td>
                                <td><a type="button" class="btn btn-outline-danger"   href="<?php echo $_SERVER['PHP_SELF'] .'?cambiarEstado=' . $tupla['idAlumno']; ?>">Eliminar <i class="fa fa-trash"></a></td>
                                
                                <td><a type="button" class="btn btn-outline-success" href="<?php echo $_SERVER['PHP_SELF'] . '?idEditar=' . $tupla['idAlumno'] . '&idCarrera=' . $tupla['idCarrera']; ?>">Editar <i class="fa fa-pencil"></i></a></td>
            
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