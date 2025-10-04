<?php

class ActualizaAlumnoException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar actualizar los datos del alumno en la base de datos", int $codigo = CodigosError::ALUMNO_ACTUALIZAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}