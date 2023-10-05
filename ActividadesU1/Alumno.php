<?php
class Alumno{
    private $nombre;
    private $año;
    private $añoActual;
    
    function _construct(){
       $this->nombre=$nom;
       $this->año=$año;
       $this ->añoActual=$añoActual;
    }

    
    function set_nombre($nom){
        $this->nombre=$nom;
    }
    function get_nombre(){
        return $this->nombre;
    }
    function set_año($año){
        $this->año=$año;
    }
    function get_año(){
        return $this->año;
    }
    function set_añoActual($añoActual){
        $this->añoActual=$añoActual;
    }
    function get_añoActual(){
        return $this->añoActual;
    }
    function edad(){
        return $this->get_añoActual()- $this->get_año();
    }
}