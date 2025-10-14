<?php
/**
 * Clase InsertaActividadAlumnoExcepcion
 * 
 * {@inheritDoc} Se personaliza lanzando un mensaje propio que hace que sea más fácil se localizar el error.
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class InsertaActividadAlumnoException extends Exception{
    
    /**
     * Constructor de la clase InsertaActividadAlumnoException
     *
     * @param Throwable|null $previo
     * @param string $mensaje
     * @param int $codigo
     */
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar insertar la actividad de la base de datos", int $codigo = CodigosError::ACTIVIDAD_INSERTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}