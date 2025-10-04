<?php

class Actividad{
    private ?int $id;
    private ?string $actividad;
    private ?string $h_inicio;
    private ?string $h_fin;
    private ?string $ubicacion;
    private ?string $dias;

    public function __construct(?string $actividad){
        $comprobacion = $this->compruebaActividad($actividad);
        if($comprobacion){
            $this->id = $comprobacion->id;
            $this->actividad = $comprobacion->actividad;
            $this->h_inicio = $comprobacion->h_inicio;
            $this->h_fin = $comprobacion->h_fin;
            $this->ubicacion = $comprobacion->ubicacion;
            $this->dias = $comprobacion->dias;
        } else{
            $this->id = null;
            $this->actividad = $actividad;
            $this->h_inicio = null;
            $this->h_fin = null;
            $this->ubicacion = null;
            $this->dias = null;
        }
        
    }

    //Getters

    public function getId(): ?int{
        return $this->id;
    }

    public function getActividad(): ?string{
        return $this->actividad;
    }

    public function getHoraInicio(): ?string{
        $h_inicio = substr($this->h_inicio,0,5);
        return $h_inicio;
    }

    public function getHoraFin(): ?string{
        $h_fin = substr($this->h_fin,0,5);
        return $h_fin;
    }

    public function getUbicacion(): ?string{
        return $this->ubicacion;
    }

    public function getDias(): ?string{
        return $this->dias;
    }

    //Setters

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setActividad(string $actividad): void{
        $this->actividad = $actividad;
    }

    public function setHoraInicio(?string $hora): void{
        $this->h_inicio = $hora;
    }

    public function setHoraFin(?string $hora): void{
        $this->h_fin = $hora;
    }

    public function setUbicacion(?string $ubicacion): void{
        $this->ubicacion = $ubicacion;
    }

    public function setDias(?string $dias): void{
        $this->dias = $dias;
    }

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
            throw new ConsultaActividadException("La actividad no se encuentra en la base de datos" . $e->getMessage());
        }

        return $resultado;
    }

    //FUNCIONES DE CASTING
    public static function cast(stdClass $actividad_recuperada): Actividad{
        $actividad = new Actividad(
            $actividad_recuperada->actividad
        );
        return $actividad;
    }

    public static function multicast(array $actividades_recuperadas): array{
        $nuevasActividades = [];
        foreach ($actividades_recuperadas as $actividad) {
            $nuevaActividad = self::cast($actividad);
            $nuevasActividades[] = $nuevaActividad;
        }
        return $nuevasActividades;
    }
}