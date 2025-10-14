<?php
/**
 * Clase EliminaFaltaExcepcion
 * 
 * {@inheritDoc} Se personaliza lanzando un mensaje propio que hace que sea más fácil se localizar el error.
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class EliminaFaltaException extends Exception{
    
    /**
     * Constructor de la clase EliminaFaltaException
     *
     * @param Throwable|null $previo
     * @param string $mensaje
     * @param int $codigo
     */
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar eliminar la falta del alumno en la base de datos", int $codigo = CodigosError::FALTA_ELIMINAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}