<?php

require_once("EDAD2.PHP");

// Crear una instancia de la clase Alumno

$alumno = new Alumno("ELI", "2002-05-11");

// Calcular la edad del alumno
$edad = $alumno->calcularEdad();

echo "Nombre: " . $alumno->getNombre() . "<br>";
echo "Edad: " . $edad . " años";
?>