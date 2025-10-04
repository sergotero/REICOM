<?php

class CreaAlumnoException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al crear el alumno", int $codigo = CodigosError::ALUMNO_CREAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}