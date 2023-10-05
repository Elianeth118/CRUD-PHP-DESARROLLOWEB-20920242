<?php
 include ('CONEXION.php');
 include ('Conect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtiene los datos del formulario
        $nombre = $_POST["Nombre"];
        $edad = $_POST["Edad"];
        $grupo = $_POST["Grupo"];
    
   
        // Llama a la función insertarDatos para insertar los datos en la base de datos
        $resultado = insertarDatos($nombre, $edad, $grupo);
    
        // Cierra la conexión a la base de datos
        $conexion->desconectar();
    
        // Muestra el resultado de la inserción
        echo $resultado;
    }
    ?>