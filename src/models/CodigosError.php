<?php

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
    public const ACTIVIDAD_INSERTAR   = 1001;
    public const ACTIVIDAD_ACTUALIZAR = 1002;
    public const ACTIVIDAD_ELIMINAR   = 1003;
    public const ACTIVIDAD_CONSULTAR  = 1004;

    // ---- Alumno (20xx) ----
    public const ALUMNO_CREAR      = 2001; // Crea / Inserta
    public const ALUMNO_INSERTAR   = 2001; // alias: mismo código
    public const ALUMNO_ACTUALIZAR = 2002;
    public const ALUMNO_ELIMINAR   = 2003;
    public const ALUMNO_CONSULTAR  = 2004;
    public const ALUMNO_COMPROBAR  = 2005;

    // ---- Asistencia (30xx) ----
    public const ASISTENCIA_INSERTAR   = 3001;
    public const ASISTENCIA_ACTUALIZAR = 3002;
    public const ASISTENCIA_ELIMINAR   = 3003;
    public const ASISTENCIA_CONSULTAR  = 3004;

    // ---- Falta (40xx) ----
    public const FALTA_INSERTAR   = 4001;
    public const FALTA_ACTUALIZAR = 4002;
    public const FALTA_ELIMINAR   = 4003;
    public const FALTA_CONSULTAR  = 4004;

    // ---- Tarifas (50xx) ----
    public const TARIFA_CREAR = 5001;
    public const TARIFA_CONSULTAR = 5004;

    // ---- Usuario (60xx) ----
    public const USUARIO_INSERTAR   = 6001;
    public const USUARIO_ACTUALIZAR = 6002;
    public const USUARIO_ELIMINAR   = 6003;
    public const USUARIO_CONSULTAR  = 6004;
    public const USUARIO_COMPROBAR  = 6005;

    // ---- Genéricos ----
    public const GENERICO_SIN_CODIGO = 0;
}
