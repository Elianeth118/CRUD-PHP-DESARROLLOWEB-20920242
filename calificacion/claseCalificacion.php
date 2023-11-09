<?php
class calificacion{
    private $conn;
    private $idCalificacion;
    private $calificacion;
    private $idAlumno;
    
    function __construct($conn){
        $this->calificacion="";
        $this->idAlumno="";
        $this->conn=$conn;

    }
    //Alumno
function listar($tabla){
        $sql = "SELECT * FROM $tabla WHERE estado = 1";
        $resultado = $this->conn->query($sql);
        return $resultado;
    }
    
    function insertarCalificacion($idDetAlumno, $calificacion) {
        // Verificar si ya existe una calificación para el idDetAlumno
        $sql_verificacion = "SELECT idCalificacion FROM calificacion WHERE idDetAlumno = ? AND estado=1";
        $stmt_verificacion = $this->conn->prepare($sql_verificacion);
        $stmt_verificacion->bind_param("i", $idDetAlumno);
        $stmt_verificacion->execute();
        $result_verificacion = $stmt_verificacion->get_result();
    
        if ($result_verificacion->num_rows > 0) {
            // Ya existe una calificación para este idDetAlumno
            echo "Este alumno ya tiene calificacion.";
        } else {
            // No existe una calificación, procede con la inserción
            $sql = 'INSERT INTO calificacion (idDetAlumno, calificacion) VALUES (?, ?)';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is", $idDetAlumno, $calificacion);
    
            // Ejecuta la consulta
            if ($stmt->execute()) {
                // La calificación se insertó con éxito
                return true;
            } else {
                // Ocurrió un error al insertar la calificación
                return false;
            }
        }
    }
    

 function editar($tabla, $idAlumno, $calificacion, $idCalificacion) {
        $calificacion = $this->conn->real_escape_string($calificacion);
        $idAlumno= $this->conn->real_escape_string($idAlumno);
    
        $sql = 'UPDATE ' . $tabla . ' SET calificacion= "' . $calificacion . '", idAlumno = "' . $idAlumno . '" WHERE idAlumno = ' . $idAlumno;
    
        if ($this->conn->query($sql) === TRUE) {
            return true; // La edición fue exitosa
        } else {
            // Error en la edición, obtener mensaje de error
            $error = $this->conn->error;
            echo "Error en la edición: " . $error;
            return false;
        }
    }
   
public function obtenerEstadoCalificacion($tabla, $idCalificacion) {
    $sql = "SELECT estado FROM $tabla WHERE idCalificacion = $idCalificacion";
    $resultado = $this->conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        return $fila['estado'];
    } else {
        return null; // Docente no encontrado o estado no definido
    }
}

public function cambiarEstadoCali($tabla, $idCalificacion) {
    // Obtener el estado actual del docente
    $estadoActual = $this->obtenerEstadoCalificacion($tabla, $idCalificacion);

    // Cambiar el estado
    $nuevoEstado = ($estadoActual == 1) ? 0 : 1;

    // Actualizar el estado en la base de datos
    $sql = "UPDATE $tabla SET estado = $nuevoEstado WHERE idCalificacion = $idCalificacion";

    return $this->conn->query($sql);
}




}

?>