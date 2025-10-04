<?php

class CompruebaAlumnosException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al realizar la consulta", int $codigo = CodigosError::ALUMNO_COMPROBAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}