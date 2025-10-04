<?php
    require_once "./../src/autoload.php";
    session_start();

    $bbdd = new MySQLBBDD();
    $gestor = new Gestor();
    $errores = [];

    //BOTÓN ACCEDER PULSADO
    if(isset($_POST['acceder'])){
        
        if(isset($_POST['email']) && isset($_POST['password'])){
            
            //Capturamos los valores del formulario
            $email = strtolower(trim($_POST['email']));
            $pass = trim($_POST['password']);
            
            //Intentamos recuperar el usuario
            $usuario = $gestor->recuperaUsuario($bbdd, $email);
            $usuario = Usuario::cast($usuario);

            //Si el usuario existe
            if($usuario){
                //Comprobamos que el password coincide
                if(password_verify($pass, $usuario->getPass())){
                
                $_SESSION['usuario'] = $usuario;
                if($usuario->getRol() == 'Profesor'){
                    header('Location: ./index.php');
                    die();
                } else {
                    header('Location: ./admin/form_listado_alumnos.php');
                    die();
                }

            } else{

                $errores[] = [
                    'codigo' => CodigosError::GENERICO_SIN_CODIGO,
                    'mensaje' => 'Alguno de los parámetros no es correcto'
                ];
            }
            } else{
                $errores[] = [
                    'codigo' => CodigosError::GENERICO_SIN_CODIGO,
                    'mensaje' => 'El usuario no existe en el sistema'
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
    <link rel="shortcut icon" href="./../images/favicon_blanco.ico" type="image/x-icon">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="./styles/general.css">
    <link rel="stylesheet" href="./styles/avisos.css">
    <link rel="stylesheet" href="./styles/botones.css">
    <link rel="stylesheet" href="./styles/forms.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <title>Acceder</title>
</head>
<body>
    <header>
        <div class="cabecera">
            <div class="logo">
                <p>REICOM</p>
            </div>
            <!-- En este apartado se mostrará la información de sesión (nombre, logout) -->
        </div>
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
            ?>
        </div>
    </header>
    <main>
        <form name="acceder" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            
        <!-- USUARIO -->
            <fieldset class="usuario">
                <h3>ACCESO</h3>
                <div class="inputgroup">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="inputgroup">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" required>
                </div>
            </fieldset>
            
            <div class="botonera">
                <button type="submit" name="acceder" id="acceder">Acceder</button>
            </div>
        </form>
    </main>
</body>
<script src="./js/form_acceso.js"></script>
</html>