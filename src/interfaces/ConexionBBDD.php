<?php
/**
 * Interfaz ConexionBBDD
 * 
 * Clase con método a implementar con el que generar una conexión con una base de datos.
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
interface ConexionBBDD{

    /**
     * Genera una conxión con la base de datos.
     * 
     * Este método deberá ser implementado por todas aquellas clases que implementen la interfaz.
     *
     * @todo retornar una conexión con una base de datos
     */
    public function getConexion();
}