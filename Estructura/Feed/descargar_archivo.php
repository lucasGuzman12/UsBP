<?php
//Llamo a BD
include("../../PHP Src/BD/conexion.php");

//Conecto a BD
$con = conectar();

//Obtengo del id del post para saber cual es el archivo
$id = intval($_GET['id']);

//Obtengo de la tabla el archivo y su nombre
$sql = "SELECT archivo, nombre_archivo FROM posts WHERE id = $id LIMIT 1";
$resultado = mysqli_query($con, $sql);
$arch = mysqli_fetch_assoc($resultado);

//Guardo en variables
$archivo = $arch["archivo"];
$nombre = $arch["nombre_archivo"];

//Descargo el archivo
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$nombre\"");
header("Content-Length: " . strlen($archivo));

echo $archivo;
exit;
