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
cabecera("../../../Media/logo_usbp.webp", false); //Argumentos: (Ruta logo, es flotante)
?>

<main>
    <div class="d-flex align-items-start justify-content-center py-5">
    
        <div class="card p-5" style="max-width: 900px; width:600px">

            <h2 class="text-center mb-4">Crear cuenta</h2>

            <form action="registrarse_PROC.php" method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="usuario@ubp.edu.ar" required>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                </div>

                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
                </div>

                <div class="mb-3 text-center">
                    <label for="fotoPerfil" class="form-label d-block">Foto de perfil</label>
                    <!-- Vista previa de la imagen -->
                    <img id="preview" src="../../../Media/perfil.png" alt="Foto de perfil" 
                        class="rounded-circle mb-2 border" 
                        style="width:120px; height:120px; object-fit:cover;">
                    
                    <!-- Campo de selección de archivo -->
                    <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" accept="image/*" required>
                </div>

                <div class="mb-3">
                    <label for="Nombre de Usuario" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="username" name="username"  required>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasenia" name="contrasenia" required>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn boton-usbp">Registrarse</button>
                </div>
            </form>

            <p class="text-center mt-3">
                ¿Ya tienes cuenta? <a href="../Iniciar Sesion/iniciar_sesion.php" class="text-decoration-none" style="color:#bb0b3b;">Inicia sesión</a>
            </p>
        </div>
    </div>
</main>
</body>

</html>
 