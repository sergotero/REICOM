<?php

class ActualizaUsuarioException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar actualizar los datos del usuario en la base de datos", int $codigo = CodigosError::USUARIO_ACTUALIZAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}