<?php

class ActualizaActividadException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error el intentar actualizar la actividad en la base de datos", int $codigo = CodigosError::ACTIVIDAD_ACTUALIZAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}