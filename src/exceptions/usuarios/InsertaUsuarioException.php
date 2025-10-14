<?php
/**
 * Clase InsertaUsuarioExcepcion
 * 
 * {@inheritDoc} Se personaliza lanzando un mensaje propio que hace que sea más fácil se localizar el error.
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class InsertaUsuarioException extends Exception{
    
    /**
     * Constructor de la clase InsertaUsuarioException
     *
     * @param Throwable|null $previo
     * @param string $mensaje
     * @param int $codigo
     */
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar insertar al usuario en la base de datos", int $codigo = CodigosError::USUARIO_INSERTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}