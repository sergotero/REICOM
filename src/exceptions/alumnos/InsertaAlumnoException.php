<?php

class InsertaAlumnoException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar insertar al alumno en la base de datos", int $codigo = CodigosError::ALUMNO_INSERTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}