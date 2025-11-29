<?php

//Llamo a BD
include("../../../PHP Src/BD/conexion.php");
$con = conectar();

//Obtengo el id de la palabra clave
$sql_pc = "SELECT id FROM palabras_clave WHERE palabra = ?"; //Consulta para buscar el id de la palabra clave ingresada
$stmt_pc = mysqli_prepare($con, $sql_pc);                    //Preparo la consulta con la funcion de conexion
mysqli_stmt_bind_param($stmt_pc, "s", $palabra_clave);       //Introduzco el dato de manera segura
mysqli_stmt_execute($stmt_pc);                               //Ejecuto
$res_pc = mysqli_stmt_get_result($stmt_pc);                  //obtengo el resultado
$fila_pc = mysqli_fetch_assoc($res_pc);                      //Extraigo una fila del resultado (si es que hay)

//Verifico si la palabra existe (osea si existe alguna fila) ----------------------------
if (!$fila_pc) { 
    //Esto se va a mostrar dentro del album, entonces antes le pongo un bloque col mas para que se vea centrado en la computadora
    ?>
    <div class="col"></div>
    <h2>Sin resultados...</h2>
    <?php
    return;
}

//Guardo el id de la palabra clave
$id_palabra_clave = $fila_pc["id"];

//Verifico que haya publicaciones con esa palabra clave ---------------------------------
$sql_posts_verificacion = "SELECT id_post FROM post_palabraclave WHERE id_palabra_clave = ?"; //Preparo la consulta con la misma estructura segura que recién
$stmt_posts_verificacion = mysqli_prepare($con, $sql_posts_verificacion);
mysqli_stmt_bind_param($stmt_posts_verificacion, "i", $id_palabra_clave);
mysqli_stmt_execute($stmt_posts_verificacion);
$res_posts_verificacion = mysqli_stmt_get_result($stmt_posts_verificacion);
$fila_posts_verificacion = mysqli_fetch_assoc($res_posts_verificacion);
//Hago la verificacion viendo si existe alguna fila en el resultado
if (!$fila_posts_verificacion) { 
    //Esto se va a mostrar dentro del album, entonces antes le pongo un bloque col mas para que se vea centrado en la computadora
    ?>
    <div class="col"></div>
    <h2>Sin resultados...</h2>
    <?php
    return;
}

// ------------------ Ahora si, si la palabra existe y tiene posts, los muestro ------------------ 
//Ahora obtengo todos los posts relacionados (va posts.* para indicar que obtenga todos los campos de posts, para evitar confusiones con el join)
$sql = "SELECT posts.*
FROM posts
JOIN post_palabraclave ON posts.id = post_palabraclave.id_post
WHERE post_palabraclave.id_palabra_clave = $id_palabra_clave
ORDER BY posts.id DESC;";

$posts_extraidos = mysqli_query(conectar(), $sql);


//Recorro cada post
while ($extraer = mysqli_fetch_array($posts_extraidos)){

    //Extraigo los datos del post
    $id = $extraer["id"];
    $id_usuario = $extraer["id_usuario"];
    $titulo = $extraer["titulo"];
    $descripcion = $extraer["texto"];
    $imagen = base64_encode($extraer["imagen"]);
    $fecha = $extraer["fecha"];
    $hora = $extraer["hora"];
    $archivo = $extraer["archivo"];
    $nombre_archivo = $extraer["nombre_archivo"];
    $likes = $extraer["likes"];

    //Extraigo el nombre de usuario por el id desde la tabla de usuarios
    $consulta_nombre_usuario = "SELECT username FROM usuarios WHERE id = $id_usuario;";
    $resultado_usuario = mysqli_query(conectar(), $consulta_nombre_usuario);

    //Variable del nombre de usuario
    $nombre_usuario = "Usuario";
    if ($fila = mysqli_fetch_assoc($resultado_usuario)) { //Intento extraer el dato de la tabla
        $nombre_usuario = $fila['username']; //Cargo el nombre de usuario a su variable
    }

    //Extraigo la foto de perfil del usuario
    $consulta_FP = "SELECT foto_perfil FROM usuarios WHERE id = $id_usuario;";
    $resultado_FP = mysqli_query(conectar(), $consulta_FP);
    //Variable del nombre de usuario
    $foto_perfil = null;
    if ($fila_FP = mysqli_fetch_assoc($resultado_FP)) { //Intento extraer el dato de la tabla
        $foto_perfil = base64_encode($fila_FP['foto_perfil']); //Cargo la foto de perfil a su variable
    }

    ?>

    <div class="col">
        <div class="card shadow-sm">
            <img src="data:image/jpg;base64,<?php echo $imagen;?>" alt="Foto publicación" style="width: 100%;">

            <div class="card-body">

                <!-- HEADER DE USUARIO -->
                <div class="d-flex align-items-center mb-3">
                    <img src="data:image/jpg;base64, <?php echo $foto_perfil;?>" 
                        alt="Foto de perfil" 
                        class="rounded-circle"
                        style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">

                    <span class="fw-bold"><?php echo $nombre_usuario;?></span>
                </div>

                <!-- Info -->
                <h3>
                    <?php echo $titulo;?>
                </h3>
                <p class="card-text">
                    <?php echo $descripcion;?>
                </p>

                <!-- APARTADO DEL ARCHIVO (Verifico si hay archivo o no) -->
                <?php
                if (!$archivo == null) {
                    ?>
                    <div class="mt-3 p-3 border rounded bg-light d-flex align-items-center justify-content-between" style="margin-bottom: 20px;">
                        <div>
                            <strong>Archivo adjunto:</strong><br>
                            <span class="text-muted" style="padding-right: 4px;"><?php echo $nombre_archivo;?></span>
                        </div>

                        <a href="descargar_archivo.php?id=<?php echo $id;?>" class="btn btn-sm btn-danger">
                            Descargar
                        </a>
                    </div>
                    <?php
                }
                ?>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                        <button 
                            class="btn btn-sm btn-outline-danger btn-like"
                            data-id="<?php echo $id; ?>">
                            Like (<span class="like-count"><?php echo $likes; ?></span>)
                        </button>
                    </div>
                    <small class="text-body-secondary"><?php echo $hora;?> - <?php echo $fecha;?></small>
                </div>
            </div>
        </div>
    </div>

    <?php
}

?>