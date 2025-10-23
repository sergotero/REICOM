<?php
/**
 * Clase Alumno
 * 
 * Representa a un alumno en el sistema. Gestiona información básica como el identificador, el nombre, los apellidos, la fecha de nacimiento, el curso, el grupo, las alergias y la tarifa del comedor.
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class Alumno {
    
    /**
     * Identificador único del alumno.
     *
     * @var integer
     */
    private int $id;

    /**
     * Nombre del alumno.
     *
     * @var string
     */
    private string $nombre;
    
    /**
     * Primer apellido del alumno.
     *
     * @var string
     */
    private string $apellido1;
    
    /**
     * Segundo apellido del alumno.
     *
     * @var string
     */
    private string $apellido2;
    
    /**
     * Fecha de nacimiento del alumno.
     *
     * @var string
     */
    private string $f_nacimiento;
    
    /**
     * Curso en el que se encuentra el alumno.
     *
     * @var string
     */
    private string $curso;
    
    /**
     * Grupo al que pertenece el alumno.
     *
     * @var string
     */
    private string $grupo;
    
    /**
     * Alergias e intolerancias desarrolladas por el alumno.
     *
     * @var string|null
     */
    private ?string $alergias;
    
    /**
     * Tarifa que se le aplica al alumno por los servicios de comedor.
     *
     * @var Tarifa
     */
    private Tarifa $tarifa;

    

    /**
     * Conjunto de grupos válidos permitidos para la validación de usuarios.
     * 
     * Esta propiedad se utiliza únicamente para comprobar si el grupo asignado a un usuario pertenece al conjunto permitido definido en la base.
     *
     * @var string[]
     */
    private array $grupos = ['A', 'B', 'C', 'D', 'E'];
    
    /**
     * Conjunto de cursos válidos permitidos para la validación de usuarios.
     * 
     * Esta propiedad se utiliza únicamente para comprobar si el curso asignado a un usuario pertenece al conjunto permitido definido en la base.
     *
     * @var array
     */
    private array $cursos = ['4º Inf.', '5º Inf.', '6º Inf.', '1º Pri.', '2º Pri.', '3º Pri.', '4º Pri.', '5º Pri.', '6º Pri.'];
    
    /**
     * Almacena las asistencias registradas en la base de datos vinculadas al alumno.
     * 
     * Puede contener elementos nulos en el hipotético caso de que un alumno no acuda nunca al centro o que haya sido dado de alta pero al que no se la haya creado todavía ninguna asistencia.
     *
     * @var Asistencia[]|null
     */
    private ?array $asistencias = [];
    
    /**
     * Almacena las faltas registradas en la base de datos vinculadas al alumno.
     * 
     * Puede contener elementos nulos en el hipotético caso de que un alumno nunca falte al centro.
     *
     * @var Falta[]|null
     */
    private ?array $faltas = [];
    
    /**
     * Almacena las actividades extraescolares en las que participa el alumno.
     * 
     * Puede contener elementos nulos dado que los alumnos no están obligados a participar en ninguna actividad.
     *
     * @var Actividad[]|null
     */
    private ?array $actividades = [];

    /**
     * Constructor de la clase Alumno.
     *
     * @param string $nombre
     * @param string $apellido1
     * @param string $apellido2
     * @param string $f_nacimiento
     * @param string $curso
     * @param string $grupo
     * @param string|null $alergias
     * @param float $valorTarifa
     */
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

    /**
     * Devuelve el identificador único del alumno.
     *
     * @return integer
     */
    public function getId(): int{
        return $this->id;
    }

    /**
     * Devuelve el nombre del alumno.
     *
     * @return string
     */
    public function getNombre(): string{
        return $this->nombre;
    }
    
    /**
     * Devuelve el primer apellido del alumno.
     *
     * @return string
     */
    public function getApellido1(): string{
        return $this->apellido1;
    }
    
    /**
     * Devuelve el segundo apellido del alumno.
     *
     * @return string
     */
    public function getApellido2(): string{
        return $this->apellido2;
    }

    /**
     * Devuelve el nombre completo del alumno.
     * 
     * Realiza una concatenación del nombre, primer apellido y segundo apellido del alumno y la devuelve.
     *
     * @return string
     */
    public function getNombreCompleto(): string{
    return $this->nombre . " " . $this->apellido1 . " " . $this->apellido2;
    }
    
    /**
     * Devuelve el curso al que pertenece el alumno.
     *
     * @return string
     */
    public function getCurso(): string{
        return $this->curso;
    }

    /**
     * Devuelve el grupo al que pertenece el alumno.
     *
     * @return string
     */
    public function getGrupo(): string{
        return $this->grupo;
    }
    
    /**
     * Devuelve la fecha de nacimiento del alumno
     * 
     * La fecha se recoge en formato YYYY-MM-DD
     *
     * @return string
     */
    public function getNacimiento(): string{
        return $this->f_nacimiento;
    }
    
    /**
     * Devuelve un array que contiene las asistencias del alumno.
     *
     * @return Asistencia[]|null
     */
    public function getAsistencias(): ?array{
        return $this->asistencias;
    }
    
    /**
     * Devuelve un array que contiene las faltas del alumno.
     *
     * @return Falta[]|null
     */
    public function getFaltas(): ?array{
        return $this->faltas;
    }

    /**
     * Devuelve las alergias e intolerancias del alumno.
     *
     * @return string|null
     */
    public function getAlergias(): ?string{
        return $this->alergias;
    }

    /**
     * Devuelve la tarifa del comedor aplicada al alumno. 
     *
     * @return Tarifa
     */
    public function getTarifa(): Tarifa{
        return $this->tarifa;
    }

    /**
     * Devuelve un array que contiene las actividades extraescolares a las que asiste el alumno.
     *
     * @return mixed
     */
    public function getActividades(): mixed{
        return $this->actividades;
    }

    /**
     * Devuelve un string con las actividades extraescolares en las que partcipa un alumno en un día concreto.
     * 
     * A diferencia de otros métodos, este método toma un parámetro que contiene una fecha e itera entre las diferentes actividades hasta encontrar la que coincide con el día de la semana. El parámetro día debe ser igual a cualquiera de los siguientes valores ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"]. Si no existen actividades, devuelve un string vacío.
     *
     * @param string $dia
     * @return string
     */
    public function getActividadDia(string $dia): string {
        $actividadDia = "";
        if($this->actividades == null || count($this->actividades) == 0){
            return $actividadDia;
        } else {
            for ($i = 0; $i < count($this->actividades); $i++) { 
                $actividad = $this->actividades[$i];
                $diasActividad = $actividad->getDias();
                
                for ($j = 0; $j < count($diasActividad); $j++) { 
                    $diaAct = $diasActividad[$j];
                    if($diaAct == $dia){
                        $actividadDia .= $actividad->getActividad() . ", ";
                    }
                }
            }
        }
        return $actividadDia;
    }

    /**
     * Establece el identificador del alumno.
     *
     * @param integer $nuevoId
     * @return void
     */
    private function setId(int $nuevoId): void{
        $this->id = $nuevoId;
    }

    /**
     * Establece el nombre del alumno.
     *
     * @param string $nuevoNombre
     * @return void
     */
    public function setNombre(string $nuevoNombre): void{
        $this->nombre = $nuevoNombre;
    }

    /**
     * Establece el primer apellido del alumno.
     *
     * @param string $nuevoApellido1
     * @return void
     */
    public function setApellido1(string $nuevoApellido1): void{
        $this->apellido1 = $nuevoApellido1;
    }

    /**
     * Establece el segundo apellido del alumno.
     *
     * @param string $nuevoApellido2
     * @return void
     */
    public function setApellido2(string $nuevoApellido2): void{
        $this->apellido2 = $nuevoApellido2;
    }

    /**
     * Establece el curso al que pertenece el alumno.
     *
     * @param string $nuevoCurso
     * @return void
     */
    public function setCurso(string $nuevoCurso): void{
        $this->curso = $nuevoCurso;
    }

    /**
     * Establece el grupo al que pertenece el alumno.
     *
     * @param string $nuevoGrupo
     * @return void
     */
    public function setGrupo(string $nuevoGrupo): void{
        $this->grupo = $nuevoGrupo;
    }

    /**
     * Establece la fecha de nacimiento del alumno.
     *
     * @param string $nuevaFecha
     * @return void
     */
    public function setNacimiento(string $nuevaFecha): void{
        $this->f_nacimiento = $nuevaFecha;
    }

    /**
     * Establece un array con las asistencias del alumno al comedor.
     *
     * @param array $nuevasAsistencias
     * @return void
     */
    public function setAsistencias(array $nuevasAsistencias): void{
        $this->asistencias = $nuevasAsistencias;
    }

    /**
     * Establece una asistencia en concreto del alumno.
     *
     * @param Asistencia $asistencia
     * @return void
     */
    public function setAsistencia(Asistencia $asistencia): void{
        $this->asistencias[] = $asistencia;
    }

    /**
     * Establece un array con las faltas del alumno al comedor.
     *
     * @param array $nuevasFaltas
     * @return void
     */
    public function setFaltas(array $nuevasFaltas): void{
        $this->faltas = $nuevasFaltas;
    }

    /**
     * Establece una falta en concreto del alumno.
     *
     * @param Falta $falta
     * @return void
     */
    public function setFalta(Falta $falta): void{
        $this->faltas[] = $falta;
    }

    /**
     * Establece las alergias e intolerancias del alumno.
     *
     * @param string|null $alergia
     * @return void
     */
    public function setAlergias(?string $alergia): void{
        $this->alergias = $alergia;
    }

    /**
     * Establece la tarifa que le corresponde al alumno por el uso del comedor.
     *
     * @param Tarifa $tarifa
     * @return void
     */
    public function setTarifa(Tarifa $tarifa): void{
        $this->tarifa = $tarifa;
    }

    /**
     * Establece un array con las actividades extraescolares en las que participa el alumno.
     *
     * @param array $nuevasActividades
     * @return void
     */
    public function setActividades(array $nuevasActividades): void{
        $this->actividades = $nuevasActividades;
    }

    /**
     * Establece una actividad extraescolar en concreto en la que participa el alumno.
     *
     * @param Actividad $actividad
     * @return void
     */
    public function setActividad(Actividad $actividad): void{
        $this->actividades[] = $actividad;
    }

    /**
     * Comprueba si el curso existe.
     * 
     * Comprueba que el parámetro que se le pasa a la función (curso), se encuentre entre los valores almacenados en el array (cursos). Si no lo está devuelve una excepción, si lo está se le asigna el nuevo valor.
     *
     * @param string $curso
     * @throws CompruebaAlumnosException
     * @return void
     */
    public function compruebaCurso(string $curso): void{
        $curso = ucfirst($curso);
        if(in_array($curso, $this->cursos)){
            $this->setCurso($curso);
        } else {
            throw new CompruebaAlumnosException(null,"El curso no existe en la base de datos");
        }
    }

    /**
     * Undocumented function
     * 
     * Comprueba que el parámetro que se le pasa a la función (grupo), se encuentre entre los valores almacenados en el array (grupos). Si no lo está devuelve una excepción, si lo está se le asigna el nuevo valor.
     *
     * @param string $grupo
     * @throws CompruebaAlumnosException
     * @return void
     */
    public function compruebaGrupo(string $grupo): void{
        $grupo = ucfirst($grupo);
        if(in_array($grupo, $this->grupos)){
            $this->setGrupo($grupo);
        } else {
            throw new CompruebaAlumnosException(null, "El grupo no existe en la base de datos");
        }
    }

    /**
     * Devuelve el registro de asistencias a partir de una fecha concreta.
     *
     * @param string $fecha
     * @return Asistencia|null 
     */
    public function getAsistencia(string $fecha): ?Asistencia {
        if($this->asistencias != null){
            foreach($this->asistencias as $asistencia){
                if($asistencia->getFecha() == $fecha){
                    return $asistencia;
                }
            }
        }
        return null;
    }
    
    /**
     * Devuelve el registro de faltas a partir de una fecha concreta.
     *
     * @param string $fecha
     * @return Falta|null
     */
    public function getFalta(string $fecha): ?Falta {
        if($this->faltas != null){
            foreach($this->faltas as $falta){
                if($falta->getFecha() == $fecha){
                    return $falta;
                }
            }
        }
        return null;
    }

    /**
     * Devuelve un array indexado con las faltas del alumno.
     * 
     * Devuelve un array indexado (1-31) con fechas. Si el día de la fecha coincide con el índice, la fecha se guarda indexada. De lo contrario se guarda un null.
     *
     * @return array
     */
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

    /**
     * Devuelve el día de una fecha
     *
     * @param Falta|null $fecha
     * @return string|null
     */
    private function getDia(?Falta $fecha): ?string{
        if($fecha == null){
            return null;
        } else{
            $array = explode("-", $fecha->getFecha());
            $dia = $array[2];
            return $dia;
        }
    }

    /**
     * Genera un array de Alumnos a partir de un CSV
     * 
     * Permite generar un array de Alumnos a partir de un objeto SplFileObject que contiene datos en formato CSV.
     *
     * @param SplFileObject $csv
     * @throws CreaAlumnoException si el curso o el grupo al que pertenece el alumno son incorrectos (no están definidos en la base).
     * @return Alumno[]
     */
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
            $tarifa = floatval(str_replace(",", ".", $fila[7]));

            
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

    /**
     * Devuelve todas las actividades extraescolares en formato string
     * 
     * Se genera un string en el que se imprimen las actividades que contiene un array.
     *
     * @return string
     */
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

    /**
     * Devuelve el último identificador de la tabla alumnos.
     *
     * @param ConexionBBDD $bbdd
     * @throws ConsultaAlumnosException
     * @return stdClass|false
     */
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

    /**
     * Devuelve una fecha formateada
     * 
     * Toma una fecha con formato YYY-MM-DD y se encarga de transformarlo en DD/MM/YYYY
     *
     * @param string $fecha
     * @return string
     */
    public static function formatoFecha(string $fecha): string{
        $nuevaFecha = explode("-", $fecha);
        $fechaFormateada = $nuevaFecha[2] . "/" . $nuevaFecha[1] . "/" .$nuevaFecha[0];
        return $fechaFormateada;
    }

    /**
     * Genera una nuevo Alumno a partir de un objeto recuperado de la base de datos
     *
     * @param stdClass $alumno_recuperado
     * @return Alumno
     */
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

    /**
     * Genera un array de Actividades a partir de un array de objetos recuperados de la base de datos
     *
     * @param array $alumnos_recuperados
     * @return Alumno[]
     */
    public static function multicast(array $alumnos_recuperados): array{
        $nuevosAlumnos = [];
        foreach ($alumnos_recuperados as $alumno) {
            $nuevoAlumno = self::cast($alumno);
            $nuevosAlumnos[] = $nuevoAlumno;
        }
        return $nuevosAlumnos;
    }
}