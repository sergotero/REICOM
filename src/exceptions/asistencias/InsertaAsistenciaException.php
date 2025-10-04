<?php

class InsertaAsistenciaException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar insertar la asistencia en la base de datos", int $codigo = CodigosError::ASISTENCIA_INSERTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}