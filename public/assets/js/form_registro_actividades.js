window.onload = () =>{
    document.getElementById('volver').addEventListener('click', retornar);
    
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

}

function retornar() {
    window.location = "./form_listado_actividades.php";
}
