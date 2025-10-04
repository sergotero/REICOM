<?php

class ActualizaFaltaException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar actualizar la falta del alumno en la base de datos", int $codigo = CodigosError::FALTA_ACTUALIZAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}