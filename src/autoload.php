<?php

//Definimos una función que recibe como parámetro un string con el nombre de la clase o el Namespace
function autoload($nombreClase){
    
    //Convertir el nombre de la clase en una ruta de archivo. Con ltrim() nos aseguramos de eliminar la barra inicial si es que existe.

    $nombreClase = ltrim($nombreClase, '\\');

    //Definimos múltiples rutas en las que podemos encontrar las clases. La constante mágica __DIR__ en PHP devuelve la ruta absoluta del directorio donde se encuentra el archivo en ejecución.

    $rutasBase = [
        __DIR__ . '/models/',
        __DIR__ . '/exceptions/',
        __DIR__ . '/exceptions/actividades/',
        __DIR__ . '/exceptions/alumnos/',
        __DIR__ . '/exceptions/asistencias/',
        __DIR__ . '/exceptions/faltas/',
        __DIR__ . '/exceptions/tarifas/',
        __DIR__ . '/exceptions/usuarios/',
        __DIR__ . '/interfaces/',
    ];
    
    //Construimos el URI para poder acceder a la clase en cuestión.
    $rutaRelativa = str_replace('\\', DIRECTORY_SEPARATOR, $nombreClase) . '.php';
    
    //Si el archivo existe, entonces se carga. De lo contrario no hace nada (podríamos poner un Throw).
    foreach ($rutasBase as $rutaBase) {
        $archivo = realpath($rutaBase . $rutaRelativa);
        if (file_exists($archivo)) {
            require_once $archivo;
            return;
        }
    }
}

//Registra la función miAutoload para que PHP la use cada vez que intente cargar una clase no definida
spl_autoload_register("autoload");