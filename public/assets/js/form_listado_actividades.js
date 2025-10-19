window.onload = () =>{
    document.getElementById('filtrador').addEventListener('keyup', filtrarTabla);
    document.getElementById('volver').addEventListener('click', retornar);
    document.getElementById('activar').addEventListener('click', deshabilitarBotones);
    
    //Código que pone a la escucha los botones de eliminar
    const celdas = document.getElementsByClassName("acciones");
    for (let i = 0; i < celdas.length; i++) {
        const celda = celdas[i];
        const boton = celda.children[0].children[1];
        boton.addEventListener('click', (evento) => eliminaActividad(evento));
    }

    deshabilitarBotones();
    eliminaAvisos();
}

function retornar() {
    console.log(this);
    
    window.location = "./form_listado_alumnos.php";
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
    const tabla = document.getElementById("listado");
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

function deshabilitarBotones(){
    //Seleccionamos todas las celdas de la columna "Acciones". Nos devuelve un HTMLCollection
    let acciones = document.getElementsByClassName('acciones');
    
    //Obtenemos el valor (boolean) del checkbox
    let activar = document.getElementById('activar').checked;
    // let botonRestablecer = document.getElementById('restablecer_base');
    
    //Obtenemos el pathname
    let path = window.location.pathname;
    
    if(path.endsWith('form_listado_actividades.php')){

        //Por cada elemento de la colección hacemos lo siguiente
        for (const celda of acciones) {
            //Seleccionamos el texto de las celdas
            const botonEliminar = celda.children[0].children[1];
            
            if(activar){
                botonEliminar.removeAttribute("disabled");
                botonEliminar.style.backgroundColor = "#9d263e";
                botonEliminar.style.color = "#FFFFFF";
                botonEliminar.style.cursor = "pointer";                
            } else{
                botonEliminar.setAttribute("disabled", true);
                botonEliminar.style.backgroundColor = "#D7D7D7";
                botonEliminar.style.color = "#363636";
                botonEliminar.style.cursor = "default";
            }
        }

    } else if(path.endsWith('form_eliminar_alumnos.php')){

        //Por cada elemento de la colección hacemos lo siguiente
        for (const celda of acciones) {
            //Seleccionamos el texto de las celdas
            const botonEliminar = celda.children[0].children[0];
            
            if(activar){
                botonEliminar.removeAttribute("disabled");
                botonEliminar.style.backgroundColor = "#9d263e";
                botonEliminar.style.color = "#FFFFFF";
                botonEliminar.style.cursor = "pointer";
                // botonRestablecer.removeAttribute("disabled");
                // botonRestablecer.style.backgroundColor = "#9d263e";
                // botonRestablecer.style.color = "#FFFFFF";
                // botonRestablecer.style.cursor = "pointer";
            } else{
                botonEliminar.setAttribute("disabled", true);
                botonEliminar.style.backgroundColor = "#D7D7D7";
                botonEliminar.style.color = "#363636";
                botonEliminar.style.cursor = "default";
                // botonRestablecer.setAttribute("disabled", true);
                // botonRestablecer.style.backgroundColor = "#D7D7D7";
                // botonRestablecer.style.color = "#363636";
                // botonRestablecer.style.cursor = "default";
            }
        }
    }
    
}

function eliminaActividad(evento){
    
    const confirmacion = confirm("¿Está segudo de que quiere continuar?");

    if(confirmacion){

        //Obtenemos el id de la actividad
        const id_actividad = evento.target.value;

        //Creamos un objeto que contiene el nombre del método que vamos a usar y los parámetros.
        const datos = {
            metodo: "borrarActividad",
            param: id_actividad,
            email: null
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
            console.log(data);
            if(data["resultado"] === true){
                
                
                //Elimina la fila
                const boton = evento.target;
                const fila = boton.closest("tr");
                const tbody = fila.closest("tbody");
                tbody.removeChild(fila);
                
                //Genera el aviso
                const avisos = document.getElementsByClassName('avisos')[0];
                const div = document.createElement("div");
                div.classList.add("exitos");
                const contenido = data['mensaje'];
                div.innerHTML = contenido;
                avisos.appendChild(div);

                eliminaAvisos();
            }

        })
        .catch(error => {
            console.error(error);
            console.log(error);
        })
    }
}

function eliminaAvisos() {
    const errores = document.getElementsByClassName('errores')[0] || undefined;
    const exitos = document.getElementsByClassName('exitos')[0] || undefined;

    if(errores || exitos){
        setTimeout(() => {
            const avisos = document.getElementsByClassName('avisos')[0];
            const div = avisos.children[0];
            avisos.removeChild(div);
        }, 5000);
    }
}