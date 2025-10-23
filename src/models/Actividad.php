<?php
/**
 * Clase Actividad
 * 
 * Representa una actividad extraescolar en el sistema. Gestiona información básica como identificador, nombre de la actividad, hora de inicio, hora de fin, ubicación y días en las que se lleva a cabo.
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class Actividad{
    
    /**
     * Identificador único de la actividad
     *
     * @var integer|null
     */
    private ?int $id;

    /**
     * Nombre de la actividad
     *
     * @var string
     */
    private string $actividad;

    /**
     * Hora de inicio de la actividad
     *
     * @var string|null
     */
    private ?string $h_inicio;
    
    /**
     * Hora de finalización de la actividad
     *
     * @var string|null
     */
    private ?string $h_fin;
    
    /**
     * Lugar en el que se realiza la actividad
     *
     * @var string|null
     */
    private ?string $ubicacion;
    
    /**
     * Días en los que se realiza la actividad
     *
     * @var array|null
     */
    // private ?string $dias;
        private ?array $dias;

    /**
     * Constructor de la clase Actividad
     *
     * @param string|null $actividad
     */
    public function __construct(?string $actividad){
        $comprobacion = $this->compruebaActividad($actividad);
        if($comprobacion){
            $this->id = $comprobacion->id;
            $this->actividad = $comprobacion->actividad;
            $this->h_inicio = $comprobacion->h_inicio;
            $this->h_fin = $comprobacion->h_fin;
            $this->ubicacion = $comprobacion->ubicacion;
            $this->setDias($comprobacion->dias);
        } else{
            $this->id = null;
            $this->actividad = $actividad;
            $this->h_inicio = null;
            $this->h_fin = null;
            $this->ubicacion = null;
            $this->dias = null;
        }
    }

    /**
     * Devuelve el identificador de la actividad
     *
     * @return integer|null
     */
    public function getId(): ?int{
        return $this->id;
    }
    
    /**
     * Devuelve el nombre de la actividad
     *
     * @return string|null
     */
    public function getActividad(): ?string{
        return $this->actividad;
    }

    /**
     * Devuelve la hora de inicio de la actividad
     *
     * @return string|null
     */
    public function getHoraInicio(): ?string{
        $h_inicio = substr($this->h_inicio,0,5);
        return $h_inicio;
    }

    /**
     * Devuelve la hora de finalización de la actividad
     *
     * @return string|null
     */
    public function getHoraFin(): ?string{
        $h_fin = substr($this->h_fin,0,5);
        return $h_fin;
    }

    /**
     * Devuelve el lugar en el que se realiza la actividad
     *
     * @return string|null
     */
    public function getUbicacion(): ?string{
        return $this->ubicacion;
    }

    /**
     * Devuelve los días en los que se lleva a cabo la actividad
     *
     * @return array|null
     */
    // public function getDias(): ?string{
    //     return $this->dias;
    // }
    public function getDias(): ?array {
        return $this->dias;
    }

    /**
     * Establece el identificador de la actividad
     *
     * @param integer $id
     * @return void
     */
    public function setId(int $id): void{
        $this->id = $id;
    }

    /**
     * Establece el nombre de la actividad
     *
     * @param string $actividad
     * @return void
     */
    public function setActividad(string $actividad): void{
        $this->actividad = $actividad;
    }

    /**
     * Establece la hora de inicio de la actividad
     *
     * @param string|null $hora
     * @return void
     */
    public function setHoraInicio(?string $hora): void{
        $this->h_inicio = $hora;
    }

    /**
     * Establece la hora de finalización de la actividad
     *
     * @param string|null $hora
     * @return void
     */
    public function setHoraFin(?string $hora): void{
        $this->h_fin = $hora;
    }

    /**
     * Establece el lugar en el que tiene lugar la actividad
     *
     * @param string|null $ubicacion
     * @return void
     */
    public function setUbicacion(?string $ubicacion): void{
        $this->ubicacion = $ubicacion;
    }

    /**
     * Establece los días en los que se desarrolla la actividad
     *
     * @param string|null $dias
     * @return void
     */
    public function setDias(?string $dias): void{
        $dias = explode(", ", $dias);
        $this->dias = $dias;
    }

    /**
     * Comprueba la existencia de una actividad en la base de datos
     *
     * @param string|null $actividad
     * @throws ConsultaActividadException si se produce algún problema al realizar la consulta sobre la tabla actividades.
     * @return mixed
     */
    private function compruebaActividad(?string $actividad): mixed{
        try {
            $bbdd = new MySQLBBDD();
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT * FROM actividades WHERE actividad = :a");
            $consulta->bindParam(":a", $actividad, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
            $bbdd = null;
        } catch (PDOException $e) {
            throw new ConsultaActividadException($e);
        }
        return $resultado;
    }

    /**
     * Genera una nueva Actividad a partir de un objeto recuperado de la base de datos
     *
     * @param stdClass $actividad_recuperada
     * @return Actividad
     */
    public static function cast(stdClass $actividad_recuperada): Actividad{
        $actividad = new Actividad(
            $actividad_recuperada->actividad
        );
        return $actividad;
    }

    /**
     * Genera un array de Actividades a partir de un array de objetos recuperados de la base de datos
     *
     * @param array $actividades_recuperadas
     * @return Actividad[]
     */
    public static function multicast(array $actividades_recuperadas): array{
        $nuevasActividades = [];
        foreach ($actividades_recuperadas as $actividad) {
            $nuevaActividad = self::cast($actividad);
            $nuevasActividades[] = $nuevaActividad;
        }
        return $nuevasActividades;
    }
}