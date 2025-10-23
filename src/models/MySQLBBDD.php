<?php
/**
 * Clase MySQLBBDD
 * 
 * Se trata de una clase que implementa la interfaz ConexionBBDD para realizar conexiones a una base de datos de tipo MySQL. Los parámetros de la clase se definen de manera automática en el método getConexion().
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class MySQLBBDD implements ConexionBBDD{
    
    /**
     * Constructor de la clase MySQLBBDD
     * 
     */
    public function __construct(){
    }

    /**
     * Genera una conexión con la base de datos.
     * 
     * Dentro del método están definidos los parámetros necesarios para inicializar la conexión.
     *
     * @return PDO
     */
    public function getConexion(): PDO{
        $host = "localhost";
        $puerto = 3306;
        $charset = "utf8mb4";
        $nombre_base = "reicom";
        $usuario = "gestion_reicom";
        $contraseña = "oido_cocina123!";
        $dns = "mysql:host={$host};port={$puerto};dbname={$nombre_base};charset={$charset}";

        return new PDO($dns, $usuario, $contraseña);
    }
}