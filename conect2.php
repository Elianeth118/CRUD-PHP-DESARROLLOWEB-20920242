<?php
require_once('CONEXION.php');
include('Bd/index.html');


$conecta= new  conexion('localhost','root','','itvo');

$conecta->conectar();

$datos=$conecta->listar('alumno');


if (isset($_GET["eliminar"]) && is_numeric($_GET["eliminar"])) {
    $idEliminar = $_GET["eliminar"];
    

    if ($conecta->eliminar('alumno', $idEliminar)) {
        echo "Registro eliminado con éxito.";
    } else {
        echo "Error al eliminar el registro.";
    }
    $datos=$conecta->listar('alumno');
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["Nombre"];
    $edad = $_POST["Edad"];
    $grupo = $_POST["Grupo"];

   $conecta->insertar($nombre, $edad, $grupo);
   $datos=$conecta->listar('alumno');


if (isset($_GET["editar"]) && is_numeric($_GET["editar"])) {
    $idEditar = $_GET["editar"];
    
   if(empty($idEditar)){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST["Nombre"];
        $edad = $_POST["Edad"];
        $grupo = $_POST["Grupo"];

       $conecta->insertar($nombre, $edad, $grupo);
       $datos=$conecta->listar('alumno');
        
    }


   }else{

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      
        $nombreNuevo = $_POST["Nombre"];
         $edadNuevo = $_POST["Edad"];
         $grupoNuevo = $_POST["Grupo"];
 
         if ($conecta->editar('alumno', $idEditar, $nombreNuevo, $edadNuevo, $grupoNuevo)) {
             echo "Registro editado con éxito.";
             
         header("Location: " . $_SERVER['PHP_SELF']);
         exit; 
         } else {
             echo "Error al editar el registro.";
         }
         $datos=$conecta->listar('alumno');
     }

   }
    

}
}

?>

<html>
    <head>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="bootstrap/css/bootstrap.bundle.min.js"></script>

    </head>
    <body>

<div class="container text-center">

    <form class="mw-md-xl mx-auto"  action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
    <div class="row align-items-center">
    <div class="col-5">
        <label  for="Nombre">Nombre:</label>
        <input type="text" name="Nombre" required class="form-control "><br><br>
        
        <label for="Edad">edad:</label>
        <input type="int" name="Edad" required class="form-control"><br><br>

        <label for="Grupo">grupo:</label>
        <input type="text" name="Grupo" required class="form-control"><br><br>
        
        <input type="submit" value="Insertar" class="btn btn-outline-primary">
</div>
</div>
        
    </form>
<br></br>
    
    
    
    <table  class="table table-dark table-striped">
    <tr>
        <th>Nombre</th>
        <th>Edad</th>
        <th>Grupo</th>
        <th colspan=2 >Accion:</th>
    </tr>
    <?php
    if($datos->num_rows>0){
        while($tupla=$datos->fetch_assoc()){
            ?>

            <tr>
                <td><?php  echo $tupla['Nombre'];?></td>
                <td><?php  echo $tupla['Edad'];?></td>
                <td><?php  echo $tupla['Grupo'];?></td>
                <td><a href="<?php echo $_SERVER['PHP_SELF'] .'?eliminar=' . $tupla['Id']; ?>"> Eliminar</a></td>
                <td><a href="<?php echo $_SERVER['PHP_SELF'] . '?editar=' . $tupla['Id']; ?>">Editar</a></td>
            </tr>
            <?php
        }
    }
    ?>
</table>
</div>
    <body>
</html> 

<?php
require_once('CONEXION.php');
include('Bd/index.html');


$conecta= new  conexion('localhost','root','','itvo');

$conecta->conectar();

$datos=$conecta->listar('alumno');

//eliminar
if (isset($_GET["eliminar"]) && is_numeric($_GET["eliminar"])) {
    $idEliminar = $_GET["eliminar"];
    
    if ($conecta->eliminar('alumno', $idEliminar)) {
        echo "Registro eliminado con éxito.";
    } else {
        echo "Error al eliminar el registro.";
    }
    $datos=$conecta->listar('alumno');
}
//insertar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["Nombre"];
    $edad = $_POST["Edad"];
    $grupo = $_POST["Grupo"];
    $idEditar=$_POST["idEditar"];

    if(empty($idEditar)){
        $conecta->insertar($nombre, $edad, $grupo);
    } else{
     
        $datosEditar=$conecta->obtenerDatosParaEditar("alumno",$idEditar);
        if($datosEditar){
            $POST['Nombre']=$datosEditar['Nombre'];
            $POST['Edad']=$datosEditar['Edad'];
            $POST['Grupo']=$datosEditar['Grupo'];
        }
        $conecta->editar("alumno",$idEditar,$nombre,$edad,$grupo);
    }
   $datos=$conecta->listar('alumno');
} 

?>

