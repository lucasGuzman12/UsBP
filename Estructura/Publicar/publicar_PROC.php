<?php
//Abro la sesion y verifico que quien este accediendo haya iniciado sesion antes
    include("../../PHP Src/Utilidades/verificacion_sesion.php");
    verificar_sesion("../Acceder/acceder.php");

    //obtenemos el id del usuario en sesion
    $id_usuario = $_SESSION['usuario_id'];
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UsBP - Publicar</title>
    <link rel="icon" type="image/x-icon" href="../../Media/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="publicar_styles.css">
</head>
<body>

    <!-- Barra inferior -->
    <nav class="fixed-bottom" style="background-color: var(--rojo-usbp);">
        <div class="d-flex justify-content-center py-3">
            <ul class="nav nav-pills gap-4">
                <li class="nav-item">
                    <a href="../Feed/feed.php" class="nav-link" style="color: var(--blanco-usbp);">
                        <img src="../../Media/BarraInferior/home.webp" style="width: 20px;" alt="Publicar">
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../Filtrar/filtrar.php" class="nav-link" style="color: var(--blanco-usbp);">
                        <img src="../../Media/BarraInferior/search.webp" style="width: 20px;" alt="Filtrar">
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../Publicar/publicar.php" class="nav-link" style="color: var(--rojo-usbp); background-color: var(--blanco-usbp);" aria-current="page">
                        <img src="../../Media/BarraInferior/addR.webp" style="width: 20px;" alt="Publicar">
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../Mi Perfil/mi_perfil.php" class="nav-link" style="color: var(--blanco-usbp);"> 
                        <img src="../../Media/BarraInferior/user.webp" style="width: 20px;" alt="Mi Perfil">
                    </a>
                </li>
            </ul>
        </div>
    </nav>


    <?php
    //Incluyo la cabecera
    include("../../PHP Src/Piezas Web/cabecera.php");
    cabecera("../../Media/logo_usbp.webp", false); //Argumentos: (Ruta logo, es flotante)
    ?>


    <main>

        <div class="contenedor-formulario" style="padding: 20px 0 4px 0;">

            <?php

            //Llamo a BD
            include("../../PHP Src/BD/conexion.php");
            $conexion = conectar();

            //Extraigo los datos del formulario
            $titulo = $_POST["titulo"];
            $descripcion = $_POST["descripcion"];
            $imagen = file_get_contents($_FILES["imagen"]["tmp_name"]);
            //Veo si se cargo un archivo
            $nombre_archivo = "";
            $archivo = "";
            if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
                $nombre_archivo = $_FILES['archivo']['name'];
                $archivo = file_get_contents($_FILES['archivo']['tmp_name']);
            }
            $id_tema = $_POST["tema"];
            //Las palabras clave se van a guardar en formato array y contendra el id de cada palabra clave
            $keywords = isset($_POST["keywords"]) ? $_POST["keywords"] : [];

            //Obtengo la fecha y hora actuales
            date_default_timezone_set('America/Argentina/Cordoba');
            $fecha = date("d/m/Y");
            $hora = date("H:i");

            //Defino los likes en 0
            $likes = 0;

            //Inserto el post a la base de datos
            $stmt = $conexion->prepare("
                INSERT INTO posts(id_usuario, titulo, texto, imagen, fecha, hora, id_tema, archivo, nombre_archivo, likes)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param( //Aseguro la consulta y doy los tipos de dato
                "isssssissi", 
                $id_usuario, 
                $titulo, 
                $descripcion, 
                $imagen, 
                $fecha, 
                $hora, 
                $id_tema, 
                $archivo, 
                $nombre_archivo, 
                $likes
            );
            $stmt->execute(); //Ejecuto la consulta
            $resultado1 = $stmt->affected_rows > 0; //Guardo si el resultado fue correcto o no

            //Extraigo el id del post creado
            $id_post = $stmt->insert_id;

            //Inserto la conexion con la/s palabra/s clave si se seleccionaron
            $resultado2 = true; //Para saber si se pudo insertar todo correctamente
            foreach ($keywords as $id_palabra_clave) {
                
                //Intento insertar
                $stmt_key = $conexion->prepare("INSERT INTO post_palabraclave(id_post, id_palabra_clave) VALUES (?, ?)");
                $stmt_key->bind_param("ii", $id_post, $id_palabra_clave);
                $resultado_keyw = $stmt_key->execute();
                $stmt_key->close();   // Cierro el statement de insertar keywords

                //Si hubo un error al insertar lo indico en resultado2
                if (!$resultado_keyw) { $resultado2 = false; }
            }

            $stmt->close();       // Cierro el statement principal del post
            $conexion->close();   // Cierro la conexión a la BD

            if ($resultado1 && $resultado2) {
            ?>
            <h2>Publicación Realizada!</h2>
            <?php
            }
            else {
            ?>
            <h2>Ha ocurrido un error al publicar...</h2>
            <?php
            }
            ?>

        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>