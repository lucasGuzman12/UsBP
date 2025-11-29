<?php
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

// Sumar +1 al like de la tabla
$conn->query("UPDATE posts SET likes = likes + 1 WHERE id = $id");

// Obtener valor actualizado
$res = $conn->query("SELECT likes FROM posts WHERE id = $id");
$fila = $res->fetch_assoc();

//Imprimo los likes ya actualizados
echo $fila["likes"];
?>