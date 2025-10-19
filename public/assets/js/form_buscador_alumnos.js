window.onload = () =>{
    document.getElementById('volver').addEventListener('click', retornar);

    //BUSCADOR DE ADMINISTRADOR
    let faltas = document.getElementsByName("ver_faltas");
    faltas.forEach(falta => {
        falta.addEventListener('click', verFaltas);
    });

    let asistencias = document.getElementsByName("ver_asistencias");
    asistencias.forEach(asistencia => {
        asistencia.addEventListener('click', verAsistencias);
    });

    // Obtener parámetros de la URL
    const params = new URLSearchParams(window.location.search);
    const origen = params.get("origen");

    if (origen === "buscador") {
        //Obtenemos un HTMLCollection con los elementos que contengan la clase migaspan y seleccionamos el único elemento
        let ul = document.getElementsByClassName('migaspan')[0];
        let actual = ul.children[1];
        
        //Creamos un nuevo elemento li que contenga un elemento a
        let li = document.createElement("li");
        let a = document.createElement("a");
        a.textContent = "Buscador alumnos";
        a.setAttribute("href", './form_buscador_alumnos.php');
        //Vinculamos los elementos
        li.appendChild(a);
        ul.insertBefore(li, actual);
    }

    //Este código genera pone a la escucha a los botones falta, asistencia y borrar
    const celdas = document.getElementsByClassName("acciones");

    for (let i = 0; i < celdas.length; i++) {
        
        const celda = celdas[i];
        const asiste = celda.children[0];
        asiste.addEventListener("click", (evento) => creaAsistencia(evento));
        const falta = celda.children[1];
        falta.addEventListener("click", (evento) => creaFalta(evento));
        const borra = celda.children[2];
        borra.addEventListener(("click"), (evento) => restablece(evento));
    }


    //BUSCADOR DE PROFESOR
    // let botones_asistencia = document.getElementsByName('asistencia');
    // botones_asistencia.forEach(boton => {
    //     boton.addEventListener('click', deshabilitarBotones);
    // })
    // let botones_falta = document.getElementsByName('faltas');
    // botones_falta.forEach(boton => {
    //     boton.addEventListener('click', deshabilitarBotones);
    // })

    //Con este pedazo de código hacemos que se borren los mensajes de error y éxito a los 3 segundos de aparecer.
    const errores = document.getElementsByClassName('errores')[0] || undefined;
    const exitos = document.getElementsByClassName('exitos')[0] || undefined;
    
    
    if(errores || exitos){
        setTimeout(() => {
            let avisos = document.getElementsByClassName('avisos')[0];
            let div = avisos.children[0];
            avisos.removeChild(div);
        }, 3000);
    }

    deshabilitarBotones();
}

function verFaltas() {

    if(this.innerHTML == 'Ver faltas'){
        this.innerHTML = 'Ocultar faltas';
        this.style.backgroundColor = "#D7D7D7";
        this.style.color = "#363636";
    } else if(this.innerHTML == 'Ocultar faltas'){
        this.innerHTML = 'Ver faltas';
        this.style.backgroundColor = "#379d7f";
        this.style.color = "#FFFFFF";
    }
    
    let fila_alumno = this.closest("tr");
    let celdas_alumno = fila_alumno.children;
    let id_num = celdas_alumno[0].textContent;
    
    const id_faltas = "faltas_" + id_num;
    
    let tabla_faltas = document.getElementById(id_faltas);

    tabla_faltas.classList.toggle("faltas_buscador");
}

function verAsistencias(){

    if(this.innerHTML == 'Ver asistencias'){
        this.innerHTML = 'Ocultar asistencias';
        this.style.backgroundColor = "#D7D7D7";
        this.style.color = "#363636";
    } else if(this.innerHTML == 'Ocultar asistencias'){
        this.innerHTML = 'Ver asistencias';
        this.style.backgroundColor = "#379d7f";
        this.style.color = "#FFFFFF";
    }

    let fila_alumno = this.closest("tr");
    let celdas_alumno = fila_alumno.children;
    let id_num = celdas_alumno[0].textContent;
    
    const id_asistencias = "asistencias_" + id_num;
    
    let tabla_asistencias = document.getElementById(id_asistencias);

    tabla_asistencias.classList.toggle("asistencias_buscador");
}

function retornar() {
    
    // Obtener parámetros de la URL
    const params = new URLSearchParams(window.location.search);
    const origen = params.get("origen");

    // Configurar el botón volver
    if (origen === "buscador") {
    window.location.href = "./form_buscador_alumnos.php";
    } else {
    window.location.href = "./form_listado_alumnos.php";
    }

}

function deshabilitarBotones(){

    //Seleccionamos todas las celdas de la columna "Comedor". Nos devuelve un HTMLCollection
    let asistencias = document.getElementsByClassName('asistencia');
    
    //Por cada elemento de la colección hacemos lo siguiente
    for (let celda of asistencias) {
        //Seleccionamos el texto de las celdas
        let asiste = celda.innerHTML;
        //Comprobamos si el texto es distinto a ""
        if(asiste != ''){
            //Seleccionamos la siguiente celda (Acciones)
            let acciones = celda.nextElementSibling;
            
            //Seleccionamos el botón de la celda acciones
            let boton1 = acciones.children[0];
            let boton2 = acciones.children[1];
            
            //Lo deshabilitamos y le cambiamos las propiedades
            //Botón ASISTIR
            boton1.setAttribute("disabled", true);
            boton1.style.backgroundColor = "#dadada";
            boton1.style.color = "#343A40";
            boton1.style.cursor = "default";
            //BOTON FALTA
            boton2.setAttribute("disabled", true);
            boton2.style.backgroundColor = "#dadada";
            boton2.style.color = "#343A40";
            boton2.style.cursor = "default";
            
        }
    }
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
            const tdComedor = row.children[2];
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
            const tdComedor = row.children[2];
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
            const tdComedor = row.children[2];
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
    const asiste = row.children[3].children[0];
    console.log(asiste);
    
    const falta = row.children[3].children[1];
    console.log(falta);
    


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