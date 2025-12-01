<?php
//Activo la sesion
session_start();

//Incluyo la función de conexión
include("../../PHP Src/BD/conexion.php");

//Reviso si llegó un id por post
if (!isset($_POST['id'])) {
    echo "error";
    exit;
}

//Guardo el id del post a likear
$id = intval($_POST['id']);
$conn = conectar();

// Obtener valor de likes
$res = $conn->query("SELECT likes FROM posts WHERE id = $id");
$fila = $res->fetch_assoc();

//Cantidad de likes
$n_likes = $fila["likes"];

//Verifico que el usuario que da like no sea el mismo que la publicó ------------
//Extraigo el id del usuario de la publicacion
$sql_verif = "SELECT id_usuario FROM posts WHERE id = $id;";
$res_verif = mysqli_query($conn, $sql_verif);
$res_id_usuario = mysqli_fetch_assoc($res_verif); // obtener la fila
$id_usuario_post = $res_id_usuario["id_usuario"]; //ID del usuario de la publicacion

//Usuario de la sesion actual
$id_usuario_sesion = $_SESSION['usuario_id'];

//Si los ids son distintos actualizo sumo un like
if ($id_usuario_post != $id_usuario_sesion) {
    // Sumar +1 al like de la tabla
    $conn->query("UPDATE posts SET likes = likes + 1 WHERE id = $id");

    // Obtener el nuevo número de likes
    $res_nuevo = $conn->query("SELECT likes FROM posts WHERE id = $id");
    $fila_nueva = $res_nuevo->fetch_assoc();

    $n_likes = $fila_nueva["likes"];
}

//Envio los likes
echo $n_likes;

?>
