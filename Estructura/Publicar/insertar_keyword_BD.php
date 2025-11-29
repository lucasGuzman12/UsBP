<?php
// insertar_keyword.php
include("../../PHP Src/BD/conexion.php");

//Verifico si recibi o no una palabra
if (!isset($_POST["palabra"])) {
    echo "ERROR: palabra no enviada";
    exit;
}

//Elimino los posibles espacios sobrantes
$palabra = trim($_POST["palabra"]);

//------ Busco la palabra en la BD para saber si existe ------
$con = conectar();
//Veo si existe (uso consulta preparada para evitar la inyecciond e codigo)
$stmt = $con->prepare("SELECT id FROM palabras_clave WHERE palabra = ? LIMIT 1"); //Preparo la consulta
$stmt->bind_param("s", $palabra);   //Aseguro la consulta y le indico el tipo de dato (string) al servidor
$stmt->execute();                   //Ejecuto la consulta
$res = $stmt->get_result();         //Obtengo el resultado
//Checkeo si existe alguna fila con ese dato y si existe devuelvo el id directamente y salgo
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    echo $row["id"]; //Devuelvo el id
    exit;
}

//En caso de que la palabra no exista la agrego a la BD:
$stmt = $con->prepare("INSERT INTO palabras_clave (palabra) VALUES (?)");
$stmt->bind_param("s", $palabra);
//Ejecuto la consulta verificando si se pudo o no
if ($stmt->execute()) { //Si no hubo errores devuelvo el nuevo id
    echo $stmt->insert_id;
}
else {
    echo "ERROR"; //Si hubo un error devuelvo el string ERROR, que hara saltar el error en el archivo js
}
?>