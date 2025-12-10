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
    $cursos = [];
    $hoy = date('Y-m-d');
    
    //Creamos la conexión a la base
    $bbdd = new MySQLBBDD();
    $gestor = new Gestor($bbdd);
    //Recuperamos los ENUMs de la base de datos.
    $cursos = $gestor->recuperaCursos($bbdd);
    $grupos = $gestor->recuperaGrupos($bbdd);
    $tarifas = $gestor->recuperaTarifas($bbdd);
    $tarifas = Tarifa::multicast($tarifas);

    if(isset($_SESSION['id_alumno'])){
        $id = $_SESSION['id_alumno'];
        //Recuperamos al alumno de la base de datos
        $alumno_recuperado = $gestor->recuperaAlumno($bbdd, $id);

        //Convertimos el resultado en un objeto Alumno
        $alumno = Alumno::cast($alumno_recuperado);

        //Recuperamos las actividades del alumno
        $actividades = $gestor->recuperaActividadesAlumno($bbdd, $alumno);

        //Convertimos el resultado a Actividad y lo asignamos.
        $actividades = Actividad::multicast($actividades);
        $alumno->setActividades($actividades);
        unset($_SESSION['id_alumno']);
    }

    if(isset($_POST['modificar'])){

        //Capturamos el id del alumno del listado
        $id_alumno = $_POST['modificar'];
        
        try {
            //Recuperamos al alumno en cuestión (para obtener el nombre)
            $alumno = $gestor->recuperaAlumno($bbdd, $id_alumno);
            if($alumno){
                $alumno = Alumno::cast($alumno);
                $nombre = $alumno->getNombreCompleto();
            }
            //Recuperamos las actividades del alumno
            $actividades = $gestor->recuperaActividadesAlumno($bbdd, $alumno);
    
            //Convertimos el resultado a Actividad y lo asignamos.
            $actividades = Actividad::multicast($actividades);
            $alumno->setActividades($actividades);

        } catch (ConsultaAlumnosException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        } catch (ConsultaActividadException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }

    }

    if(isset($_POST['guardar'])){
        
        try {
            //Capturamos los valores modificados del formulario
            $id_alumno = intval(trim($_POST['id_alumno']));
            $nombre = trim($_POST['nombre_alumno']);
            $apellido1 = trim($_POST['apellido1_alumno']);
            $apellido2 = trim($_POST['apellido2_alumno']);
            $f_nacimiento = trim($_POST['f_nacimiento']);
            $curso = trim($_POST['curso']);
            $grupo = trim($_POST['grupo']);
            $alergias = ($_POST['alergias'])?trim($_POST['alergias']): null;
            $valorTarifa = floatval($_POST['tarifa']);
            $actividades = $_POST['actividades'];
    
            //Se recupera el objeto Alumno a partir del id
            $alumno = $gestor->recuperaAlumno($bbdd, $id_alumno);
            //Se hace una conversión a Alumno
            $alumno = Alumno::cast($alumno);
    
            //Usamos los setters definidos en el objeto para asignarle nuevos valores
            $alumno->setNombre($nombre);
            $alumno->setApellido1($apellido1);
            $alumno->setApellido2($apellido2);
            $alumno->setNacimiento($f_nacimiento);
            $alumno->setCurso($curso);
            $alumno->setGrupo($grupo);
            $alumno->setAlergias($alergias);
            
            //Creamos una nueva Tarifa y se la asociamos a Alumno
            $tarifa = new Tarifa($valorTarifa);
            $alumno->setTarifa($tarifa);
            
            //Actualizamos la tabla alumnos
            $resultado = $gestor->actualizaAlumno($bbdd, $alumno);
            if($resultado){
                $exitos[] = [
                    'codigo' => CodigosExito::ALUMNO_ACTUALIZAR,
                    'mensaje' => 'Los datos del alumno se han modificado con éxito'
                ];
            }

            //Eliminamos todos los registros de actividad del alumno de la tabla actividades_alumno
            $resultadoEliminar = $gestor->eliminaActividadesAlumno($bbdd, $alumno);
            
            //Insertamos de nuevo todas las actividades en la tabla actividades_alumnos
            foreach ($actividades as $actividad) {

                $act = new Actividad($actividad);
                $alumno->setActividad($act);
                $gestor->insertaActividadAlumno($bbdd, $alumno, $act);

            }
        } catch (ActualizaAlumnoException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }catch (CreaTarifasException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        } catch (ConsultaAlumnosException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }catch(EliminaActividadException $e){
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
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
    <title>Modificar datos</title>
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
            <li class="actual">Modificar datos de <span class="nombre"><?php echo $alumno->getNombreCompleto();?></span></li>
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
        <form name="mod_alumno" action="<?php echo $_SERVER['PHP_SELF'] . "?origen=buscador"; ?>" method="post">

            <!-- ALUMNO -->
            <fieldset class="alumno">
                <h3>MODIFICAR DATOS ALUMNO</h3>
                <div class="inputgroup">
                    <input type="text" name="id_alumno" id="id_alumno" value="<?php echo $alumno->getId();?>" hidden>
                </div>
                <div class="inputgroup">
                    <label for="nombre_alumno">Nombre</label>
                    <input type="text" name="nombre_alumno" id="nombre_alumno" value="<?php echo $alumno->getNombre();?>" required>
                </div>
                <div class="inputgroup">
                    <label for="apellido1_alumno">1<sup>er</sup> Apellido</label>
                    <input type="text" name="apellido1_alumno" id="apellido1_alumno" value="<?php echo $alumno->getApellido1();?>" required>
                </div>
                <div class="inputgroup">
                    <label for="apellido2_alumno">2<sup>do</sup> Apellido</label>
                    <input type="text" name="apellido2_alumno" id="apellido2_alumno" value="<?php echo $alumno->getApellido2();?>" required>
                </div>
                <div class="inputgroup">
                    <label for="f_nacimiento">Fecha nacimiento</label>
                    <input type="date" name="f_nacimiento" id="f_nacimiento" value="<?php echo $alumno->getNacimiento();?>" min="1975-01-01" max="2030-12-31" title="Las fechas se pueden elegir desde 01/01/1975 hasta 31/12/2030" required>
                </div>
                <div class="inputgroup">
                    <label for="curso">Curso</label>
                    <select name="curso" id="curso" required>
                        <?php
                            foreach ($cursos as $curso) {
                                if($curso == $alumno->getCurso()){
                                    echo "<option value='{$curso}' selected>{$curso}</option>";
                                } else{
                                    echo "<option value='{$curso}'>{$curso}</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="inputgroup">
                    <label for="grupo">Grupo</label>
                    <select name="grupo" id="grupo">
                        <?php
                            foreach ($grupos as $grupo) {
                                if($grupo == $alumno->getGrupo()){
                                    echo "<option value='{$grupo}' selected>{$grupo}</option>";
                                } else{
                                    echo "<option value='{$grupo}'>{$grupo}</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="inputgroup">
                    <label for="tarifa">Tarifa</label>
                    <select name="tarifa" id="tarifa" required>
                        <?php
                            foreach ($tarifas as $tarifa) {
                                if($tarifa->getId() === $alumno->getTarifa()->getId()){
                                    echo "<option value='{$alumno->getTarifa()->getTarifa()}' selected>{$tarifa->getTarifa()} €</option>";
                                } else{
                                    echo "<option value='{$tarifa->getTarifa()}'>{$tarifa->getTarifa()} €</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="inputgroup">
                    <label for="alergias">Alergias e intolerancias</label>
                    <textarea name="alergias" id="alergias" placeholder="Lactosa, Gluten, Frutos secos, Pescado, etc."><?php echo $alumno->getAlergias();?></textarea>
                </div>
                <br><br>
                <h3>ACTIVIDADES EXTRAESCOLARES</h3>
                <div class="inputgroup">
                    <div class="actividades">
                        <?php
                            $actividades = $gestor->recuperaActividades($bbdd);
                            $actividades = Actividad::multicast($actividades);
                            foreach ($actividades as $actividad) {
                                if(in_array($actividad, $alumno->getActividades())){
                                    echo <<<MARCA
                                    <div class="inputgroupcheck">
                                        <label for="{$actividad->getActividad()}">{$actividad->getActividad()}</label>
                                        <input type="checkbox" name="actividades[]" value="{$actividad->getActividad()}" checked>
                                    </div>
                                    MARCA;
                                } else{
                                    echo <<<MARCA
                                    <div class="inputgroupcheck">
                                        <label for="{$actividad->getActividad()}">{$actividad->getActividad()}</label>
                                        <input type="checkbox" name="actividades[]" value="{$actividad->getActividad()}">
                                    </div>
                                    MARCA;
                                }
                            }
                        ?>
                    </div>
                </div>
            </fieldset>
            <div class="botonera">
                <button type="submit" name="guardar" id="guardar">Guardar</button>
                <button type="button" name="volver" id="volver">Volver</button>
            </div>
        </form>
    </main>
</body>
<script src="./../assets/js/form_buscador_alumnos.js"></script>
</html>