<?php
    require_once "./../../src/autoload.php";
    session_start();
    
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

    if(isset($_POST['salir'])){
        session_destroy();
        header('Location: ./../index.php');
        die();
    }

    $errores = [];
    $exitos = [];
    $accion = $_SERVER['PHP_SELF'];
    
    //Creamos la conexión a la base
    $bbdd = new MySQLBBDD();
    $gestor = new Gestor($bbdd);

    if(isset($_POST['guardar'])){
        
        if(isset($_POST['nombre'])){
        
            //Capturamos los valores del formulario
            $id_actividad = intval($_POST['id']);
            $nombre = ucfirst(trim($_POST['nombre']));
            $h_inicio = $_POST['h_inicio'];
            $h_fin = $_POST['h_fin'];
            $dias = $_POST['dias'];
            if($dias != null){
                $dias = implode(", ", $dias);
            }
            $ubicacion = $_POST['ubicacion'];

            try {
                $actividad = new Actividad($nombre);
                $actividad->setId($id_actividad);
                $actividad->setHoraInicio($h_inicio);
                $actividad->setHoraFin($h_fin);
                $actividad->setDias($dias);
                $actividad->setUbicacion($ubicacion);

                $resultado = $gestor->actualizaActividad($bbdd, $actividad);

                if($resultado){
                    $exitos[] = [
                        'codigo' => CodigosExito::ACTIVIDAD_ACTUALIZAR,
                        'mensaje' => 'Se ha modificado la actividad con éxito'
                    ];
                }

            } catch (ActualizaActividadException $e) {
                $errores[] = [
                    'codigo'   => $e->getCode(),
                    'mensaje'  => $e->getMessage(),
                    'archivo'  => $e->getFile(),
                    'linea'    => $e->getLine(),
                ];
            }
        }
    }

    if(isset($_SESSION['id_actividad'])){
        //Capturamos el id del alumno del listado
        $id_actividad = intval($_SESSION['id_actividad']);

        //Recuperamos al usuario en cuestión
        $actividad = $gestor->recuperaActividad($bbdd, $id_actividad);
        if($actividad){
            $actividad = Actividad::cast($actividad);

        }
        unset($_SESSION['id_actividad']);
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
    <title>Modificar actividades</title>
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
            <li><a href="./form_listado_actividades.php">Listado actividades</a></li>
            <li class="actual">Modificar actividad</li>
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
        <form name="mod_actividades" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            
        <!-- ACTIVIDAD -->
            <fieldset class="actividad">
                <h3>MODIFICAR ACTIVIDAD</h3>
                <div class="inputgroup">
                    <input type="hidden" name="id" id="id" value="<?php echo $actividad->getId(); ?>">
                </div>
                <div class="inputgroup">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="<?php echo $actividad->getActividad(); ?>" required>
                </div>
                <div class="inputgroup">
                    <label for="h_inicio">Hora inicio</label>
                    <input type="time" name="h_inicio" id="h_inicio" value="<?php echo $actividad->getHoraInicio(); ?>">
                </div>
                <div class="inputgroup">
                    <label for="h_fin">Hora fin</label>
                    <input type="time" name="h_fin" id="h_fin" value="<?php echo $actividad->getHoraFin(); ?>">
                </div>
                <div class="inputgroup">
                    <label for="nombre">Ubicación</label>
                    <input type="text" name="ubicacion" id="ubicacion" value="<?php echo $actividad->getUbicacion(); ?>">
                </div>
                <br>
                <br>
                <h3>DÍAS</h3>
                <div class="inputgroup">
                    <?php
                        $dias_semana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                        $dias = explode(", ", $actividad->getDias());
                        foreach ($dias_semana as $dia) {
                            if(in_array($dia, $dias)){
                                echo <<<MARCA
                                <div class="inputgroupcheck">
                                    <label for="{$dia}">{$dia}</label>
                                    <input type="checkbox" name="dias[]" value="{$dia}" checked>
                                </div>
                                MARCA;
                            } else{
                                echo <<<MARCA
                                <div class="inputgroupcheck">
                                    <label for="{$dia}">{$dia}</label>
                                    <input type="checkbox" name="dias[]" value="{$dia}">
                                </div>
                                MARCA;
                            }
                        }
                    ?>
                </div>
            </fieldset>
            
            <div class="botonera">
                <button type="submit" name="guardar" id="guardar">Guardar</button>
                <button type="button" name="volver" id="volver">Volver</button>
            </div>
        </form>
    </main>
</body>
<script src="./../assets/js/form_modificar_actividades.js"></script>
</html>