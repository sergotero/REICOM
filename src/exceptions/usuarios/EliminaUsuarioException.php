<?php

class EliminaUsuarioException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar eliminar al usuario de la base de datos", int $codigo = CodigosError::USUARIO_ELIMINAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}