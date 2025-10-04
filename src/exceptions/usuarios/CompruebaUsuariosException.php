<?php

class CompruebaUsuariosException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error durante la comprobación en la base de datos", int $codigo = CodigosError::USUARIO_COMPROBAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}