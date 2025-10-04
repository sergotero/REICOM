<?php

class Tarifa{
    private int $id;
    private float $tarifa;

    public function __construct(float $tarifa){
        $this->tarifa = $tarifa;
        $id_tarifa = $this->consultaIdTarifa($tarifa);
        if($id_tarifa){
            $this->id = (int) $id_tarifa->id;
        }
    }

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

    //Getters

    public function getId(): int{
        return $this->id;
    }

    public function getTarifa(): float{
        return $this->tarifa;
    }

    //FUNCIONES DE CASTING
    public static function cast(stdClass $tarifa_recuperada): Tarifa{
        $tarifa = new Tarifa(floatval($tarifa_recuperada->tarifa));
        return $tarifa;
    }

    public static function multicast(array $tarifas_recuperadas): array{
        $nuevasTarifas = [];
        foreach ($tarifas_recuperadas as $tarifa) {
            $nuevaTarifa = self::cast($tarifa);
            $nuevasTarifas[] = $nuevaTarifa;
        }
        return $nuevasTarifas;
    }
}