window.onload = () =>{
    document.getElementById("filtrador").addEventListener("keyup", filtrarTabla);
    document.getElementById("imprimir").addEventListener("click", imprimir);
    
    //Con este pedazo de código hacemos que se borren los mensajes de error y éxito a los 3 segundos de aparecer.
    const errores = document.getElementsByClassName("errores")[0] || undefined;
    const exitos = document.getElementsByClassName("exitos")[0] || undefined;

    if(errores || exitos){
        setTimeout(() => {
            let avisos = document.getElementsByClassName("avisos")[0];
            let div = avisos.children[0];
            avisos.removeChild(div);
        }, 5000);
    }

    //Este código genera pone a la escucha a los botones falta, asistencia y borrar
    const celdas = document.getElementsByClassName("acciones");

    for (let i = 0; i < celdas.length; i++) {
        
        const celda = celdas[i];
        
        const formAsiste = celda.children[0];
        const formFalta = celda.children[1];
        const formRestablece = celda.children[2];
        
        
        const asiste = formAsiste.children[0];
        asiste.addEventListener("click", (evento) => creaAsistencia(evento));
        const falta = formFalta.children[0];
        falta.addEventListener("click", (evento) => creaFalta(evento));
        const borra = formRestablece.children[0];
        borra.addEventListener(("click"), (evento) => restablece(evento));
    }

    //Este código deshabilita los botones si existen faltas o asistencias
    deshabilitarBotones();

    //Este código deshabilita los botones del administrador si la base de datos no contiene alumnos, permitiendo sólo crear nuevos.
    let tablas = document.getElementsByTagName("thead");
    let cabecera = tablas[1];
    if(!cabecera){
        let thead = tablas[0];
        let botones = thead.getElementsByTagName("button");
        for (let boton of botones) {
            
            if(boton.name != "crear" && boton.name != "multiple"){
                boton.setAttribute("disabled", true);
                boton.style.backgroundColor = "#D7D7D7";
                boton.style.color = "#363636";
                boton.style.cursor = "default";
            }
        }
        
    }

}

function deshabilitarBotones(){
    //Seleccionamos todas las celdas de la columna "Asiente". Nos devuelve un HTMLCollection
    let asistencias = document.getElementsByClassName("asistencia");
    
    //Por cada elemento de la colección hacemos lo siguiente
    for (let celda of asistencias) {
        //Seleccionamos el texto de las celdas
        let asiste = celda.innerHTML;
        //Comprobamos si el texto es distinto a ""
        if(asiste != ""){
            //Seleccionamos la siguiente celda (Acciones)
            let botonera = celda.nextElementSibling;
            //Seleccionamos el primer y el segundo formulario de dicha celda
            let form1 = botonera.children[0];
            let form2 = botonera.children[1];
            //Seleccionamos el botón de cada formulario
            let boton1 = form1.children[0];
            let boton2 = form2.children[0];
            
            //Lo deshabilitamos y le cambiamos las propiedades
            //Botón ASISTIR
            boton1.setAttribute("disabled", true);
            boton1.style.backgroundColor = "#D7D7D7";
            boton1.style.color = "#363636";
            boton1.style.cursor = "default";
            //BOTON FALTA
            boton2.setAttribute("disabled", true);
            boton2.style.backgroundColor = "#D7D7D7";
            boton2.style.color = "#363636";
            boton2.style.cursor = "default";
            
        }
    }
}

function filtrarTabla() {
    
    // Quita tildes y pasa a mayúsculas
    const normalizar = (texto) => {
        return texto
            .normalize("NFD")              // separa letra base y acento
            .replace(/[\u0300-\u036f]/g, "") // elimina acentos
            .toUpperCase();                 // mayúsculas para uniformar
    };
    
    // Capturamos el select
    const select = document.getElementById("filtros");
    const indiceColumna = parseInt(select.value);

    // Capturamos el input (ya normalizado)
    const input = document.getElementById("filtrador");
    let texto = normalizar(input.value);

    // Capturamos la tabla
    const tabla = document.getElementById("listado_alumnos");
    const filas = tabla.getElementsByTagName("tr");
    

    // Recorremos filas desde la 2 (evitamos cabecera)
    for (let i = 2; i < filas.length; i++) {
        const celdas = filas[i].getElementsByTagName("td");

        if (celdas && celdas[indiceColumna]) {
            // Normalizamos el valor de la celda
            const valor = normalizar(celdas[indiceColumna].textContent || celdas[indiceColumna].innerText);

            // Comprobamos coincidencia
            if (valor.indexOf(texto) > -1) {
                filas[i].style.display = "";
            } else {
                filas[i].style.display = "none";
            }
        }
    }
}

