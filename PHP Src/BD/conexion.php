<?php

function conectar(){
    $user = "root";
    $pass = "";
    $server = "localhost";
    $db = "usbp_pruebas";

    $con = mysqli_connect($server, $user, $pass, $db);

    //Si hay un error salgo
    if(!$con){
        die("Error al conectar a la Base de Datos: " . mysqli_connect_error());
    }

    //Especifico la codificacion utf8
    mysqli_set_charset($con, "utf8mb4");

    return $con;
}


?>