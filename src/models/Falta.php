<?php

class Falta{
    //Atributos
    private int $id_falta;
    private ?string $f_falta;
    private ?string $h_aviso;
    private ?string $emisor_aviso;

    //Constructor
    public function __construct(string $fecha, ?string $emisor, ?string $hora){
        $this->f_falta = $fecha;
        $this->emisor_aviso = $emisor;
        $this->h_aviso = $hora;
    }

    //Getters & Setters

    public function getId(): int{
        return $this->id_falta;
    }

    public function getFecha(): string{
        return $this->f_falta;
    }

    public function getEmisor(): ?string{
        return $this->emisor_aviso;
    }

    public function getHora(): ?string{
        return $this->h_aviso;
    }

    private function setId(int $nuevoId): void{
        $this->id_falta = $nuevoId;
    }

    public function setFecha(string $nuevaFecha): void{
        $this->f_falta = $nuevaFecha;
    }

    public function setEmisor(string $nuevoEmisor): void{
        $this->emisor_aviso = $nuevoEmisor;
    }

    public function setHora(string $nuevaHora): void{
        $this->h_aviso = $nuevaHora;
    }

    //FUNCIONES DE CASTING
    public static function cast(stdClass $falta_recuperada): Falta{
        $falta = new Falta(
            $falta_recuperada->f_falta,
            $falta_recuperada->emisor_aviso,
            $falta_recuperada->h_aviso
        );
        $falta->setId($falta_recuperada->id);
        return $falta;
    }

    public static function multicast(array $faltas_recuperadas): array{
        $nuevasFaltas = [];
        foreach ($faltas_recuperadas as $falta) {
            $nuevaFalta = self::cast($falta);
            $nuevasFaltas[] = $nuevaFalta;
        }
        return $nuevasFaltas;
    }

}