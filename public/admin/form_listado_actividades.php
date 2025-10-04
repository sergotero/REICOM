<?php
    require_once "./../../src/autoload.php";
    session_start();

    $errores = [];
    $exitos = [];
    $accion = $_SERVER['PHP_SELF'];

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

    //BOTON CREAR USUARIO
    if(isset($_POST['crear_actividad'])){
        header('Location: ./form_registro_actividades.php');
        die();
    }

    //BOTON MODIFICAR
    if(isset($_POST['modificar'])){
        $_SESSION['id_actividad'] = $_POST['modificar'];
        header('Location: ./form_modificar_actividades.php');
        die();
    }

    //BOTON ELIMINAR USUARIO 
    if(isset($_POST['eliminar'])){
        //Recuperamos los datos
        $id_actividad = $_POST['eliminar'];

        try {
            //Recuperamos al usuario en cuestión
            $actividad = $gestor->recuperaActividad($bbdd, $id_actividad);
            if($actividad){
                $actividad = Actividad::cast($actividad);
            }
            //Eliminamos al alumno de la base
            $gestor->eliminaActividad($bbdd, $actividad);

        } catch (EliminaActividadException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }
        
        $exitos[] = [
            'codigo' => CodigosExito::ACTIVIDAD_ELIMINAR,
            'mensaje' => 'Se ha eliminado la actividad con éxito'
        ];
    }

    //Consulta para generar la tabla
    try {
        $actividades = $gestor->recuperaActividades($bbdd);
        if($actividades){
            $actividades = Actividad::multicast($actividades);
        }
        //Modificar esta excepción
    } catch (ConsultaActividadException $e) {
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
    <title>Listado de actividades</title>
</head>
<body>
    <header>
        <div class="cabecera">
            <div class="logo">
                <p><a href="./form_listado_alumnos.php">REICOM</a></p>
            </div>            <div class="gestion_usuarios">
                <form name="gestion_usuarios" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <button type="submit" name="crear_actividad">Crear actividad</button>
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
            <li><a href="./form_listado_alumnos.php">Listado alumnos</a></li>
            <li class="actual">Listado actividades</li>
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
                    <option value="1">Actividad</option>
                    <option value="2">Horario</option>
                    <option value="3">Días</option>
                    <option value="4">Ubicación</option>
                </select>
                <input type="text" name="filtrador" id="filtrador" placeholder="Filtrar...">
            </div>
        </div>
        <div class="contenedor_listado">
            <?php
                if(isset($actividades)){
                    echo <<<MARCA
                    <table id='listado'>
                        <thead>
                            <th colspan='8' class='celda_vacia'>
                                <!-- Botones sólo disponibles para la vista de administrador -->
                                <div class="celda_aviso eliminar">
                                    <div class="inputgroup">
                                        <h4>Recuerde que al eliminar una actividad, todos los alumnos que estén vinculados a ella se verán afectados.</h4>
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
                            <th>Actividad</th>
                            <th>Horario</th>
                            <th>Días</th>
                            <th>Ubicación</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                    MARCA;

                    foreach ($actividades as $actividad) {
                        $ubicacion = explode(",", $actividad->getUbicacion());
                        $ubicacion = implode("<br>", $ubicacion);
                        $dias = explode(",", $actividad->getDias());
                        $dias = implode("<br>", $dias);
                        echo <<<MARCA
                            <tr>
                                <td hidden>{$actividad->getId()}</td>
                                <td>{$actividad->getActividad()}</td>
                                <td>{$actividad->getHoraInicio()} - {$actividad->getHoraFin()}</td>
                                <td>{$dias}</td>
                                <td>{$ubicacion}</td>
                                <td class="acciones">
                                    <form name='acciones_usuario' action='{$accion}' method='POST'>
                                        <button type='submit' name='modificar' value='{$actividad->getId()}' title='Permite modificar los datos de la actividad'>Modificar</button>    
                                        <button type='submit' name='eliminar' value='{$actividad->getId()}' title='Elimina la actividad de la base de datos'>Eliminar</button>
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
<script src="./../js/form_eliminar_alumnos.js"></script>
</html>