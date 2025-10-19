# Changelog
Todos los cambios notables en este proyecto se documentarán en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/)  
y este proyecto sigue [Semantic Versioning](https://semver.org/lang/es/).

> **Nota:** La aplicación continúa en fase de desarrollo y aún no ha sido publicada ni probada por usuarios finales.  
> Las versiones indicadas (0.x.x) corresponden a hitos internos previos a la versión estable 1.0.0.


---

## [0.8.0] - 2025-10-19
### Cambiado
- Eliminado el código PHP referido a los botones de añadir `Asiste`, `Falta` y `Borrar A/F` en `form_listado_alumnos.php`.
- Eliminado el código PHP referido a los botones de añadir `Asiste`, `Falta` y `Borrar A/F` en `form_buscador_alumnos.php`.
- Eliminado el código PHP referido al botón de `Eliminar` del `form_eliminar_alumnos.php`.
- Eliminado el código PHP referido al botón de `Eliminar` del `form_listado_usuarios.php`.
- Eliminado el código PHP referido al botón de `Eliminar` del `form_listado_actividades.php`.
- Comentado el código para que se muestre el botón de `Restablecer base` para futuras implementaciones. De momento, se considera potencialmente peligroso para los usuarios.
- Cambio de nombre del directorio `tests` por `examples` y nueva ubicación. Ahora se encuentra en `private/examples`

## Corregido
- Documentación de la clase Gestor (gran parte de los métodos no habían sido debidamente documentados).

## Añadido
- Creación del directorio `public/api_fetch`.
- Creación del archivo `fetch.php` que contiene los nuevos métodos en JavaScript:
  - `crearAsistencia`: sustituye al bloque PHP que permitía crear asistencias en `form_listado_alumnos.php` y en `form_buscador_alumnos.php`.
  - `crearFalta`: sustituye al bloque PHP que permitía crear faltas en `form_listado_alumnos.php` y en `form_buscador_alumnos.php`.
  - `borraAsistenciaFalta`: sustituye al bloque PHP que permitía borrar asistencias/faltas en `form_listado_alumnos.php` y en `form_buscador_alumnos.php`.
  - `eliminarAlumno`: sustituye al bloque PHP que permitía eliminar alumnos en `form_eliminar_alumnos.php`.
  - `borrarUsuario`: sustituye al bloque PHP que permitía crear asistencias en `form_listado_usuarios.php.php`.
  - `borrarActividad`: sustituye al bloque PHP que permitía crear asistencias en `form_listado_actividades.php`.
- Creación de nuevo método (`rehabilitarBotones`) en JavaScript que permite reestablecer los estilos previos de los botones `Asiste`, `Falta` y `Borra A/F`.

---

## [0.0.3 → 0.6.0] - Consolidación posterior (julio–septiembre 2025)
Además de los hitos detallados arriba, durante este periodo se implementaron y ajustaron los siguientes aspectos:

### Interfaz y estilo
- Cambios de diseño en cabecera, colores y estilos CSS.
- Implementación de migas de pan (*breadcrumbs*) para mejorar la navegación.
- Limpieza de estilos y revisión general de la presentación.

### Funcionalidades
- Creación y refinamiento de clases `CodigosError`, `CodigosExito` y sistema de excepciones.
- Mejoras en código JavaScript y componentes interactivos (interruptores, botones, validaciones).
- Reformas en la base de datos y generación de documentos PDF.
- Añadido soporte para buscadores avanzados y botones de asistencia múltiple.
- Creación del botón **Eliminador**, sección específica y mejora en el sistema de informes.
- Inclusión de nuevas gráficas e informes visuales (Gantt, comedor, estadísticas).

---

## [0.7.1] - 2025-10-15
### Cambiado
- Modificaciones en la estructura del proyecto: se añaden las carpetas como `assets`, `api_fetch`, `tools` y `docs`.
- Modificación de enlaces que afectaban a JavaScript y CSS.

## Corregido
- Cambio en los tipos de la clase `Alumno` y `Gestor`. Los tipos existentes eran un tanto genéricos y se han cambiado por otros más restrictivos.

## Añadido
- Comentarios en todas las clases, interfaces y excepciones para la futura generación de la documentación.

---

## [0.7.0] - 2025-09-26
### Cambiado
- Reforma completa de métodos relacionados con **Asistencia** y **Faltas**.
- Reestructuración interna de la clase `CodigosError`.
- Refactorización estructural: nuevas clases, reorganización lógica y optimización del flujo interno.

---

## [0.6.0] - 2025-09-15
### Eliminado / Pospuesto
- Descartada temporalmente la **vista Profesor** por motivos de seguridad.

### Añadido
- Nuevos formularios para **gestión de usuarios** (listado, modificación y eliminación).  
  Se incluye una capa de protección adicional mediante un botón JS que habilita la eliminación.
- Nuevos formularios para **actividades extraescolares** con las mismas protecciones.
- Nuevo botón que genera un **informe tipo Gantt**, mostrando las faltas de alumnos de un curso durante un mes determinado.

---

## [0.5.0] - 2025-08-29
### Cambiado
- Ajustes en las consultas SQL (`ORDER BY`) para mejorar la consistencia de los resultados.
- Modificaciones en el **informe de comedor**: ahora sólo se muestran los alumnos con faltas y se añade una columna con **alergias e intolerancias alimentarias**.

---

## [0.4.0] - 2025-08-17
### Añadido
- En la **vista Administrador** se incorporan las funciones de la vista Profesor:
  - Crear asistencia.
  - Crear falta.
  - Eliminar asistencias y faltas.
- Nuevo botón que **genera automáticamente una asistencia para todos los alumnos**.
- **Buscador de alumnos** por clase y periodo de tiempo.
- Simplificación del proceso de creación de faltas (se genera automáticamente con la fecha del día).
- **Informe de comedor**: genera una lista de alumnos indicando su asistencia al comedor.

### Cambiado
- Se deshabilitan los botones de crear asistencia y crear falta mediante un script JS cuando no corresponde su uso.

---

## [0.3.0] - 2025-07-31
### Añadido
- En la **vista Administrador**:
  - Formulario de registro múltiple de alumnos mediante archivo CSV.
  - Botón para descargar una **plantilla CSV**.
  - Botón de **instrucciones en formato texto**.
  - Formulario de estadísticas globales (asistencias y faltas totales en un periodo determinado).
  - Botón para **restablecer la base de datos** (eliminar registros de alumnos, faltas y asistencias, y reiniciar identificadores).

---

## [0.2.0] - 2025-07-21
### Añadido
- Primera versión funcional (aproximadamente 40% completada):
  - **Vista Profesor:** listado de alumnos con botones para crear asistencias, crear faltas y eliminar registros.
  - **Vista Administrador:** listado similar, con botones adicionales para modificar datos, ver estadísticas y eliminar alumnos.
  - Botón para registrar nuevos alumnos en la base.
  - Formularios individuales asociados a cada acción (modificar, ver estadísticas, registrar, etc.).

---

## [0.1.0] - 2025-07-(fecha intermedia)
### Añadido
- **Diseño de la base de datos**: creación de tablas, relaciones y claves foráneas.  
- Implementación inicial de la conexión con la base de datos y pruebas básicas de inserción y consulta.

---

## [0.0.2] - 2025-07-06
### Añadido
- **Inicio del proyecto Reicom.**
- Creación del sistema de carpetas base y estructura inicial del proyecto (controladores, modelos, vistas, recursos y utilidades).

---
