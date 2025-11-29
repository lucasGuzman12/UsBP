<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>USBP - Acceder</title>
    <link rel="icon" type="image/x-icon" href="../../Media/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="acceder_styles.css">
</head>
<body>

    <?php
    //Incluyo la cabecera
    include("../../PHP Src/Piezas Web/cabecera.php");
    cabecera("../../Media/logo_usbp.webp", true); //Argumentos: (Ruta logo, es flotante)
    ?>

    <div class="d-flex justify-content-center align-items-center vh-100">

        <div class="card shadow p-4" style="width: 22rem;">
            <h3 class="text-center mb-4">Acceder a UsBP</h3>

            <div class="d-grid gap-3">
                <a href="Iniciar Sesion/iniciar_sesion.php" class="btn btn-usbp">Iniciar sesión</a>
                <p class="text-muted text-center mb-1">----- ¿No tienes una cuenta? -----</p>
                <a href="Registrarse/registrarse.php" class="btn btn-usbp-blanco">Registrarse</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
