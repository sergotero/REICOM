<?php
/**
 * Clase CodigosExito
 * 
 * Contiene una definición de constantes para cada uno de los códigos correspondientes a cada tarea realizada de manera exitosa que se puedan producir en la aplicación.
 * 
 * @author Sergio Otero
 * @version 1.0.0
 * @since 1.0.0
 */
class CodigosExito
{
    // ---------------------------
    // ACTIVIDAD (10x)
    // ---------------------------
    
    /**
     * Código que se genera tras realizar con éxito una actualización en la tabla actividades.
     * 
     * @var int
     */
    public const ACTIVIDAD_ACTUALIZAR = 100;
    
    /**
     * Código que se genera tras realizar con éxito una consulta en la tabla actividades.
     * 
     * @var int
     */
    public const ACTIVIDAD_CONSULTAR  = 101;
    
    /**
     * Código que se genera tras realizar con éxito una inserción en la tabla actividades.
     * 
     * @var int
     */
    public const ACTIVIDAD_INSERTAR   = 102;
    
    /**
     * Código que se genera tras realizar con éxito una eliminación en la tabla actividades.
     * 
     * @var int
     */
    public const ACTIVIDAD_ELIMINAR   = 103;

    // ---------------------------
    // ALUMNO (20x)
    // ---------------------------
    /**
     * Código que se genera tras realizar con éxito una actualización en la tabla alumnos.
     * 
     * @var int
     */
    public const ALUMNO_ACTUALIZAR    = 200;
    
    /**
     * Código que se genera tras realizar con éxito una comprobación en la tabla alumnos.
     * 
     * @var int
     */
    public const ALUMNO_COMPROBAR     = 201;

    /**
     * Código que se genera tras realizar con éxito una consulta en la tabla alumnos.
     * 
     * @var int
     */
    public const ALUMNO_CONSULTAR     = 202;

    /**
     * Código que se genera tras realizar con éxito la creación de un nuevo alumno.
     * 
     * @var int
     */
    public const ALUMNO_CREAR         = 203;

    /**
     * Código que se genera tras realizar con éxito una eliminación en la tabla alumnos.
     * 
     * @var int
     */
    public const ALUMNO_ELIMINAR      = 204;

    /**
     * Código que se genera tras realizar con éxito una inserción en la tabla alumnos.
     * 
     * @var int
     */
    public const ALUMNO_INSERTAR      = 205;

    /**
     * Código que se genera tras realizar con éxito el reestablecimiento de la base de datos.
     * 
     * Sólo se genera cuando se reestablecen la tabla alumnos, faltas, asistencias y actividades y se establecen los identificadores de cada tabla a cero.
     * 
     * @var int
     */
    public const ALUMNO_RESTABLECER   = 206;


    // ---------------------------
    // ASISTENCIA (30x)
    // ---------------------------
    
    /**
     * Código que se genera tras realizar con éxito una actualización en la tabla asistencias.
     * 
     * @var int
     */
    public const ASISTENCIA_ACTUALIZAR = 300;
    
    /**
     * Código que se genera tras realizar con éxito una consulta en la tabla asistencias.
     * 
     * @var int
     */
    public const ASISTENCIA_CONSULTAR  = 301;
    
    /**
     * Código que se genera tras realizar con éxito una eliminación en la tabla asistencias.
     * 
     * @var int
     */
    public const ASISTENCIA_ELIMINAR   = 302;
    
    /**
     * Código que se genera tras realizar con éxito una inserción en la tabla asistencias.
     * 
     * @var int
     */
    public const ASISTENCIA_INSERTAR   = 303;

    // ---------------------------
    // FALTA (40x)
    // ---------------------------
    
    /**
     * Código que se genera tras realizar con éxito una actualización en la tabla faltas.
     * 
     * @var int
     */
    public const FALTA_ACTUALIZAR     = 400;
    
    /**
     * Código que se genera tras realizar con éxito una consulta en la tabla faltas.
     * 
     * @var int
     */
    public const FALTA_CONSULTAR      = 401;
    
    /**
     * Código que se genera tras realizar con éxito una eliminación en la tabla faltas.
     * 
     * @var int
     */
    public const FALTA_ELIMINAR       = 402;
    
    /**
     * Código que se genera tras realizar con éxito una inserción en la tabla faltas.
     * 
     * @var int
     */
    public const FALTA_INSERTAR       = 403;

    // ---------------------------
    // TARIFAS (50x)
    // ---------------------------
    
    /**
     * Código que se genera tras crear con éxito una tarifa.
     * 
     * @var int
     */
    public const TARIFAS_CREAR        = 500;

    // ---------------------------
    // USUARIO (60x)
    // ---------------------------
    
    /**
     * Código que se genera tras realizar con éxito una actualización en la tabla usuario.
     * 
     * @var int
     */
    public const USUARIO_ACTUALIZAR   = 600;
    
    /**
     * Código que se genera tras realizar con éxito una comprobación en la tabla usuario.
     * 
     * @var int
     */
    public const USUARIO_COMPROBAR    = 601;
    
    /**
     * Código que se genera tras realizar con éxito una consulta en la tabla usuario.
     * 
     * @var int
     */
    public const USUARIO_CONSULTAR    = 602;
    
    /**
     * Código que se genera tras realizar con éxito una eliminación en la tabla usuario.
     * 
     * @var int
     */
    public const USUARIO_ELIMINAR     = 603;
    
    /**
     * Código que se genera tras realizar con éxito una inserción en la tabla usuario.
     * 
     * @var int
     */
    public const USUARIO_INSERTAR     = 604;
}
