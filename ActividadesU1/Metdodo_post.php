<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <Form method="post " action="<?php  echo $_SERVER['PHP_SELF']?>">
    Nombre: <input type="text" name="nombre">
    Edad: <input type="text" name="edad">
    <input type="submit" >


    </Form>
    <?php
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $name=$_POST['nombre'];
    $edad=$_POST['edad'];

    if(empty($name)AND empty($edad)){
        echo "sin datos";
    }else{
        echo $name." tienes ".$edad;
    }
 

    }

    ?>
</body>
</html>