<?php
/**
 * Clase Gestor
 * 
 * Se trata de una clase que gestiona CRUD con la base de datos.
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class Gestor{

    /**
     * Constructor de la clase Gestor.
     */
    public function __construct(){
    }

    /**
     * Comprueba si un alumno está registrado en la base de datos.
     * 
     * Este método sólo realiza la comprobación con los datos contenidos en la tabla alumnos de la base de datos.
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @throws ConsultaAlumnosException
     * @return bool
     */
    public function compruebaAlumno(ConexionBBDD $bbdd, Alumno $alumno): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM alumnos WHERE nombre LIKE :nombre AND apellido1 LIKE :apellido1 AND apellido2 LIKE :apellido2 AND f_nacimiento = :f_nacimiento");

            $nombre = '%' . $alumno->getNombre() . '%';
            $apellido1 = '%' . $alumno->getApellido1() . '%';
            $apellido2 = '%' . $alumno->getApellido2() . '%';
            $nacimiento = $alumno->getNacimiento();

            $consulta->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $consulta->bindParam(":apellido1", $apellido1, PDO::PARAM_STR);
            $consulta->bindParam(":apellido2", $apellido2, PDO::PARAM_STR);
            $consulta->bindParam(":f_nacimiento", $nacimiento, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaAlumnosException($e);
        }
        return ($resultado)?true:false;
    }

    /**
     * Recupera a un alumno de la base de datos.
     * 
     * Este método recupera de la base de datos la información necesaria para construir un objeto Alumno completo (con excepción de las faltas y asistencias). Retorna un objeto StdClass o un false;
     *
     * @param  ConexionBBDD $bbdd
     * @param  int $id_alumno
     * @throws ConsultaAlumnosException
     * @return mixed
     */
    public function recuperaAlumno(ConexionBBDD $bbdd, int $id_alumno): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT alumnos.*, tarifas.tarifa, actividades.actividad FROM alumnos LEFT JOIN tarifas ON alumnos.id_tarifa = tarifas.id LEFT JOIN actividades_alumno ON actividades_alumno.id_alumno = alumnos.id LEFT JOIN actividades ON actividades_alumno.id_actividad = actividades.id WHERE alumnos.id = :id ORDER BY curso, grupo, apellido1, apellido2, nombre");
            $consulta->bindValue(":id", $id_alumno, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaAlumnosException($e);
        }
        return $resultado;
    }

    /**
     * Recupera a todos los alumnos de la base de datos.
     * 
     * Este método recupera de la base de datos la información necesaria de cada alumno para construir un objeto Alumno completo (con excepción de las faltas, asistencias y actividades). Retorna un StdClass[] o false.
     *
     * @param  ConexionBBDD $bbdd
     * @throws ConsultaAlumnosException
     * @return mixed
     */
    public function recuperaAlumnos(ConexionBBDD $bbdd): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT alumnos.*, tarifas.tarifa FROM alumnos LEFT JOIN tarifas ON alumnos.id_tarifa = tarifas.id ORDER BY curso, grupo, apellido1, apellido2, nombre");
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaAlumnosException($e);
        }
        return $resultado;
    }
    
    /**
     * Recupera a todos los alumnos de un curso de la base de datos.
     * 
     * Este método recupera de la base de datos la información necesaria de cada alumno para construir un objeto Alumno completo (con excepción de las faltas, asistencias y actividades), filtrados por curso.
     *
     * @param  ConexionBBDD $bbdd
     * @param  string $curso
     * @return mixed
     */
    public function recuperaAlumnosCurso(ConexionBBDD $bbdd, string $curso): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT alumnos.*, tarifas.tarifa FROM alumnos LEFT JOIN tarifas ON alumnos.id_tarifa = tarifas.id WHERE curso = :c ORDER BY curso, grupo, apellido1, apellido2, nombre");
            $consulta->bindParam(":c", $curso, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaAlumnosException($e);
        }
        return $resultado;
    }
    
    /**
     * recuperaAlumnosCursoGrupo
     *
     * @param  ConexionBBDD $bbdd
     * @param  string $curso
     * @param  string $grupo
     * @return mixed
     */
    public function recuperaAlumnosCursoGrupo(ConexionBBDD $bbdd, string $curso, string $grupo): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT alumnos.*, tarifas.tarifa FROM alumnos LEFT JOIN tarifas ON alumnos.id_tarifa = tarifas.id WHERE curso = :c AND grupo = :g ORDER BY curso, grupo, apellido1, apellido2, nombre");
            $consulta->bindParam(":c", $curso, PDO::PARAM_STR);
            $consulta->bindParam(":g", $grupo, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaAlumnosException($e);
        }
        return $resultado;
    }

    
    /**
     * recuperaCursosGruposConFaltas
     *
     * @param  ConexionBBDD $bbdd
     * @return mixed
     */
    public function recuperaCursosGruposConFaltas(ConexionBBDD $bbdd): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT alumnos.curso, alumnos.grupo, faltas.f_falta FROM alumnos LEFT JOIN faltas ON faltas.id_alumno = alumnos.id WHERE f_falta = CURDATE() AND f_falta IS NOT NULL ORDER BY alumnos.curso, alumnos.grupo;");

            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaFaltasException($e);
        }
        return $resultado;
    }
    
    /**
     * recuperaUsuario
     *
     * @param  ConexionBBDD $bbdd
     * @param  string $email
     * @return mixed
     */
    public function recuperaUsuario(ConexionBBDD $bbdd, string $email): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email = :email");
            $consulta->bindParam(":email", $email);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new ConsultaUsuarioException($e);
        }
        return $resultado;
    }
    
    /**
     * recuperaUsuarios
     *
     * @param  ConexionBBDD $bbdd
     * @return mixed
     */
    public function recuperaUsuarios(ConexionBBDD $bbdd): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM usuarios ORDER BY apellido1, apellido2, nombre");
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaUsuarioException($e);
        }
        return $resultado;
    }
    
    /**
     * recuperaCursos
     *
     * @param  ConexionBBDD $bbdd
     * @return mixed
     */
    public function recuperaCursos(ConexionBBDD $bbdd): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            //Esta consulta devuelve los valores del ENUM definido en la base
            $consulta = $conexion->prepare("SELECT SUBSTRING(COLUMN_TYPE,5) AS enum FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='reicom' AND TABLE_NAME='alumnos' AND COLUMN_NAME='curso'");
            $consulta->execute();
            //El método fetch() hace que se transforme en un objeto stdClass. El objeto contiene un atributo llamado "enum" (debido al alias que le dimos en la consulta).
            $resultado = $consulta->fetch(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
            
            //Creamos un array con los caracteres que necesita reemplazar
            $reemplazos = ["'", "(", ")"];

            //Reemplazamos los caracteres del atributo y lo convertimos a un array de nuevo antes de retornarlo
            $cursos = str_replace($reemplazos, "", $resultado->enum);

        } catch (PDOException $e) {
            throw new ConsultaAlumnosException($e);
        }
        return explode(",", $cursos);
    }
    
    /**
     * recuperaGrupos
     *
     * @param  ConexionBBDD $bbdd
     * @return mixed
     */
    public function recuperaGrupos(ConexionBBDD $bbdd): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            //Esta consulta devuelve los valores del ENUM definido en la base
            $consulta = $conexion->prepare("SELECT SUBSTRING(COLUMN_TYPE,5) AS enum FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='reicom' AND TABLE_NAME='alumnos' AND COLUMN_NAME='grupo'");
            $consulta->execute();
            //El método fetch() hace que se transforme en un objeto stdClass. El objeto contiene un atributo llamado "enum" (debido al alias que le dimos en la consulta).
            $resultado = $consulta->fetch(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
            
            //Creamos un array con los caracteres que necesita reemplazar
            $reemplazos = ["'", "(", ")"];

            //Reemplazamos los caracteres del atributo y lo convertimos a un array de nuevo antes de retornarlo
            $grupos = str_replace($reemplazos, "", $resultado->enum);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();

        } catch (PDOException $e) {
            throw new ConsultaAlumnosException($e);
        }
        return explode(",", $grupos);
    }
    
    /**
     * recuperaRoles
     *
     * @param  ConexionBBDD $bbdd
     * @return mixed
     */
    public function recuperaRoles(ConexionBBDD $bbdd): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            //Esta consulta devuelve los valores del ENUM definido en la base
            $consulta = $conexion->prepare("SELECT SUBSTRING(COLUMN_TYPE,5) AS enum FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='reicom' AND TABLE_NAME='usuarios' AND COLUMN_NAME='rol'");
            $consulta->execute();
            //El método fetch() hace que se transforme en un objeto stdClass. El objeto contiene un atributo llamado "enum" (debido al alias que le dimos en la consulta).
            $resultado = $consulta->fetch(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
            
            //Creamos un array con los caracteres que necesita reemplazar
            $reemplazos = ["'", "(", ")"];

            //Reemplazamos los caracteres del atributo y lo convertimos a un array de nuevo antes de retornarlo
            $grupos = str_replace($reemplazos, "", $resultado->enum);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();

        } catch (PDOException $e) {
            throw new ConsultaAlumnosException($e);
        }
        return explode(",", $grupos);
    }
    
    /**
     * recuperaTarifas
     *
     * @param  ConexionBBDD $bbdd
     * @return mixed
     */
    public function recuperaTarifas(ConexionBBDD $bbdd): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM tarifas");
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (ConsultarTarifasException $e) {
            throw new ConsultarTarifasException($e);
        }
        return $resultado;
    }
    
    /**
     * recuperaFaltas
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return mixed
     */
    public function recuperaFaltas(ConexionBBDD $bbdd, Alumno $alumno): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM faltas WHERE id_alumno = :id");

            $id = $alumno->getId();

            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaFaltasException($e);
        }
        return $resultado;
    }
    
    /**
     * recuperaAsistencias
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return mixed
     */
    public function recuperaAsistencias(ConexionBBDD $bbdd, Alumno $alumno): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM asistencias WHERE id_alumno = :id");

            $id = $alumno->getId();

            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaAsistenciasException($e);
        }
        return $resultado;
    }
    
    /**
     * recuperaFaltasHoy
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return mixed
     */
    public function recuperaFaltasHoy(ConexionBBDD $bbdd, Alumno $alumno): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM faltas WHERE id_alumno = :id AND f_falta = :hoy");

            $id = $alumno->getId();
            $hoy = date('Y-m-d');

            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->bindParam(":hoy", $hoy, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaFaltasException($e);
        }
        return $resultado;
    }
    
    /**
     * recuperaAsistenciasHoy
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return mixed
     */
    public function recuperaAsistenciasHoy(ConexionBBDD $bbdd, Alumno $alumno): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM asistencias WHERE id_alumno = :id AND f_asistencia = :hoy");

            $id = $alumno->getId();
            $hoy = date('Y-m-d');

            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->bindParam(":hoy", $hoy, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaAsistenciasException($e);
        }
        return $resultado;
    }
    
    /**
     * recuperaFaltasPeriodo
     *
     * @param  ConexionBBDD $bbdd
     * @param  int $id
     * @param  string $f_inicio
     * @param  string $f_fin
     * @return mixed
     */
    public function recuperaFaltasPeriodo(ConexionBBDD $bbdd, int $id, string $f_inicio, string $f_fin): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM faltas WHERE id_alumno = :id AND f_falta >= :f_inicio AND f_falta <= :f_fin");

            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->bindParam(":f_inicio", $f_inicio, PDO::PARAM_STR);
            $consulta->bindParam(":f_fin", $f_fin, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaFaltasException($e);
        }
        return $resultado;
    }
    
    /**
     * recuperaFaltasMesAno
     *
     * @param  ConexionBBDD $bbdd
     * @param  int $id
     * @param  int $mes
     * @param  int $ano
     * @return mixed
     */
    public function recuperaFaltasMesAno(ConexionBBDD $bbdd, int $id, int $mes, int $ano): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM faltas WHERE id_alumno = :id AND MONTH(f_falta) = :mes AND YEAR(f_falta) = :ano");

            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->bindValue(":mes", $mes, PDO::PARAM_INT);
            $consulta->bindValue(":ano", $ano, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaFaltasException($e);
        }
        return $resultado;
    }
    
    /**
     * recuperaAsistenciasPeriodo
     *
     * @param  ConexionBBDD $bbdd
     * @param  int $id
     * @param  string $f_inicio
     * @param  string $f_fin
     * @return mixed
     */
    public function recuperaAsistenciasPeriodo(ConexionBBDD $bbdd, int $id, string $f_inicio, string $f_fin): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM asistencias WHERE id_alumno = :id AND f_asistencia >= :f_inicio AND f_asistencia <= :f_fin");

            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->bindParam(":f_inicio", $f_inicio, PDO::PARAM_STR);
            $consulta->bindParam(":f_fin", $f_fin, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaAsistenciasException($e);
        }
        return $resultado;
    }

    public function recuperaActividad(ConexionBBDD $bbdd, int $id_actividad): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM actividades WHERE id = :id");

            $consulta->bindValue(":id", $id_actividad, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaActividadException($e);
        }

        return $resultado;
    }

    public function recuperaActividades(ConexionBBDD $bbdd): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM actividades ORDER BY actividad");
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaActividadException($e);
        }

        return $resultado;
    }

    public function recuperaActividadesAlumno(ConexionBBDD $bbdd, Alumno $alumno): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT alumnos.*, actividades.actividad FROM alumnos LEFT JOIN actividades_alumno ON alumnos.id = actividades_alumno.id_alumno LEFT JOIN actividades ON actividades.id = actividades_alumno.id_actividad WHERE alumnos.id = :id;");
            
            $id_alumno = $alumno->getId();

            $consulta->bindValue(":id", $id_alumno, PDO::PARAM_INT);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaActividadException($e);
        }

        return $resultado;
    }

    //FUNCIONES DE INSERCIÓN DE DATOS    
    /**
     * insertaAlumno
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return bool
     */
    public function insertaAlumno(ConexionBBDD $bbdd, Alumno $alumno): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("INSERT INTO alumnos (nombre, apellido1, apellido2, curso, grupo, f_nacimiento, alergias, id_tarifa) VALUES (:n, :a1, :a2, :c, :g, :f, :al, :t)");

            $nombre = $alumno->getNombre();
            $apellido1 = $alumno->getApellido1();
            $apellido2 = $alumno->getApellido2();
            $curso = $alumno->getCurso();
            $grupo = $alumno->getGrupo();
            $nacimiento = $alumno->getNacimiento();
            $alergias = $alumno->getAlergias();
            $id_tarifa = $alumno->getTarifa()->getId();

            $consulta->bindParam(':n', $nombre, PDO::PARAM_STR);
            $consulta->bindParam(':a1', $apellido1, PDO::PARAM_STR);
            $consulta->bindParam(':a2', $apellido2, PDO::PARAM_STR);
            $consulta->bindParam(':c', $curso, PDO::PARAM_STR);
            $consulta->bindParam(':g', $grupo, PDO::PARAM_STR);
            $consulta->bindParam(':f', $nacimiento, PDO::PARAM_STR);
            $consulta->bindParam(':al', $alergias, PDO::PARAM_STR);
            $consulta->bindValue(':t', $id_tarifa, PDO::PARAM_INT);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new InsertaAlumnoException($e);
        }
        return $resultado;
    }
    
    /**
     * insertaUsuario
     *
     * @param  ConexionBBDD $bbdd
     * @param  Usuario $usuario
     * @return bool
     */
    public function insertaUsuario(ConexionBBDD $bbdd, Usuario $usuario): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("INSERT INTO usuarios (nombre, apellido1, apellido2, email, pass, rol) VALUES (:n, :a1, :a2, :e, :p, :r)");

            $nombre = $usuario->getNombre();
            $apellido1 = $usuario->getApellido1();
            $apellido2 = $usuario->getApellido2();
            $email = $usuario->getEmail();
            $pass = $usuario->getPass();
            $rol = $usuario->getRol();

            $consulta->bindParam(':n', $nombre, PDO::PARAM_STR);
            $consulta->bindParam(':a1', $apellido1, PDO::PARAM_STR);
            $consulta->bindParam(':a2', $apellido2, PDO::PARAM_STR);
            $consulta->bindParam(':e', $email, PDO::PARAM_STR);
            $consulta->bindParam(':p', $pass, PDO::PARAM_STR);
            $consulta->bindParam(':r', $rol, PDO::PARAM_STR);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new InsertaUsuarioException($e);
        }
        return $resultado;
    }
    
    /**
     * insertaFalta
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return bool
     */
    public function insertaFalta(ConexionBBDD $bbdd, Alumno $alumno): bool{
        //Es posible que sea necesario comprobar que no existe una falta/asistencia ya
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("INSERT INTO faltas (id_alumno, f_falta, emisor_aviso, h_aviso) VALUES (:id, :ff, :ea, :ha)");
            
            $hoy = date('Y-m-d');
            $id = $alumno->getId();
            $f_falta = $alumno->getFalta($hoy)->getFecha();
            $emisor_aviso = $alumno->getFalta($hoy)->getEmisor();
            $h_aviso = $alumno->getFalta($hoy)->getHora();

            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->bindParam(":ff", $f_falta, PDO::PARAM_STR);
            $consulta->bindParam(":ea", $emisor_aviso, PDO::PARAM_STR);
            $consulta->bindParam(":ha", $h_aviso, PDO::PARAM_STR);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new InsertaFaltaException($e);
        }
        return $resultado;
    }
    
    /**
     * insertaAsistencia
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return bool
     */
    public function insertaAsistencia(ConexionBBDD $bbdd, Alumno $alumno): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("INSERT INTO asistencias (id_alumno, f_asistencia) VALUES (:id, :fa)");
    
            $hoy = date('Y-m-d');
            $id = $alumno->getId();
            $f_asistencia = $alumno->getAsistencia($hoy)->getFecha();

            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->bindParam(":fa", $f_asistencia, PDO::PARAM_STR);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new InsertaAsistenciaException($e);
        }
        return $resultado;
    }
    
    /**
     * insertaActividadAlumno
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return bool
     */
    public function insertaActividadAlumno(ConexionBBDD $bbdd, Alumno $alumno, Actividad $actividad): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("INSERT INTO actividades_alumno(id_alumno, id_actividad) VALUES(:id, :ac)");

            $id_alumno = $alumno->getId();
            $id_actividad = $actividad->getId();

            $consulta->bindValue(":id", $id_alumno, PDO::PARAM_INT);
            $consulta->bindValue(":ac", $id_actividad, PDO::PARAM_INT);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
            
        } catch (PDOException $e) {
            throw new InsertaActividadAlumnoException($e);
        }
        return $resultado;
    }
    
    /**
     * insertaActividad
     *
     * @param  ConexionBBDD $bbdd
     * @param  Actividad $actividad
     * @return bool
     */
    public function insertaActividad(ConexionBBDD $bbdd, Actividad $actividad): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("INSERT INTO actividades(actividad, h_inicio, h_fin, ubicacion, dias) VALUES(:ac, :hi, :hf, :u, :di)");

            $nombre = $actividad->getActividad();
            $h_inicio = $actividad->getHoraInicio();
            $h_fin = $actividad->getHoraFin();
            $ubicacion = $actividad->getUbicacion();
            $dias = $actividad->getDias();

            $consulta->bindParam(":ac", $nombre, PDO::PARAM_STR);
            $consulta->bindParam(":hi", $h_inicio, PDO::PARAM_STR);
            $consulta->bindParam(":hf", $h_fin, PDO::PARAM_STR);
            $consulta->bindParam(":u", $ubicacion, PDO::PARAM_STR);
            $consulta->bindParam(":di", $dias, PDO::PARAM_STR);

            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
            
        } catch (PDOException $e) {
            throw new InsertaActividadException($e);
        }
        return $resultado;
    }

    //FUNCIONES DE ACTUALIZACIÓN DE DATOS    
    /**
     * actualizaAlumno
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return bool
     */
    public function actualizaAlumno(ConexionBBDD $bbdd, Alumno $alumno): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("UPDATE alumnos SET nombre = :n, apellido1 = :a1, apellido2 = :a2, curso = :c, grupo = :g, f_nacimiento = :f, alergias = :al, id_tarifa = :t WHERE id = :id");

            $id = $alumno->getId();
            $nombre = $alumno->getNombre();
            $apellido1 = $alumno->getApellido1();
            $apellido2 = $alumno->getApellido2();
            $curso = $alumno->getCurso();
            $grupo = $alumno->getGrupo();
            $nacimiento = $alumno->getNacimiento();
            $alergias = $alumno->getAlergias();
            $id_tarifa = $alumno->getTarifa()->getId();

            $consulta->bindParam(':n', $nombre, PDO::PARAM_STR);
            $consulta->bindParam(':a1', $apellido1, PDO::PARAM_STR);
            $consulta->bindParam(':a2', $apellido2, PDO::PARAM_STR);
            $consulta->bindParam(':c', $curso, PDO::PARAM_STR);
            $consulta->bindParam(':g', $grupo, PDO::PARAM_STR);
            $consulta->bindParam(':f', $nacimiento, PDO::PARAM_STR);
            $consulta->bindParam(':al', $alergias, PDO::PARAM_STR);
            $consulta->bindValue(':t', $id_tarifa, PDO::PARAM_INT);
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ActualizaAlumnoException($e);
        }
        return $resultado;
    }
    
    /**
     * actualizaUsuario
     *
     * @param  ConexionBBDD $bbdd
     * @param  Usuario $usuario
     * @param  int $id_usuario
     * @return bool
     */
    public function actualizaUsuario(ConexionBBDD $bbdd, Usuario $usuario, int $id_usuario): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("UPDATE usuarios SET nombre = :n, apellido1 = :a1, apellido2 = :a2, email = :e, pass = :p, rol = :r WHERE id = :id");
            
            $nombre = $usuario->getNombre();
            $apellido1 = $usuario->getApellido1();
            $apellido2 = $usuario->getApellido2();
            $email = $usuario->getEmail();
            $pass = $usuario->getPass();
            $rol = $usuario->getRol();

            $consulta->bindParam(':n', $nombre, PDO::PARAM_STR);
            $consulta->bindParam(':a1', $apellido1, PDO::PARAM_STR);
            $consulta->bindParam(':a2', $apellido2, PDO::PARAM_STR);
            $consulta->bindParam(':e', $email, PDO::PARAM_STR);
            $consulta->bindParam(':p', $pass, PDO::PARAM_STR);
            $consulta->bindParam(':r', $rol, PDO::PARAM_STR);
            $consulta->bindValue(':id', $id_usuario, PDO::PARAM_INT);

            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ActualizaUsuarioException($e);
        }
        return $resultado;
    }
    
    /**
     * actualizaFalta
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return bool
     */
    public function actualizaFalta(ConexionBBDD $bbdd, Alumno $alumno): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("UPDATE falta SET f_falta = :ff, emisor_aviso = :ae, h_falta = :hf WHERE id = :id");

            $hoy = date('Y-m-d');
            $id = $alumno->getFalta($hoy)->getId();
            $f_falta = $alumno->getFalta($hoy)->getFecha();
            $emisor_aviso = $alumno->getFalta($hoy)->getEmisor();
            $h_aviso = $alumno->getFalta($hoy)->getHora();

            $consulta->bindParam(':ff', $f_falta, PDO::PARAM_STR);
            $consulta->bindParam(':ae', $emisor_aviso, PDO::PARAM_STR);
            $consulta->bindParam(':hf', $h_aviso, PDO::PARAM_STR);
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ActualizaFaltaException($e);
        }
        return $resultado;
    }
    
    /**
     * actualizaAsistencia
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return bool
     */
    public function actualizaAsistencia(ConexionBBDD $bbdd, Alumno $alumno): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("UPDATE asistencia SET f_asistencia = :fa WHERE id = :id");

            $hoy = date('Y-m-d');
            $id = $alumno->getAsistencia($hoy)->getId();
            $f_asistencia = $alumno->getAsistencia($hoy)->getFecha();

            $consulta->bindParam(':fa', $f_asistencia, PDO::PARAM_STR);
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ActualizaAsistenciaException($e);
        }
        return $resultado;
    }
    
    /**
     * actualizaActividad
     *
     * @param  ConexionBBDD $bbdd
     * @param  Actividad $actividad
     * @return bool
     */
    public function actualizaActividad(ConexionBBDD $bbdd, Actividad $actividad): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("UPDATE actividades SET actividad = :ac, h_inicio = :hi, h_fin = :hf, ubicacion = :u, dias = :di WHERE id = :id");

            $id = $actividad->getId();
            $nombre = $actividad->getActividad();
            $h_inicio = $actividad->getHoraInicio();
            $h_fin = $actividad->getHoraFin();
            $ubicacion = $actividad->getUbicacion();
            $dias = $actividad->getDias();

            $consulta->bindParam(':ac', $nombre, PDO::PARAM_STR);
            $consulta->bindParam(':hi', $h_inicio, PDO::PARAM_STR);
            $consulta->bindParam(':hf', $h_fin, PDO::PARAM_STR);
            $consulta->bindParam(':u', $ubicacion, PDO::PARAM_STR);
            $consulta->bindParam(':di', $dias, PDO::PARAM_STR);
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $resultado = $consulta->execute();
            $valor = $consulta->rowCount();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ActualizaActividadException($e);
        }
        return $resultado;
    }

    //FUNCIONES DE ELIMINACIÓN DE DATOS    
    /**
     * eliminaFaltas
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return bool
     */
    public function eliminaFaltas(ConexionBBDD $bbdd, Alumno $alumno): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("DELETE FROM faltas WHERE id = :id AND f_falta = :hoy");

            
            $hoy = date('Y-m-d');
            $id = $alumno->getFalta($hoy)->getId();

            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->bindParam(':hoy', $hoy, PDO::PARAM_STR);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new EliminaFaltaException($e);
        }
        return $resultado;
    }
    
    /**
     * eliminaAsistencias
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return bool
     */
    public function eliminaAsistencias(ConexionBBDD $bbdd, Alumno $alumno): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("DELETE FROM asistencias WHERE id = :id AND f_asistencia = :hoy");
            
            $hoy = date('Y-m-d');
            $id = $alumno->getAsistencia($hoy)->getId();

            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->bindParam(':hoy', $hoy, PDO::PARAM_STR);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new EliminaAsistenciaException($e);
        }
        return $resultado;
    }
    
    /**
     * eliminaAlumno
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return bool
     */
    public function eliminaAlumno(ConexionBBDD $bbdd, Alumno $alumno): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("DELETE FROM alumnos WHERE id = :id");
            
            $id = $alumno->getId();

            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new EliminaAlumnoException($e);
        }
        return $resultado;
    }
    
    /**
     * eliminaUsuario
     *
     * @param  ConexionBBDD $bbdd
     * @param  Usuario $usuario
     * @return bool
     */
    public function eliminaUsuario(ConexionBBDD $bbdd, Usuario $usuario): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("DELETE FROM usuarios WHERE email = :e");
            
            $email = $usuario->getEmail();

            $consulta->bindParam(':e', $email, PDO::PARAM_STR);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new EliminaUsuarioException($e);
        }
        return $resultado;
    }
    
    /**
     * eliminaActividadAlumno
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @param  Actividad $actividad
     * @return bool
     */
    public function eliminaActividadAlumno(ConexionBBDD $bbdd, Alumno $alumno, Actividad $actividad): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("DELETE FROM actividades_alumno WHERE id_alumno = :id AND id_actividad = :ac)");

            $id_alumno = $alumno->getId();
            $id_actividad = $actividad->getId();

            $consulta->bindValue(":id", $id_alumno, PDO::PARAM_INT);
            $consulta->bindValue(":ac", $id_actividad, PDO::PARAM_INT);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
            
        } catch (PDOException $e) {
            throw new EliminaActividadAlumnoException($e);
        }
        return $resultado;
    }
    
    /**
     * eliminaActividadesAlumno
     *
     * @param  ConexionBBDD $bbdd
     * @param  Alumno $alumno
     * @return bool
     */
    public function eliminaActividadesAlumno(ConexionBBDD $bbdd, Alumno $alumno): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("DELETE FROM actividades_alumno WHERE id_alumno = :id");

            $id_alumno = $alumno->getId();

            $consulta->bindValue(":id", $id_alumno, PDO::PARAM_INT);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
            
        } catch (PDOException $e) {
            throw new EliminaActividadAlumnoException($e);
        }
        return $resultado;
    }
    
    /**
     * eliminaActividad
     *
     * @param  ConexionBBDD $bbdd
     * @param  Actividad $actividad
     * @return bool
     */
    public function eliminaActividad(ConexionBBDD $bbdd, Actividad $actividad): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("DELETE FROM actividades WHERE id = :id");

            $id_actividad = $actividad->getId();

            $consulta->bindValue(":id", $id_actividad, PDO::PARAM_INT);
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
            
        } catch (PDOException $e) {
            throw new EliminaActividadException($e);
        }
        return $resultado;
    }

    //FUNCIONES DE CALCULO    
    /**
     * totalFaltasPeriodo
     *
     * @param  ConexionBBDD $bbdd
     * @param  int $id
     * @param  string $f_inicio
     * @param  string $f_fin
     * @return mixed
     */
    public function totalFaltasPeriodo(ConexionBBDD $bbdd, int $id, string $f_inicio, string $f_fin): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT COUNT(DISTINCT f_falta) FROM faltas WHERE id_alumno = :id AND f_falta >= :f_inicio AND f_falta <= :f_fin");

            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->bindParam(":f_inicio", $f_inicio, PDO::PARAM_STR);
            $consulta->bindParam(":f_fin", $f_fin, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchColumn();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaFaltasException($e);
        }
        return $resultado;
    }
        
    /**
     * totalAsistenciasPeriodo
     *
     * @param  ConexionBBDD $bbdd
     * @param  int $id
     * @param  string $f_inicio
     * @param  string $f_fin
     * @return mixed
     */
    public function totalAsistenciasPeriodo(ConexionBBDD $bbdd, int $id, string $f_inicio, string $f_fin): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT COUNT(DISTINCT f_asistencia) FROM asistencias WHERE id_alumno = :id AND f_asistencia >= :f_inicio AND f_asistencia <= :f_fin");

            $consulta->bindValue(":id", $id, PDO::PARAM_INT);
            $consulta->bindParam(":f_inicio", $f_inicio, PDO::PARAM_STR);
            $consulta->bindParam(":f_fin", $f_fin, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetchColumn();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new ConsultaAsistenciasException($e);
        }
        return $resultado;
    }

    //FUNCIÓN PARA RESETEO COMPLETO DE LA BASE (excepto usuarios)    
    /**
     * reseteaBBDD
     *
     * @param  ConexionBBDD $bbdd
     * @return bool
     */
    public function reseteaBBDD(ConexionBBDD $bbdd): bool{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("DELETE FROM alumnos; ALTER TABLE alumnos AUTO_INCREMENT =0; ALTER TABLE faltas AUTO_INCREMENT = 0; ALTER TABLE asistencias AUTO_INCREMENT = 0; DELETE FROM actividades; ALTER TABLE actividades AUTO_INCREMENT = 0");
            $resultado = $consulta->execute();
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
        } catch (PDOException $e) {
            throw new EliminaAlumnoException($e);
        }
        return $resultado;
    }
}