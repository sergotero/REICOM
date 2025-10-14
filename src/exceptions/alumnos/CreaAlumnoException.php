<?php
/**
 * Clase CreaAlumnoExcepcion
 * 
 * {@inheritDoc} Se personaliza lanzando un mensaje propio que hace que sea más fácil se localizar el error.
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class CreaAlumnoException extends Exception{
    
    /**
     * Constructor de la clase CreaAlumnoException
     *
     * @param Throwable|null $previo
     * @param string $mensaje
     * @param int $codigo
     */
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al crear el alumno", int $codigo = CodigosError::ALUMNO_CREAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}