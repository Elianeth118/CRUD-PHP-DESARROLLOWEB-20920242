<?php
class conexion{
    private $servidor;
    private $usuario;
    private $pass;
    private $basedatos;
    private $conn;
     
    function __construct($srv,$usr,$pw,$bd){
        $this->servidor=$srv;
        $this->usuario=$usr;
        $this->pass=$pw;
        $this->basedatos=$bd;

    }
    function conectar(){
        $this->conn= new mysqli($this->servidor,$this->usuario,$this->pass,$this->basedatos);
        if($this->conn->connect_errno){
            echo "Fallo al conectar MYSQL: (" .$this->conn->connect_ernro .")".$this->conn->connect_erno;
        }
    }
    function desconectar(){
        $this->conn->close();
    }



function get_conn(){
    return $this->conn;

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





