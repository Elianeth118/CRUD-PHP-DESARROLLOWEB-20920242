<?php
require_once('../CONEXION.php');
require_once('claseMateria.php');
include('index.html');

$conecta = new conexion('localhost', 'root', '', 'bditvo');
$conecta->conectar();
$objMateria=new materia($conecta->get_conn());
$datos =$objMateria->listar('materia');

// Cambiar estado (activo o inactivo)
if (isset($_GET["cambiarEstado"]) && is_numeric($_GET["cambiarEstado"])) {
    $idCambiarEstado = $_GET["cambiarEstado"];
    
    if ($objMateria->cambiarEstadoMateria('materia', $idCambiarEstado)) {
        echo "Estado de la materia actualizado con éxito.";
    } else {
        echo "Error al actualizar el estado de la materia";
    }
    $datos = $objMateria->listar('materia');
}

//insertar o editar
$nomMateriaEditar = "";
$creditosEditar = "";
$semestreEditar = "";
$claveEditar = "";

if (isset($_GET["idEditar"]) && is_numeric($_GET["idEditar"])) {
    $idEditar = $_GET["idEditar"];
    $datosEditar = $objMateria->obtenerDatosMateria("materia", $idEditar);

 
    if ($datosEditar) {
        $nomMateriaEditar = $datosEditar['nomMateria'];
        $creditosEditar = $datosEditar['creditos'];
        $semestreEditar = $datosEditar['semestre'];
        $claveEditar = $datosEditar['clave'];
       
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomMateria = $_POST["nomMateria"];
    $creditos = $_POST["creditos"];
    $semestre = $_POST["semestre"];
    $clave = $_POST["clave"];
    $idEditar = $_POST["idEditar"];

    if (empty($idEditar)) {
        $objMateria->insertarMateria($nomMateria,$creditos,$semestre,$clave);
        $datos = $objMateria->listar('materia');
    } else {
        $datosEditar = $objMateria->obtenerDatosMateria("materia", $idEditar);

        if ($datosEditar) {
            $nomMateriaEditar = $datosEditar['nomMateria'];
            $creditosEditar = $datosEditar['creditos']; // Obtener 'creditos' de $datosEditar
            $semestreEditar = $datosEditar['semestre']; // Obtener 'semestre' de $datosEditar
            $claveEditar = $datosEditar['clave'];
           
        }

        $objMateria->editarMateria("materia", $idEditar, $nomMateria,$creditos,$semestre,$clave);

        $nomMateriaEditar = "";
        $creditosEditar = "";
        $semestreEditar = "";
        $claveEditar = "";
        
     
    }

    $datos = $objMateria->listar('materia');
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
        <h3 style="font-size: 16px; font-weight: bold;text-align: center">Registro de materias</h3>
<div class="mt-3"></div>
<div class="container">
<div class="row justify-content-center">
    <div class="col-md-6 mt-3">
    <form  action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <input type="hidden" name="idEditar" value="<?php echo isset($_GET['idEditar']) ? $_GET['idEditar'] : ''; ?>">
                <div class="form-group">
                <label  for="nomMateria">Nombre Materia:</label>
                <input type="text" name="nomMateria" required class="form-control " value="<?php echo $nomMateriaEditar; ?>" ><br><br>
                </div>
                <div class="form-group">
                <label  for="creditos">Creditos:</label>
                <input type="text" name="creditos" required class="form-control " value="<?php echo $creditosEditar; ?>" ><br><br>
                </div>
                <div class="form-group">
                <label  for="semestre">Semestre:</label>
                <input type="text" name="semestre" required class="form-control " value="<?php echo $semestreEditar; ?>" ><br><br>
                </div>
                <div class="form-group">
                <label  for="clave">clave:</label>
                <input type="text" name="clave" required class="form-control " value="<?php echo $claveEditar; ?>" ><br><br>
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
                        <th>Materia</th>
                        <th>Creditos</th>
                        <th>Semestre</th>
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
                    <td><?php  echo $tupla['nomMateria'];?></td>
                    <td><?php  echo $tupla['creditos'];?></td>
                    <td><?php  echo $tupla['semestre'];?></td>
                    <td><?php  echo $tupla['clave'];?></td>
                    <td><?php  echo $estado ?></td>
                    
                    
                   
                    <td><a type="button" class="btn btn-outline-danger"  href="<?php echo $_SERVER['PHP_SELF'] .'?cambiarEstado=' . $tupla['idMateria']; ?>">Eliminar <i class="fa fa-trash"></a></td>
                    <td><a type="button" class="btn btn-outline-success" href="<?php echo $_SERVER['PHP_SELF'] . '?idEditar=' . $tupla['idMateria']; ?>">Editar <i class="fa fa-pencil"></i></a></td>
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
