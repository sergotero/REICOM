window.onload = () =>{
    document.getElementById('filtrador').addEventListener('keyup', filtrarTabla);
    document.getElementById('imprimir').addEventListener('click', imprimir);
    
    //Con este pedazo de código hacemos que se borren los mensajes de error y éxito a los 3 segundos de aparecer.
    const errores = document.getElementsByClassName('errores')[0] || undefined;
    const exitos = document.getElementsByClassName('exitos')[0] || undefined;

    if(errores || exitos){
        setTimeout(() => {
            let avisos = document.getElementsByClassName('avisos')[0];
            let div = avisos.children[0];
            avisos.removeChild(div);
        }, 5000);
    }

    deshabilitarBotones();

    //Este código deshabilita los botones del administrador si la base de datos no contiene alumnos, permitiendo sólo crear nuevos.
    let tablas = document.getElementsByTagName('thead');
    let cabecera = tablas[1];
    if(!cabecera){
        let thead = tablas[0];
        let botones = thead.getElementsByTagName('button');
        for (let boton of botones) {
            
            if(boton.name != 'crear' && boton.name != 'multiple'){
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
    let asistencias = document.getElementsByClassName('asistencia');
    
    //Por cada elemento de la colección hacemos lo siguiente
    for (let celda of asistencias) {
        //Seleccionamos el texto de las celdas
        let asiste = celda.innerHTML;
        //Comprobamos si el texto es distinto a ""
        if(asiste != ''){
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
                alert('Error al generar PDF: ' + obj.error);
            } catch(e) {
                // No es JSON, es PDF => abrirlo
                const url = URL.createObjectURL(blob);
                window.open(url, '_blank');
            }
        }
        reader.readAsText(blob);
    });
}