<?php

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
            $sql = 'SELECT * FROM ' .$tabla;
            $resultado = $this->conn->query($sql);
            return $resultado;
        }
        public function asignarMateria($idDocente, $idMateria) {
            // Verificar si ya existe una asignación para este maestro y materia
            $sql = "SELECT * FROM detMateria WHERE idDocente = ? AND idMateria = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $idDocente, $idMateria);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                echo "Esta materia ya está asignada a este docente."; // Ya existe una asignación para este maestro y materia
            }else{
  // Si no existe, realizar la inserción
  $sql = "INSERT INTO detMateria (idDocente, idMateria) VALUES (?, ?)";
  $stmt = $this->conn->prepare($sql);
  $stmt->bind_param("ii", $idDocente, $idMateria);

  if ($stmt->execute()) {
      return true; // Éxito al asignar la materia
  } else {
      return false; // Error al asignar la materia
  }

            }
        
          
        }
  
    }



?>