<?php
/**
 * Clase Asistencia
 * 
 * Representa una actividad extraescolar en el sistema. Gestiona información básica como identificador y fecha de la asistencia.
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class Asistencia{
    
    /**
     * Identificador único de la asistencia
     *
     * @var integer
     */
    private int $id_asistencia;
    
    /**
     * Fecha de la asistencia.
     *
     * @var string
     */
    private string $f_asistencia;

    /**
     * Constructor de la clase Asistencia.
     *
     * @param string $f_asistencia
     */
    public function __construct(string $f_asistencia){
        $this->f_asistencia = $f_asistencia;
    }

    /**
     * Devuelve el identificador de la asistencia.
     *
     * @return integer
     */
    public function getId(): int{
        return $this->id_asistencia;
    }

    /**
     * Devuelve la fecha de la asistencia.
     *
     * @return string
     */
    public function getFecha(): string{
        return $this->f_asistencia;
    }
    
    /**
     * Establece el id de la asistencia.
     *
     * @param integer $nuevoId
     * @return void
     */
    private function setId(int $nuevoId): void{
        $this->id_asistencia = $nuevoId;
    }

    /**
     * Establece la fecha de la asistencia.
     *
     * @param string $nuevaFecha
     * @return void
     */
    public function setFecha(string $nuevaFecha): void{
        $this->f_asistencia = $nuevaFecha;
    }

    /**
     * Genera una nueva Asistencia a partir de un objeto recuperado de la base de datos.
     *
     * @param stdClass $asistencia_recuperada
     * @return Asistencia
     */
    public static function cast(stdClass $asistencia_recuperada): Asistencia{
        $asistencia = new Asistencia(
            $asistencia_recuperada->f_asistencia,
        );
        $asistencia->setId($asistencia_recuperada->id);
        return $asistencia;
    }

    /**
     * Genera un array de Actividades a partir de un array de objetos recuperados de la base de datos.
     *
     * @param array $asistencias_recuperadas
     * @return Asistencia[]
     */
    public static function multicast(array $asistencias_recuperadas): array{
        $nuevasAsistencias = [];
        foreach ($asistencias_recuperadas as $asistencia) {
            $nuevaAsistencia = self::cast($asistencia);
            $nuevasAsistencias[] = $nuevaAsistencia;
        }
        return $nuevasAsistencias;
    }

}