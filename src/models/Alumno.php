<?php

class Alumno {
    
    //Atributos
    private int $id;
    private string $nombre;
    private string $apellido1;
    private string $apellido2;
    private string $f_nacimiento;
    private string $curso;
    private string $grupo;
    private ?string $alergias;
    private Tarifa $tarifa;

    private array $grupos = ['A', 'B', 'C', 'D', 'E'];
    private array $cursos = ['4º Inf.', '5º Inf.', '6º Inf.', '1º Pri.', '2º Pri.', '3º Pri.', '4º Pri.', '5º Pri.', '6º Pri.'];
    private ?array $asistencias = [];
    private ?array $faltas = [];
    private ?array $actividades = [];

    //Constructor
    public function __construct(string $nombre, string $apellido1, string $apellido2, string $f_nacimiento, string $curso, string $grupo, ?string $alergias, float $valorTarifa){
        $this->nombre = $nombre;
        $this->apellido1 = $apellido1;
        $this->apellido2 = $apellido2;
        $this->f_nacimiento = $f_nacimiento;
        $this->compruebaCurso($curso);
        $this->compruebaGrupo($grupo);
        $this->alergias = $alergias;
        $tarifa = new Tarifa($valorTarifa);
        $this->tarifa = $tarifa;
    }

    //Getters

    public function getId(): int{
        return $this->id;
    }

    public function getNombre(): string{
        return $this->nombre;
    }
    
    public function getApellido1(): string{
        return $this->apellido1;
    }
    
    public function getApellido2(): string{
        return $this->apellido2;
    }

    public function getNombreCompleto(): string{
    return $this->nombre . " " . $this->apellido1 . " " . $this->apellido2;
    }
    
    public function getCurso(): string{
        return $this->curso;
    }

    public function getGrupo(): string{
        return $this->grupo;
    }
    
    public function getNacimiento(): string{
        return $this->f_nacimiento;
    }
    
    public function getAsistencias(): mixed{
        return $this->asistencias;
    }
    
    public function getFaltas(): mixed{
        return $this->faltas;
    }

    public function getAlergias(): ?string{
        return $this->alergias;
    }

    public function getTarifa(): Tarifa{
        return $this->tarifa;
    }

    public function getActividades(): mixed{
        return $this->actividades;
    }

    //Setters
    
    private function setId(int $nuevoId): void{
        $this->id = $nuevoId;
    }

    public function setNombre(string $nuevoNombre): void{
        $this->nombre = $nuevoNombre;
    }

    public function setApellido1(string $nuevoApellido1): void{
        $this->apellido1 = $nuevoApellido1;
    }

    public function setApellido2(string $nuevoApellido2): void{
        $this->apellido2 = $nuevoApellido2;
    }

    public function setCurso(string $nuevoCurso): void{
        $this->curso = $nuevoCurso;
    }

    public function setGrupo(string $nuevoGrupo): void{
        $this->grupo = $nuevoGrupo;
    }

    public function setNacimiento(string $nuevaFecha): void{
        $this->f_nacimiento = $nuevaFecha;
    }

    public function setAsistencias(array $nuevasAsistencias): void{
        $this->asistencias = $nuevasAsistencias;
    }

    public function setAsistencia(Asistencia $asistencia): void{
        $this->asistencias[] = $asistencia;
    }

    public function setFaltas(array $nuevasFaltas): void{
        $this->faltas = $nuevasFaltas;
    }

    public function setFalta(Falta $falta): void{
        $this->faltas[] = $falta;
    }

    public function setAlergias(?string $alergia): void{
        $this->alergias = $alergia;
    }

    public function setTarifa(Tarifa $tarifa): void{
        $this->tarifa = $tarifa;
    }

    public function setActividades(array $nuevasActividades): void{
        $this->actividades = $nuevasActividades;
    }

    public function setActividad(Actividad $actividad): void{
        $this->actividades[] = $actividad;
    }

    //FUNCIONES DE COMPROBACIÓN
    //Comprueba que el parámetro que se le pasa a la función (curso), se encuentre entre los valores almacenados en el array. Si no lo está devuelve una excepción, si lo está usa el setter para darle el nuevo valor.
    public function compruebaCurso(string $curso): void{
        $curso = ucfirst($curso);
        if(in_array($curso, $this->cursos)){
            $this->setCurso($curso);
        } else {
            throw new CompruebaAlumnosException(null,"El curso no existe en la base de datos");
        }
    }

    //Comprueba que el parámetro que se le pasa a la función (grupo), se encuentre entre los valores almacenados en el array. Si no lo está devuelve una excepción, si lo está usa el setter para darle el nuevo valor.
    public function compruebaGrupo(string $grupo): void{
        $grupo = ucfirst($grupo);
        if(in_array($grupo, $this->grupos)){
            $this->setGrupo($grupo);
        } else {
            throw new CompruebaAlumnosException(null, "El grupo no existe en la base de datos");
        }
    }

    //FUNCIONES PROPIAS
    //Devuelve el registro de asistencias a partir de una fecha concreta. Si no hay ninguna devuelve null.
    public function getAsistencia(string $fecha): mixed{
        if($this->asistencias != null){
            foreach($this->asistencias as $asistencia){
                if($asistencia->getFecha() == $fecha){
                    return $asistencia;
                }
            }
        }
        return null;
    }
    //Devuelve el último registro de asistencias de la base de datos
    // public function getUltimaAsistencia(): mixed{
    //     $asistencias = $this->asistencias;
    //     if(count($asistencias)>0){
    //         return $asistencias[count($asistencias)-1];
    //     } else {
    //         return null;
    //     }
    // }
    
