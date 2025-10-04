<?php

class MySQLBBDD implements ConexionBBDD{
    
    public function __construct(){
    }

    public function getConexion(): PDO{
        $host = "localhost";
        $puerto = 3308;
        $charset = "utf8mb4";
        $nombre_base = "reicom";
        $usuario = "gestion_reicom";
        $contraseña = "oido_cocina123!";
        $dns = "mysql:host={$host};port={$puerto};dbname={$nombre_base};charset={$charset}";

        return new PDO($dns, $usuario, $contraseña);
    }
}