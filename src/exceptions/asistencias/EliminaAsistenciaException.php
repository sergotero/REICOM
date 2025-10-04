<?php

class EliminaAsistenciaException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar eliminar la asistencia del alumno en la base de datos", int $codigo = CodigosError::ASISTENCIA_ELIMINAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}