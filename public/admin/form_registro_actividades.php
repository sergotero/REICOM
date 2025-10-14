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

//BOTÓN ACCEDER PULSADO
if(isset($_POST['crear_actividad'])){
    
    if(isset($_POST['nombre'])){
        
        //Capturamos los valores del formulario
        $nombre = ucfirst(trim($_POST['nombre']));
        $h_inicio = $_POST['h_inicio'];
        $h_fin = $_POST['h_fin'];
        $dias = $_POST['dias'];
        if($dias != null){
            $dias = implode(", ", $dias);
        }
        $ubicacion = empty($_POST['ubicacion']) ? null : trim($_POST['ubicacion']);

        try {
            $actividad = new Actividad($nombre);
            $actividad->setHoraInicio($h_inicio);
            $actividad->setHoraFin($h_fin);
            $actividad->setDias($dias);
            $actividad->setUbicacion($ubicacion);

            $resultado = $gestor->insertaActividad($bbdd, $actividad);
            if($resultado){
                $exitos[] = [
                    'código' => CodigosExito::ACTIVIDAD_INSERTAR,
                    'mensaje' => 'Se ha creado con éxito una nueva actividad en la base de datos'
                ];
            }
        } catch (InsertaAsistenciaException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }
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
    <title>Registrar actividades</title>
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
            <li class="actual">Registro actividades</li>
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
        <form name="actividades" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            
        <!-- ACTIVIDAD -->
            <fieldset class="actividad">
                <h3>REGISTRAR ACTIVIDAD</h3>
                <div class="inputgroup">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>
                <div class="inputgroup">
                    <label for="h_inicio">Hora inicio</label>
                    <input type="time" name="h_inicio" id="h_inicio">
                </div>
                <div class="inputgroup">
                    <label for="h_fin">Hora fin</label>
                    <input type="time" name="h_fin" id="h_fin">
                </div>
                <div class="inputgroup">
                    <label for="nombre">Ubicación</label>
                    <input type="text" name="ubicacion" id="ubicacion" placeholder="Lugar 1(L), Lugar 2(M), Lugar 3(X)...">
                </div>
                <br>
                <br>
                <h3>DIAS</h3>
                <div class="inputgroup">
                    <div class="inputgroupcheck">
                        <label for="lunes">Lunes</label>
                        <input type="checkbox" name="dias[]" id="lunes" value="Lunes">
                    </div>
                    <div class="inputgroupcheck">
                        <label for="martes">Martes</label>
                        <input type="checkbox" name="dias[]" id="martes" value="Martes">
                    </div>
                    <div class="inputgroupcheck">
                        <label for="miercoles">Miércoles</label>
                        <input type="checkbox" name="dias[]" id="miercoles" value="Miércoles">
                    </div>
                    <div class="inputgroupcheck">
                        <label for="jueves">Jueves</label>
                        <input type="checkbox" name="dias[]" id="jueves" value="Jueves">
                    </div>
                    <div class="inputgroupcheck">
                        <label for="viernes">Viernes</label>
                        <input type="checkbox" name="dias[]" id="viernes" value="Viernes">
                    </div>
                </div>
            </fieldset>
            
            <div class="botonera">
                <button type="submit" name="crear_actividad" id="crear_actividad">Crear</button>
                <button type="button" name="volver" id="volver">Volver</button>
            </div>
        </form>
    </main>
</body>
<script src="./../assets/js/form_registro_actividades.js"></script>
</html>