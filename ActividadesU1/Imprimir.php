<?php
require_once ('Alumno.php');

$alumno= new Alumno();
$alumno->set_nombre('Elianeth');
$alumno->set_año(2003);
$alumno->set_añoActual(2023);



echo  "El nombre del alumno es ".$alumno->get_nombre()." la edad del alumno es  ".$alumno->edad();