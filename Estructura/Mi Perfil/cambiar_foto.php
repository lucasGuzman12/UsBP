<?php
//Comenzar la sesion
session_start();
if (!isset($_SESSION['usuario_id'])) { //Corroborar sesion activa
    die("Usuario no autenticado");
}
$usuario_id = $_SESSION['usuario_id']; //Usuario de la sesion

//Conexion con BD
include("../../PHP Src/BD/conexion.php");
$con = conectar();

//Verificar que se haya seleccionado un archivo
if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    die("Error al recibir archivo");
}

$imagen = file_get_contents($_FILES['foto']['tmp_name']);

//Consulta para actualizar la foto
$sql = "UPDATE usuarios SET foto_perfil = ? WHERE id = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "si", $imagen, $usuario_id);

//Ejecutar consulta
if (mysqli_stmt_execute($stmt)) {
    echo "OK";
} else {
    echo "ERROR: " . mysqli_error($con);
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
