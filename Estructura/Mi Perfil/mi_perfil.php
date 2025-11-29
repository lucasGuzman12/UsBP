<?php
//Abro la sesion y verifico que quien este accediendo haya iniciado sesion antes
include("../../PHP Src/Utilidades/verificacion_sesion.php");
verificar_sesion("../Acceder/acceder.php");

//incluimos el file con la función de conexión
include("../../PHP Src/BD/conexion.php");
$con = conectar();



// ============================
//	 OBTENER DATOS DEL USUARIO
// ============================

//obtenemos el id del usuario en sesion
$usuario_id = $_SESSION['usuario_id'];

//obtenemos el username, email y foto de perfil del usuario con su id
$sql_user = "SELECT username, email, foto_perfil FROM usuarios WHERE id = ?";

//preparamos la consulta
$stmt_user = mysqli_prepare($con, $sql_user);

//verificamos que la consulta sea correcta
if (!$stmt_user) {
	die("Error al preparar consulta de usuario: " . mysqli_error($con));
}

//Preparamos las variables para los datos a extraer
$username = "";
$email = "";

//vinculamos el parámetro y ejecutamos la consulta
mysqli_stmt_bind_param($stmt_user, "i", $usuario_id);// "i" indica que el parámetro es un entero
mysqli_stmt_execute($stmt_user);//ejecutamos la consulta
mysqli_stmt_store_result($stmt_user);//almacenamos el resultado
mysqli_stmt_bind_result($stmt_user, $username, $email, $foto_perfil);//vinculamos las variables al resultado
mysqli_stmt_fetch($stmt_user);//obtenemos los datos
mysqli_stmt_close($stmt_user);//cerramos la consulta



/* ============================
	OBTENER POSTS DEL USUARIO
============================ */
//preparamos la consulta para obtener los posts del usuario
$sql_posts = "SELECT id, titulo, texto, imagen, archivo, nombre_archivo, fecha, hora, likes 
			  FROM posts 
			  WHERE id_usuario = ?
			  ORDER BY id DESC";
$stmt_posts = mysqli_prepare($con, $sql_posts);//preparamos consulta

if (!$stmt_posts) {
	die("Error al preparar consulta de posts: " . mysqli_error($con));//verfificamos consulta
}

mysqli_stmt_bind_param($stmt_posts, "i", $usuario_id);//vinculamos parametro
mysqli_stmt_execute($stmt_posts);//ejecutamos consulta
$result_posts = mysqli_stmt_get_result($stmt_posts);//obtenemos resultados y almacenamos en result_posts
?>



<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mi Perfil - UsBP</title>
	<link rel="icon" type="image/x-icon" href="../../Media/favicon.ico">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
	<link rel="stylesheet" href="mi_perfil_styles.css">

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
                    <a href="../Publicar/publicar.php" class="nav-link" style="color: var(--blanco-usbp);">
                        <img src="../../Media/BarraInferior/add.webp" style="width: 20px;" alt="Publicar">
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../Mi Perfil/mi_perfil.php" class="nav-link" style="color: var(--rojo-usbp); background-color: var(--blanco-usbp);" aria-current="page"> 
                        <img src="../../Media/BarraInferior/userR.webp" style="width: 20px;" alt="Mi Perfil">
                    </a>
                </li>
            </ul>
        </div>
    </nav>

	<!-- HEADER -->
	<header class="header-usbp text-white text-center py-4 shadow-sm">
		<h2>Mi Perfil UsBP</h2>
	</header>

    <main style="margin-bottom: 70px;">
        <!-- PERFIL -->
        <div id="info_foto" class="container py-5 text-center">

            <!-- Foto de perfil -->
            <div class="position-relative d-inline-block mb-3">
                <?php
                if (!empty($foto_perfil)) {
                ?>
                <!-- obtenemos foto de perfil -->
                <img id="fotoPerfil"
                    src="data:image/jpeg;base64,<?php echo base64_encode($foto_perfil); ?>" 
                    class="rounded-circle border border-4 border-white shadow"
                    width="160" height="160"
                    alt="Foto de perfil">
                
                <?php
                }
                ?>
                <!-- cambio Foto de perfil -->
                <label for="inputFoto"
                        class="btn btn-light rounded-circle position-absolute bottom-0 end-0 p-2 border"
                        title="Cambiar foto" style="border-color:#bb0b3b;">
                    <i class="bi bi-camera-fill" style="color:#bb0b3b;"></i>
                </label>
                <input type="file" id="inputFoto" class="d-none" accept="image/*" onchange="cambiarFoto(event)">

            </div>

            <!-- INFO USUARIO -->
            <div id="info_usuario">
                <h3  id="nombreUsuario" ><?php echo htmlspecialchars($username); //imprimimos el nombre de usuario?></h3>
                <p class="text-secondary mb-1"><?php echo htmlspecialchars($email); //imprimimos el email del usuario?></p>
            </div>
        </div>


        <!-- PUBLICACIONES -->
        <div class="container" id="publicaciones_container">
            <h4>Mis publicaciones</h4>
            <hr id="barra" class="mx-auto">

            <?php
            //Verificamos si hay publicaciones
            if (mysqli_num_rows($result_posts) === 0) {
                ?>
                <p class="text-center mb-2">Todavía no tienes publicaciones.</p>
                <?php
            }
            else {
            ?>
                <div class="row mt-4" id="publicaciones">
                <?php
                while ($post = mysqli_fetch_assoc($result_posts)) {
                    //Obtenemos cada publicacion
                    ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card border-0 p-3 posts" id="post-<?php echo $post['id']; ?>">

                            <h3><strong><?php echo htmlspecialchars($post['titulo']); //Titulo del post?></strong></h3>
                            
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($post['imagen']); ?>" class="img-fluid rounded mt-2"><!-- imprimimos la imagen del post -->

                            <p>
                                <strong>Descripción:</strong> <?php echo htmlspecialchars($post['texto']); //Texto del post?>
                            </p>
                            <?php
                            if (!empty($post['archivo'])) { //Verifico si hay archivo
                            ?>
                                <p>
                                    <strong>Archivo cargado:</strong> <?php echo htmlspecialchars($post['nombre_archivo']); //mostramos solo el nombre del archivo?>
                                </p>
                            <?php
                            }
                            ?>
                            <p>
                                <strong>Fecha:</strong> <?php echo $post['fecha']; //imprimimos la fecha en la que se subio el post ?>
                            </p>
                            <p>
                                <strong>Hora:</strong> <?php echo $post['hora']; //imprimimos la hora en la que se subio el post ?>
                            </p>
                            <p>
                                <strong>Likes:</strong> <?php echo $post['likes']; //imprimimos los likes del post?>
                            </p>

                            <!-- boton para eliminar post -->
                            <button class="btn btn-outline-danger btn-sm me-2" onclick="eliminarPost(<?php echo $post['id']; ?>)">Eliminar</button>

                        </div>
                    </div>

                <?php
                }
                ?>
                </div>
            <?php
            }
            ?>
    
        </div>

    </main>

    <script src="acciones.js"></script>
</body>
</html>