<html>
    <head>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="bootstrap/css/bootstrap.bundle.min.js"></script>

    </head>
    <body>

<div class="container text-center">

    <form class="mw-md-xl mx-auto"  action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
    <div class="row align-items-center">
    <div class="col-5">
        <input type="hidden" name="idEditar" value= "<?php if (isset($_GET['editar'])) echo $_GET['editar']; ?>">
        <label  for="Nombre">Nombre:</label>
        <input type="text" name="Nombre" required class="form-control "><br><br>
        
        <label for="Edad">edad:</label>
        <input type="int" name="Edad" required class="form-control"><br><br>

        <label for="Grupo">grupo:</label>
        <input type="text" name="Grupo" required class="form-control"><br><br>
        
        <input type="submit" value="Insertar" class="btn btn-outline-primary">
</div>
</div>
        
    </form>
<br></br>
    
    
    
    <table  class="table table-dark table-striped">
    <tr>
        <th>Nombre</th>
        <th>Edad</th>
        <th>Grupo</th>
        <th colspan=2 >Accion:</th>
    </tr>
    <?php
    if($datos->num_rows>0){
        while($tupla=$datos->fetch_assoc()){
            ?>

            <tr>
                <td><?php  echo $tupla['Nombre'];?></td>
                <td><?php  echo $tupla['Edad'];?></td>
                <td><?php  echo $tupla['Grupo'];?></td>
                <td><a href="<?php echo $_SERVER['PHP_SELF'] .'?eliminar=' . $tupla['Id']; ?>"> Eliminar</a></td>
                <td><a href="<?php echo $_SERVER['PHP_SELF'] . '?editar=' . $tupla['Id']; ?>">Editar</a></td>
            </tr>
            <?php
        }
    }
    ?>
</table>
</div>
    <body>
</html> 


<?php
require_once('CONEXION.php');
include('Bd/index.html');


$conecta= new  conexion('localhost','root','','itvo');

$conecta->conectar();

$datos=$conecta->listar('alumno');

//eliminar
if (isset($_GET["eliminar"]) && is_numeric($_GET["eliminar"])) {
    $idEliminar = $_GET["eliminar"];
    
    if ($conecta->eliminar('alumno', $idEliminar)) {
        echo "Registro eliminado con éxito.";
    } else {
        echo "Error al eliminar el registro.";
    }
    $datos=$conecta->listar('alumno');
}
//insertar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["Nombre"];
    $edad = $_POST["Edad"];
    $grupo = $_POST["Grupo"];
    $idEditar=$_POST["idEditar"];

    if(empty($idEditar)){
        $conecta->insertar($nombre, $edad, $grupo);
    } else{
     
        $datosEditar=$conecta->obtenerDatosParaEditar("alumno",$idEditar);
        if($datosEditar){
            $POST['Nombre']=$datosEditar['Nombre'];
            $POST['Edad']=$datosEditar['Edad'];
            $POST['Grupo']=$datosEditar['Grupo'];
        }
        $conecta->editar("alumno",$idEditar,$nombre,$edad,$grupo);
    }
   $datos=$conecta->listar('alumno');
} 

?>

<html>
    <head>
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="bootstrap/css/bootstrap.bundle.min.js"></script>

    </head>
    <body>

<div class="container text-center">

    <form class="mw-md-xl mx-auto"  action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
    <div class="row align-items-center">
    <div class="col-5">
        <input type="hidden" name="idEditar" value= "<?php if (isset($_GET['editar'])) echo $_GET['editar']; ?>">
        <label  for="Nombre">Nombre:</label>
        <input type="text" name="Nombre" required class="form-control "><br><br>
        
        <label for="Edad">edad:</label>
        <input type="int" name="Edad" required class="form-control"><br><br>

        <label for="Grupo">grupo:</label>
        <input type="text" name="Grupo" required class="form-control"><br><br>
        
        <input type="submit" value="Insertar" class="btn btn-outline-primary">
</div>
</div>
        
    </form>
<br></br>
    
    
    
    <table  class="table table-dark table-striped">
    <tr>
        <th>Nombre</th>
        <th>Edad</th>
        <th>Grupo</th>
        <th colspan=2 >Accion:</th>
    </tr>
    <?php
    if($datos->num_rows>0){
        while($tupla=$datos->fetch_assoc()){
            ?>

            <tr>
                <td><?php  echo $tupla['Nombre'];?></td>
                <td><?php  echo $tupla['Edad'];?></td>
                <td><?php  echo $tupla['Grupo'];?></td>
                <td><a href="<?php echo $_SERVER['PHP_SELF'] .'?eliminar=' . $tupla['Id']; ?>"> Eliminar</a></td>
                <td><a href="<?php echo $_SERVER['PHP_SELF'] . '?editar=' . $tupla['Id']; ?>">Editar</a></td>
            </tr>
            <?php
        }
    }
    ?>
</table>
</div>
    <body>
</html> 