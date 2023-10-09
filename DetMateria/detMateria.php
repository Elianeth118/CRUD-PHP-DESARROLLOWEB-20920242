
<?php
require_once('../CONEXION.php');
require_once('../index.html');
require_once('claseDetMat.php');

$conecta = new conexion('localhost', 'root', '', 'itvo2');
$conecta->conectar();
$objDetMateria=new detMateria($conecta->get_conn());

?>


<!DOCTYPE html>
<html>
<head>
    <title>Formulario de Asignación de Materias</title>
</head>
<body>
    <h1>Asignación de Materias a Docentes</h1>
    
    <!-- Formulario para seleccionar docente y materia -->
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
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
        <label for="materia">Seleccione una Materia:</label>
        <select name="materia" id="materia">
            <?php
                // Aquí deberías consultar tu base de datos para obtener la lista de materias
                // y llenar las opciones del select con sus nombres y IDs
                // Esto es solo un ejemplo estático
                echo '<option value="1">Materia 1</option>';
                echo '<option value="2">Materia 2</option>';
                echo '<option value="3">Materia 3</option>';
            ?>
        </select>

        <input type="submit" value="Asignar Materia">
    </form>

    <!-- Tabla para mostrar los datos de asignación -->
    <h2>Asignaciones Actuales</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID Asignación</th>
                <th>Docente</th>
                <th>Materia</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Aquí deberías consultar tu base de datos para obtener las asignaciones actuales
                // y mostrarlas en la tabla
                // Esto es solo un ejemplo estático
                echo '<tr><td>1</td><td>Docente 1</td><td>Materia 1</td></tr>';
                echo '<tr><td>2</td><td>Docente 2</td><td>Materia 2</td></tr>';
                echo '<tr><td>3</td><td>Docente 3</td><td>Materia 3</td></tr>';
            ?>
        </tbody>
    </table>
</body>
</html>
