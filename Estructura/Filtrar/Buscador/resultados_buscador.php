<?php
//Abro la sesion y verifico que quien este accediendo haya iniciado sesion antes
    include("../../../PHP Src/Utilidades/verificacion_sesion.php");
    verificar_sesion("../../Acceder/acceder.php");
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UsBP - Filtrado por Palabras Clave</title>
    <link rel="icon" type="image/x-icon" href="../../../Media/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="resultados_busqueda_styles.css">
</head>
<body>

    <!-- Barra inferior -->
    <nav class="fixed-bottom" style="background-color: var(--rojo-usbp);">
        <div class="d-flex justify-content-center py-3">
            <ul class="nav nav-pills gap-4">
                <li class="nav-item">
                    <a href="../../Feed/feed.php" class="nav-link" style="color: var(--blanco-usbp);">
                        <img src="../../../Media/BarraInferior/home.webp" style="width: 20px;" alt="Publicar">
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../../Filtrar/filtrar.php" class="nav-link" style="color: var(--rojo-usbp); background-color: var(--blanco-usbp);" aria-current="page">
                        <img src="../../../Media/BarraInferior/searchR.webp" style="width: 20px;" alt="Filtrar">
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
    //Incluyo la cabecera
    include("../../../PHP Src/Piezas Web/cabecera.php");
    cabecera("../../../Media/logo_usbp.webp", false); //Argumentos: (Ruta logo, es flotante)

    //Extraigo la palabra clave
    $palabra_clave = trim($_POST["buscar"]);
    ?>


    <main>
        <div class="album album-publicaciones">

            <h2>Publicaciones sobre: <?php echo $palabra_clave;?></h2>

            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

                    <?php
                    //Extraigo los posts de la palabra clave
                    include("extraer_por_busqueda.php");
                    ?>

                </div>
            </div>
        </div>
    </main>

    <script>
    //Espero a que la pagina termine de cargar
    document.addEventListener("DOMContentLoaded", () => {
        
        //Selecciono los botones que tengan la clase btb-like
        const botones = document.querySelectorAll(".btn-like");

        //Recorro cada boton y agrego un evento click
        botones.forEach(boton => {
            boton.addEventListener("click", () => {

                //Obtengo el id del post desde el data-id
                const id = boton.dataset.id;
                //Encuentro el span donde se muestra la cantidad
                const contador = boton.querySelector(".like-count");

                //Envio al servidor un post hacia like.php (que se encuentra en la carpeta de feed) con el id
                fetch("../../Feed/like.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id=" + id
                })
                .then(res => res.text()) //Convierto a texto plano
                .then(nuevoValor => {
                    contador.textContent = nuevoValor; //Reemplazo por el nuevo valor
                })
                .catch(err => console.log(err));
            });
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>