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
        
        function insertar($idDetMateria,$idAlumno){
            $sql='INSERT INTO detalumno (idDetMateria,idAlumno,estado) VALUES ('.$idDetMateria.','.$idAlumno.',1);';
            $this->conn->query($sql);
        }

        function listar($tabla){
            $sql = "SELECT * FROM $tabla WHERE estado = 1";
            $resultado = $this->conn->query($sql);
            return $resultado;
        }
        public function listarPorEstado($tabla, $estado) {
            $sql = "SELECT * FROM $tabla WHERE estado = $estado";
            $resultado = $this->conn->query($sql);
            return $resultado;
        }
        public function asignarMateriaAlumno($idAlumno,$idDetMateria ) {
            // Verificar si ya existe una asignación para este maestro y materia
            $sql = "SELECT * FROM detalumno WHERE idAlumno = ? AND idDetMateria = ? AND estado=1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $idAlumno, $idDetMateria);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                echo "Esta materia ya está asignada a este Alumno."; // Ya existe una asignación para este maestro y materia
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

        public function cambiarEstadoDetAlumno($tabla, $idDetAlumno) {
            $estadoActual = $this->obtenerEstadoDetalumno($tabla, $idDetAlumno);
            // Cambiar el estado
            $nuevoEstado = ($estadoActual == 1) ? 0 : 1;
            // Actualizar el estado en la base de datos
            $sql = "UPDATE $tabla SET estado = $nuevoEstado WHERE idDetAlumno = $idDetAlumno";
            return $this->conn->query($sql);
        }

        
        public function obtenerEstadoDetalumno($tabla, $idDetAlumno) {
            $sql = "SELECT estado FROM detalumno WHERE idDetAlumno= $idDetAlumno";
            $resultado = $this->conn->query($sql);
            if ($resultado->num_rows > 0) {
                $fila = $resultado->fetch_assoc();
                return $fila['estado'];
            } else {
                return null; 
            }
        }
        

        function mostrarDetalleAlumno($idDetMateria){
            $sql="SELECT D.idDetMateria, A.nombre, A.grupo, D.idDetAlumno FROM alumno A JOIN detAlumno D ON A.idAlumno=D.idAlumno WHERE D.estado=1 AND A.estado=1 AND D.idDetMateria='".$idDetMateria."';";
            $resultado=$this->conn->query($sql);
            return $resultado;
        }




    }



?>