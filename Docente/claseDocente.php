<?php
class docente{
    private $conn;
    private $idDocente;
    private $nomDocente;
    private $estado;
    
    function __construct($conn){
        $this->nomDocente="";
        $this->estado="";
        $this->conn=$conn;

    }
    
    function listar($tabla){
        $sql = "SELECT * FROM $tabla WHERE estado = 1";
        $resultado = $this->conn->query($sql);
        return $resultado;
    }
    
//Docente
function insertarDocente($nomDocente){
    $sql='INSERT INTO Docente (nomDocente) VALUES ("' .$nomDocente .'")';
    $this->conn->query($sql);
    
}
function eliminarDocente($tabla, $idDocente){
    $sql = 'DELETE FROM ' . $tabla . ' WHERE idDocente = ' . $idDocente;
    
    if ($this->conn->query($sql) === TRUE) {
        return true; 
    } else {
        return false;
    }
}
function editarDocente($tabla, $idDocente, $nomDocente){
    $sql = 'UPDATE ' . $tabla . ' SET nomDocente = "' . $nomDocente .'" WHERE idDocente= ' . $idDocente;
    
    if ($this->conn->query($sql) === TRUE) {
        return true; // La edición fue exitosa
    } else {
        return false; // Hubo un error en la edición
    }
}
function obtenerDatosDocente($tabla, $idDocente) {
    $sql = "SELECT * FROM $tabla WHERE idDocente = $idDocente";
    $resultado = $this->conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    } else {
        return false; 
}
}

public function obtenerEstadoDocente($tabla, $idDocente) {
    $sql = "SELECT estado FROM $tabla WHERE idDocente = $idDocente";
    $resultado = $this->conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        return $fila['estado'];
    } else {
        return null; // Docente no encontrado o estado no definido
    }
}

public function cambiarEstadoDocente($tabla, $idDocente) {
    // Obtener el estado actual del docente
    $estadoActual = $this->obtenerEstadoDocente($tabla, $idDocente);

    // Cambiar el estado
    $nuevoEstado = ($estadoActual == 1) ? 0 : 1;

    // Actualizar el estado en la base de datos
    $sql = "UPDATE $tabla SET estado = $nuevoEstado WHERE idDocente = $idDocente";

    return $this->conn->query($sql);
}

}
