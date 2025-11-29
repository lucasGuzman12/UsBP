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
    ?>

<main>
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="card p-5" style="max-width: 400px;">

            <h2 class="text-center mb-4">Iniciar sesión</h2>

            <form action="iniciar_sesion_PROC.php" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="email" placeholder="tumail@ubp.edu.ar" name="email" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" placeholder="********" name="contrasenia">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn boton-usbp">Acceder</button>
                </div>
            </form>
            
            <p class="text-center mt-3">
                ¿No tienes cuenta? <a href="../Registrarse/registrarse.php" class="text-decoration-none" style="color:#bb0b3b;">Regístrate aquí</a>
            </p>
        </div>
    </div>
</main>
</body>
</html>
