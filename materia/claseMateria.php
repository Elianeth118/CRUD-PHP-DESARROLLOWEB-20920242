<?php
class docente{
    private $conn;
    private $idMateria;
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
}