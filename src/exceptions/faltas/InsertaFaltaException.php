<?php

class InsertaFaltaException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar insertar la falta en la base de datos", int $codigo = CodigosError::FALTA_INSERTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}