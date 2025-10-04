<?php

class InsertaUsuarioException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar insertar al usuario en la base de datos", int $codigo = CodigosError::USUARIO_INSERTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}