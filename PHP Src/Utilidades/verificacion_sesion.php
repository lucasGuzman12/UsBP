<?php
    function verificar_sesion($ruta_acceder) {
        //Abrimos la sesion
        session_start();

        // Verificamos si hay una sesion activa, sino lo mandamos a iniciar sesion / registrarse
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: $ruta_acceder"); //redirecciono a la pagina de inicio de sesion / registro
            exit();
        }
    }
?>