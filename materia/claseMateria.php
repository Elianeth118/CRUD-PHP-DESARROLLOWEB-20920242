<?php
class materia{
    private $conn;
    private $idMateria;
    private $nomMateria;
    private $creditos;
    private $semestre;
    private $clave;
    private $estado;
    
    function __construct($conn){
        $this->nomMateria="";
        $this->creditos="";
        $this->semestre="";
        $this->clave="";
        $this->estado="";
        $this->conn=$conn;

    }
    
    function listar($tabla){
        $sql = "SELECT * FROM $tabla WHERE estado = 1";
        $resultado = $this->conn->query($sql);
        return $resultado;
    }
    



//Materia
function insertarMateria($nomMateria,$creditos,$semestre,$clave){
    $sql='INSERT INTO materia (nomMateria, creditos,semestre, clave) VALUES ("' .$nomMateria .'","' .$creditos .'","' .$semestre .'","' .$clave.'")';
    $this->conn->query($sql);
    
}
function eliminarMateria($tabla, $idMateria){
    $sql = 'DELETE FROM ' . $tabla . ' WHERE idMateria = ' . $idMateria;
    
    if ($this->conn->query($sql) === TRUE) {
        return true; 
    } else {
        return false;
    }
}
function editarMateria($tabla, $idMateria, $nomMateria,$creditos,$semestre,$clave){
    $sql = 'UPDATE ' . $tabla . ' SET nomMateria = "' . $nomMateria .'" , creditos = "' . $creditos .'", semestre= "' . $semestre .'",clave = "' . $clave .'"  WHERE idMateria= ' . $idMateria;
    
    if ($this->conn->query($sql) === TRUE) {
        return true; // La edición fue exitosa
    } else {
        return false; // Hubo un error en la edición
    }
}
function obtenerDatosMateria($tabla, $idMateria) {
    $sql = "SELECT * FROM $tabla WHERE idMateria = $idMateria";
    $resultado = $this->conn->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    } else {
        return false; 
}
}
public function obtenerEstadoMateria($tabla, $idMateria) {
    $sql = "SELECT estado FROM $tabla WHERE idMateria = $idMateria";
    $resultado = $this->conn->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        return $fila['estado'];
    } else {
        return null; // Docente no encontrado o estado no definido
    }
}

public function cambiarEstadoMateria($tabla, $idMateria) {
    // Obtener el estado actual del docente
    $estadoActual = $this->obtenerEstadoMateria($tabla, $idMateria);

    // Cambiar el estado
    $nuevoEstado = ($estadoActual == 1) ? 0 : 1;

    // Actualizar el estado en la base de datos
    $sql = "UPDATE $tabla SET estado = $nuevoEstado WHERE idMateria = $idMateria";

    return $this->conn->query($sql);
}



}