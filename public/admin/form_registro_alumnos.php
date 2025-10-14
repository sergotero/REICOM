<?php
    require_once "./../../src/autoload.php";
    session_start();
    
    //BOTÓN SALIR PULSADO
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
    
    $accion = $_SERVER['PHP_SELF'];
    $errores = [];
    $exitos = [];
    
    //Generamos los objetos necesarios para realizar la conexión a la BBDD
    $bbdd = new MySQLBBDD();
    $gestor = new Gestor();

    //BOTÓN CREAR PULSADO
    if(isset($_POST['crear'])){

        //Todos los campos deben de haber sido completados
        if(isset($_POST['nombre_alumno']) && isset($_POST['apellido1_alumno']) && isset($_POST['apellido2_alumno']) && isset($_POST['f_nacimiento']) && isset($_POST['curso']) && isset($_POST['grupo']) && isset($_POST['tarifa'])){

            //Se recuperan los valores
            $nombre = trim($_POST['nombre_alumno']);
            $apellido1 = trim($_POST['apellido1_alumno']);
            $apellido2 = trim($_POST['apellido2_alumno']);
            $f_nacimiento = trim($_POST['f_nacimiento']);
            $curso = trim($_POST['curso']);
            $grupo = trim($_POST['grupo']);
            $alergias = ($_POST['alergias'])?trim($_POST['alergias']): null;
            $tarifa = floatval($_POST['tarifa']);
            $actividades = $_POST['actividades']??null;

            try {
                //Se crea un nuevo objeto alumno
                $alumno = new Alumno($nombre, $apellido1, $apellido2, $f_nacimiento, $curso, $grupo, $alergias, $tarifa);
                
                //Creamos un objeto que pueda realizar la conexión con la base de datos
                $bbdd = new MySQLBBDD();
    
                //Creamos un nuevo gestor de alumnos
                $gestor = new Gestor($bbdd);
    
                //Comprobamos que el alumno no existe en la base de datos
                if(!$gestor->compruebaAlumno($bbdd, $alumno)){
                    $resultado = $gestor->insertaAlumno($bbdd, $alumno);
                    if($resultado){
                        $exitos[] = [
                            'codigo' => CodigosExito::ALUMNO_INSERTAR,
                            'mensaje' => 'El alumno ha sido introducido en la base de datos con éxito.'
                        ];
                    }
                } else{
                    $errores[] = [
                        'codigo' => 1,
                        'mensaje' => 'El alumno ya existe en la base de datos.'
                    ];
                }
                //Recuperamos al alumno con el id que le ha adjudicado la base de datos.
                $id_nuevoAlumno = Alumno::ultimoId($bbdd);
                $nuevoAlumno = $gestor->recuperaAlumno($bbdd, $id_nuevoAlumno->id);
                $nuevoAlumno = Alumno::cast($nuevoAlumno);
                if($actividades != null){
                    foreach ($actividades as $actividad) {
                        $act = new Actividad($actividad);
                        $gestor->insertaActividadAlumno($bbdd, $nuevoAlumno, $act);
                    }
                }

            } catch (CreaAlumnoException $e) {
                $errores[] = [
                    'codigo'   => $e->getCode(),
                    'mensaje'  => $e->getMessage(),
                    'archivo'  => $e->getFile(),
                    'linea'    => $e->getLine(),
                ];
            }catch (ConsultaAlumnosException $e) {
                $errores[] = [
                    'codigo'   => $e->getCode(),
                    'mensaje'  => $e->getMessage(),
                    'archivo'  => $e->getFile(),
                    'linea'    => $e->getLine(),
                ];
            } catch (InsertaAlumnoException $e){
                $errores[] = [
                    'codigo'   => $e->getCode(),
                    'mensaje'  => $e->getMessage(),
                    'archivo'  => $e->getFile(),
                    'linea'    => $e->getLine(),
                ];
            } catch (CreaTarifasException $e){
                $errores[] = [
                    'codigo'   => $e->getCode(),
                    'mensaje'  => $e->getMessage(),
                    'archivo'  => $e->getFile(),
                    'linea'    => $e->getLine(),
                ];
            }
        } else{
            $errores[] = [
                'codigo' => CodigosError::GENERICO_SIN_CODIGO,
                'mensaje' => 'No se han introducido los datos correctamente'
            ];
        }
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
    <title>Añadir alumno</title>
</head>

<body>
    <header>
        <div class="cabecera">
            <div class="logo">
                <p><a href="./form_listado_alumnos.php">REICOM</a></p>
            </div>
            <div class="gestion_usuarios">
                
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
            <li class="actual">Registro alumnos</li>
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
        <form name="crea_alumno" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <!-- ALUMNO -->
            <fieldset class="alumno">
                <h3>DATOS ALUMNO</h3>
                <div class="inputgroup">
                    <label for="nombre_alumno">Nombre</label>
                    <input type="text" name="nombre_alumno" id="nombre_alumno" required>
                </div>
                <div class="inputgroup">
                    <label for="apellido1_alumno">1<sup>er</sup> Apellido</label>
                    <input type="text" name="apellido1_alumno" id="apellido1_alumno" required>
                </div>
                <div class="inputgroup">
                    <label for="apellido2_alumno">2<sup>do</sup> Apellido</label>
                    <input type="text" name="apellido2_alumno" id="apellido2_alumno" required>
                </div>
                <div class="inputgroup">
                    <label for="f_nacimiento">Fecha nacimiento</label>
                    <input type="date" name="f_nacimiento" id="f_nacimiento" min="2010-01-01" max="<?php echo date('Y-m-d')?>" title="El rango de fechas va desde 01/01/2010 hasta el día actual" required>
                </div>
                <div class="inputgroup">
                    <label for="curso">Curso</label>
                    <select name="curso" id="curso" required>
                        <?php
                            $cursos = $gestor->recuperaCursos($bbdd);
                            echo "<option value='null' hidden>- -</option>";
                            foreach ($cursos as $curso) {
                                echo "<option value='$curso'>$curso</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="inputgroup">
                    <label for="grupo">Grupo</label>
                    <select name="grupo" id="grupo" required>
                        <?php
                            $grupos = $gestor->recuperaGrupos($bbdd);
                            echo "<option value='null' hidden>- -</option>";
                            foreach ($grupos as $grupo) {
                                echo "<option value='$grupo'>$grupo</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="inputgroup">
                    <label for="tarifa">Tarifa</label>
                    <select name="tarifa" id="tarifa" required>
                        <?php
                            $tarifas = $gestor->recuperaTarifas($bbdd);
                            $tarifas = Tarifa::multicast($tarifas);
                            echo "<option value='null' hidden>- -</option>";
                            foreach ($tarifas as $tarifa) {
                                echo "<option value='{$tarifa->getTarifa()}'>{$tarifa->getTarifa()} €</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="inputgroup">
                    <label for="alergias">Alergias e intolerancias</label>
                    <textarea name="alergias" id="alergias" placeholder="Lactosa, Gluten, Frutos secos, Pescado, etc."></textarea>
                </div>
                <br><br>
                <h3>ACTIVIDADES EXTRAESCOLARES</h3>
                <div class="inputgroup">
                    <div class="actividades">
                        <?php
                            $actividades = $gestor->recuperaActividades($bbdd);
                            $actividades = Actividad::multicast($actividades);
                            foreach ($actividades as $actividad) {
                                echo <<<MARCA
                                <div class="inputgroupcheck">
                                    <label for="{$actividad->getActividad()}">{$actividad->getActividad()}</label>
                                    <input type="checkbox" name="actividades[]" value="{$actividad->getActividad()}">
                                </div>
                                MARCA;
                            }
                        ?>
                    </div>
                </div>
            </fieldset>
            
            <div class="botonera">
                <button type="submit" name="crear" id="crear">Crear</button>
                <button type="button" name="volver" id="volver">Volver</button>
            </div>
        </form>
    </main>
</body>
<script src="./../assets/js/form_registro_alumnos.js"></script>
</html>