<?php
require_once('../CONEXION.php');
include('index.html');

$conecta = new conexion('localhost', 'root', '', 'itvo2');
$conecta->conectar();

$datos = $conecta->listar('materia');

// Cambiar estado (activo o inactivo)
if (isset($_GET["cambiarEstado"]) && is_numeric($_GET["cambiarEstado"])) {
    $idCambiarEstado = $_GET["cambiarEstado"];
    
    if ($conecta->cambiarEstadoMateria('materia', $idCambiarEstado)) {
        echo "Estado de la materia actualizado con éxito.";
    } else {
        echo "Error al actualizar el estado de la materia";
    }
    $datos = $conecta->listar('materia');
}

//insertar o editar
$nomMateriaEditar = "";
$creditosEditar = "";
$semestreEditar = "";
$claveEditar = "";

if (isset($_GET["idEditar"]) && is_numeric($_GET["idEditar"])) {
    $idEditar = $_GET["idEditar"];
    $datosEditar = $conecta->obtenerDatosMateria("materia", $idEditar);

 
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
        $conecta->insertarMateria($nomMateria,$creditos,$semestre,$clave);
        $datos = $conecta->listar('materia');
    } else {
        $datosEditar = $conecta->obtenerDatosMateria("materia", $idEditar);

        if ($datosEditar) {
            $nomMateriaEditar = $datosEditar['nomMateria'];
            $creditosEditar = $datosEditar['creditos']; // Obtener 'creditos' de $datosEditar
            $semestreEditar = $datosEditar['semestre']; // Obtener 'semestre' de $datosEditar
            $claveEditar = $datosEditar['clave'];
           
        }

        $conecta->editarMateria("materia", $idEditar, $nomMateria,$creditos,$semestre,$clave);

        $nomMateriaEditar = "";
        $creditosEditar = "";
        $semestreEditar = "";
        $claveEditar = "";
        
     
    }

    $datos = $conecta->listar('materia');
}
?>

<html>
<head>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../bootstrap/css/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container text-center">
    <form class="mw-md-xl mx-auto mt-4"  action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <div class="row align-items-center">
            <div class="col-5">
                <input type="hidden" name="idEditar" value="<?php echo isset($_GET['idEditar']) ? $_GET['idEditar'] : ''; ?>">
                <label  for="nomMateria">Nombre Materia:</label>
                <input type="text" name="nomMateria" required class="form-control " value="<?php echo $nomMateriaEditar; ?>" ><br><br>
                <label  for="creditos">Creditos:</label>
                <input type="text" name="creditos" required class="form-control " value="<?php echo $creditosEditar; ?>" ><br><br>
                <label  for="semestre">Semestre:</label>
                <input type="text" name="semestre" required class="form-control " value="<?php echo $semestreEditar; ?>" ><br><br>
                <label  for="clave">clave:</label>
                <input type="text" name="clave" required class="form-control " value="<?php echo $claveEditar; ?>" ><br><br>
               

                

                <input type="submit" value="Insertar" class="btn btn-outline-primary">
            </div>
        </div>
    </form>
    <br></br>

    <table  class="table table-primary table-striped table-hover">
        <tr>
            <th>Materia</th>
            <th>Creditos</th>
            <th>Semestre</th>
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
                    <td><?php  echo $tupla['nomMateria'];?></td>
                    <td><?php  echo $tupla['creditos'];?></td>
                    <td><?php  echo $tupla['semestre'];?></td>
                    <td><?php  echo $tupla['clave'];?></td>
                    <td><?php  echo $estado ?></td>
                    
                    
                   
                    <td><a href="<?php echo $_SERVER['PHP_SELF'] .'?cambiarEstado=' . $tupla['idMateria']; ?>">Cambiar Estado</a></td>
                    <td><a href="<?php echo $_SERVER['PHP_SELF'] . '?idEditar=' . $tupla['idMateria']; ?>">Editar</a></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>
</body>
</html>
