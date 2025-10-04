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
    if(isset($_POST['crear_usuarios'])){
        header('Location: ./form_registro_usuarios.php');
        die();
    }

    //BOTON MODIFICAR
    if(isset($_POST['modificar'])){
        $_SESSION['recuperar'] = $_POST['modificar'];
        header('Location: ./form_modificar_usuario.php');
        die();
    }

    //BOTON ELIMINAR USUARIO 
    if(isset($_POST['eliminar'])){
        //Recuperamos los datos
        $email = $_POST['eliminar'];

        try {
            //Recuperamos al usuario en cuestión
            $resultado = $gestor->recuperaUsuario($bbdd, $email);
            if($resultado){
                $usuario = Usuario::cast($resultado);
            }
            //Eliminamos al alumno de la base
            $gestor->eliminaUsuario($bbdd, $usuario);

        } catch (EliminaUsuarioException $e) {
            $errores[] = [
                'codigo'   => $e->getCode(),
                'mensaje'  => $e->getMessage(),
                'archivo'  => $e->getFile(),
                'linea'    => $e->getLine(),
            ];
        }
        
        $exitos[] = [
            'codigo' => CodigosExito::USUARIO_ELIMINAR,
            'mensaje' => 'Se ha eliminado al usuario con éxito'
        ];
    }

    //Consulta para generar la tabla
    try {
        $resultado = $gestor->recuperaUsuarios($bbdd);
        if($resultado){
            $usuarios = Usuario::multicast($resultado);
        }
        //Modificar esta excepción
    } catch (CompruebaUsuariosException $e) {
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
    <title>Listado de usuarios</title>
</head>
<body>
    <header>
        <div class="cabecera">
            <div class="logo">
                <p><a href="./form_listado_alumnos.php">REICOM</a></p>
            </div>            <div class="gestion_usuarios">
                <form name="gestion_usuarios" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <button type="submit" name="crear_usuarios">Crear usuarios</button>
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
            <li class="actual">Listado usuarios</li>
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
                if(isset($usuarios)){
                    echo <<<MARCA
                    <table id='listado_usuarios'>
                        <thead>
                            <th colspan='8' class='celda_vacia'>
                                <!-- Botones sólo disponibles para la vista de administrador -->
                                <div class="celda_aviso eliminar">
                                    <div class="inputgroup">
                                        <h4>Eliminar usuarios es una acción <strong>IRREVERSIBLE</strong>. Tenga cuidado antes de proceder.</h4>
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
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                    MARCA;

                    foreach ($usuarios as $usuario) {                        
                        //Sólo queremos que se muestren los usuarios habilitados (hay dos que van a quedar creados por si se necesitan modificaciones en el futuro)
                        if($usuario->getEmail() == "admin@reicom.com" || $usuario->getEmail() == "profe@reicom.com"){
                            continue;
                        }
                        
                        echo <<<MARCA
                            <tr>
                                <td hidden>{$usuario->getId()}</td>
                                <td>{$usuario->getApellido1()} {$usuario->getApellido2()}, {$usuario->getNombre()}</td>
                                <td>{$usuario->getEmail()}</td>
                                <td>{$usuario->getRol()}</td>
                                <td class='acciones'>
                                    <form name='acciones_usuario' action='{$accion}' method='POST'>
                                        <button type='submit' name='modificar' value='{$usuario->getEmail()}' title='Permite modificar los datos del usuario'>Modificar</button>    
                                        <button type='submit' name='eliminar' value='{$usuario->getEmail()}' title='Elimina al usuario de la base'>Eliminar</button>
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
<script src="./../js/form_listado_usuarios.js"></script>
</html>