<?php

class Conexion{
    private static ?Conexion $instancia = null;
    private PDO $conexion;

    // Constructor privado con inyección de dependencias (se le pasa la interfaz ConexionBBDD)
    private function __construct(ConexionBBDD $bbdd) {

        //Se realiza la conexión con la base de datos dentro del propio constructor con la dependencia (ConexionBBDD) que le hemos pasado.
        try {
            $this->conexion = $bbdd->getConexion();
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    //Este método nos permite comprobar si existe ya una instancia de la clase
    public static function getInstancia(ConexionBBDD $bbdd): Conexion {
        if (self::$instancia === null) {
            self::$instancia = new Conexion($bbdd);
        }
        return self::$instancia;
    }

    public function getConexion(): PDO {
        return $this->conexion;
    }

    public function cerrarConexion(): void{
        self::$instancia = null;
    }
}