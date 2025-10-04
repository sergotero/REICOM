<?php
    require_once "./../../src/autoload.php";
    session_start();

    $errores = [];
    $exitos = [];
    $accion = $_SERVER['PHP_SELF'];
    //Creamos la fecha que se va a mostrar para la asistencia
    $hoy_formato = date('d-m-Y');
    $hoy = date('Y-m-d');

    //Generamos los objetos necesarios para realizar la conexión a la BBDD
    $bbdd = new MySQLBBDD();
    $gestor = new Gestor();
    
    //BOTON SALIR
    if(isset($_POST['salir'])){
        session_destroy();
        header('Location: ./../index.php');
        die();
    }

    if(!isset($_SESSION['usuario'])){
        session_destroy();
        header('Location: ./../index.php');
        die();
    }else {
        $usuario = $_SESSION['usuario'];
        if($usuario->getRol() == 'Profesor'){
            header('Location: ./../index.php');
            die();
        }
    }

    //BOTON LISTAR USUARIOS
    if(isset($_POST['listar_usuarios'])){
        header('Location: ./form_listado_usuarios.php');
        die();
    }

    //BOTON LISTAR ACTIVIDADES
    if(isset($_POST['listar_actividades'])){
        header('Location: ./form_listado_actividades.php');
        die();
    }

    //BOTON AÑADIR ALUMNO
    if(isset($_POST['crear'])){
        header('Location: ./form_registro_alumnos.php');
        die();
    }

    //BOTON AÑADIR MULTIPLE
    if(isset($_POST['multiple'])){
        header('Location: ./form_subida_multiple.php');
        die();
    }

    //BOTON ESTADISTICAS
    if(isset($_POST['estadisticas'])){
        header('Location: ./form_estadistica_alumnos.php');
        die();
    }
    
    //BOTON ASISTENCIA 
    if(isset($_POST['asistencia'])){
        //Recuperamos los datos
        $id_alumno = $_POST['asistencia'];
        $f_asistencia = $hoy;

        try {
            //Recuperamos al alumno en cuestión
            $resultado = $gestor->recuperaAlumno($bbdd, $id_alumno);
            
            if($resultado){
                //Hacemos un casting
                $alumno = Alumno::cast($resultado);
            }
            
            //Generamos una nueva asistencia
            $asistencia = new Asistencia($f_asistencia);
            //La metemos en el alumno
            $alumno->setAsistencia($asistencia);
            //Insertamos la asistencia en la base
            $gestor->insertaAsistencia($bbdd, $alumno);

        } catch (InsertaAsistenciaException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }
        
        $exitos[] = [
            'codigo' => CodigosExito::ASISTENCIA_INSERTAR,
            'mensaje' => 'Se ha guardado la asistencia con éxito'
        ];
    }

    //BOTÓN ASISTENCIA MÚLTIPLE
    if(isset($_POST['asisten_todos'])){
        
        $hoy = date('Y-m-d');

        try {
            $resultado = $gestor->recuperaAlumnos($bbdd);
            
            if($resultado){
                $alumnado = Alumno::multicast($resultado);
            }
            
            foreach ($alumnado as $alumno) {
                //Generamos una nueva asistencia
                $asistencia = new Asistencia($hoy);

                //Comprobamos si ya existe una falta u asistencia para el día en curso
                if(($gestor->recuperaAsistenciasHoy($bbdd, $alumno)) || ($gestor->recuperaFaltasHoy($bbdd, $alumno))){
                    continue;
                } else{

                    //La metemos en el alumno
                    $alumno->setAsistencia($asistencia);
                    //Insertamos la asistencia en la base
                    $gestor->insertaAsistencia($bbdd, $alumno);
                }

            }

        } catch (ConsultaAlumnosException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        } catch (InsertaAsistenciaException $e){
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }
    }

    //BOTON FALTA 
    if(isset($_POST['falta'])){
        //Recuperamos los datos
        $id_alumno = $_POST['falta'];

        try {
            //Recuperamos al alumno en cuestión
            $resultado = $gestor->recuperaAlumno($bbdd, $id_alumno);
            
            if($resultado){
                //Hacemos un casting
                $alumno = Alumno::cast($resultado);
            }
            
            //Generamos una nueva asistencia
            $falta = new Falta($hoy, null, null);
            //La metemos en el alumno
            $alumno->setFalta($falta);
            //Insertamos la asistencia en la base
            $gestor->insertaFalta($bbdd, $alumno);

        } catch (InsertaFaltaException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }
        
        $exitos[] = [
            'codigo' => CodigosExito::FALTA_INSERTAR,
            'mensaje' => 'Se ha guardado la falta con éxito'
        ];
    }

    //BOTON BUSCADOR
    if(isset($_POST['buscador'])){
        if(isset($_SESSION['alumnos'])){
            unset($_SESSION['alumnos']);
            unset($_SESSION['curso']);
            unset($_SESSION['grupo']);
        }
        header('Location: ./form_buscador_alumnos.php');
        die();
    }

    //BOTON GANTT
    if(isset($_POST['gantt'])){
        header('Location: ./form_gantt.php');
        die();
    }

    //BOTON RESTABLECER
    if(isset($_POST['restablecer'])){
        
        $id_alumno = $_POST['restablecer'];
        
        //Se recupera el alumno y se hace un casting
        $alumno = $gestor->recuperaAlumno($bbdd, $id_alumno);
        $alumno = Alumno::cast($alumno);

        //Recuperamos las faltas
        $faltas = $gestor->recuperaFaltasHoy($bbdd, $alumno);
        $asistencias = $gestor->recuperaAsistenciasHoy($bbdd, $alumno);
        
        //Si hay faltas, hacemos un casting, y las adjudicamos a Alumno para proceder a borrarlas 
        if($faltas){
            $faltas = Falta::multicast($faltas);
            $alumno->setFaltas($faltas);
            try {
                $resultado = $gestor->eliminaFaltas($bbdd, $alumno);
                if($resultado){
                    $exitos[] = [
                        'codigo' => CodigosExito::FALTA_ELIMINAR,
                        'mensaje' => 'La falta se ha eliminado con éxito'
                    ];
                }
            } catch (EliminaFaltaException $e) {
                $errores[] = [
                    'codigo'   => $e->getCode(),
                    'mensaje'  => $e->getMessage(),
                    'archivo'  => $e->getFile(),
                    'linea'    => $e->getLine(),
                ];
            }
        }
        //Si hay asistencias, hacemos un casting, y las adjudicamos a Alumno para proceder a borrarlas
        if($asistencias){
            $asistencias = Asistencia::multicast($asistencias);
            $alumno->setAsistencias($asistencias);
            try {
                $resultado = $gestor->eliminaAsistencias($bbdd, $alumno);
                if($resultado){
                    $exitos[] = [
                        'codigo' => CodigosExito::ASISTENCIA_ELIMINAR,
                        'mensaje' => 'La asistencia se ha eliminado con éxito'
                    ];
                }
            } catch (EliminaAsistenciaException $e) {
                $errores[] = [
                    'codigo'   => $e->getCode(),
                    'mensaje'  => $e->getMessage(),
                    'archivo'  => $e->getFile(),
                    'linea'    => $e->getLine(),
                ];
            }
        }
    }

    //BOTON ALUMNOS (BORRAR)
    if(isset($_POST['listar_alumnos'])){
        header('Location: ./form_eliminar_alumnos.php');
        die();
    }

    //Consulta para generar la tabla
    try {
        $resultado = $gestor->recuperaAlumnos($bbdd);
        if($resultado){
            $alumnado = Alumno::multicast($resultado);
        }
        //Modificar esta excepción
    } catch (ConsultaAlumnosException $e) {
        $errores[] = [
            'codigo'   => $e->getCode(),
            'mensaje'  => $e->getMessage(),
            'archivo'  => $e->getFile(),
            'linea'    => $e->getLine(),
        ];
    }

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Sergio Otero">
    <link rel="shortcut icon" href="./../images/favicon_blanco.ico" type="image/x-icon">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="./../styles/general.css">
    <link rel="stylesheet" href="./../styles/avisos.css">
    <link rel="stylesheet" href="./../styles/botones.css">
    <link rel="stylesheet" href="./../styles/forms.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/92c8ed6d3a.js" crossorigin="anonymous"></script>
    <title>Listado de alumnos</title>
