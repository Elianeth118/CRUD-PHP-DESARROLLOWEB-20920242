<?php
class alumno{
    private $conn;
    private $idAlumno;
    private $nombre;
    private $edad;
    private $grupo;
    private $estado;
    private $idCarrera;
    function __construct($conn){
        $this->nombre="";
        $this->edad="";
        $this->grupo="";
        $this->estado="";
        $this->idCarrera="";
        $this->conn=$conn;

    }
    //Alumno
function listar($tabla){
        $sql = "SELECT * FROM $tabla WHERE estado = 1";
        $resultado = $this->conn->query($sql);
        return $resultado;
    }
function insertar($nombre, $edad, $grupo, $idCarrera) {
        // Escapar y validar los parámetros
        $nombre = $this->conn->real_escape_string($nombre);
        $edad = $this->conn->real_escape_string($edad);
        $grupo = $this->conn->real_escape_string($grupo);
        $idCarrera = intval($idCarrera); // Convertir a entero
    
        // Verificar si el idCarrera existe en la tabla carrera
        $sql = 'SELECT COUNT(*) as count FROM carrera WHERE idCarrera = ' . $idCarrera;
        $result = $this->conn->query($sql);
    
        if ($result->num_rows === 0) {
            echo "El idCarrera proporcionado no existe en la tabla carrera.";
            return false;
        }
    
        // Insertar los datos en la tabla alumno
        $sql = 'INSERT INTO alumno (nombre, edad, grupo, idCarrera) VALUES ("' . $nombre . '","' . $edad . '","' . $grupo . '","' . $idCarrera . '")';
    
        if ($this->conn->query($sql)) {
            return true; // Inserción exitosa
        } else {
            // Error en la inserción, obtener mensaje de error
            $error = $this->conn->error;
            echo "Error en la inserción: " . $error;
            return false;
        }
    }
    

 function eliminar($tabla, $idAlumno){
        $sql = 'DELETE FROM ' . $tabla . ' WHERE idAlumno = ' . $idAlumno;
        
        if ($this->conn->query($sql) === TRUE) {
            return true; 
        } else {
            return false;
        }
    }
 function editar($tabla, $idAlumno, $nombre, $edad, $grupo, $idCarrera) {
        $nombre = $this->conn->real_escape_string($nombre);
        $edad = $this->conn->real_escape_string($edad);
        $grupo = $this->conn->real_escape_string($grupo);
        $idCarrera = $this->conn->real_escape_string($idCarrera);
    
        $sql = 'UPDATE ' . $tabla . ' SET nombre = "' . $nombre . '", edad = "' . $edad . '", grupo = "' . $grupo . '", idCarrera = "' . $idCarrera . '" WHERE idAlumno = ' . $idAlumno;
    
        if ($this->conn->query($sql) === TRUE) {
            return true; // La edición fue exitosa
        } else {
            // Error en la edición, obtener mensaje de error
            $error = $this->conn->error;
            echo "Error en la edición: " . $error;
            return false;
        }
    }
    
 function obtenerDatosAlumno($tabla, $idAlumno) {
        $sql = "SELECT * FROM $tabla WHERE idAlumno = $idAlumno";
        $resultado = $this->conn->query($sql);
    
        if ($resultado && $resultado->num_rows > 0) {
            return $resultado->fetch_assoc();
        } else {
            return false; 
    }
}
public function obtenerEstadoAlumno($tabla, $idAlumno) {
    $sql = "SELECT estado FROM $tabla WHERE idAlumno = $idAlumno";
    $resultado = $this->conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        return $fila['estado'];
    } else {
        return null; // Docente no encontrado o estado no definido
    }
}

public function cambiarEstadoAlumno($tabla, $idAlumno) {
    // Obtener el estado actual del docente
    $estadoActual = $this->obtenerEstadoAlumno($tabla, $idAlumno);

    // Cambiar el estado
    $nuevoEstado = ($estadoActual == 1) ? 0 : 1;

    // Actualizar el estado en la base de datos
    $sql = "UPDATE $tabla SET estado = $nuevoEstado WHERE idAlumno = $idAlumno";

    return $this->conn->query($sql);
}




}

?>