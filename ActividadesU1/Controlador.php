<?php
require_once ('Fruta.php');

$manzana= new Fruta();
echo  "la fruta  ".$manzana->get_nombre()."  Es de color  ".$manzana->get_color() ;
$manzana->set_color('Roja');
$manzana->set_nombre('manzanita');

echo  "la fruta  ".$manzana->get_nombre()."  Es de color  ".$manzana->get_color();