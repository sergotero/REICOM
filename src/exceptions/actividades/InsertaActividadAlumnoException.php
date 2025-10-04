<?php

class InsertaActividadAlumnoException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar insertar la actividad de la base de datos", int $codigo = CodigosError::ACTIVIDAD_INSERTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}