<?php
require_once('../CONEXION.php');
require_once('claseDocente.php');
include('index.html');

$conecta = new conexion('localhost', 'root', '', 'itvo2');
$conecta->conectar();
$objDocente=new docente($conecta->get_conn());
$datos = $objDocente->listar('docente');

// Cambiar estado (activo o inactivo)
// Cambiar estado (activo o inactivo)
if (isset($_GET["cambiarEstado"]) && is_numeric($_GET["cambiarEstado"])) {
    $idCambiarEstado = $_GET["cambiarEstado"];
    
    if ($objDocente->cambiarEstadoDocente('docente', $idCambiarEstado)) {
        echo "Estado del docente actualizado con éxito.";
    } else {
        echo "Error al actualizar el estado del docente.";
    }
    $datos = $objDocente->listar('docente');
}

//insertar o editar
$nomDocenteEditar = "";
if (isset($_GET["idEditar"]) && is_numeric($_GET["idEditar"])) {
    $idEditar = $_GET["idEditar"];
    $datosEditar =$objDocente->obtenerDatosDocente("docente", $idEditar);

    if ($datosEditar) {
        $nomDocenteEditar = $datosEditar['nomDocente'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomDocente = $_POST["nomDocente"];
    $idEditar = $_POST["idEditar"];

    if (empty($idEditar)) {
        $objDocente->insertarDocente($nomDocente);
        $datos = $objDocente->listar('docente');
    } else {
        $datosEditar = $objDocente->obtenerDatosDocente("docente", $idEditar);

        if ($datosEditar) {
            $nomDocenteEditar = $datosEditar['nomDocente'];
        }

        $objDocente->editarDocente("docente", $idEditar, $nomDocente);
        
        $nomDocenteEditar = "";
    }
 
    $datos = $objDocente->listar('docente');
}
?>

<html>
<head>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../bootstrap/css/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="mt-3"></div>
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-6 mt-3">
    <form  action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <input type="hidden" name="idEditar" value="<?php echo isset($_GET['idEditar']) ? $_GET['idEditar'] : ''; ?>">
                <div class="form-group">
                <label  for="nomDocente">Nombre Docente:</label>
                <input type="text" name="nomDocente" required class="form-control " value="<?php echo $nomDocenteEditar; ?>" ><br><br>
                </div>
                <div class="form-group text-center mt-3">
                <input type="submit" value="Insertar" class="btn btn-outline-primary">
            </div>
    </form>
    <div class="mt-3"></div>
    </div>
    </div>
    </div>
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Nombre Carrera</th>
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
                    <td><?php  echo $tupla['nomDocente'];?></td>
                    <td><?php  echo $estado ?></td>
                    <td><a href="<?php echo $_SERVER['PHP_SELF'] .'?cambiarEstado=' . $tupla['idDocente']; ?>">Cambiar Estado</a></td>
                    <td><a href="<?php echo $_SERVER['PHP_SELF'] . '?idEditar=' . $tupla['idDocente']; ?>">Editar</a></td>
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
  
   +
</body>
</html>
