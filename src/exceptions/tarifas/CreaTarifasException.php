<?php

class CreaTarifasException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar crear la tarifa", int $codigo = CodigosError::TARIFA_CREAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}