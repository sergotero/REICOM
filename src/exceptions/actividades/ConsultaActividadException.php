<?php

class ConsultaActividadException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "La actividad no se encuentra en la base de datos", int $codigo = CodigosError::ACTIVIDAD_CONSULTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}