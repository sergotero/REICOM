window.onload = () =>{
    document.getElementById('volver').addEventListener('click', volver);
    //Con este pedazo de código hacemos que se borren los mensajes de error y éxito a los 3 segundos de aparecer.
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

function volver() {
    window.location = './form_listado_usuarios.php';
}