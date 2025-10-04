<?php

class Asistencia{
    //Atributos
    private int $id_asistencia;
    private string $f_asistencia;

    //Constructor
    public function __construct(string $f_asistencia){
        $this->f_asistencia = $f_asistencia;
    }

    //Getters & Setters
    public function getId(): int{
        return $this->id_asistencia;
    }

    public function getFecha(): string{
        return $this->f_asistencia;
    }
    
    private function setId(int $nuevoId): void{
        $this->id_asistencia = $nuevoId;
    }

    public function setFecha($nuevaFecha): void{
        $this->f_asistencia = $nuevaFecha;
    }

    //FUNCIONES DE CASTING
    public static function cast(stdClass $asistencia_recuperada): Asistencia{
        $asistencia = new Asistencia(
            $asistencia_recuperada->f_asistencia,
        );
        $asistencia->setId($asistencia_recuperada->id);
        return $asistencia;
    }

    public static function multicast(array $asistencias_recuperadas): array{
        $nuevasAsistencias = [];
        foreach ($asistencias_recuperadas as $asistencia) {
            $nuevaAsistencia = self::cast($asistencia);
            $nuevasAsistencias[] = $nuevaAsistencia;
        }
        return $nuevasAsistencias;
    }

}