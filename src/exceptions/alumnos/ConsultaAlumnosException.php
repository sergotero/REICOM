<?php

class ConsultaAlumnosException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al realizar la consulta en la base de datos", int $codigo = CodigosError::ALUMNO_CONSULTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}