function imprimir() {
    
    fetch("./form_imprimir_diario.php")
    .then(response => {
        if(!response.ok){
            throw new Error(`Error HTTP: ${response.status}`);
        } else{
            return response.blob();
        }
    })
    .then( blob =>{
        // Verificar si es JSON (error) o PDF
        const reader = new FileReader();
        reader.onload = function() {
            try {
                const obj = JSON.parse(reader.result);
                // Si llega aquí, es JSON => error
                alert("Error al generar PDF: " + obj.error);
            } catch(e) {
                // No es JSON, es PDF => abrirlo
                const url = URL.createObjectURL(blob);
                window.open(url, "_blank");
            }
        }
        reader.readAsText(blob);
    });
}

function creaAsistencia(evento){
    //Obtenemos el id del alumno
    const id_alumno = evento.target.value;

    //Creamos un objeto que contiene el nombre del método que vamos a usar y los parámetros.
    const datos = {
        metodo: "crearAsistencia",
        param: id_alumno
    };

    fetch("./../api_fetch/fetch.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(datos)
        }
    )
    .then(response => {
        
        if(!response.ok){
            throw new Error(`Error HTTP ${response.status}`);
        }
        return response.json();

    })
    .then(data => {
        if(data["resultado"] === true){
            const contenido = "<i class='fa-solid fa-check'></i>";
            const botonAsistencia = evento.target;
            const row = botonAsistencia.closest("tr");
            const tdComedor = row.children[4];
            tdComedor.innerHTML = contenido;
            deshabilitarBotones();
        }

    })
    .catch(error => {
        console.error(error);
    })
    
}


function creaFalta(evento){
    //Obtenemos el id del alumno
    const id_alumno = evento.target.value;

    //Creamos un objeto que contiene el nombre del método que vamos a usar y los parámetros.
    const datos = {
        metodo: "crearFalta",
        param: id_alumno
    };

    fetch("./../api_fetch/fetch.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(datos)
        }
    )
    .then(response => {
        
        if(!response.ok){
            throw new Error(`Error HTTP ${response.status}`);
        }
        return response.json();

    })
    .then(data => {
        if(data["resultado"] === true){
            const contenido = "<i class='fa-solid fa-xmark'></i>";
            const botonFalta = evento.target;
            const row = botonFalta.closest("tr");
            const tdComedor = row.children[4];
            tdComedor.innerHTML = contenido;
            deshabilitarBotones();
        }

    })
    .catch(error => {
        console.error(error);
    })
    
}


function restablece(evento){
    //Obtenemos el id del alumno
    const id_alumno = evento.target.value;

    //Creamos un objeto que contiene el nombre del método que vamos a usar y los parámetros.
    const datos = {
        metodo: "borrarAsistenciaFalta",
        param: id_alumno
    };

    fetch("./../api_fetch/fetch.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(datos)
        }
    )
    .then(response => {
        
        if(!response.ok){
            throw new Error(`Error HTTP ${response.status}`);
        }
        return response.json();

    })
    .then(data => {
        if(data[0]["resultado"] === true){
            const botonFalta = evento.target;
            const row = botonFalta.closest("tr");
            const tdComedor = row.children[4];
            tdComedor.innerHTML = "";
            rehabilitarBotones(evento);
        }

    })
    .catch(error => {
        console.error(error);
    })
    
}

function rehabilitarBotones(evento){
    const borrar = evento.target;
    const row = borrar.closest("tr");
    const asiste = row.children[5].children[0].children[0];
    const falta = row.children[5].children[1].children[0];


    //Botón ASISTIR
    asiste.removeAttribute("disabled");
    asiste.style.backgroundColor = "#44bd8d";
    asiste.style.color = "#ffffffff";
    asiste.style.cursor = "pointer";
    //BOTON FALTA
    falta.removeAttribute("disabled");
    falta.style.backgroundColor = "#b1304a";
    falta.style.color = "#ffffffff";
    falta.style.cursor = "pointer";

}