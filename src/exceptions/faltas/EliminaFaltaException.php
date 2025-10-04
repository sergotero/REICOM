<?php

class EliminaFaltaException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar eliminar la falta del alumno en la base de datos", int $codigo = CodigosError::FALTA_ELIMINAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}