<?php
/**
 * Clase ConsultaAsistenciaExcepcion
 * 
 * {@inheritDoc} Se personaliza lanzando un mensaje propio que hace que sea más fácil se localizar el error.
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class ConsultaAsistenciasException extends Exception{
    
    /**
     * Constructor de la clase ConsultaAsistenciaException
     *
     * @param Throwable|null $previo
     * @param string $mensaje
     * @param int $codigo
     */
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al realizar la consulta en la base de datos", int $codigo = CodigosError::ASISTENCIA_CONSULTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}