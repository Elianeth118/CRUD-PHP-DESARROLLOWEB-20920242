
<?php
class Alumno {
    private $nombre;
    private $fechaNacimiento;

    public function __construct($nombre, $fechaNacimiento) {
        $this->nombre = $nombre;
        $this->fechaNacimiento = $fechaNacimiento;
    }

    public function calcularEdad() {
        $fechaActual = new DateTime();
        $fechaNacimiento = new DateTime($this->fechaNacimiento);
        $diferencia = $fechaActual->diff($fechaNacimiento);
        return $diferencia->y; // Devuelve la diferencia en años
    }

    public function getNombre() {
        return $this->nombre;
    }
}
