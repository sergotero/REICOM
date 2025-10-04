window.onload = () =>{
    document.getElementById('volver').addEventListener('click', retornar);
    document.getElementById('buscar').addEventListener('click', imprimir);

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

function retornar() {
    window.location = './form_listado_alumnos.php';
}

function imprimir(){
    let curso = document.getElementById('curso').value;
    let mes = document.getElementById('mes').value;
    let ano = document.getElementById('ano').value;
    
    datos = {curso, mes, ano};
    datos.timestamp = Date.now();
    console.log(datos);
    
    fetch('./form_imprimir_gantt.php',{
        method: 'POST',
        headers:{
            'Content-type': 'application/json'
        },
        body: JSON.stringify(datos)
    })
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