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




}





