window.onload = () =>{
    document.getElementById('volver').addEventListener('click', retornar);

    let errores = document.getElementsByClassName('errores')[0];
    let exitos = document.getElementsByClassName('exitos')[0];

    if(errores || exitos){
        setTimeout(() => {
            let avisos = document.getElementsByClassName('avisos')[0];
            let div = avisos.children[0];
            avisos.removeChild(div);
        }, 3000);
    }
}

function retornar(){
    window.location = "./form_listado_alumnos.php";
}