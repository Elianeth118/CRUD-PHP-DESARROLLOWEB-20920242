<?php
require_once('../CONEXION.php');
require_once('claseCarrera.php');
require_once('index.html');

$conecta = new conexion('localhost', 'root', '', 'bditvo');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="mt-3"></div>
        <h3 style="font-size: 16px; font-weight: bold;text-align: center">Registro de carreras</h3>
<div class="mt-3"></div>
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-6 mt-3">
    <form  action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <input type="hidden" name="idEditar" value="<?php echo isset($_GET['idEditar']) ? $_GET['idEditar'] : ''; ?>">
                <div class="form-group">
                <label  for="nomCarrera">Nombre carrera:</label>
                <input type="text" name="nomCarrera" required class="form-control " value="<?php echo $nomCarreraEditar; ?>" ><br><br>
                </div>
                <div class="form-group">
                <label for="clave">clave:</label>
                <input type="text" name="clave" required class="form-control" value="<?php echo $claveEditar; ?>" ><br><br>
                </div>
                <div class="form-group text-center mt-3">
                <input type="submit" value="Insertar" class="btn btn-outline-primary">
                </div>
    </form>
    <div class="mt-3"></div>
    </div>
    </div>
    </div>
    <br></br>
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-primary">
                        <tr>
   
                            <th>Nombre Carrera</th>
                             <th>Clave</th>
                             <th>Estado</th>
                             <th colspan=2 >Acción:</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($datos->num_rows > 0){
            while($tupla = $datos->fetch_assoc()){
                $estado = ($tupla['estado'] == 1) ? 'Activo' : 'Inactivo';
                ?>
                <tr>
                    <td><?php  echo $tupla['nomCarrera'];?></td>
                    <td><?php  echo $tupla['clave'];?></td>
                    <td><?php  echo $estado ?></td>
                    <td><a  type="button" class="btn btn-outline-danger"  href="<?php echo $_SERVER['PHP_SELF'] .'?cambiarEstado=' . $tupla['idCarrera']; ?>">Eliminar <i class="fa fa-trash"></a></td>
                    <td><a type="button" class="btn btn-outline-success" href="<?php echo $_SERVER['PHP_SELF'] . '?idEditar=' . $tupla['idCarrera']; ?>">Editar <i class="fa fa-pencil"></i></a></td>
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
