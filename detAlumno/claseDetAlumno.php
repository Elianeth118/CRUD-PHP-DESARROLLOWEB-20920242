<?php

class detAlumno{
    
        private $conn;
        private $idDetAlumno;
        private $idDetMateria;
        private $idAlumno;
      
   
        
        function __construct($conn){
            $this->idDetMateria="";
            $this->idAlumno="";
            $this->conn=$conn;
    
        }

        function listar($tabla){
            $sql = 'SELECT * FROM ' .$tabla;
            $resultado = $this->conn->query($sql);
            return $resultado;
        }
        public function asignarMateriaAlumno($idAlumno,$idDetMateria ) {
            // Verificar si ya existe una asignación para este maestro y materia
            $sql = "SELECT * FROM detalumno WHERE idAlumno = ? AND idDetMateria = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $idAlumno, $idDetMateria);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                echo "Esta materia ya está asignada a este docente."; // Ya existe una asignación para este maestro y materia
            }else{
  // Si no existe, realizar la inserción
  $sql = "INSERT INTO detalumno (idAlumno, idDetMateria) VALUES (?, ?)";
  $stmt = $this->conn->prepare($sql);
  $stmt->bind_param("ii", $idAlumno, $idDetMateria);

  if ($stmt->execute()) {
      return true; // Éxito al asignar la materia
  } else {
      return false; // Error al asignar la materia
  }

            }
        
          
        }
        public function obtenerMateriaPorID($idMateria) {
            $sql = "SELECT * FROM materia WHERE idMateria = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idMateria);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows == 1) {
                return $result->fetch_assoc();
            } else {
                return null; // Devuelve null si no se encuentra la materia
            }
        }
        public function obtenerDocentePorID($idDocente) {
            $sql = "SELECT * FROM docente WHERE idDocente = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $idDocente);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows == 1) {
                return $result->fetch_assoc();
            } else {
                return null; // Devuelve null si no se encuentra la materia
            }
        }
  
    }



?>