<?php
//Verifico la sesion
include("../../../PHP Src/Utilidades/verificacion_sesion.php");
verificar_sesion("../../Acceder/acceder.php");
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UsBP - Ver publicación</title>
    <link rel="icon" type="image/x-icon" href="../../../Media/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
<!-- Barra inferior -->
<nav class="fixed-bottom" style="background-color: var(--rojo-usbp);">
    <div class="d-flex justify-content-center py-3">
        <ul class="nav nav-pills gap-4">
            <li class="nav-item">
                <a href="../feed.php" class="nav-link" style="color: var(--rojo-usbp); background-color: var(--blanco-usbp);" aria-current="page">
                    <img src="../../../Media/BarraInferior/homeR.webp" style="width: 20px;" alt="Feed">
                </a>
            </li>
            <li class="nav-item">
                <a href="../../Filtrar/filtrar.php" class="nav-link" style="color: var(--blanco-usbp);">
                    <img src="../../../Media/BarraInferior/search.webp" style="width: 20px;" alt="Filtrar">
                </a>
            </li>
            <li class="nav-item">
                <a href="../../Publicar/publicar.php" class="nav-link" style="color: var(--blanco-usbp);">
                    <img src="../../../Media/BarraInferior/add.webp" style="width: 20px;" alt="Publicar">
                </a>
            </li>
            <li class="nav-item">
                <a href="../../Mi Perfil/mi_perfil.php" class="nav-link" style="color: var(--blanco-usbp);"> 
                    <img src="../../../Media/BarraInferior/user.webp" style="width: 20px;" alt="Mi Perfil">
                </a>
            </li>
        </ul>
    </div>
</nav>

<?php
// Cabecera
include("../../../PHP Src/Piezas Web/cabecera.php");
cabecera("../../../Media/logo_usbp.webp", false);

// Conexión a BD
include("../../../PHP Src/BD/conexion.php");
$con = conectar();

// Validar que llega el ID
if (!isset($_GET['id'])) {
    echo "ID no especificado.";
    exit;
}

//Obtengo el id pasado por get
$id_post = intval($_GET['id']);


// Consulto el post
$sql = "SELECT id, id_usuario, titulo, texto, contenido, imagen, fecha, hora, archivo, nombre_archivo, likes, vistas 
        FROM posts 
        WHERE id = ?";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_post);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Si no existe el post
if (mysqli_num_rows($result) == 0) {
    echo "El post no existe.";
    exit;
}

$post = mysqli_fetch_assoc($result);

// Cargo las variables
$id = $post["id"];
$id_usuario = $post["id_usuario"];
$titulo = $post["titulo"];
$descripcion = $post["texto"];
$contenido = $post["contenido"];
$imagen = base64_encode($post["imagen"]);
$fecha = $post["fecha"];
$hora = $post["hora"];
$archivo = $post["archivo"];
$nombre_archivo = $post["nombre_archivo"];
$likes = $post["likes"];
$vistas = $post["vistas"];

// Extraigo el nombre de usuario y la foto de perfil en funcion del id del post
$sql_user = "SELECT username, foto_perfil FROM usuarios WHERE id = ?";
$stmt2 = mysqli_prepare($con, $sql_user);
mysqli_stmt_bind_param($stmt2, "i", $id_usuario);
mysqli_stmt_execute($stmt2);
$res_user = mysqli_stmt_get_result($stmt2);

//Preparo las variables del usuario
$nombre_usuario = "Usuario";
$foto_perfil = null;

//Cargo los valores a las variables
if ($u = mysqli_fetch_assoc($res_user)) {
    $nombre_usuario = $u["username"];
    $foto_perfil = base64_encode($u["foto_perfil"]);
}

//Actualizo las vistas del post
$sql = "UPDATE posts SET vistas = vistas + 1 WHERE id = $id";
mysqli_query($con, $sql);

//Cierro las conexiones a BD
mysqli_stmt_close($stmt);
mysqli_stmt_close($stmt2);
mysqli_close($con);
?>

<main style="padding-bottom: 90px; ">
    <div class="container">
        <div class="card shadow-sm "style="padding:3%;" >

            <!-- Titulo -->
            <h3 style="font-size:50px; font-weight:bold; margin-bottom:20px;">
                <?php echo $titulo;?>
            </h3>

            <!-- HEADER DE USUARIO -->
            <div class="d-flex align-items-center mb-3 " >
                <img src="data:image/jpg;base64, <?php echo $foto_perfil;?>" 
                    alt="Foto de perfil" 
                    class="rounded-circle"
                    style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">

                <span class="fw-bold"><?php echo $nombre_usuario;?></span>
            </div>

            <!-- Descripcion -->
            <p class="card-text">
                <?php echo $descripcion;?>
            </p>

            <hr>

            <!-- Imagen del post -->
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 text-center">
                        <img src="data:image/jpg;base64,<?php echo $imagen;?>" 
                            class="img-fluid rounded" 
                            alt="Foto publicación">
                    </div>
                </div>
            </div>

            <hr>
            
            <div class="card-body">

                <!-- Contenido -->
                <p class="card-text">
                    <?php echo $contenido;?>
                </p>

                <!-- Archivo -->
                <?php
                //Verifico si hay archivo o no
                if (!$archivo == null) {
                    ?>
                    <div class="mt-3 p-3 border rounded bg-light d-flex align-items-center justify-content-between shadow-sm" style="margin-bottom: 20px;">
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
                    <small class="text-body-secondary">Likes: <?php echo $likes;?></small>
                    <small class="text-body-secondary">Visitas: <?php echo $vistas;?></small>
                    <small class="text-body-secondary"><?php echo $hora;?> - <?php echo $fecha;?></small>
                </div>
            </div>
        </div>
    </div>
</main>
</body>