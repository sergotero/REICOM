<?php
    require_once "./../../src/autoload.php";
    session_start();
    
    if(isset($_POST['salir'])){
        session_destroy();
        header('Location: ./../index.php');
    }

    if(!isset($_SESSION['usuario'])){
        session_destroy();
        header('Location: ./../index.php');
    }else {
        $usuario = $_SESSION['usuario'];
        if($usuario->getRol() == 'Profesor'){
            header('Location: ./../index.php');
        }
    }

    $errores = [];
    $exitos = [];
    $accion = $_SERVER['PHP_SELF'];

    //Creamos un contador para las faltas y las asistencias
    $contadorA = 0;
    $contadorF = 0;

    //Generamos los objetos necesarios para realizar la conexión a la BBDD
    $bbdd = new MySQLBBDD();
    $gestor = new Gestor();

    if(isset($_POST['estadisticas'])){
        //Recuperamos el id del alumno
        $id_alumno = $_POST['estadisticas'];

        try {
            //Recuperamos al alumno en cuestión (para obtener el nombre)
            $alumno = $gestor->recuperaAlumno($bbdd, $id_alumno);
            if($alumno){
                $alumno = Alumno::cast($alumno);
                $nombre = $alumno->getNombreCompleto();
            }
        } catch (ConsultaAlumnosException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }
    }
    
    if(isset($_POST['buscar'])){
        //Recuperamos los valores para hacer la búsqueda
        $f_inicio = $_POST['f_inicio'];
        $f_fin = $_POST['f_fin'];
        $id_alumno = $_POST['id_alumno'];
        $nombre = "";

        $fini_formateada = Alumno::formatoFecha($f_inicio);
        $ffin_formateada = Alumno::formatoFecha($f_fin);
        
        try {
            //Recuperamos al alumno en cuestión
            $alumno = $gestor->recuperaAlumno($bbdd, $id_alumno);
            if($alumno){
                $alumno = Alumno::cast($alumno);
                $nombre = $alumno->getNombreCompleto();
            }
            //Recuperamos las faltas del alumno
            $faltas = $gestor->recuperaFaltasPeriodo($bbdd, $id_alumno, $f_inicio, $f_fin);
            if($faltas){
                $faltas = Falta::multicast($faltas);
                $totalF = $gestor->totalFaltasPeriodo($bbdd, $id_alumno, $f_inicio, $f_fin);
            } else{
                $errores[] = [
                'codigo' => CodigosError::GENERICO_SIN_CODIGO,
                'mensaje' => 'No existen registros de faltas en el periodo especificado.'
                ];
            }

            //Recuperamos las asistencias del alumno
            $asistencias = $gestor->recuperaAsistenciasPeriodo($bbdd, $id_alumno, $f_inicio, $f_fin);
            if($asistencias){
                $asistencias = Asistencia::multicast($asistencias);
                $totalA = $gestor->totalAsistenciasPeriodo($bbdd, $id_alumno, $f_inicio, $f_fin);
            }else{
                $errores[] = [
                'codigo' => CodigosError::GENERICO_SIN_CODIGO,
                'mensaje' => 'No existen registros de asistencia en el periodo especificado.'
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
    <title>Estadística alumno</title>
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
            <li class="actual">Estadísticas de <span class="nombre"><?php echo $alumno->getNombreCompleto();?></span></li>
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
        <div class="periodos">
            <form action="<?php echo $accion; ?>" name="periodos" method="post">
                <!-- En este apartado se selecciona el periodo de tiempo -->
                <fieldset>
                    <h3>SELECCIONAR PERIODO</h3>
                    <div class="inputgroup">
                        <input type="number" name="id_alumno" id="id_alumno" value="<?php echo $id_alumno;?>" hidden>
                    </div>
                    <div class="inputgroup">
                        <label for="f_inicio">Fecha Inicio</label>
                        <input type="date" name="f_inicio" id="f_inicio">
                    </div>
                    <div class="inputgroup">
                        <label for="f_fin">Fecha Fin</label>
                        <input type="date" name="f_fin" id="f_fin">
                    </div>
                </fieldset>
                <!-- En este apartado se muestra el listado -->

                <?php
                    if(isset($faltas) && ($faltas != null)){
                        echo <<<MARCA
                        <fieldset>
                            <h3>LISTADO DE FALTAS</h3>
                            <br>
                            <table>
                                <thead>
                                    <th>#</th>
                                    <th>Fecha falta</th>
                                    <th>Emisor aviso</th>
                                    <th>Hora aviso</th>
                                </thead>
                                <tbody>
                        MARCA;
                        foreach ($faltas as $falta){
                            $contadorF++;
                            $fecha = Alumno::formatoFecha($falta->getFecha());
                            $emisor = $falta->getEmisor()??'- -';
                            $hora = $falta->getHora()??'- -';
                            echo "<tr>";
                            echo "<td>{$contadorF}</td>";
                            echo "<td>{$fecha}</td>";
                            echo "<td>{$emisor}</td>";
                            echo "<td>{$hora}</td>";
                            echo "</tr>";
                        }
                        echo <<<MARCA
                                </tbody>
                            </table>
                        </fieldset>
                        MARCA;
                    }

                    if(isset($asistencias) && ($asistencias != null)){
                        
                        echo <<<MARCA
                        <fieldset>
                            <h3>LISTADO DE ASISTENCIAS</h3>
                            <br>
                            <table>
                                <thead>
                                    <th>#</th>
                                    <th>Fecha asistencia</th>
                                </thead>
                                <tbody>
                        MARCA;
                        foreach ($asistencias as $asistencia){
                            $fecha = Alumno::formatoFecha($asistencia->getFecha());
                            $contadorA++;
                            echo "<tr>";
                            echo "<td>{$contadorA}</td>";
                            echo "<td>{$fecha}</td>";
                            echo "</tr>";
                        }
                        echo <<<MARCA
                                </tbody>
                            </table>
                        </fieldset>
                        MARCA;
                    }

                    if(isset($totalF) || isset($totalA)){
                        if(!isset($totalF)){
                            $totalF = 0;
                        }

                        if(!isset($totalA)){
                            $totalA = 0;
                        }
                        echo <<<MARCA
                        <fieldset>
                            <h3>RECUENTO $nombre</h3>
                            <br>
                            <table>
                                <thead>
                                    <th>Fecha inicio</th>
                                    <th>Fecha fin</th>
                                    <th>Total faltas</th>
                                    <th>Total asistencias</th>
                                </thead>
                                <tbody>
                                    <td>{$fini_formateada}</td>
                                    <td>{$ffin_formateada}</td>
                                    <td>{$totalF}</td>
                                    <td>{$totalA}</td>
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
        </div>
    </main>
</body>
<script src="./../assets/js/form_estadistica_alumnos.js"></script>
</html>