<?php
    require_once "./../../src/autoload.php";
    session_start();
    
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
            //Redirigimos la página al buscador normal
            header('Location: ./../index.php');
            die();
        }
    }

    $errores = [];
    $exitos = [];
    $alumnos = [];
    $accion = $_SERVER['PHP_SELF'];
    $hoy = date('Y-m-d');
    $hoy_formato = date('d-m-Y');

    //Generamos los objetos necesarios para realizar la conexión a la BBDD
    $bbdd = new MySQLBBDD();
    $gestor = new Gestor();

    if(isset($_POST['buscar'])){
        //Recuperamos los valores para hacer la búsqueda
        $curso = $_POST['curso'];
        $grupo = $_POST['grupo'];

        //Los nombres los usaremos para que se mantengan una vez realizada la búsqueda
        $_SESSION['curso'] = $_POST['curso'];
        $_SESSION['grupo'] = $_POST['grupo'];
        
        try {
            //Recuperamos a todos los alumnos
            $alumnos = $gestor->recuperaAlumnosCursoGrupo($bbdd, $curso, $grupo);
            if($alumnos){
                $alumnos = Alumno::multicast($alumnos);
                foreach ($alumnos as $alumno) {
                    //Recuperamos las faltas
                    $faltas = $gestor->recuperaFaltas($bbdd, $alumno);
                    if($faltas){
                        $faltas = Falta::multicast($faltas);
                        $alumno->setFaltas($faltas);
                    }
                    //Recuperamos las asistencias
                    $asistencias = $gestor->recuperaAsistencias($bbdd, $alumno);
                    if($asistencias){
                        $asistencias = Asistencia::multicast($asistencias);
                        $alumno->setAsistencias($asistencias);
                    }
                    $_SESSION['alumnos'][$alumno->getId()] = $alumno;
                }
            } else{
                $errores[] = [
                    'codigo'   => CodigosError::GENERICO_SIN_CODIGO,
                    'mensaje'  => 'No hay alumnos registrado en ese grupo y curso',
                ];
            }

        } catch (ConsultaAlumnosException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        } catch (ConsultaFaltasException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        } catch (ConsultaAsistenciasException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }
    }

    //BOTON ASISTENCIA 
    if(isset($_POST['asistencia'])){
        //Recuperamos los datos
        $id_alumno = $_POST['asistencia'];

        try {
            //Recuperamos al alumno en cuestión
            $resultado = $gestor->recuperaAlumno($bbdd, $id_alumno);
            
            if($resultado){
                //Hacemos un casting
                $alumno = Alumno::cast($resultado);
            }
            
            //Generamos una nueva asistencia
            $asistencia = new Asistencia($hoy);
            //La metemos en el alumno
            $alumno->setAsistencia($asistencia);
            //Insertamos la asistencia en la base
            $gestor->insertaAsistencia($bbdd, $alumno);

            //Renovamos los datos del alumno en el array de $_SESSION
            foreach($_SESSION['alumnos'] as $id => $al){
                if($id == $alumno->getId()){
                    $_SESSION['alumnos'][$id] = $alumno;
                }
            }

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

            //Renovamos los datos del alumno en el array de $_SESSION
            foreach($_SESSION['alumnos'] as $id => $al){
                if($id == $alumno->getId()){
                    $_SESSION['alumnos'][$id] = $alumno;
                }
            }

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
        
        //Renovamos los datos del alumno en el array de $_SESSION
        foreach($_SESSION['alumnos'] as $id => $al){
            if($id == $alumno->getId()){
                $_SESSION['alumnos'][$id] = $alumno;
            }
        }
        
    }

    if(isset($_POST['modificar'])){
        $id_alumno = $_POST['modificar'];
        $_SESSION['id_alumno'] = $id_alumno;
        header('Location: ./form_modificar_alumnos.php?origen=buscador');
        die();
    }

    if(isset($_SESSION['alumnos'])){
        $alumnos = [];
        foreach ($_SESSION['alumnos'] as $id => $alumno) {
            $alumnos[] = $alumno;
        }
        $curso = $_SESSION['curso'];
        $grupo = $_SESSION['grupo'];
        foreach ($alumnos as $alumno) {
            //Recuperamos las faltas
            $faltas = $gestor->recuperaFaltas($bbdd, $alumno);
            $faltas = Falta::multicast($faltas);
            $alumno->setFaltas($faltas);

            //Recuperamos las asistencias
            $asistencias = $gestor->recuperaAsistencias($bbdd, $alumno);
            $asistencias = Asistencia::multicast($asistencias);
            $alumno->setAsistencias($asistencias);
        }
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
    <title>Buscador</title>
</head>

<body>
    <header>
        <div class="cabecera">
            <div class="logo">
                <p><a href="./form_listado_alumnos.php">REICOM</a></p>
            </div>            <div class="gestion_usuarios">
                
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
            <li><a href="./form_listado_alumnos.php">Listado alumnos</a></li>
            <li class="actual">Buscador alumnos</li>
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
                            echo $error['mensaje'] . "<br>";
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
        <form action="<?php echo $accion; ?>" name="curso_grupo" method="post">
            <!-- En este apartado se selecciona el grupo y el curso y el periodo -->
            <fieldset>
                <h3>SELECCIONAR CURSO Y GRUPO</h3>
                <div class="inputgroup">
                    <label for="curso">Curso</label>
                    <select name="curso" id="curso">
                        <?php
                        $cursos = $gestor->recuperaCursos($bbdd);
                        echo "<option value=''>- -</option>";
                        foreach ($cursos as $cur) {
                            echo "<option value='$cur'>$cur</option>";
                        }
                        ?>
                    </select>

                </div>
                <div class="inputgroup">
                    <label for="grupo">Grupo</label>
                    <select name="grupo" id="grupo">
                        <?php
                        $grupos = $gestor->recuperaGrupos($bbdd);
                        echo "<option value=''>- -</option>";
                        foreach ($grupos as $gru) {
                            echo "<option value='$gru'>$gru</option>";
                        }
                        ?>
                    </select>
            </fieldset>

            <!-- En este apartado se muestra el listado -->
            <?php
                if($alumnos){

                    echo <<<MARCA
                    <fieldset class="curso_grupo">
                        <h3>ALUMNOS ----- {$curso} - {$grupo}</h3>
                        <br>
                        <table>
                            <thead>
                                <th hidden>Id</th>
                                <th>Apellidos y nombre</th>
                                <th>Comedor</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>
                    MARCA;
                    foreach ($alumnos as $alumno) {
                        $contenido = "";
                        //Comprobamos que el array sea distinto a null y que la fecha de la falta/asistencia coincida con el día en curso.
                        if(($alumno->getFaltas() != null) && ($alumno->getFalta($hoy) != null) && ($alumno->getFalta($hoy)->getFecha() == $hoy)){

                            $contenido .= "<i class='fa-solid fa-xmark'></i>";

                        }else if(($alumno->getAsistencias() != null) && ($alumno->getAsistencia($hoy) != null) && ($alumno->getAsistencia($hoy)->getFecha() == $hoy)){
                            
                            $contenido .= "<i class='fa-solid fa-check'></i>";
                        }
                        echo <<<MARCA
                            <tr>
                                <td hidden>{$alumno->getId()}</td>
                                <td>{$alumno->getApellido1()} {$alumno->getApellido2()}, {$alumno->getNombre()}</td>
                                <td class='asistencia' title='Asiste el día {$hoy_formato}'>$contenido</td>
                                <td class='acciones'>
                                    <button type='submit' name='asistencia' value='{$alumno->getid()}'>Asiste</button>
                                    <button type='submit' name='falta' value='{$alumno->getid()}'>Falta</button>
                                    <button type='submit' name='restablecer' value='{$alumno->getid()}' title='Elimina la asistencia/falta del alumno del día en curso'>Elimina A/F</button>
                                    <button type='submit' name='modificar' value='{$alumno->getid()}' title='Permite modificar los datos del alumno'>Modificar</button>
                                </td>
                            </tr>
                        MARCA;
                    }

                    echo <<<MARCA
                            </tbody>
                        </table>
                    </fieldset>
                    MARCA;
                }
            ?>

            <div class="botonera">
                <button type="submit" name="buscar" id="buscar">Buscar</button>
                <button type="button" name="volver" id="volver">Volver</button>
            </div>
        </form>
    </main>
</body>
<script src="./../js/form_buscador_alumnos.js"></script>
</html>