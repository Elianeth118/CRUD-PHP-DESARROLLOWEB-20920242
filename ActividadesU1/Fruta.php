<?php
class Fruta{
    private $nombre;

    private $color;
    
    function _construct(){
       $this->nombre="";
       $this->color="";
    }

    
    function set_nombre($nom){
        $this->nombre=$nom;
    }
    function get_nombre(){
        return $this->nombre;
    }
    function set_color($clr){
        $this->color=$clr;
    }
    function get_color(){
        return $this->color;
    }
       
}
?>