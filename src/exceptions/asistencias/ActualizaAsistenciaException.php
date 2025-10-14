<?php
/**
 * Clase ActualizaAsistenciaExcepcion
 * 
 * {@inheritDoc} Se personaliza lanzando un mensaje propio que hace que sea más fácil se localizar el error.
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class ActualizaAsistenciaException extends Exception{
    
    /**
     * Constructor de la clase ActualizaAsistenciaException
     *
     * @param Throwable|null $previo
     * @param string $mensaje
     * @param int $codigo
     */
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar actualizar la asistencia del alumno en la base de datos", int $codigo = CodigosError::ASISTENCIA_ACTUALIZAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}