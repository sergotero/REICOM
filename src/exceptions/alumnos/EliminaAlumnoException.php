<?php

class EliminaAlumnoException extends Exception{
    public function __construct(?Throwable $previo, string $mensaje = "Se ha producido un error al intentar eliminar al alumno de la base de datos", int $codigo = CodigosError::ALUMNO_ELIMINAR){
        parent::__construct($mensaje, $codigo, $previo);
    }
}