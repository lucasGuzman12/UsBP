<?php

session_start();//inicio sesion
//verificacion de sesion
if (!isset($_SESSION['usuario_id'])) {
    die("NO_LOGIN");
}

//CONEXION BD------------------------------
include("../../PHP Src/BD/conexion.php");
//conecto a BD
$con = conectar();
//verifico conexion
if (!$con) {
    die("ERROR_CONEXION");
}

//OBTENEMOS DATOS------------------------------
$usuario_id = intval($_SESSION['usuario_id']);
$id_post = intval($_GET['id']);

//PREPARO Y EJECUTO CONSULTA PARA ELIMINAR PALABRA CLAVE RELACIONADA------------------------------
$sql1 = "DELETE FROM post_palabraclave WHERE id_post = ?";
$stmt1 = mysqli_prepare($con, $sql1);
$stmt1->bind_param("i", $id_post);
$stmt1->execute();

//PREPARO Y EJECUTO CONSULTA PARA ELIMINAR EL POST------------------------------
$sql = "DELETE FROM posts WHERE id = ? AND id_usuario = ?";
$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id_post, $usuario_id);
mysqli_stmt_execute($stmt);

//PREPARO Y EJECUTO CONSULTA PARA ELIMINAR EL POST------------------------------
if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "OK";
}
else {
    echo "NO_DELETE";
    echo "Error al eliminar el post: " . mysqli_error($con);
}
?>
