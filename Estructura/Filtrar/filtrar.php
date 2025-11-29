<?php
//Abro la sesion y verifico que quien este accediendo haya iniciado sesion antes
    include("../../PHP Src/Utilidades/verificacion_sesion.php");
    verificar_sesion("../Acceder/acceder.php");
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UsBP - Filtrar</title>
    <link rel="icon" type="image/x-icon" href="../../Media/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="filtrar_styles.css">
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
                    <a href="../Filtrar/filtrar.php" class="nav-link" style="color: var(--rojo-usbp); background-color: var(--blanco-usbp);" aria-current="page">
                        <img src="../../Media/BarraInferior/searchR.webp" style="width: 20px;" alt="Filtrar">
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../Publicar/publicar.php" class="nav-link" style="color: var(--blanco-usbp);">
                        <img src="../../Media/BarraInferior/add.webp" style="width: 20px;" alt="Publicar">
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

        <h2>Buscar por Palabra Clave o Tema</h2>

        <div class="container mt-4">

            <!-- BUSCADOR por palabra clave -->
            <div class="card shadow p-3 bg-white mb-4">
                <form class="d-flex justify-content-center" action="Buscador/resultados_buscador.php" method="POST" enctype="multipart/form-data">
                    <div class="input-group" style="max-width: 400px;">
                        <input class="form-control"  id="buscar" name="buscar" type="search" placeholder="Palabra clave..." aria-label="Buscar">
                        <button class="btn boton-usbp" type="submit">Buscar</button>
                    </div>
                </form>
            </div>

            <!-- BUSCADOR por tema -->
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <?php
                //Llamo a BD
                include("../../PHP Src/BD/conexion.php");

                //Obtengo los temas
                $sql = "SELECT * FROM temas;";
                $temas = mysqli_query(conectar(), $sql);

                //Recorro cada tema
                while ($extraer = mysqli_fetch_array($temas)){
                    //Extraigo los datos del tema
                    $id = $extraer["id"];
                    $nombre_tema = $extraer["tema"];
                    
                    //Agrego el tema poniendo el id del tema en el enlace para usar REQUEST
                    ?>
                    <a href="Temas/resultados_temas.php?id=<?php echo $id; ?>&nom=<?php echo $nombre_tema; ?>" class="btn d-flex tema-usbp justify-content-center align-items-center">
                        <?php echo $nombre_tema; ?>
                    </a>
                    <?php
                }
                ?>
            </div>

        </div>
        
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>