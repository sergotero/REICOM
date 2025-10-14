<?php
/**
 * Clase Conexion
 * 
 * Clase que se encarga de manejar la conexión con la base de datos.
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class Conexion{
    
    /**
     * Instancia de la propia clase.
     * 
     * Se trata de un atributo definido como static para garantizar que sólo se crea una única conexión con la base de datos.
     *
     * @var Conexion|null
     */
    private static ?Conexion $instancia = null;

    /**
     * Conexión con la base de datos.
     *
     * @var PDO
     */
    private PDO $conexion;

    /**
     * Constructor de la clase ConexionBBDD
     * 
     * Constructor privado cuyo único parámetro se trata de cualquier objeto que implemente la clase ConexionBBDD. De esta forma, se posibilitan las implementaciones en el futuro de conexiones con diferentes tipos de bases de datos.
     *
     * @param ConexionBBDD $bbdd
     * @throws PDOException
     */
    private function __construct(ConexionBBDD $bbdd) {

        //Se realiza la conexión con la base de datos dentro del propio constructor con la dependencia (ConexionBBDD) que le hemos pasado.
        try {
            $this->conexion = $bbdd->getConexion();
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    /**
     * Devuelve la instancia de la propia clase.
     * 
     * Este método nos permite comprobar si existe ya una instancia de la clase. Si existe, la devuelve y si no existe, la genera.
     *
     * @param ConexionBBDD $bbdd
     * @return Conexion
     */
    public static function getInstancia(ConexionBBDD $bbdd): Conexion {
        if (self::$instancia === null) {
            self::$instancia = new Conexion($bbdd);
        }
        return self::$instancia;
    }

    /**
     * Devuelve la conexión a la base de datos.
     *
     * @return PDO
     */
    public function getConexion(): PDO {
        return $this->conexion;
    }

    /**
     * Cierra la conexión con la base de datos.
     *
     * @return void
     */
    public function cerrarConexion(): void{
        self::$instancia = null;
    }
}