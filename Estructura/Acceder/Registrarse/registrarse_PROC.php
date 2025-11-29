<?php
session_start(); //Inicio la sesion
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>USBP - Registrarse</title>
    <link rel="icon" type="image/x-icon" href="../../../Media/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="registrarse_styles.css">
</head>


<body>

<?php
    //Incluyo la cabecera
    include("../../../PHP Src/Piezas Web/cabecera.php");
    cabecera("../../../Media/logo_usbp.webp", true); //Argumentos: (Ruta logo, es flotante)

    //Llamo a BD
    include("../../../PHP Src/BD/conexion.php");

    $conectar = conectar();

    //Preparo los datos ingresados para cargarlos a BD
    $nombre     = $_POST["nombre"];
    $apellido   = $_POST["apellido"];
    $username   = $_POST["username"];
    $email      = $_POST["email"];
    $contrasenia = password_hash($_POST["contrasenia"], PASSWORD_DEFAULT); //Hasheo la contraseña para que no se vea en la BD
    $foto_perfil = null; //Preparo la carga de la foto de perfil
    if (isset($_FILES["foto_perfil"]) && $_FILES["foto_perfil"]["error"] === 0) {
        //Obtengo la foto de perfil cargada por el usuario
        $foto_perfil = file_get_contents($_FILES["foto_perfil"]["tmp_name"]);
    }

    //Antes que nada verifico que el mail no exista (para que no sea una cuenta existente ya) -----------------------------
    $sql_verificacion = "SELECT id FROM usuarios WHERE email = ?";      //Busco un usuario que tenga ese mail
    $stmt_verificacion = mysqli_prepare($conectar, $sql_verificacion);  //Preparo para ejecutar la consulta
    mysqli_stmt_bind_param($stmt_verificacion, "s", $email);            //Reemplazo el dato en la consulta
    mysqli_stmt_execute($stmt_verificacion);                            //Ejecuto
    $resultado_verificacion = mysqli_stmt_get_result($stmt_verificacion); //Guardo resultados
    // Si se encontró un usuario con ese email detengo y muestro un mensaje
    if (mysqli_num_rows($resultado_verificacion) > 0) { //Veo si hay algun resultado para la consulta (si hay es porque ya existe)
        ?>
        <main class="d-flex align-items-center justify-content-center vh-100">

            <div class="card p-5 text-center" style="max-width: 400px;">
                <h1 class="mb-3">Registro inválido</h1>
                <p class="text-muted mb-4">El email ya está registrado.</p>

                <p class="text-center mt-3">
                    ¿Ya tienes cuenta? <a href="../Iniciar Sesion/iniciar_sesion.php" class="text-decoration-none" style="color:#bb0b3b;">Inicia sesión</a>
                </p>
            </div>
        </main>
        <?php
        exit;
    }


    // ------------------ Ahora si, si el mail no existía, registro la cuenta ------------------
    // Preparo la consulta para insertar usuario
    $sql = "INSERT INTO usuarios 
    (nombre, apellido, username, email, contrasenia, foto_perfil)
    VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conectar, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "sssssb",
        $nombre,
        $apellido,
        $username,
        $email,
        $contrasenia,
        $foto_perfil
    );
    mysqli_stmt_send_long_data($stmt, 5, $foto_perfil); //Cargar la foto de perfil en la posicion 5

    //Intento ejecutar la consulta y atajo posibles errores
    if (mysqli_stmt_execute($stmt)) {

        // Obtener ID del usuario recién registrado
        $usuario_id = mysqli_insert_id($conectar);

        // Usuario válido: Iniciar las variables de sesion
        session_regenerate_id(true); //Regenero el id de la sesion eliminando el viejo por seguridad (previene ataques)
        $_SESSION['email'] = $email;
        $_SESSION['usuario_id'] = $usuario_id;
        ?>
        <main>
            <div class="d-flex align-items-center justify-content-center vh-100">
                <div class="card p-5 text-center" style="max-width: 400px;">

                    <h1 class="mb-3">
                        Bienvenido a <strong>UsBP</strong>
                    </h1>

                    <p class="text-muted mb-4">
                        Tu portal de conexión universitaria
                    </p>

                    <p class="text-muted mb-4">
                        Registro exitoso!
                    </p>

                    <div class="d-grid gap-3">
                        <a href="../../Feed/feed.php" class="btn boton-usbp">Ingresar</a>
                    </div>

                </div>
            </div>
        </main>
        <?php
    }
    else {
        ?>
        <main>
            <div class="card p-5 text-center" style="max-width: 400px;">

                <h1 class="mb-3">
                    Ha ocurrido un error al registrar
                </h1>

            </div>
        </main>
        <?php
    }
    ?>
    </div>

</body>
</html>
 