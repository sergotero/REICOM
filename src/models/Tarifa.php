<?php
/**
 * Clase Tarifa
 * 
 * Representa una tarifa en el sistema. Gestiona información básica como identificador y el valor de la tarifa.
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class Tarifa{
    
    /**
     * Identificador único de la tarifa.
     *
     * @var integer
     */
    private int $id;

    /**
     * Valor de la tarifa.
     *
     * @var float
     */
    private float $tarifa;

    /**
     * Constructor de la clase Tarifa.
     *
     * @param float $tarifa
     */
    public function __construct(float $tarifa){
        $this->tarifa = $tarifa;
        $id_tarifa = $this->consultaIdTarifa($tarifa);
        if($id_tarifa){
            $this->id = (int) $id_tarifa->id;
        }
    }

    /**
     * Comprueba si la tarifa existe en la base de datos.
     * 
     * Este método se usa en el constructor de la clase para retornar el identificador de la tarifa de la base de datos.
     * 
     * @param float $tarifa
     * @throws CreaTarifasException
     * @return stdClass|null
     */
    private function consultaIdTarifa(float $tarifa): mixed{
        try {
            $bbdd = new MySQLBBDD();
            $conexion = Conexion::getInstancia($bbdd)->getConexion();
            $consulta = $conexion->prepare("SELECT id FROM tarifas WHERE tarifa = :t");
            $consulta->bindParam(":t", $tarifa, PDO::PARAM_STR);
            $consulta->execute();
            $resultado = $consulta->fetch(PDO::FETCH_OBJ);
            $conexion = Conexion::getInstancia($bbdd)->cerrarConexion();
            $bbdd = null;
        } catch (PDOException $e) {
            throw new CreaTarifasException($e);
        }
        return $resultado;
    }

    /**
     * Devuelve el identificador de la tarifa.
     *
     * @return integer
     */
    public function getId(): int{
        return $this->id;
    }

    /**
     * Devuelve el valor de la tarifa.
     *
     * @return float
     */
    public function getTarifa(): float{
        return $this->tarifa;
    }

    /**
     * Genera una nueva Tarifa a partir de un objeto recuperado de la base de datos
     *
     * @param stdClass $tarifa_recuperada
     * @return Tarifa
     */
    public static function cast(stdClass $tarifa_recuperada): Tarifa{
        $tarifa = new Tarifa(floatval($tarifa_recuperada->tarifa));
        return $tarifa;
    }

    /**
     * Genera un array de Tarifas a partir de un array de objetos recuperados de la base de datos
     *
     * @param array $tarifas_recuperadas
     * @return Tarifa[]
     */
    public static function multicast(array $tarifas_recuperadas): array{
        $nuevasTarifas = [];
        foreach ($tarifas_recuperadas as $tarifa) {
            $nuevaTarifa = self::cast($tarifa);
            $nuevasTarifas[] = $nuevaTarifa;
        }
        return $nuevasTarifas;
    }
}