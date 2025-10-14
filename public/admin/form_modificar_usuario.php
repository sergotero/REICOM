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
    $roles = $gestor->recuperaRoles($bbdd);


    if(isset($_SESSION['recuperar'])){

        //Capturamos el id del alumno del listado
        $email = $_SESSION['recuperar'];

        //Recuperamos al usuario en cuestión
        $resultado = $gestor->recuperaUsuario($bbdd, $email);
        if($resultado){
            $usuarioRecuperado = Usuario::cast($resultado);
            $id_usuario = $usuarioRecuperado->getId();
        }
    }

    if(isset($_POST['guardar'])){
        
        if(isset($_POST['id_usuario']) && isset($_POST['nombre']) && isset($_POST['apellido1']) && isset($_POST['apellido2']) && isset($_POST['email']) && isset($_POST['rol']) && isset($_POST['password']) && isset($_POST['password_confirm'])){
        
            //Capturamos los valores del formulario
            $id_usuario = $_POST['id_usuario'];
            $nombre = ucfirst(trim($_POST['nombre']));
            $apellido1 = ucfirst(trim($_POST['apellido1']));
            $apellido2 = ucfirst(trim($_POST['apellido2']));
            $email = strtolower(trim($_POST['email']));
            $rol = ucfirst(trim($_POST['rol']));
            $pass = trim($_POST['password']);
            $pass_confirm = trim($_POST['password_confirm']);

            if($pass === $pass_confirm){
                $pass = password_hash($pass, PASSWORD_DEFAULT);
                $usuario = new Usuario($nombre, $apellido1, $apellido2, $email, $pass, $rol);
                try {
                    $resultado = $gestor->actualizaUsuario($bbdd, $usuario, $id_usuario);
                    if($resultado){
                        $exitos[] = [
                            'codigo' => CodigosError::GENERICO_SIN_CODIGO,
                            'mensaje' => 'El usuario se ha actualizado en la base de datos'
                        ];
                    }

                } catch (ActualizaUsuarioException $e) {
                    $errores[] = [
                        'codigo'   => $e->getCode(),
                        'mensaje'  => $e->getMessage(),
                        'archivo'  => $e->getFile(),
                        'linea'    => $e->getLine(),
                    ];
                }
            } else{
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
    <title>Modificar usuarios</title>
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
            <li><a href="./form_listado_usuarios.php">Listado usuarios</a></li>
            <li class="actual">Modificar usuario</li>
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
        <form name="mod_usuario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <!-- USUARIO -->
            <fieldset class="usuario">
                <h3>MODIFICAR USUARIO</h3>
                <div class="inputgroup">
                    <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $id_usuario;?>" hidden>
                </div>
                <div class="inputgroup">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="<?php echo $usuarioRecuperado->getNombre();?>" required>
                </div>
                <div class="inputgroup">
                    <label for="apellido1">1<sup>er</sup> Apellido</label>
                    <input type="text" name="apellido1" id="apellido1" value="<?php echo $usuarioRecuperado->getApellido1();?>" required>
                </div>
                <div class="inputgroup">
                    <label for="apellido2">2<sup>do</sup> Apellido</label>
                    <input type="text" name="apellido2" id="apellido2" value="<?php echo $usuarioRecuperado->getApellido2();?>" required>
                </div>
                <div class="inputgroup">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo $usuarioRecuperado->getEmail();?>" required>
                </div>
                <div class="inputgroup">
                    <label for="rol">Rol</label>
                    <select name="rol" id="rol" required>
                        <?php
                            foreach ($roles as $rol) {
                                if($rol == $usuarioRecuperado->getRol()){
                                    echo "<option value='{$rol}' selected>{$rol}</option>";
                                } else{
                                    echo "<option value='{$rol}'>{$rol}</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="inputgroup">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="inputgroup">
                    <label for="password_confirm">Contraseña</label>
                    <input type="password" name="password_confirm" id="password_confirm" required>
                </div>
            </fieldset>
            <div class="botonera">
                <button type="submit" name="guardar" id="guardar">Guardar</button>
                <button type="button" name="volver" id="volver">Volver</button>
            </div>
        </form>
    </main>
</body>
<script src="./../assets/js/form_modificar_usuario.js"></script>
</html>