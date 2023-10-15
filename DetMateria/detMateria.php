<?php
require_once('../CONEXION.php');
require_once('../Docente/claseDocente.php');
require_once('../materia/claseMateria.php');
require_once('claseDetMat.php');
require_once('index.html');


$conecta = new conexion('localhost', 'root', '', 'itvo2');
$conecta->conectar();
$objdetMateria = new detMateria($conecta->get_conn());

$datos = $objdetMateria->listar('detMateria');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idDocente = $_POST["idDocente"];
    $idMateria = $_POST["idMateria"];

    $objdetMateria->asignarMateria($idDocente, $idMateria);
}
// Cambiar estado (activo o inactivo)
if (isset($_GET["cambiarEstado"]) && is_numeric($_GET["cambiarEstado"])) {
    $idCambiarEstado = $_GET["cambiarEstado"];
    
    if ($objdetMateria->cambiarEstado('detmateria', $idCambiarEstado)) {
        //echo "Estado de la materia actualizado con éxito.";
    } else {
        //echo "Error al actualizar el estado de la materia";
    }
    
$datos = $objdetMateria->listar('detMateria');

}
$datos = $objdetMateria->listar('detMateria');
?>

<html>
<head>
    <title>Asignación de Materias a Docentes</title>
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../bootstrap/css/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css.css">
  
</head>
<body>
<div class="mt-3"></div>
        <h3 style="font-size: 16px; font-weight: bold;text-align: center">Asignación de Materias a Docentes</h3>
    <div class="row justify-content-center">
    <div class="col-md-6 mt-3">
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
    <input type="hidden" name="idEditar" value="<?php echo isset($_GET['idEditar']) ? $_GET['idEditar'] : ''; ?>">
    <div class="form-group">
        <label for="Docente">Docente:</label>
        <select name="idDocente" required class="form-control">
            <option value="">Seleccione un Docente</option>
            <?php
            $docentes = $objdetMateria->listar('docente');
            while ($docente= $docentes->fetch_assoc()) {
                $selected = ($docente['idDocente'] == $idDocente) ? "selected" : "";
                echo "<option value='{$docente['idDocente']}' $selected>{$docente['nomDocente']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="Materia">Materia:</label>
        <select name="idMateria" required class="form-control">
            <option value="">Seleccione una Materia</option>
            <?php
            $materias = $objdetMateria->listar('materia');
            while ($materia= $materias->fetch_assoc()) {
                $selected = ($materia['idMateria'] == $idMateria) ? "selected" : "";
                echo "<option value='{$materia['idMateria']}' $selected>{$materia['nomMateria']}</option>";
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
                            <th>Docente</th>
                            <th>Materia</th>
                            <th>Estado</th>
                            <th colspan=2>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
    if ($datos->num_rows > 0) {
        while ($tupla = $datos->fetch_assoc()) {
            $estado = ($tupla['estado'] == 1) ? 'Activo' : 'Inactivo';
            $docenteNombre = ""; // Variable para almacenar el nombre del docente
            $materiaNombre = ""; // Variable para almacenar el nombre de la materia

            // Obtener el nombre del docente asociado al ID
            $docentes = $objdetMateria->listar('docente');
            while ($docente = $docentes->fetch_assoc()) {
                if ($docente['idDocente'] == $tupla['idDocente']) {
                    $docenteNombre = $docente['nomDocente'];
                    break;
                }
            }

            // Obtener el nombre de la materia asociada al ID
            $materias = $objdetMateria->listar('materia');
            while ($materia = $materias->fetch_assoc()) {
                if ($materia['idMateria'] == $tupla['idMateria']) {
                    $materiaNombre = $materia['nomMateria'];
                    break;
                }
            }
            ?>
            <tr>
                <td><?php echo $docenteNombre; ?></td>
                <td><?php echo $materiaNombre; ?></td>
                <td><?php  echo $estado ?></td>

                <td><a href="<?php echo $_SERVER['PHP_SELF'] .'?cambiarEstado=' . $tupla['idDetMateria']; ?>">Cambiar Estado</a></td>
               

              
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
