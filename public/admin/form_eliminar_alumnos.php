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

    //BOTON ELIMINAR ALUMNO 
    if(isset($_POST['eliminar'])){
        //Recuperamos los datos
        $id_alumno = $_POST['eliminar'];

        try {
            //Recuperamos al alumno en cuestión
            $resultado = $gestor->recuperaAlumno($bbdd, $id_alumno);
            if($resultado){
                $alumno = Alumno::cast($resultado);
            }
            //Eliminamos al alumno de la base
            $gestor->eliminaAlumno($bbdd, $alumno);

        } catch (EliminaAlumnoException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }
        
        $exitos[] = [
            'codigo' => CodigosExito::ALUMNO_ELIMINAR,
            'mensaje' => 'Se ha eliminado al alumno con éxito'
        ];
    }

    //RESTABLECER BASE
    if(isset($_POST['restablecer_base'])){
        $resultado = $gestor->reseteaBBDD($bbdd);
        if($resultado){
            $exitos[] = [
                'codigo' => CodigosExito::ALUMNO_RESTABLECER,
                'mensaje' => 'Se ha reestablecido la base de datos con éxito'
            ];
        }
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
    <link rel="shortcut icon" href="./../assets/images/favicon_blanco.ico" type="image/x-icon">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="./../assets/styles/general.css">
    <link rel="stylesheet" href="./../assets/styles/avisos.css">
    <link rel="stylesheet" href="./../assets/styles/botones.css">
    <link rel="stylesheet" href="./../assets/styles/forms.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/92c8ed6d3a.js" crossorigin="anonymous"></script>
    <title>Eliminador alumnos</title>
</head>
<body>
    <header>
        <div class="cabecera">
            <div class="logo">
                <p><a href="./form_listado_alumnos.php">REICOM</a></p>
            </div>
            <div class="gestion_usuarios">
                <!-- BOTON PARA REESTABLECER EL SISTEMA -->
                <!-- <form name='opciones' action='{$accion}' method='POST'>
                    <button type='submit' name='restablecer_base' id='restablecer_base' title='Elimina todos los alumnos (junto con faltas y asistencias) y actividades de la base y establece los identificadores a cero'>Restablecer BBDD</button>
                </form> -->
            </div>
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
            <li class="actual">Eliminar alumnos</li>
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
            </div>
        </div>
        <div class="contenedor_listado">
            <?php
                if(isset($alumnado)){
                    echo <<<MARCA
                    <table id='listado'>
                        <thead>
                            <th colspan='8'>
                                <!-- Botones sólo disponibles para la vista de administrador -->
                                <div class="celda_aviso eliminar">
                                    <div class="inputgroup">
                                        <h4>Las acciones llevadas acabo en esta página son <strong>IRREVERSIBLES</strong>.<br> Con la eliminación del alumno, se destruirán también los registros de faltas y asistencias.</h4>
                                    </div>
                                    <div class="contenedor_switch">
                                        <label class="switch">
                                        <input type="checkbox" id="activar">
                                        <span class="slider round"></span>
                                    </div>
                                    </label>
                                </div>
                            </th>
                        </thead>
                        <thead>
                            <th hidden>Identificador</th>
                            <th>Apellidos y nombre</th>
                            <th>Curso</th>
                            <th>Grupo</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                    MARCA;

                    foreach ($alumnado as $alumno) {
                        echo <<<MARCA
                            <tr>
                                <td hidden>{$alumno->getid()}</td>
                                <td>{$alumno->getApellido1()} {$alumno->getApellido2()}, {$alumno->getNombre()}</td>
                                <td>{$alumno->getCurso()}</td>
                                <td>{$alumno->getGrupo()}</td>
                                <td class='acciones'>
                                    <form action='{$accion}' method='POST'>
                                        <button type='button' name='eliminar' value='{$alumno->getid()}' title='Elimina los datos del alumno'>Eliminar</button>
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
        <div class="botonera">
            <button type="button" name="volver" id="volver">Volver</button>
        </div>
    </main>
</body>
<script src="./../assets/js/form_eliminar_alumnos.js"></script>
</html>