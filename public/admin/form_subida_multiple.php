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
    $error_alumnos = [];
    
    //Creamos la conexión a la base
    $bbdd = new MySQLBBDD();
    $gestor = new Gestor($bbdd);

    if(isset($_POST['subir'])){

        if(isset($_FILES['archivo_csv'])){

            //Guardamos el contenido del fichero en una variable.
            $archivo = $_FILES['archivo_csv'];
    
            /*
            $_FILES['archivo'] siempre devuelve el siguiente array asociativo:
    
                [
                    'name' => 'ejemplo.csv',         // nombre original
                    'type' => 'text/csv',            // tipo MIME
                    'tmp_name' => '/tmp/php123.tmp', // nombre temporal en el servidor
                    'error' => 0,                    // código de error (0 = OK)
                    'size' => 1234                   // tamaño en bytes
                ]
            */
    
            // Validar si hubo errores
            if ($archivo['error'] !== UPLOAD_ERR_OK) {
                $errores[] = [
                    'codigo' => $archivo['error'],
                    'mensaje' => 'Se ha producido un error durante la subida.'
                ];
                die();
            }
    
            //Validar la extensión del archivo
            $extensionesPermitidas = ['csv', 'xlsx'];
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    
            if (!in_array($extension, $extensionesPermitidas)) {
                $errores[] = [
                    'codigo' => CodigosError::GENERICO_SIN_CODIGO,
                    'mensaje' => 'La extensión del archivo no es válida.'
                ];
                die();
            }
    
            /*
            Creamos un nuevo SplFileObject.
            Con SplFileObject no es necesario hacer fopen() ni fclose() porque ya se encarga el propio objeto de hacerlo. Al haber marcado el Flag "READ_CSV" le decimos que trate cada fila como si fuese un array, por lo que no sería necesario usar fgetcsv().
            */
    
            $csv = new SplFileObject($archivo['tmp_name'], 'r');
            $csv->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY);
            $csv->setCsvControl(";", "\"", "\\");
            
            try {
                //Generamos un array de Alumnos a partir del CSV
                $alumnos = Alumno::creaDesdeCSV($csv);
            } catch (CreaAlumnoException $e) {
                $errores[] = [
                    'codigo'   => $e->getCode(),
                    'mensaje'  => $e->getMessage(),
                    'archivo'  => $e->getFile(),
                    'linea'    => $e->getLine(),
                ];
            }
    
            foreach ($alumnos as $alumno) {
                
                try {
                    
                    //Comprobamos que no exista ya el alumno
                    $comprobacion = $gestor->compruebaAlumno($bbdd, $alumno);
                    
                    //Si no existe, procedemos a insertarlo en la base
                    if(!$comprobacion){
                        $gestor->insertaAlumno($bbdd, $alumno);
                    } else{
                        
                        $error_alumnos[] = [
                            'codigo' => CodigosError::GENERICO_SIN_CODIGO,
                            'mensaje' => "<li>{$alumno->getNombre()} {$alumno->getApellido1()} {$alumno->getApellido2()}</li>"
                        ];
                    }
                        
                } catch (ConsultaAlumnosException $e){
                    $errores[] = [
                        'codigo'   => $e->getCode(),
                        'mensaje'  => $e->getMessage(),
                        'archivo'  => $e->getFile(),
                        'linea'    => $e->getLine(),
                    ];
                    continue;
                }catch (InsertaAlumnoException $e) {
                    $errores[] = [
                        'codigo'   => $e->getCode(),
                        'mensaje'  => $e->getMessage(),
                        'archivo'  => $e->getFile(),
                        'linea'    => $e->getLine(),
                    ];
                    continue;
                }
            }
        } else{
            $errores[] = [
                'codigo' => CodigosError::GENERICO_SIN_CODIGO,
                'mensaje' => 'Se ha producido un error durante la subida'
            ];
        }

    }

    //Genera una plantilla
    if(isset($_POST['plantilla'])){
        header('Location: ./plantilla_alumnos.php');
        die();
    }

    //Genera un documento de texto con las instrucciones
    if(isset($_POST['instrucciones'])){
        header('Location: ./instrucciones_plantilla.php');
        die();
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
    <title>Añadir múltiple</title>
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
            <li class="actual">Subida múltiple</li>
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
                            echo $error['mensaje'];
                        }
                    }
                
                echo "</div>";
            }
            if(count($error_alumnos) > 0){
                echo <<<MARCA
                <div class="error_alumnos">
                MARCA;
                    if($error_alumnos){
                        echo "<p>Los siguientes alumnos ya se encuentran registrados en la base:</p>";
                        echo "<ul>";
                        foreach ($error_alumnos as $error) {
                            echo $error['mensaje'];
                        }
                        echo "</ul>";
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
        <form name="crea_multiple" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="post">

            <!-- SUBIDA MÚLTIPLE -->
            <fieldset class="crea_multiple">
                <h3>ARCHIVO CSV</h3>
                <div class="inputgroup">
                    <label for="archivo_csv" class="subida-archivos">Subir archivo</label>
                    <input type="file" name="archivo_csv" id="archivo_csv">
                    <span id="nombre_archivo"></span>
                </div>
            </fieldset>
            
            <div class="botonera">
                <button type="submit" name="subir" id="subir">Subir</button>
                <button type="submit" name="plantilla" id="plantilla" title="Descarga un archivo en formato .csv que debe ser abierto con Excel o similares y que contiene los campos necesarios para realizar la subida en masa">Descargar plantilla</button>
                <button type="submit" class="boton" name="instrucciones" id="instrucciones">Instrucciones</button>
                <button type="button" name="volver" id="volver">Volver</button>
            </div>
        </form>
    </main>
</body>
<script src="./../js/form_subida_multiple.js"></script>
</html>