//Función para agregar una keyword al formulario y a su vez cargarla en la BD con un archivo php
function agregarKeyword() {
    //Extraigo el texto de la palabra clave nueva ingresada
    const texto_palabra_clave = document.getElementById("newKeyword").value.trim();
    if (texto_palabra_clave === "") return;

    //Paso la palabra a minusculas y corrijo espacios
    const palabra = texto_palabra_clave.toLowerCase().replace(/\s+/g, "_");

    // -------------------- AJAX para pasar a BD sin tener que recargar --------------------
    //Creo una estructura para simular un formulario y enviarlo con post
    const formulario_simulado = new FormData();
    formulario_simulado.append("palabra", palabra); //Cargo la palabra en el formulario

    //Uso el concepto de ajax para enviar los datos del "formulario" con post al archivo de php
    fetch("insertar_keyword_BD.php", {
        method: "POST",
        body: formulario_simulado //Le paso el formulario con POST
    })
    .then(res => res.text()) //Espero la respuesta desde el php
    .then(id => { // Ahora actualizo el html para que se vea la nueva palabra clave ya seleccionada

        //Corroboro si NO es un numero, ya que si devuelve un texto es por el 
        //error de que no pudo cargar la palabra a la BD en el insertar_keyword_BD.php
        if (isNaN(id)) {
            alert("Error al cargar la palabra clave.");
            return;
        }

        // ------------- Creo el checkbox en la pagina -------------
        //Obtengo una instancia del div que a las keywords (keywordsBox)
        const keywordsBox = document.getElementById("keywordsBox");

        //Creo un item div de palabra clave como las que ya estan
        const item = document.createElement("div");
        item.classList.add("keyword-item");

        //Creo un elemento input como los que ya estan y como valor le pongo el id de la BD
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.name = "keywords[]";
        checkbox.value = id; //Id que tiene en la BD
        checkbox.checked = true; //La marco como checkeada para que ya quede marcada al crearse

        //Creo el span para el nombre
        const label = document.createElement("span");
        label.textContent = texto_palabra_clave;

        //Agrego el checkbox y el label adentro de la etiqueta div de item
        item.appendChild(checkbox);
        item.appendChild(label);

        //Agrego el item a la etiqueta div de keywordsBox
        keywordsBox.appendChild(item);

        //Vacio el formulario de la palabra clave
        document.getElementById("newKeyword").value = "";
    })
    .catch(err => { //Atajo el posible error de conexion con el servidor
        console.error(err);
        alert("Error de conexión al servidor.");
    });
}
