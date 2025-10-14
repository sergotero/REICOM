window.onload = () =>{
    document.getElementById('archivo_csv').addEventListener('change', asignaNombre);
    document.getElementById('volver').addEventListener('click', retornar);

    
    const errores = document.getElementsByClassName('errores')[0] || undefined;
    const exitos = document.getElementsByClassName('exitos')[0] || undefined;
    const error_alumnos = document.getElementsByClassName('error_alumnos')[0] || undefined;

    if(errores || exitos || error_alumnos){
        setTimeout(() => {
            let avisos = document.getElementsByClassName('avisos')[0];
            let div = avisos.children[0];
            avisos.removeChild(div);
        }, 10000);
    }
}

function asignaNombre(){
    let archivo = document.getElementById('archivo_csv');
    let nombre = archivo.files[0].name;
    let span = document.getElementById('nombre_archivo');
    
    span.innerHTML = nombre;
}

function retornar(){
    window.location = "./form_listado_alumnos.php";
}