</head>
<body>
    <header>
        <div class="cabecera">
            <div class="logo">
                <p><a href="./form_listado_alumnos.php">REICOM</a></p>
            </div>
            <div class="gestion_usuarios">
                <form name="gestion_usuarios" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <button type="submit" name="listar_usuarios" title="Accede a la página desde la que gestionar los usuarios de la plataforma">Usuarios</button>
                    <button type='submit' name='listar_alumnos' id='listar_alumnos' title='Accede a la página desde la que se pueden eliminar los alumnos uno a uno y/o restablecer la base de datos poniendo los identificadores a cero'>Alumnos</button>
                    <button type="submit" name="listar_actividades" title="Accede a la página desde la que gestionar las actividades">Actividades</button>
                </form>
            </div>
            <!-- En este apartado se mostrará la información de sesión -->
            <?php
                //Recuperamos los datos del usuario
                if(isset($_SESSION['usuario'])){
                    $usuario = $_SESSION['usuario'];
                    echo <<<MARCA
                    <div class='inputgroup_perfil'>
                        <div class='perfil'>{$usuario->getEmail()}</div>
                        <form name='form_salir' action='{$accion}' method='POST'>
                            <button class='salir' type='submit' name='salir' id='salir'>Salir</button>
                        </form>
                    </div>
                    MARCA;
                }
            ?>
        </div>
        <ul class="migaspan">
            <li class="actual">Listado alumnos</li>
        </ul>
    </header>
    <main>
        <div class="avisos">
            <?php
                if(count($errores) > 0){
                    echo <<<MARCA
                    <div class="errores">
                    MARCA;
                        if($errores){
                            foreach ($errores as $error) {
                                echo $error['mensaje'] . PHP_EOL;
                            }
                        }
                    
                    echo "</div>";
                }
                if(count($exitos) > 0){
                    echo <<<MARCA
                    <div class="exitos">
                    MARCA;
                        if($exitos){
                            foreach ($exitos as $exito) {
                                echo $exito['mensaje'] . PHP_EOL;
                            }
                        }
                    echo "</div>";
                }
            ?>
        </div>
        <div class="filtrador">
            <div class="inputgroupfiltro">
                <!-- En el select, el value no empieza en 0 porque hay una columna oculta en la tabla que también se recorre mediante el JavaScript -->
                <select name="filtros" id="filtros">
                    <option value="1">Apellidos y nombre</option>
                    <option value="2">Curso</option>
                    <option value="3">Grupo</option>
                </select>
                <input type="text" name="filtrador" id="filtrador" placeholder="Filtrar...">
                <form action="<?php echo $accion; ?>" method="post">
                    <button type="submit" name="buscador" id="buscador" title="Permite ver las faltas y asistencias de los alumnos por curso y grupo">Buscador</button>
                </form>
            </div>
        </div>
        <div class="contenedor_listado">
            <?php
                
                echo <<<MARCA
                <table id='listado_alumnos'>
                    <thead>
                        <th colspan='8' class='celda_vacia'>
                            <!-- Botones sólo disponibles para la vista de administrador -->
                            <div class='botonera_admin'>
                                <form name='opciones' action='{$accion}' method='POST'>
                                    <button type='submit' name='crear' id='crear' title='Permite añadir alumnos uno a uno'>Añadir alumno</button>
                                    <button type='submit' name='multiple' id='multiple' title='Permite añadir varios alumnos a partir de un archivo CSV'>Añadir múltiple</button>
                                    <button type='submit' name='asisten_todos' id='asisten_todos' title='Marca a todos los alumnos como asistentes al comedor en el día en curso'>Asisten todos</button>
                                    <button type='submit' name='gantt' id='gantt' title='Imprime las estadísticas mensuales en formato diagrama de Gantt'>Faltas mes</button>
                                    <button type='button' name='imprimir' id='imprimir' title='Permite imprimir las faltas del día de los alumnos'>Faltas comedor</button>
                                </form>
                            </div>
                        </th>
                    </thead>
                MARCA;

                if(isset($alumnado)){
                    echo <<< MARCA
                        <thead>
                            <th hidden>Identificador</th>
                            <th>Apellidos y nombre</th>
                            <th>Curso</th>
                            <th>Grupo</th>
                            <th>Comedor</th>
                            <th>Acciones</th>

                        </thead>
                        <tbody>
                    MARCA;
                
                    foreach ($alumnado as $alumno) {
                        //Recuperamos las faltas y las asistencias para cada alumno
                        $faltas = $gestor->recuperaFaltas($bbdd, $alumno);
                        $asistencias = $gestor->recuperaAsistencias($bbdd, $alumno);
                        //Las convertimos a objetos Falta y a Asistencia
                        $faltas = Falta::multicast($faltas);
                        $asistencias = Asistencia::multicast($asistencias);
                        //Las configuramos en Alumno si el array no está vacío
                        if(count($faltas)>0){
                            $alumno->setFaltas($faltas);
                        }
                        if(count($asistencias)>0){
                            $alumno->setAsistencias($asistencias);
                        }

                        //Comprobamos si asiste o no
                        $asistencia = '';

                        //Comprobamos que el array sea distinto a null y que la fecha de la falta/asistencia coincida con el día en curso.
                        if(($alumno->getFaltas() != null) && ($alumno->getFalta($hoy) != null) && ($alumno->getFalta($hoy)->getFecha() == $hoy)){

                            $asistencia .= "<i class='fa-solid fa-xmark'></i>";
                            
                        }else if(($alumno->getAsistencias() != null) && ($alumno->getAsistencia($hoy) != null) && ($alumno->getAsistencia($hoy)->getFecha() == $hoy)){

                            $asistencia .= "<i class='fa-solid fa-check'></i>";
                            
                        }
                        
                        echo <<<MARCA
                            <tr>
                                <td hidden>{$alumno->getid()}</td>
                                <td>{$alumno->getApellido1()} {$alumno->getApellido2()}, {$alumno->getNombre()}</td>
                                <td>{$alumno->getCurso()}</td>
                                <td>{$alumno->getGrupo()}</td>
                                <td class='asistencia' title='Asiste el día {$hoy_formato}'>{$asistencia}</td>
                                <td class='acciones'>
                                    <form action='{$accion}' method='POST'>
                                        <button type='submit' name='asistencia' value='{$alumno->getid()}' title='Marca la asistencia del alumno al comedor en el día en curso'>Asiste</button>
                                    </form>
                                    <form action='{$accion}' method='POST'>
                                        <button type='submit' name='falta' value='{$alumno->getid()}' title='Marca la falta del alumno al comedor en el día en curso'>Falta</button>
                                    </form>
                                    <form action='{$accion}' method='POST'>
                                        <button type='submit' name='restablecer' value='{$alumno->getid()}' title='Elimina la asistencia/falta del alumno del día en curso'>Borrar A/F</button>
                                    </form>
                                    <form action='form_estadistica_alumnos.php' method='POST'>
                                    <button type='submit' name='estadisticas' value='{$alumno->getid()}' title='Muestra las asistencias y faltas del alumno'>Estadísticas</button>
                                    </form>
                                    <form action='form_modificar_alumnos.php?origen=listado' method='POST'>
                                        <button type='submit' name='modificar' value='{$alumno->getid()}' title='Modifica los datos del alumno'>Modificar datos</button>
                                    </form>
                                </td>
                            </tr>
                        MARCA;
                    }

                    echo <<<MARCA
                        </tbody>
                    </table>
                    MARCA;
                }
            ?>
        </div>
    </main>
</body>
<script src="./../js/form_listado_alumnos.js"></script>
</html>