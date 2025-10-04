<?php

class ActualizaAsistenciaException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar actualizar la asistencia del alumno en la base de datos", int $codigo = CodigosError::ASISTENCIA_ACTUALIZAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}