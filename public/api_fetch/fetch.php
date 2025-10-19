<?php

require_once "./../../src/autoload.php";

/*
El código JS hace un fetch (POST) con JSON en el cuerpo de la petición, pero no llegarán a PHP como $_POST['curso'], sino como un flujo JSON. Por ello hay que usar $inputJSON = file_get_contents('php://input');
 */

// Leer el contenido raw de la petición
$datosCodificados = file_get_contents('php://input');
$datos = json_decode($datosCodificados, true);

// Intentar ejecutar el método solicitado con los parámetros proporcionados
try {

    $metodo = $datos['metodo'];
    $id = intval($datos['param']);
    $email = ($datos['email'])??null;

    switch ($metodo){
        case 'crearAsistencia':
            $resultado = crearAsistencia($id);
            if($resultado){
                // Enviar de vuelta el resultado al frontend como JSON
                echo json_encode(['resultado' => true]);
            } else{
                echo json_encode(['resultado' => false]);
            }
            break;
        
        case 'crearFalta':
            $resultado = crearFalta($id);
            if($resultado){
                // Enviar de vuelta el resultado al frontend como JSON
                echo json_encode(['resultado' => true]);
            } else{
                echo json_encode(['resultado' => false]);
            }
            break;

        case 'borrarAsistenciaFalta':
            $mensaje = borrarAsistenciaFalta($id);
            echo json_encode($mensaje);
            break;

        case 'eliminarAlumno':
            $mensaje = eliminarAlumno($id);
            echo json_encode($mensaje);
            break;

        case 'borrarUsuario':
            $mensaje = borrarUsuario($email);
            echo json_encode($mensaje);
            break;

        case 'borrarActividad':
            $mensaje = borrarActividad($id);
            echo json_encode($mensaje);
            break;
    }

} catch (Throwable $e) {
    // Si algo falla durante la ejecución del método, capturamos el error y lo devolvemos
    http_response_code(500); // Error interno
    echo json_encode(['error' => $e->getMessage()]);
}


//Definimos las funciones que se van a usar
function crearAsistencia($id_alumno) {

    $bbdd = new MySQLBBDD();
    $gestor = new Gestor();
    $hoy = date('Y-m-d');

    //Recuperamos los datos
    $f_asistencia = $hoy;

    try {
        //Recuperamos al alumno en cuestión
        $alumno_recuperado = $gestor->recuperaAlumno($bbdd, $id_alumno);
        
        if($alumno_recuperado){
            //Hacemos un casting
            $alumno = Alumno::cast($alumno_recuperado);
        }
        
        //Generamos una nueva asistencia
        $asistencia = new Asistencia($f_asistencia);
        //La metemos en el alumno
        $alumno->setAsistencia($asistencia);
        //Insertamos la asistencia en la base
        $resultado = $gestor->insertaAsistencia($bbdd, $alumno);
        $mensaje = [
            'resultado' => $resultado,
            'codigo' => CodigosExito::ASISTENCIA_INSERTAR,
            'mensaje' => 'Se ha guardado la asistencia con éxito'
        ];

    } catch (InsertaAsistenciaException $e) {
        // Enviar JSON con error si se produce algún error
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getPrevious()->getMessage()]);
        exit;
    }

    return $mensaje;
}

function crearFalta($id_alumno) {
    
    $bbdd = new MySQLBBDD();
    $gestor = new Gestor();
    $hoy = date('Y-m-d');

    try {
        //Recuperamos al alumno en cuestión
        $faltaRecuperada = $gestor->recuperaAlumno($bbdd, $id_alumno);
        
        if($faltaRecuperada){
            //Hacemos un casting
            $alumno = Alumno::cast($faltaRecuperada);
        }
        
        //Generamos una nueva asistencia
        $falta = new Falta($hoy, null, null);
        //La metemos en el alumno
        $alumno->setFalta($falta);
        //Insertamos la asistencia en la base
        $resultado = $gestor->insertaFalta($bbdd, $alumno);
        $mensaje = [
            'resultado' => $resultado,
            'codigo' => CodigosExito::FALTA_INSERTAR,
            'mensaje' => 'Se ha guardado la falta con éxito'
        ];

    } catch (InsertaFaltaException $e) {
        // Enviar JSON con error si se produce algún error
        header('Content-Type: application/json');
        echo json_encode(['codigo' => CodigosError::FALTA_INSERTAR,'error' => $e->getPrevious()->getMessage()]);
        exit;
    }

    return $mensaje;
}

