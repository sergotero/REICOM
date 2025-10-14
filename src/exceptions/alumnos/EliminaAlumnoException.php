<?php
/**
 * Clase EliminaAlumnoExcepcion
 * 
 * {@inheritDoc} Se personaliza lanzando un mensaje propio que hace que sea más fácil se localizar el error.
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class EliminaAlumnoException extends Exception{
    
    /**
     * Constructor de la clase EliminaAlumnoException
     *
     * @param Throwable|null $previo
     * @param string $mensaje
     * @param int $codigo
     */
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar eliminar al alumno de la base de datos", int $codigo = CodigosError::ALUMNO_ELIMINAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}