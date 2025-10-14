<?php
/**
 * Clase Falta
 * 
 * Representa una actividad extraescolar en el sistema. Gestiona información básica como el identificador, la fecha de la falta, la hora a la que se realiza el aviso y la persona que notifica la falta.
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class Falta{
    /**
     * Identificador único de la falta.
     *
     * @var integer
     */
    private int $id_falta;
    
    /**
     * Fecha de la falta.
     *
     * @var string|null
     */
    private ?string $f_falta;
    
    /**
     * Hora a la que se produce el aviso
     *
     * @var string|null
     */
    private ?string $h_aviso;

    /**
     * Persona que notifica el aviso.
     *
     * @var string|null
     */
    private ?string $emisor_aviso;

    /**
     * Constructor de la clase Falta
     *
     * @param string $fecha
     * @param string|null $emisor
     * @param string|null $hora
     */
    public function __construct(string $fecha, ?string $emisor, ?string $hora){
        $this->f_falta = $fecha;
        $this->emisor_aviso = $emisor;
        $this->h_aviso = $hora;
    }

    /**
     * Devuelve el identificador de la falta.
     *
     * @return integer
     */
    public function getId(): int{
        return $this->id_falta;
    }

    /**
     * Devuelve la fecha de la falta.
     *
     * @return string
     */
    public function getFecha(): string{
        return $this->f_falta;
    }

    /**
     * Devuelve el emisor de la falta.
     *
     * @return string|null
     */
    public function getEmisor(): ?string{
        return $this->emisor_aviso;
    }

    /**
     * Devuelve la hora de la falta.
     *
     * @return string|null
     */
    public function getHora(): ?string{
        return $this->h_aviso;
    }

    /**
     * Establece el identificador de la falta.
     *
     * @param integer $nuevoId
     * @return void
     */
    private function setId(int $nuevoId): void{
        $this->id_falta = $nuevoId;
    }

    /**
     * Establece la fecha de la falta.
     *
     * @param string $nuevaFecha
     * @return void
     */
    public function setFecha(string $nuevaFecha): void{
        $this->f_falta = $nuevaFecha;
    }

    /**
     * Establece el emisor de la falta.
     *
     * @param string $nuevoEmisor
     * @return void
     */
    public function setEmisor(string $nuevoEmisor): void{
        $this->emisor_aviso = $nuevoEmisor;
    }

    /**
     * Establece la hora de la falta.
     *
     * @param string $nuevaHora
     * @return void
     */
    public function setHora(string $nuevaHora): void{
        $this->h_aviso = $nuevaHora;
    }

    /**
     * Genera una nueva Falta a partir de un objeto recuperado de la base de datos
     *
     * @param stdClass $falta_recuperada
     * @return Falta
     */
    public static function cast(stdClass $falta_recuperada): Falta{
        $falta = new Falta(
            $falta_recuperada->f_falta,
            $falta_recuperada->emisor_aviso,
            $falta_recuperada->h_aviso
        );
        $falta->setId($falta_recuperada->id);
        return $falta;
    }

    /**
     * Genera un array de Faltas a partir de un array de objetos recuperados de la base de datos
     *
     * @param array $faltas_recuperadas
     * @return Falta[]
     */
    public static function multicast(array $faltas_recuperadas): array{
        $nuevasFaltas = [];
        foreach ($faltas_recuperadas as $falta) {
            $nuevaFalta = self::cast($falta);
            $nuevasFaltas[] = $nuevaFalta;
        }
        return $nuevasFaltas;
    }

}