<?php
/**
 * Clase ConsultaActividadExcepcion
 * 
 * {@inheritDoc} Se personaliza lanzando un mensaje propio que hace que sea más fácil se localizar el error.
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class ConsultaActividadException extends Exception{
    
    /**
     * Constructor de la clase ConsultaActividadException
     *
     * @param Throwable|null $previo
     * @param string $mensaje
     * @param int $codigo
     */
    public function __construct(?Throwable $previo, string $mensaje = "La actividad no se encuentra en la base de datos", int $codigo = CodigosError::ACTIVIDAD_CONSULTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}