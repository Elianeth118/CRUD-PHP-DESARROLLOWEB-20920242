<?php
require_once('../CONEXION.php');
require_once('claseCarrera.php');
require_once('index.html');

class detMateria{
    
        private $conn;
        private $idDetMateria;
        private $idDocente;
        private $idMateria;
   
        
        function __construct($conn){
            $this->idDocente="";
            $this->idMateria="";
            $this->conn=$conn;
    
        }

        function listar($tabla){
            $sql = "SELECT * FROM $tabla WHERE estado = 1";
            $resultado = $this->conn->query($sql);
            return $resultado;
        }
        function asignarMateriaADocente($idDocente, $idMateria) {
            global $conn;
        
            // Asegurémonos de que los IDs de docente y materia sean válidos
            // Esto debe hacerse antes de ejecutar la inserción en la base de datos para evitar problemas de seguridad
            $idDocente = mysqli_real_escape_string($conn, $idDocente);
            $idMateria = mysqli_real_escape_string($conn, $idMateria);
        
            // Query SQL para insertar la asignación en la tabla detMateria
            $sql = "INSERT INTO detmateria (idDocente, idMateria) VALUES ('$idDocente', '$idMateria')";
        
            if ($conn->query($sql) === TRUE) {
                echo "Asignación exitosa.";
            } else {
                echo "Error al asignar la materia: " . $conn->error;
            }
        }
}


?>