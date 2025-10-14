<?php

/**
 * Clase CodigosError
 * 
 * Contiene una definición de constantes para cada uno de los códigos de error que se puedan producir en la aplicación.
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class CodigosError{
    /*
    Reservamos rangos por entidad (Actividad, Alumno, Asistencia, Falta, Usuario, Tarifas). Dentro de cada rango, agrupamos por tipo de acción. La idea es construir los códigos siguiendo una fórmula que sea fácil de razonar y de ampliar:

        código = (código_entidad * 100) + código_accion

    código_entidad identifica la entidad / recurso (Actividad, Alumno, Asistencia, etc.).
    código_accion identifica la acción (crear/insertar, actualizar, eliminar, consultar, comprobar).

    Entidades (código_entidad):

        Actividad = 10
        Alumno = 20
        Asistencia = 30
        Falta = 40
        Tarifas = 50
        Usuario = 60

    Acciones (código_accion):

        01 = Crear / Insertar
        02 = Actualizar
        03 = Eliminar
        04 = Consultar
        05 = Comprobar (uso para las excepciones Comprueba...)
    */

    // ---- Actividad (10xx) ----
    
    /**
     * Código que se genera cuando se produce un error durante la inserción en la tabla actividades.
     * 
     * @var int
     */
    public const ACTIVIDAD_INSERTAR   = 1001;
    
    /**
     * Código que se genera cuando se produce un error durante la actualización en la tabla actividades.
     * 
     * @var int
     */
    public const ACTIVIDAD_ACTUALIZAR = 1002;
    
    /**
     * Código que se genera cuando se produce un error durante una eliminación en la tabla actividades.
     * 
     * @var int
     */
    public const ACTIVIDAD_ELIMINAR   = 1003;
    
    /**
     * Código que se genera cuando se produce un error durante la consulta de la tabla actividades.
     * 
     * @var int
     */
    public const ACTIVIDAD_CONSULTAR  = 1004;

    // ---- Alumno (20xx) ----
    
    /**
     * Código que se genera cuando se produce un error durante la creación de un objeto alumno.
     * 
     * @var int
     */
    public const ALUMNO_CREAR      = 2001; // Crea / Inserta
    
    /**
     * Código que se genera cuando se produce un error durante la inserción en la tabla alumnos.
     * 
     * @var int
     */
    public const ALUMNO_INSERTAR   = 2001; // alias: mismo código
    
    /**
     * Código que se genera cuando se produce un error durante la actualización de la tabla alumnos.
     * 
     * @var int
     */
    public const ALUMNO_ACTUALIZAR = 2002;
    
    /**
     * Código que se genera cuando se produce un error durante una eliminación en la tabla alumnos.
     * 
     * @var int
     */
    public const ALUMNO_ELIMINAR   = 2003;
    
    /**
     * Código que se genera cuando se produce un error durante una consulta en la tabla alumnos.
     * 
     * @var int
     */
    public const ALUMNO_CONSULTAR  = 2004;
    
    /**
     * Código que se genera cuando se produce un error durante una comprobación en la tabla alumnos.
     * 
     * @var int
     */
    public const ALUMNO_COMPROBAR  = 2005;

    // ---- Asistencia (30xx) ----
    
    /**
     * Código que se genera cuando se produce un error durante una inserción en la tabla asistencias.
     * 
     * @var int
     */
    public const ASISTENCIA_INSERTAR   = 3001;
    
    /**
     * Código que se genera cuando se produce un error durante una actualización de la tabla asistencias.
     * 
     * @var int
     */
    public const ASISTENCIA_ACTUALIZAR = 3002;
    
    /**
     * Código que se genera cuando se produce un error durante una eliminación en la tabla asistencias.
     * 
     * @var int
     */
    public const ASISTENCIA_ELIMINAR   = 3003;
    
    /**
     * Código que se genera cuando se produce un error durante una consulta en la tabla asistencias.
     * 
     * @var int
     */
    public const ASISTENCIA_CONSULTAR  = 3004;

    // ---- Falta (40xx) ----
    
    /**
     * Código que se genera cuando se produce un error durante una inserción en la tabla faltas.
     * 
     * @var int
     */
    public const FALTA_INSERTAR   = 4001;
    
    /**
     * Código que se genera cuando se produce un error durante una actualización en la tabla faltas.
     * 
     * @var int
     */
    public const FALTA_ACTUALIZAR = 4002;
    
    /**
     * Código que se genera cuando se produce un error durante una eliminación en la tabla faltas.
     * 
     * @var int
     */
    public const FALTA_ELIMINAR   = 4003;
    
    /**
     * Código que se genera cuando se produce un error durante una consulta en la tabla faltas.
     * 
     * @var int
     */
    public const FALTA_CONSULTAR  = 4004;

    // ---- Tarifas (50xx) ----
    
    /**
     * Código que se genera cuando se produce un error durante la creación de un objeto Tarifa.
     * 
     * @var int
     */
    public const TARIFA_CREAR = 5001;
    
    /**
     * Código que se genera cuando se produce un error durante una consulta en la tabla tarifas.
     * 
     * @var int
     */
    public const TARIFA_CONSULTAR = 5004;

    // ---- Usuario (60xx) ----
    
    /**
     * Código que se genera cuando se produce un error durante una inserción en la tabla usuarios.
     * 
     * @var int
     */
    public const USUARIO_INSERTAR   = 6001;
    
    /**
     * Código que se genera cuando se produce un error durante una actualización en la tabla usuarios.
     * 
     * @var int
     */
    public const USUARIO_ACTUALIZAR = 6002;
    
    /**
     * Código que se genera cuando se produce un error durante una eliminación en la tabla usuarios.
     * 
     * @var int
     */
    public const USUARIO_ELIMINAR   = 6003;
    
    /**
     * Código que se genera cuando se produce un error durante una consulta en la tabla usuarios.
     * 
     * @var int
     */
    public const USUARIO_CONSULTAR  = 6004;
    
    /**
     * Código que se genera cuando se produce un error durante una comprobación en la tabla usuarios.
     * 
     * @var int
     */
    public const USUARIO_COMPROBAR  = 6005;

    // ---- Genéricos ----
    
    /**
     * Código que utiliza cuando el error es genérico o no tiene un código específico.
     * 
     * @var int
     */
    public const GENERICO_SIN_CODIGO = 0;
}
