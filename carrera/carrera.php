<?php
require_once('../CONEXION.php');
require_once('claseCarrera.php');
require_once('index.html');

$conecta = new conexion('localhost', 'root', '', 'itvo2');
$conecta->conectar();
$objCarrera=new carrera($conecta->get_conn());

$datos =$objCarrera->listar('carrera');

// Cambiar estado (activo o inactivo)
if (isset($_GET["cambiarEstado"]) && is_numeric($_GET["cambiarEstado"])) {
    $idCambiarEstado = $_GET["cambiarEstado"];
    
    if ($objCarrera->cambiarEstadoCarrera('carrera', $idCambiarEstado)) {
        echo "Estado del docente actualizado con éxito.";
    } else {
        echo "Error al actualizar el estado del docente.";
    }
    $datos = $objCarrera->listar('carrera');
}


//insertar o editar
$nomCarreraEditar = "";
$claveEditar = "";

//$nomDocente="";
if (isset($_GET["idEditar"]) && is_numeric($_GET["idEditar"])) {
    $idEditar = $_GET["idEditar"];
    $datosEditar = $objCarrera->obtenerDatosCarrera("carrera", $idEditar);

 
    if ($datosEditar) {
        $nomCarreraEditar = $datosEditar['nomCarrera'];
        $claveEditar = $datosEditar['clave'];
       
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomCarrera = $_POST["nomCarrera"];
    $clave = $_POST["clave"];
    $idEditar = $_POST["idEditar"];

    if (empty($idEditar)) {
        $objCarrera->insertarCarrera($nomCarrera, $clave);
        $datos = $objCarrera->listar('carrera');
    } else {
        $datosEditar = $objCarrera->obtenerDatosCarrera("carrera", $idEditar);

        if ($datosEditar) {
            $nomCarreraEditar = $datosEditar['nomCarrera'];
            $claveEditar = $datosEditar['clave'];
           
        }

        $objCarrera->editarCarrera("carrera", $idEditar, $nomCarrera, $clave);

        $nomCarreraEditar = "";
        $claveEditar = "";
       
        
     
    }

    $datos = $objCarrera->listar('carrera');
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
                <label  for="nomCarrera">Nombre carrera:</label>
                <input type="text" name="nomCarrera" required class="form-control " value="<?php echo $nomCarreraEditar; ?>" ><br><br>

                <label for="clave">clave:</label>
                <input type="text" name="clave" required class="form-control" value="<?php echo $claveEditar; ?>" ><br><br>

                

                <input type="submit" value="Insertar" class="btn btn-outline-primary">
            </div>
        </div>
    </form>
    <br></br>

    <table  class="table table-primary table-striped table-hover">
        <tr>
            <th>Nombre Carrera</th>
            <th>Clave</th>
            <th>Estado</th>
            
            <th colspan=2 >Acción:</th>
        </tr>
        <?php
        if($datos->num_rows > 0){
            while($tupla = $datos->fetch_assoc()){
                $estado = ($tupla['estado'] == 1) ? 'Activo' : 'Inactivo';
                ?>
                <tr>
                    <td><?php  echo $tupla['nomCarrera'];?></td>
                    <td><?php  echo $tupla['clave'];?></td>
                    <td><?php  echo $estado ?></td>
                   
                    <td><a href="<?php echo $_SERVER['PHP_SELF'] .'?cambiarEstado=' . $tupla['idCarrera']; ?>">Cambiar Estado</a></td>
                    <td><a href="<?php echo $_SERVER['PHP_SELF'] . '?idEditar=' . $tupla['idCarrera']; ?>">Editar</a></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>
</body>
</html>
