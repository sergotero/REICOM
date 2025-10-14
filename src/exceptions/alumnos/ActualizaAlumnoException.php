<?php
/**
 * Clase ActualizaAlumnoExcepcion
 * 
 * {@inheritDoc} Se personaliza lanzando un mensaje propio que hace que sea más fácil se localizar el error.
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class ActualizaAlumnoException extends Exception{
    
    /**
     * Constructor de la clase ActualizaAlumnoException
     *
     * @param Throwable|null $previo
     * @param string $mensaje
     * @param int $codigo
     */
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar actualizar los datos del alumno en la base de datos", int $codigo = CodigosError::ALUMNO_ACTUALIZAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}