//Se define una IIFE (Función autoejecutable), para poder aislar variables y crear un ambiente privado para el js.
(function() {
    // -------------------- ELIMINAR POST --------------------
    const PATH_ELIMINAR = "eliminar.php";  // Ruta al script para eliminar posts      

    //Funcion de eliminar post que se expondrá al html
    window.eliminarPost = function(id) { //id del post

        //Ventana emergente para confirmación
        if (!confirm("¿Seguro que quieres eliminar esta publicación?")) return;

        //Inicio el ajax para eliminar con el archivo eliminar.php
        fetch(PATH_ELIMINAR + "?id=" + encodeURIComponent(id), {//envía el id del post a eliminar
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(async r => {//espera la respuesta del servidor
            const text = await r.text();
            console.log("Eliminar response:", r.status, text);

            //si la respuesta del php es OK es porque se elimino correctamente
            if (r.ok && text.trim() === "OK") {
                
                //Avisa al usuario y recarga la página para mostrar cómo quedaron las publicaciones
                alert("Publicación Eliminada Exitosamente.");
                setTimeout(() => {
                    location.reload();
                }, 100);
            
            }
            else {
                //Aviso de algún error
                alert("Error eliminando la publicación.");
            }
        })
        .catch(err => { //Atajar errores
            console.error("Fetch eliminar error:", err);
            alert("Error de red al eliminar.");
        });
    };



    // -------------------- CAMBIAR FOTO DE PERFIL --------------------
    const PATH_CAMBIAR_FOTO = "cambiar_foto.php";

    //Funcion de cambiar foto que se expondrá al html
    window.cambiarFoto = function(event) {
        const archivo = event.target.files[0];
        if (!archivo) return;

        // Validar mimetype
        if (!archivo.type.startsWith("image/")) {
            alert("Elige un archivo de imagen válido.");
            return;
        }

        // Vista previa inmediata
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById("fotoPerfil");
            if (img) img.src = e.target.result;
        };
        reader.readAsDataURL(archivo);

        // Enviar imagen al servidor
        const formData = new FormData();
        formData.append("foto", archivo);

        //Crear ajax para no tener que trabajar en otro archivo
        fetch(PATH_CAMBIAR_FOTO, {
            method: "POST",
            body: formData,
            credentials: "same-origin"
        })
        .then(async r => { //Esperara respuesta del servidor
            const text = await r.text();
            console.log("Cambiar foto response:", r.status, text);

            //Avisar al usuario de los resultados
            if (r.ok && text.trim() === "OK") {
                alert("Foto actualizada correctamente.");
            }
            else {
                alert("No se pudo actualizar la foto. Ver consola.");
            }
        })
        .catch(err => { //Atajar errores
            console.error("Fetch cambiar foto error:", err);
            alert("Error de red al intentar subir la foto.");
        });
    };

}());