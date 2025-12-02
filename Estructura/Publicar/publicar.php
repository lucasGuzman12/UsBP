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
    <title>UsBP - Publicar</title>
    <link rel="icon" type="image/x-icon" href="../../Media/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="publicar_styles.css">
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
                    <a href="../Publicar/publicar.php" class="nav-link" style="color: var(--rojo-usbp); background-color: var(--blanco-usbp);" aria-current="page">
                        <img src="../../Media/BarraInferior/addR.webp" style="width: 20px;" alt="Publicar">
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

        <div class="contenedor-formulario">
            <h2>Nueva Publicación</h2>

            <form action="publicar_PROC.php" method="POST" enctype="multipart/form-data">
        
                <!--Titulo-->
                <label for="titulo">Título *</label>
                <input id="titulo" type="text" name="titulo" placeholder="Escriba el título..." required>

                <!--Sintesis del post-->
                <label for="descripcion">Breve síntesis del post *</label>
                <textarea id="descripcion" name="descripcion" placeholder="Escriba una breve síntesis..." required></textarea>

                <!--Contenido del post-->
                <label for="contenido">Contenido *</label>
                <textarea id="contenido" name="contenido" placeholder="Escriba el contenido del post..." required></textarea>

                <!--Imagen-->
                <label for="imagen">Imagen *</label>
                <input id="imagen" type="file" name="imagen" accept="image/*" required>

                <!--Archivo, si se quiere-->
                <label for="archivo">Archivo adicional (opcional)</label>
                <input id="archivo" type="file" name="archivo">

                <!--Tema-->
                <label for="tema">Tema *</label>
                <select id="tema" name="tema" required>
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
                        
                        //Agrego el tema al formulario poniendo su id como value
                        ?>
                        <option value="<?php echo $id; ?>">
                            <?php echo $nombre_tema; ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>

                <!--Palabras clave-->
                <label>Palabras clave (hashtags)</label>
                <div id="keywordsBox" class="keywords-box">
                    <?php
                    //Obtengo las palabras clave disponibles
                    $sql = "SELECT * FROM palabras_clave;";
                    $temas = mysqli_query(conectar(), $sql);

                    //Recorro cada palabra clave
                    while ($extraer = mysqli_fetch_array($temas)){
                        //Extraigo los datos de la palabra clave actual
                        $id = $extraer["id"];
                        $palabra_clave = $extraer["palabra"];
                        
                        //Agrego la palabra clave al formulario poniendo su id como value
                        ?>
                        <div class="keyword-item">
                            <input type="checkbox" name="keywords[]" value="<?php echo $id; ?>">
                            <span><?php echo $palabra_clave; ?></span>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <input type="text" id="newKeyword" placeholder="Agregar nueva palabra clave...">
                <button type="button" class="add-keyword-btn" onclick="agregarKeyword()">Agregar palabra clave</button>

                <!--Enviar formulario-->
                <button type="submit">Publicar</button>
            </form>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="agregar_keyword.js"></script>
</body>
</html>
