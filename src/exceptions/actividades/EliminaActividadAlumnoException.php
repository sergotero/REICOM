<?php

class EliminaActividadAlumnoException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar eliminar la actividad de la base de datos", int $codigo = CodigosError::ACTIVIDAD_ELIMINAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}