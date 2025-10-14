<?php
/**
 * Clase CompruebaAlumnoExcepcion
 * 
 * {@inheritDoc} Se personaliza lanzando un mensaje propio que hace que sea más fácil se localizar el error.
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class CompruebaAlumnosException extends Exception{
    
    /**
     * Constructor de la clase CompruebaAlumnoException
     *
     * @param Throwable|null $previo
     * @param string $mensaje
     * @param int $codigo
     */
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al realizar la consulta", int $codigo = CodigosError::ALUMNO_COMPROBAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}