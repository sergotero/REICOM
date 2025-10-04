<?php

class ConsultarTarifasException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al realizar la consulta a la base de datos", int $codigo = CodigosError::TARIFA_CONSULTAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}