function borrarAsistenciaFalta($id_alumno) {

    $bbdd = new MySQLBBDD();
    $gestor = new Gestor();
    $mensaje = [];


    //Se recupera el alumno y se hace un casting
    $alumnoRecuperado = $gestor->recuperaAlumno($bbdd, $id_alumno);
    $alumno = Alumno::cast($alumnoRecuperado);

    //Recuperamos las faltas
    $faltasRecuperadas = $gestor->recuperaFaltasHoy($bbdd, $alumno);
    $asistenciasRecuperadas = $gestor->recuperaAsistenciasHoy($bbdd, $alumno);
    
    //Si hay faltas, hacemos un casting, y las adjudicamos a Alumno para proceder a borrarlas 
    if($faltasRecuperadas){
        $faltas = Falta::multicast($faltasRecuperadas);
        $alumno->setFaltas($faltas);
        try {
            $resultadoFalta = $gestor->eliminaFaltas($bbdd, $alumno);
            if($resultadoFalta){
                $mensaje[] = [
                    'codigo' => CodigosExito::FALTA_ELIMINAR,
                    'resultado' => $resultadoFalta,
                    'mensaje' => 'La falta se ha eliminado con éxito'
                ];
            }
        } catch (EliminaFaltaException $e) {
            // Enviar JSON con error si se produce algún error
            header('Content-Type: application/json');
            echo json_encode(['codigo' => CodigosError::FALTA_ELIMINAR,'error' => $e->getPrevious()->getMessage()]);
            exit;
        }
    }
    //Si hay asistencias, hacemos un casting, y las adjudicamos a Alumno para proceder a borrarlas
    if($asistenciasRecuperadas){
        $asistencias = Asistencia::multicast($asistenciasRecuperadas);
        $alumno->setAsistencias($asistencias);
        try {
            $resultadoAsistencia = $gestor->eliminaAsistencias($bbdd, $alumno);
            if($resultadoAsistencia){
                $mensaje[] = [
                    'codigo' => CodigosExito::ASISTENCIA_ELIMINAR,
                    'resultado' => $resultadoAsistencia,
                    'mensaje' => 'La asistencia se ha eliminado con éxito'
                ];
            }
        } catch (EliminaAsistenciaException $e) {
            // Enviar JSON con error si se produce algún error
            header('Content-Type: application/json');
            echo json_encode(['codigo' => CodigosError::ASISTENCIA_ELIMINAR,'error' => $e->getPrevious()->getMessage()]);
            exit;
        }
    }

    return $mensaje;
}

function eliminarAlumno($id_alumno){
    $bbdd = new MySQLBBDD();
    $gestor = new Gestor();
    $mensaje = [];

    try {
        //Recuperamos al alumno en cuestión
        $alumnoRecuperado = $gestor->recuperaAlumno($bbdd, $id_alumno);
        if($alumnoRecuperado){
            $alumno = Alumno::cast($alumnoRecuperado);
        }
        //Eliminamos al alumno de la base
        $resultado = $gestor->eliminaAlumno($bbdd, $alumno);
        $mensaje = [
            'codigo' => CodigosExito::ALUMNO_ELIMINAR,
            'resultado' => $resultado,
            'mensaje' => 'El alumno ha sido eliminado con éxito'
        ];

    } catch (EliminaAlumnoException $e) {
        header('Content-Type: application/json');
        echo json_encode(['codigo' => CodigosError::ALUMNO_ELIMINAR,'error' => $e->getPrevious()->getMessage()]);
        exit;
    }
    return $mensaje;
}

//El $id_usuario en esta función es el email del usuario.
function borrarUsuario($email){
    $bbdd = new MySQLBBDD();
    $gestor = new Gestor();
    $mensaje = [];

    try {
        //Recuperamos al usuario en cuestión
        $usuarioRecuperado = $gestor->recuperaUsuario($bbdd, $email);
        if($usuarioRecuperado){
            $usuario = Usuario::cast($usuarioRecuperado);
        }
        $resultado = $gestor->eliminaUsuario($bbdd, $usuario);
        $mensaje = [
            'codigo' => CodigosExito::USUARIO_ELIMINAR,
            'resultado' => $resultado,
            'mensaje' => 'El usuario ha sido eliminado con éxito'
        ];

    } catch (EliminaUsuarioException $e) {
        header('Content-Type: application/json');
        echo json_encode(['codigo' => CodigosError::USUARIO_ELIMINAR,'error' => $e->getPrevious()->getMessage()]);
        exit;
    }
    return $mensaje;
}

function borrarActividad($id_actividad){
    $bbdd = new MySQLBBDD();
    $gestor = new Gestor();
    $mensaje = [];

    try {
        //Recuperamos al usuario en cuestión
        $actividadRecuperada = $gestor->recuperaActividad($bbdd, $id_actividad);
        if($actividadRecuperada){
            $actividad = Actividad::cast($actividadRecuperada);
        }
        $resultado = $gestor->eliminaActividad($bbdd, $actividad);
        $mensaje = [
            'codigo' => CodigosExito::ACTIVIDAD_ELIMINAR,
            'resultado' => $resultado,
            'mensaje' => 'La actividad ha sido eliminada con éxito'
        ];

    } catch (EliminaActividadException $e) {
        header('Content-Type: application/json');
        echo json_encode(['codigo' => CodigosError::ACTIVIDAD_ELIMINAR,'error' => $e->getPrevious()->getMessage()]);
        exit;
    }
    return $mensaje;
}