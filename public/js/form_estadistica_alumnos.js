window.onload = () =>{
    document.getElementById('volver').addEventListener('click', retornar);

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
        deshabilitarCampos();
        deshabilitarBotones(errores, exitos);
    }

}


function retornar(){
    window.location = "./form_listado_alumnos.php";
}