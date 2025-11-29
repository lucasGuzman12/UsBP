<?php
session_start(); //Inicio la sesion
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>USBP - Iniciar sesión</title>
    <link rel="icon" type="image/x-icon" href="../../../Media/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <?php

    //Incluyo la cabecera
    include("../../../PHP Src/Piezas Web/cabecera.php");
    cabecera("../../../Media/logo_usbp.webp", true); //Argumentos: (Ruta logo, es flotante)


    // CONEXIÓN A LA BD
    include("../../../PHP Src/BD/conexion.php");
    $conectar = conectar();

    // PROCESAR FORMULARIO
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //Extraigo los datos
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $contrasenia = $_POST['contrasenia'];

        // Buscar usuario
        $sql = "SELECT id, email, contrasenia FROM usuarios WHERE email = ?";
        $stmt = mysqli_prepare($conectar, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        $usuario = mysqli_fetch_assoc($resultado);

        // Validación usuario erronea
        if (!$usuario) {
            ?>
            <main class="d-flex align-items-center justify-content-center vh-100">
                <div class="card p-5 text-center" style="max-width: 400px;">
                    <h1 class="mb-3">Usuario inválido</h1>
                    <p class="text-muted mb-4">El email no está registrado.</p>
                
                    <p class="text-center mt-3">
                        ¿No tienes cuenta? <a href="../Registrarse/registrarse.php" class="text-decoration-none" style="color:#bb0b3b;">Regístrate aquí</a>
                    </p>
                </div>
            </main>
            <?php
            exit;
        }

        // Validación contraseña erronea
        if (!password_verify($contrasenia, $usuario["contrasenia"])) {
            ?>
            <main class="d-flex align-items-center justify-content-center vh-100">
                <div class="card p-5 text-center" style="max-width: 400px;">
                    <h1 class="mb-3">Usuario inválido</h1>
                    <p class="text-muted mb-4">Contraseña incorrecta.</p>
                </div>
            </main>
            <?php
            exit;
        }

        // Usuario válido: Iniciar las variables de sesion
        session_regenerate_id(true); //Regenero el id de la sesion eliminando el viejo por seguridad (previene ataques)
        $_SESSION['email'] = $email;
        $_SESSION['usuario_id'] = $usuario['id'];
        ?>
        <main class="d-flex align-items-center justify-content-center vh-100">

            <div class="card p-5 text-center" style="max-width: 400px;">

                <h1 class="mb-3">Bienvenido a <strong>UsBP</strong></h1>
                <p class="text-muted mb-4">Tu portal de conexión universitaria</p>

                <div class="d-grid gap-3">
                    <a href="../../Feed/feed.php" class="btn boton-usbp">Ingresar</a>
                </div>
            </div>
        </main>
        <?php
        exit;

    }
    else {
        ?>
        <main class="d-flex align-items-center justify-content-center vh-100">
            <div class="card p-5 text-center" style="max-width: 400px;">
                <h1 class="mb-3">Ha ocurrido un error...</h1>
                <p class="text-muted mb-4">Consulta con el soporte técnico.</p>
            </div>
        </main>
        <?php
    }
    ?>

</body>
</html>
