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
    let errores = document.getElementsByClassName('errores')[0];
    console.log(errores);
    
    let exitos = document.getElementsByClassName('exitos')[0];
    console.log(exitos);
    
    
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