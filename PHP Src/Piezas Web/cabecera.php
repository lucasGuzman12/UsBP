<?php
function cabecera($ruta_logo, $flotante) {
    ?>
    <div class="container"
        style="width: 100%;
            max-width: 100%;
            padding: 5px 40px;
            background-color: #a3223a;
            box-shadow: 0 5px 10px rgba(0,0,0,0.35);
            margin-bottom: 10px;
            <?php
            if ($flotante == true) { ?>
            position: fixed;
            top: 0;
            left: 0;
            z-index: 999; <?php } ?>">

        <header class="d-flex flex-wrap justify-content-center py-3 mb-4" style="border-bottom: 1px solid #f8f9fa;">
            <a href="" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <img src="<?php echo $ruta_logo;?>" alt="Logo UsBP" style="width: 50px; margin-right: 10px;">
                <span class="fs-4" style="color: #f8f9fa;">UsBP</span>
            </a>
        </header>
    </div>
    <?php
}
?>