    //Devuelve el registro de faltas a partir de una fecha concreta. Si no hay ninguna devuelve null.
    public function getFalta(string $fecha): mixed{
        if($this->faltas != null){
            foreach($this->faltas as $falta){
                if($falta->getFecha() == $fecha){
                    return $falta;
                }
            }
        }
        return null;
    }

    //Devuelve un array indexado con fechas o valores null (en caso de que no haya fecha)
    public function getCalendario(): array{
        $calendario = [1 => "", 2 => "", 3 => "", 4 => "", 5 => "", 6 => "", 7 => "", 8 => "", 9 => "", 10 => "", 11 => "", 12 => "", 13 => "", 14 => "", 15 => "", 16 => "", 17 => "", 18 => "", 19 => "", 20 => "", 21 => "", 22 => "", 23 => "", 24 => "", 25 => "", 26 => "", 27 => "", 28 => "", 29 => "", 30 => "", 31 => ""];
        
        foreach ($calendario as $dia => $fecha) {
            //Comprobamos si existen faltas
            if($this->faltas == null){
                continue;
            } else{
                //Si existen recorremos el array
                foreach ($this->faltas as $falta) {
                    //Nos devuelve un string o un null
                    $dia_falta = $this->getDia($falta);
                    if(($dia_falta != null) && ($dia_falta == $dia)){
                        $calendario[$dia] = $falta->getFecha();
                    } else {
                        continue;
                    }
                }
                
            }
        }
        return $calendario;
    }

    //Devuelve el día de una fecha (en formato string)
    private function getDia(?Falta $fecha): ?string{
        if($fecha == null){
            return null;
        } else{
            $array = explode("-", $fecha->getFecha());
            $dia = $array[2];
            return $dia;
        }
    }
    
    //Devuelve el último registro de faltas de la base de datos
    // public function getUltimaFalta(): mixed{
    //     $faltas = $this->faltas;
    //     if(count($faltas)>0){
    //         return $faltas[count($faltas)-1];
    //     } else {
    //         return null;
    //     }
    // }

    //Permite generar un array de Alumnos a partir de un objeto SplFileObject que contiene datos en formato CSV
    public static function creaDesdeCSV(SplFileObject $csv): array{
        $alumnos = [];
        foreach ($csv as $index => $fila) {
            
            //Este paso nos permite saltarnos el encabezado del CSV y las filas vacías.
            if($index == 0 || empty($fila)){
                continue;
            }
            
            $nombre = ucfirst(trim($fila[0]));
            $apellido1 = ucfirst(trim($fila[1]));
            $apellido2 = ucfirst(trim($fila[2]));
            $curso = trim($fila[3]);
            $grupo = ucfirst(trim($fila[4]));
            $f_nacimiento = trim($fila[5]);
            $alergias = ucfirst(trim($fila[6]));
            if($alergias == '' || $alergias == 'null'){
                $alergias = null;
            }
            $tarifa = floatval($fila[7]);

            
            try {
                $alumno = new Alumno($nombre, $apellido1, $apellido2, $f_nacimiento, $curso, $grupo, $alergias, $tarifa);
            } catch (CompruebaAlumnosException $e) {
                throw new CreaAlumnoException($e);
            }

            $alumnos[] = $alumno;
            
            //Este paso evitará que se añada la fila vacía al final del archivo.
            if($csv->eof()){
                break;
            }
        }
        
        return $alumnos;
    }

    //Devuelve las actividades de un Alumno en formato string
    public function actividadesToString(): string{
        $texto = "";
        $actividades = $this->getActividades();
        if($actividades != null){

            for ($i=0; $i < count($actividades); $i++) { 
                if($i == (count($actividades)-1)){
                    $texto .= $actividades[$i]->getActividad();
                } else{
                    $texto .= $actividades[$i]->getActividad() . ", ";
                }
            }
            
        }
        return $texto;
    }

    public static function ultimoId(ConexionBBDD $bbdd): mixed{
        try {
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT id FROM alumnos ORDER BY id DESC LIMIT 1;");
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
            
        } catch (PDOException $e) {
            throw new ConsultaAlumnosException($e);
        }
        return $resultado;
    }

    //FUNCIONES DE FORMATO
    //Toma una fecha con formato YYY-MM-DD y se encarga de transformarlo en DD/MM/AAAA
    public static function formatoFecha(string $fecha): string{
        $nuevaFecha = explode("-", $fecha);
        $fechaFormateada = $nuevaFecha[2] . "/" . $nuevaFecha[1] . "/" .$nuevaFecha[0];
        return $fechaFormateada;
    }

    //FUNCIONES DE CASTING
    public static function cast(stdClass $alumno_recuperado): Alumno{
        $alumno = new Alumno(
            $alumno_recuperado->nombre,
            $alumno_recuperado->apellido1,
            $alumno_recuperado->apellido2,
            $alumno_recuperado->f_nacimiento,
            $alumno_recuperado->curso,
            $alumno_recuperado->grupo,
            $alumno_recuperado->alergias,
            $alumno_recuperado->tarifa
        );
        $alumno->setId($alumno_recuperado->id);
        return $alumno;
    }

    public static function multicast(array $alumnos_recuperados): array{
        $nuevosAlumnos = [];
        foreach ($alumnos_recuperados as $alumno) {
            $nuevoAlumno = self::cast($alumno);
            $nuevosAlumnos[] = $nuevoAlumno;
        }
        return $nuevosAlumnos;
    }
}