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
            $sql = "SELECT * FROM $tabla WHERE estado = 1";
            $resultado = $this->conn->query($sql);
            return $resultado;
        }

        public function asignarMateria($idDocente, $idMateria) {
            // Verificar si ya existe una asignación para este maestro y materia
            $sql = "SELECT * FROM detMateria WHERE idDocente = ? AND idMateria = ? AND estado=1";
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
        public function obtenerEstado($tabla, $idDetMateria) {
            $sql = "SELECT estado FROM $tabla WHERE idDetMateria= $idDetMateria";
            $resultado = $this->conn->query($sql);
        
            if ($resultado->num_rows > 0) {
                $fila = $resultado->fetch_assoc();
                return $fila['estado'];
            } else {
                return null; // Docente no encontrado o estado no definido
            }
        }
        
        public function cambiarEstado($tabla, $idDetMateria) {
            // Obtener el estado actual del docente
            $estadoActual = $this->obtenerEstado($tabla, $idDetMateria);
        
            // Cambiar el estado
            $nuevoEstado = ($estadoActual == 1) ? 0 : 1;
        
            // Actualizar el estado en la base de datos
            $sql = "UPDATE $tabla SET estado = $nuevoEstado WHERE idDetMateria = $idDetMateria";
        
            return $this->conn->query($sql);
        }
            function mostrarDetalle($condicion=NULL){
            $sql='SELECT DT.idDetMateria, D.nomDocente AS docente, M.nomMateria AS materia
            FROM docente D
            JOIN detmateria DT ON D.idDocente=DT.idDocente
            JOIN materia M ON DT.idMateria=M.idMateria
            WHERE DT.estado=1'.$condicion;
            $resultado=$this ->conn->query($sql);
            return $resultado;
            }
        
    

            public function contarAlumnosPorDocente($idDocente, $idDetMateria) {
                $sql = "SELECT COUNT(DA.idAlumno) AS total_alumnos
                        FROM detMateria DM
                        JOIN detAlumno DA ON DM.idDetMateria = DA.idDetMateria
                        WHERE DM.idDocente = $idDocente AND DM.idDetMateria = $idDetMateria AND DA.estado = 1";
        
                $result = $this->conn->query($sql);
        
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        return $row["total_alumnos"];
                    }
                } else {
                    return 0;
                }
            }

            }
            
    



?>