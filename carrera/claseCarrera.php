<?php
class carrera{
    private $conn;
    private $idCarrera;
    private $nomCarrera;
    private $clave;
    private $estado;
    
    function __construct($conn){
        $this->nomCarrera="";
        $this->clave="";
        $this->estado="";
        $this->conn=$conn;

    }

function listar($tabla){
        $sql = "SELECT * FROM $tabla WHERE estado = 1";
        $resultado = $this->conn->query($sql);
        return $resultado;
    }
    
//Carrera
function insertarCarrera($nomCarrera, $clave){
    $sql='INSERT INTO carrera (nomCarrera, clave) VALUES ("' .$nomCarrera .'","' .$clave .'")';
    $this->conn->query($sql);
    
}
function eliminarCarrera($tabla, $idCarrera){
    $sql = 'DELETE FROM ' . $tabla . ' WHERE idCarrera = ' . $idCarrera;
    
    if ($this->conn->query($sql) === TRUE) {
        return true; 
    } else {
        return false;
    }
}
function editarCarrera($tabla, $idCarrera, $nomCarrera, $clave){
    $sql = 'UPDATE ' . $tabla . ' SET nomCarrera = "' . $nomCarrera . '", clave = "' . $clave . '" WHERE idCarrera = ' . $idCarrera;
    
    if ($this->conn->query($sql) === TRUE) {
        return true; // La edición fue exitosa
    } else {
        return false; // Hubo un error en la edición
    }
}
function obtenerDatosCarrera($tabla, $idCarrera) {
    $sql = "SELECT * FROM $tabla WHERE idCarrera = $idCarrera";
    $resultado = $this->conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    } else {
        return false; 
}
}
public function obtenerEstadoCarrera($tabla, $idCarrera) {
    $sql = "SELECT estado FROM $tabla WHERE idCarrera = $idCarrera";
    $resultado = $this->conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        return $fila['estado'];
    } else {
        return null; // Docente no encontrado o estado no definido
    }
}

public function cambiarEstadoCarrera($tabla, $idCarrera) {
    // Obtener el estado actual del docente
    $estadoActual = $this->obtenerEstadoCarrera($tabla, $idCarrera);

    // Cambiar el estado
    $nuevoEstado = ($estadoActual == 1) ? 0 : 1;

    // Actualizar el estado en la base de datos
    $sql = "UPDATE $tabla SET estado = $nuevoEstado WHERE idCarrera = $idCarrera";

    return $this->conn->query($sql);
}



    }
    ?>