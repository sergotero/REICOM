# REICOM

**Sistema de Gestión de Comedores Escolares**  
**Autor:** Sergio Otero Pérez  
**Ciclo Formativo de Grado Superior en Desarrollo de Aplicaciones Web**  
**Centro educativo:** I.E.S. Rodeira  
**Curso académico:** 2023/2025  
**Versión: 1.0.0.**  

# INTRODUCCIÓN Y ALCANCE DEL PROYECTO

En los últimos años, se han estado produciendo recortes presupuestarios en numerosos servicios públicos de primera categoría como el servicio sanitario o el educativo. Como es de suponer, estas decisiones gubernamentales dan lugar una serie de daños colaterales inevitables que conllevan serias consecuencias para el propio personal como para los usuarios de dicho servicio, resultando, principalmente, en el deterioro de la calidad y en el desgaste físico y/o mental del personal.

Una de las formas en las que los recortes y la privatización de los servicios públicos se ha hecho palpable es en los comedores escolares, donde ha sido necesaria la presión de ciertos colectivos y asociaciones para evitar el cierre de muchos de ellos. En general, la estrategia seguida por la Xunta de Galicia consiste en sacar a concurso público la gestión de estos servicios[^1] con el fin de que empresas privadas de cátering se hagan con el control (sin tener en cuenta que, a menudo, la calidad del producto final es claramente inferior). En este punto es importante, además, recordar que los usuarios de este servicio son menores de edad que, además, se hayan en una tapa donde una buena alimentación es crucial para su correcto desarrollo.

A mayores de lo ya mencionado, las herramientas informáticas que la Xunta de Galicia ha habilitado para todos aquellos colegios que dispongan del servicio de comedor son insuficientes y no permiten el desempeño de ciertas tareas administrativas (por ejemplo, el cómputo de asistencias, faltas, tarifas, etc.) que les son solicitadas a los centros. Esto empuja a los colegios a realizar estas acciones de manera manual, lo que a su vez tiene una repercusión sobre el tiempo útil de trabajo del personal administrativo y el aumento de posibles errores humanos debido al trajín constante de papeles. Y es en este punto donde entra en juego el proyecto REICOM.

## Descripción del Proyecto

Mediante este proyecto, se busca desarrollar una aplicación web que simplifique y optimice la gestión de los alumnos que hacen uso del comedor, ofreciendo una herramienta sencilla, intuitiva y eficiente al personal administrativo del centro.

Las principales funcionalidades de la aplicación incluyen:

*   Registro de alumnos que hacen uso del servicio comedor.
*   Registro de faltas vinculado a cada alumno.
*   Registro de asistencias vinculado a cada alumno.
*   Listado de asistencias y faltas del alumno en un periodo de tiempo determinado.
*   Gestión de bajas (alumnos que se han graduado o abandonado el centro) y modificaciones de los datos del alumno.
*   Generación de informes PDF diarios para los encargados del comedor (listado de alumnos que faltan) que incluye información sobre alergias e intolerancias alimenticias, así como las actividades a las que asiste el alumno a continuación.
*   Generación de informes PDF mensuales de faltas para el personal administrativo.
*   Registro de actividades extraescolares.
*   Gestión de actividades (eliminación y modificación de datos).
*   Registro de usuarios (personal administrativo y profesorado).
*   Gestión de usuarios (eliminación de cuentas, cambio de permisos y modificación de datos).
*   Acceso diferenciado para administradores y personal autorizado.

## Justificación

Como hemos mencionado, la gestión de los comedores escolares que no dependen de un servicio externalizado (cátering), recae en el personal administrativo de cada centro que, a su vez, no cuenta con las debidas herramientas informáticas.

Así pues, a través de la aplicación REICOM se pretende:

*   Agilizar el proceso de control de asistencia y faltas.
*   Garantizar un registro fiable y actualizado en tiempo real.
*   Reducir la posibilidad de errores humanos durante el proceso.
*   Reducir el uso de papel y apostar por la digitalización.
*   Facilitar la labor del personal administrativo y docente.

## Objetivos

Ahora que hemos establecido el contexto y hemos sustentado la justificación del proyecto, podemos hablar de los objetivos que se persiguen. En este sentido, podemos diferenciar dos tipos de objetivos: los generales y los específicos.

### Objetivo general

*   Diseñar e implementar una aplicación web que facilite la gestión de alumnos que hacen uso del comedor escolar en los centros educativos que cuenten con este servicio.

### Objetivos específicos

*   Crear un sistema de autenticación y control de usuarios (asignación de roles, permisos, etc.).
*   Permitir el alta, baja y modificación de los datos de los alumnos.
*   Registrar las asistencias y las faltas diarias de manera sencilla, rápida y eficaz.
*   Generar informes PDF tanto para los responsables del comedor como para el personal administrativo.
*   Desarrollar una interfaz sencilla, responsive y accesible.

## Alcance

El proyecto abarca todo el desarrollo de la aplicación web en sí. Dicho de otro modo, va desde el análisis de requisitos (intercambio de información con los responsables del centro) hasta las pruebas finales.

No obstante, es necesario tener en cuenta que al contener la aplicación información sobre menores de edad que, además, está protegida por el Reglamento General de Protección de Datos (RGDP), será necesario solicitar permiso, en primer lugar, a la Xunta de Galicia para obtener el permiso necesario para poder elaborar una base de datos a nivel local y, en segundo lugar, para solicitar la instalación de XAMPP en alguno de los ordenadores del centro.

Al tratarse la creación de un base de datos a nivel local, solamente accesible desde un único ordenador en el centro que, además, sólo podrá ser utilizado por personal autorizado, no se esperan mayores impedimentos. Sin embargo, para garantizar las posibilidades de que el proyecto salga adelante, ambas peticiones deberán ser efectuadas por propio personal del colegio.

# Arquitectura del sistema

## Visión General de la Arquitectura (Capas)

El sistema REICOM está diseñado siguiendo una arquitectura de tres capas, característica de las aplicaciones web monolíticas basadas en PHP:

*   Capa de presentación (Frontend): Compuesta por archivos HTML/PHP y JavaScript. Se encarga de la interfaz de usuario, la validación a nivel de cliente (JS) y la interacción dinámica (AJAX/Fetch API) con la capa de control para el registro de asistencias/faltas.
*   Capa de lógica de negocio y control (Backend): Constituida por archivos PHP (formularios de gestión, index.php, y fetch.php). Esta capa gestiona la lógica de la aplicación, la autenticación, la validación de negocio y la comunicación con la base de datos a través de la clase Gestor. Aquí residen las clases de dominio (Alumno, Usuario, etc.).
*   Capa de acceso a datos (Persistencia): Incluye la clase Gestor, las clases de conexión (Conexion, MySQLBBDD) y el servidor de base de datos MySQL/MariaDB. Esta capa se encarga de las operaciones CRUD y el manejo de excepciones de la base de datos (PDO).

## Requisitos mínimos de Hardware y Software

Esta sección describe los requisitos mínimos y recomendados para instalar y ejecutar correctamente la aplicación.

### Requisitos de hardware

|     |     |     |
| --- | --- | --- |  
| Componente | Mínimo recomendado | Entorno de desarrollo utilizado |
| Procesador | Intel Core i3 o equivalente | Intel Core i7-8700 @ 3.20 GHz |
| Memoria RAM | 4 GB (recomendado: 8 GB) | 32 GB |
| Almacenamiento | 500 MB libres (más espacio para datos) | SSD 250 GB + HDD 2 TB |
| Tarjeta gráfica | No requerida específicamente | NVIDIA GeForce GTX 1050 Ti (4 GB) |
| Arquitectura | 64 bits (x64) | Sistema operativo de 64 bits |

Nota: La aplicación no requiere aceleración gráfica ni recursos avanzados de CPU. Se recomienda un entorno con SSD para un mejor rendimiento en la lectura y escritura de datos.

### Requisitos de software

1.  Sistema operativo:
    *   Windows 10 o superior (x64)
    *   También compatible con Linux (Ubuntu 20.04 o superior) o macOS, siempre que se disponga de un entorno con PHP y servidor web equivalente.
2.  Entorno de servidor:
    *   XAMPP v3.3.0 (o superior), que incluye:
    *   Apache 2.4.x
    *   MariaDB / MySQL (versión incluida en XAMPP)
3.  Lenguaje y extensiones PHP:
    *   PHP: 8.2.12 o superior
    *   Zend Engine: v4.2.12
    *   Extensiones necesarias:
        *   pdo\_mysql
        *   mbstring
        *   json
        *   curl
        *   gd (recomendado para funcionalidades gráficas)
        *   opcache (recomendado para rendimiento)
4.  Extensiones opcionales:
    *   xdebug (solo para entornos de desarrollo)
5.  Librerías externas
    *   TCPDF (para la generación de documentos PDF). Más información sobre esta librería en el sitio oficial: https://tcpdf.org/
    *   Instalación recomendada: incluir en el directorio src/libs/tcpdf/ o equivalente.
6.  Navegadores compatibles: la aplicación está optimizada para navegadores modernos con soporte para HTML5 y CSS3.
    *   Google Chrome 110 o superior
    *   Mozilla Firefox 100 o superior
    *   Microsoft Edge 110 o superior

### Requisitos de red

*   Conexión a Internet solo necesaria si la aplicación accede a servicios externos (no obligatoria para uso local).
*   Puerto 80 (HTTP) o 443 (HTTPS) habilitado en el servidor local.

### Conocimientos del usuario

*   No se requieren conocimientos técnicos para el uso de la aplicación. Se recomienda únicamente:
*   Manejo básico de ordenador (entorno Windows o similar)
*   Conocimientos ofimáticos básicos, especialmente en hojas de cálculo como Microsoft Excel o equivalentes (LibreOffice Calc, Google Sheets, etc.)

### Entorno de desarrollo utilizado

*   Sistema operativo: Windows 10 Pro (64 bits)
*   Procesador: Intel Core i7-8700 @ 3.20 GHz
*   RAM: 32 GB
*   Almacenamiento: SSD Samsung 860 EVO 250 GB + HDD Seagate 2 TB
*   Servidor local: XAMPP v3.3.0
*   PHP: 8.2.12 (con Zend Engine v4.2.12 y Xdebug v3.3.2)
*   Librería externa: TCPDF
*   Base de datos: MariaDB incluida en XAMPP

### Recomendaciones adicionales

*   Mantener actualizado PHP y las extensiones utilizadas.
*   Evitar rutas con espacios o caracteres especiales en la instalación.
*   Verificar los permisos de escritura en directorios que gestionen archivos (por ejemplo, /tmp/, /uploads/, o /pdfs/).

### Tabla resumen

   
| Tipo | Componente | Mínimo Recomendado | Notas de Implementación |
| --- | --- | --- | --- |
| Hardware | Procesador | Intel Core i3 o equivalente | Arquitectura de 64 bits (x64) |
| Hardware | Memoria RAM | 4 GB (Recomendado: 8 GB) | Se recomienda SSD para la lectura/escritura de datos. |
| Software | Sistema Operativo | Windows 10+, Linux (Ubuntu 20.04+), macOS | Entorno basado en software libre. |
| Software | Entorno Servidor | XAMPP v3.3.0 (Apache 2.4.x,<br><br>MariaDB/MySQL) | Utilizado para desarrollo y entorno local de producción. |
| Software | PHP | PHP 8.2.12 o superior | Requiere extensiones: pdo\_mysql, mbstring, json, curl, gd. |
| Librerías | PDF | TCPDF | Utilizada para la generación de informes PDF diarios y mensuales (Gantt). |
| Navegador | Web | Chrome 110+, Firefox 100+,<br><br>Edge 110+ | Optimizado para navegadores modernos con soporte HTML5 y CSS3. |

# Diseño de la base de datos  

## Diagrama Entidad-Relación de la Base de Datos  

El diseño de la base de datos sigue un modelo relacional normalizado que soporta la gestión integral de alumnos, usuarios y las interacciones diarias con el comedor y las actividades.

<img width="1920" height="1371" alt="Diagrama_ER" src="https://github.com/user-attachments/assets/ea9053a2-6053-45aa-a149-89e6b9a4635f" />

## Tablas principales y relaciones:

  
| Tabla | Propósito | Relación Clave |
| --- | --- | --- |
| usuarios | Almacena la información de acceso de los empleados (Administradores y Profesores). | N/A |
| alumnos | Entidad central que registra los datos personales del alumnado, su curso, grupo, alergias y la tarifa aplicada. | 1:1 con tarifas<br><br>(FK: id\_tarifa) |
| tarifas | Catálogo de tarifas de comedor disponibles, permitiendo que la tabla alumnos referencie solo el ID. | N/A |
| actividades | Catálogo de actividades extraescolares con detalles (horario, ubicación, días). | N/A |
| actividades\_alumno | Tabla pivote (N:M) que vincula alumnos con actividades. | N:M entre alumnos y actividades |
| asistencias | Registra las fechas en que un alumno asiste al comedor. | 1:N con alumnos<br><br>(FK: id\_alumno) |
| faltas | Registra las fechas en que un alumno falta al comedor, incluyendo datos de aviso. | 1:N con alumnos<br><br>(FK: id\_alumno) |

# Diseño del SOFTWARE  

## Esquema UML de la Jerarquía de Clases  

El diseño orientado a objetos del sistema sigue un patrón de separación de responsabilidades: Entidades de dominio, conexión y un gestor central.

<img width="3840" height="2404" alt="Diagrama_UML" src="https://github.com/user-attachments/assets/c255402e-2812-4c4b-a3b2-67fa1e9d976e" />
   
| Relación | Tipo UML | Cardinalidad (A → B) | Descripción |
| --- | --- | --- | --- |
| Alumno<br><br>↔<br><br>Tarifa | Asociación | Alumno 1 → Tarifa 1;<br><br>Tarifa 1 → Alumno 0..\* | Cada alumno tiene una única tarifa; una tarifa puede aplicarse a varios alumnos. Es una relación estructural permanente, pero sin dependencia de vida. |
| Alumno<br><br>↔<br><br>Asistencia | Composición | Alumno 1 → Asistencia 0..\*;<br><br>Asistencia 1 → Alumno 1 | Una asistencia pertenece exclusivamente a un alumno y no puede existir sin él; si se elimina el alumno, desaparecen sus asistencias. |
| Alumno<br><br>↔<br><br>Falta | Composición | Alumno 1 → Falta 0..\*;<br><br>Falta 1 → Alumno 1 | Igual que en Asistencia: la falta depende completamente del alumno al que pertenece. |
| Alumno<br><br>↔<br><br>Actividad | Asociación (N:N) | 0..\* ↔ 0..\* | Un alumno puede participar en varias actividades y una actividad puede incluir a varios alumnos. No hay dependencia de existencia entre ellos. |
| Conexion<br><br>↔<br><br>ConexionBBDD | Dependencia | Conexion 1 → ConexionBBDD 1 | Conexion usa una clase que implementa la interfaz ConexionBBDD. No mantiene un enlace persistente; la usa en tiempo de ejecución. |
| MySQLBBD<br><br>↔<br><br>ConexionBBDD | Realización | —   | MySQLBBD implementa la interfaz ConexionBBDD, por lo que se modela como una realización (herencia de interfaz). |
| Gestor<br><br>↔<br><br>Alumno | Dependencia | Gestor 0..\* → Alumno 0..\* | Gestor usa instancias de Alumno (individuales o en colección) como parámetros de métodos para acceder o modificar datos. |
| Gestor<br><br>↔<br><br>Tarifa | Dependencia | Gestor 0..\* → Tarifa 0..\* | Gestor utiliza objetos Tarifa al realizar operaciones relacionadas con los alumnos y las tarifas en la base de datos. |
| Gestor<br><br>↔<br><br>Asistencia | Dependencia | Gestor 0..\* → Asistencia 0..\* | Gestor recibe y maneja objetos Asistencia para registrar o consultar datos. No hay relación permanente entre ellos. |
| Gestor<br><br>↔<br><br>Falta | Dependencia | Gestor 0..\* → Falta 0..\* | Gestor usa instancias de Falta para gestionar la información de ausencias. No guarda referencia estable. |
| Gestor<br><br>↔<br><br>Usuario | Dependencia | Gestor 0..\* → Usuario 0..\* | Gestor maneja información de usuarios para validar o registrar acciones, pero no los contiene ni los crea. |
| Gestor<br><br>↔<br><br>Conexion | Dependencia | Gestor 1 → Conexion 1 | Gestor utiliza la conexión a la base de datos (Singleton) para ejecutar consultas. Solo existe una instancia de Conexion. |
| Gestor<br><br>↔<br><br>CodigosError | Dependencia | —   | Gestor accede a constantes definidas en CodigosError. No crea instancias ni mantiene referencias. |

## Componentes Clave del Diseño

### Entidades del diagrama:

*   Alumno, Usuario, Actividad, Tarifa, Asistencia, Falta: estas clases representan las entidades de la base de datos y encapsulan la lógica de negocio y validación de sus respectivos datos.
*   Métodos comunes: todas las clases de entidad (excepto las de utilidades) implementan métodos estáticos cast() y multicast() para transformar objetos genéricos (stdClass) devueltos por la capa de persistencia (Gestor) en objetos fuertemente tipados.
*   Relaciones de composición: la clase Alumno mantiene una relación de composición con Actividad, Falta, Asistencia y Tarifa, indicando que la existencia de las colecciones de eventos y actividades, así como el objeto Tarifa, están fuertemente ligados a la instancia de Alumno.

### Capa de persistencia y conexión:

*   ConexionBBDD (Interfaz): define el contrato (getConexion()) para establecer la conexión, permitiendo futuras implementaciones (ej. PostgreSQL, Oracle) sin modificar la capa de control.
*   MySQLBBDD (Clase): implementa la interfaz ConexionBBDD, conteniendo los parámetros y la lógica específica para la conexión a MySQL.
*   Conexion (Singleton): implementa el patrón Singleton (getInstancia()) para asegurar que solo exista una conexión activa (PDO) con la base de datos en cualquier momento.

### Lógica de control:

*   Gestor: es la clase que centraliza la lógica de acceso a datos (CRUD). Utiliza la interfaz ConexionBBDD y la clase Conexion para ejecutar consultas SQL y devolver objetos genéricos que luego son transformados por las clases. Esta clase desacopla las operaciones de la base de datos de la lógica del controlador (PHP front-end).

### Jerarquía de Excepciones:

*   Aunque no se recogen en el diagrama principal de UML (a excepción de las constantes), la documentación anterior mostró una jerarquía de clases de excepción personalizadas (ej., ConsultaAlumnosException), que heredan de la clase base Exception y utilizan códigos específicos definidos en CodigosError para una gestión de errores precisa.

# Clases  

La documentación correspondiente a las clases está disponible dentro del propio repositorio, [a través de este enlace](/docs/index.html). 

# Flujos de trabajo y lógica de negocio

La aplicación se estructura en torno a diferentes roles de usuario (Administrador y Profesor) y se centra en la gestión y el seguimiento de los alumnos del comedor, especialmente sus asistencias y faltas.

## Flujo de Autenticación y Acceso

Este es el punto de entrada a la aplicación y determina la experiencia del usuario según su rol.

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso | index.php | El usuario introduce su email y contraseña. |
| 2   | Verificación | index.php<br><br>(Gestor::recuperaUsuario()) | El sistema recupera el usuario por email y verifica la contraseña utilizando password\_verify(). |
| 3   | Rol y Redirección | index.php | Si la autenticación es exitosa, se guarda el objeto Usuario en la sesión y se redirige según el rol. |
| 3.1 | Administrador | index.php| Redirección a:<br><br>./admin/form\_listado\_alumnos.php (Acceso completo). |
| 3.2 | Profesor | index.php| Redirección a:<br><br>./profesor/form\_listado\_alumnos.php<br><br>(Acceso limitado, principalmente al buscador). |
| 4   | Salir | Varios formularios | El botón “Salir” destruye la sesión (session\_destroy()) y redirige de nuevo a index.php. |

## Flujo de Gestión Individual de Alumnos (CRUD)

Este flujo se centra en las operaciones de creación y modificación de los datos de un alumno.

### Flujo de registro de nuevo alumno

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso | form\_listado\_alumnos.phpn\-><br><br>form\_registro\_alumnos.php | El Administrador hace clic en “Añadir alumno” en el listado. |
| 2   | Presentación del Formulario | form\_registro\_alumnos.php | Se cargan los campos del formulario, poblando los selects (curso, grupo, tarifa, actividades) mediante consultas a la BBDD (Gestor::recuperaCursos, recuperaGrupos(), recuperaTarifas(), recuperaActividades()). |
| 3   | Creación | form\_registro\_alumnos.php | El Administrador envía el formulario con los datos. |
| 4   | Validación y Creación del Objeto | form\_registro\_alumnos.php | Se crea una instancia de Alumno y se realizan validaciones internas (ej. Alumno::compruebaCurso()). |
| 5   | Inserción en BBDD | form\_registro\_alumnos.php<br><br>(Gestor::compruebaAlumno(),<br><br>Gestor::insertaAlumno(),<br><br>Gestor::insertaActividadAlumno()) | *   Se comprueba que el alumno no exista.<br>*   Se inserta el alumno en la tabla alumnos.<br>*   Si hay actividades seleccionadas, se inserta la relación en actividades\_alumno. |
| 6   | Resultado | form\_registro\_alumnos.php | Se muestra un mensaje de éxito o de error. |

### Flujo de modificación de datos del alumno

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso | form\_listado\_alumnos.php o<br><br>form\_buscador\_alumnos.php | El usuario hace clic en el botón “Modificar datos” o “Modificar”. El ID del alumno se pasa vía POST o se recupera de `$_SESSION[‘id_alumno’]`. |
| 2   | Recuperación de Datos | form\_modificar\_alumnos.php<br><br>(Gestor::recuperaAlumno(),<br><br>Gestor::recuperaActividadesAlumno()) | Se recuperan todos los datos del alumno, incluyendo su tarifa y sus actividades asociadas. Se crea el objeto Alumno. |
| 3   | Presentación del Formulario | form\_modificar\_alumnos.php | Los campos del formulario se rellenan con los datos actuales del alumno. Las actividades se marcan como checked si el alumno las tiene asociadas. |
| 4   | Guardado | form\_modificar\_alumnos.php | El usuario envía el formulario con las modificaciones. |
| 5   | Actualización en BBDD | form\_modificar\_alumnos.php | *   Se actualizan los datos principales del alumno (Gestor::actualizaAlumno()).<br>*   Se eliminan todas las actividades asociadas al alumno (Gestor::eliminaActividadesAlumno()).<br>*   Se reinsertan las actividades marcadas en el formulario (Gestor::insertaActividadAlumno()). |
| 6   | Resultado | form\_modificar\_alumnos.php | Se muestra un mensaje de éxito o de error. |

## Flujo de Subida Masiva de Alumnos

Este flujo permite al Administrador insertar múltiples alumnos desde un archivo CSV.

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso | form\_listado\_alumnos.php \-><br><br>form\_subida\_multiple.php | El Administrador hace clic en “Añadir múltiple”. |
| 2   | Subida del Archivo | form\_subida\_multiple.php | El Administrador selecciona un archivo y pulsa “Subir”. |
| 3   | Validación del Archivo | form\_subida\_multiple.php | Se valida el estado de la subida (errores PHP) y la extensión (.csv, .xlsx). |
| 4   | Procesamiento del CSV | form\_subida\_multiple.php<br><br>(Alumno::creaDesdeCSV()) | Se utiliza SplFileObject para leer el archivo y se convierte cada fila en un objeto Alumno. |
| 5   | Inserción Masiva | form\_subida\_multiple.php<br><br>(Gestor::compruebaAlumno(),<br><br>Gestor::insertaAlumno()) | Se Itera sobre el array de objetos Alumno:<br><br>*   Se comprueba si cada alumno ya existe.<br>*   Si no existe, se inserta en la BBDD. |
| 6   | Resultados | form\_subida\_multiple.php | Se muestra un mensaje de éxito general y un listado de los alumnos que ya estaban registrados (evitando duplicados). |

## Flujo de Búsqueda y Registro Diario

Este flujo es el principal para el trabajo diario de profesores y administradores.

### Visualización y búsqueda de alumnos

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Listado General (Admin) | form\_listado\_alumnos.php | Muestra todos los alumnos. En la carga inicial, recupera todos los alumnos (Gestor::recuperaAlumnos()), sus faltas y asistencias para marcar el estado del día actual. |
| 2   | Búsqueda Filtrada (Admin/Profesor) | form\_buscador\_alumnos.php | Permite buscar alumnos por curso y grupo. Recupera los alumnos filtrados (Gestor::recuperaAlumnosCursoGrupo()) y su estado de asistencia/falta para el día actual. |

### Registro de asistencias/faltas y borrado de asistencias/faltas

Las acciones de registro diario se manejan de forma asíncrona a través de fetch.php.

   
| #   | Paso | Archivo / JS | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Interfaz de Botones | Función deshabilitarBotones() de JavaScript | Al cargar la página, si un alumno tiene ya una marca de asistencia (✓) o falta (X), los botones “Asiste” y “Falta” se deshabilitan para esa fila. |
| 2   | Creación A/F (AJAX) | Función creaAsistencia()/<br><br>creaFalta() de JavaScript \-> fetch.php | El usuario pulsa “Asiste” o “Falta”. Se realiza una petición fetch (POST/JSON) al método correspondiente en fetch.php. |
| 3   | Ejecución en BBDD | fetch.php | Se crea la instancia de Asistencia o Falta y se llama a Gestor::insertaAsistencia() o Gestor::insertaFalta(). |
| 4   | Actualización UI | Función creaAsistencia() /<br><br>creaFalta() de JavaScript | Si la inserción es exitosa, el JS:<br><br>*   Inserta el icono (✓ o X) en la columna “Comedor”.<br>*   Vuelve a llamar a deshabilitarBotones() para bloquear los botones de esa fila. |
| 5   | Borrado A/F (AJAX) | Función restablece() de JavaScript \-><br><br>fetch.php | El usuario pulsa “Borrar A/F”. Se llama a fetch.php (borrarAsistenciaFalta()). |
| 6   | Eliminación en BBDD | fetch.php | Se llama a Gestor::eliminaFaltas() y/o Gestor::eliminaAsistencias() para el día actual. |
| 7   | Reactivación UI | Función restablece() de JavaScript | Si la eliminación es exitosa, el JS:<br><br>*   Borra el icono de la columna “Comedor”.<br>*   Llama a rehabilitarBotones() para re-habilitar los botones “Asiste” y “Falta”. |
| 8   | Avisos de Estado | Función eliminaAvisos() y onload de JavaScript | Los mensajes de error y éxito (div.errores, div.exitos) se eliminan automáticamente de la interfaz después de 3 a 5 segundos. |
| 9   | Asisten Todos | form\_listado\_alumnos.php<br><br>(POST directo) | Itera sobre todos los alumnos (Gestor::recuperaAlumnos()), comprueba si tienen registro hoy y, si no, inserta una asistencia. |

## Flujos de Informes y Estadísticas (Admin)

Estos flujos generan vistas detalladas o documentos PDF.

### Flujo de estadísticas individuales

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso/Selección | form\_listado\_alumnos.php \-><br><br>form\_estadistica\_alumnos.php | El usuario selecciona un alumno y es redirigido a la página de estadísticas. |
| 2   | Búsqueda por Período | form\_estadistica\_alumnos.php | El usuario introduce una Fecha Inicio y una Fecha Fin y pulsa “Buscar”. |
| 3   | Consulta de Datos | form\_estadistica\_alumnos.php<br><br>(Gestor::recuperaFaltasPeriodo(),<br><br>Gestor::recuperaAsistenciasPeriodo()) | Se consultan las faltas y asistencias del alumno en el rango de fechas especificado. |
| 4   | Cómputo Total | form\_estadistica\_alumnos.php<br><br>(Gestor::totalFaltasPeriodo(),<br><br>Gestor::totalAsistenciasPeriodo()) | Se calculan los totales de días con falta y días con asistencia en el período. |
| 5   | Visualización | form\_estadistica\_alumnos.php | Se muestran dos tablas: una con el listado detallado de faltas y otra con el listado detallado de asistencias, más una tabla final con el resumen de totales. |

### Flujo de generación de un informe diario (PDF)

Este flujo genera un PDF con el listado de alumnos que han faltado el día en curso.

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso | form\_listado\_alumnos.php \-><br><br>form\_imprimir\_diario.php | El Administrador hace clic en “Faltas comedor” (botón imprimir manejado por JS, que redirige al PHP de generación). |
| 2   | Consulta de Cursos/Grupos | form\_imprimir\_diario.php<br><br>(Gestor::recuperaCursosGruposConFaltas()) | Se identifican qué cursos y grupos tienen alumnos con faltas registradas hoy. |
| 3   | Generación del PDF | form\_imprimir\_diario.php<br><br>(TCPDF) | Se inicia la creación del documento PDF:<br><br>*   Se itera sobre los cursos/grupos identificados.<br>*   Por cada alumno de esos grupos, se recuperan sus datos y actividades.<br>*   Se verifica si la falta es la de hoy.<br>*   Si la falta es de hoy, se imprime la fila del alumno incluyendo curso, grupo, nombre/apellidos, alergias y actividades. |
| 4   | Salida | form\_imprimir\_diario.php | El PDF se envía al navegador ($pdf->Output(‘listado.pdf’, ‘I’)). |

### Flujo de generación de informe mensual Gantt (PDF)

Este flujo genera un diagrama de Gantt (tabulado) con las faltas mensuales de los alumnos de un curso.

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso/Selección | form\_listado\_alumnos.php \-><br><br>form\_gantt.php | El Administrador hace clic en “Faltas mes”. Selecciona curso, mes y año. |
| 2   | Solicitud de PDF | form\_gantt.php (JS) \-><br><br>form\_imprimir\_gantt.php<br><br>(fetch POST) | El JS recoge los parámetros de búsqueda y realiza una petición POST (JSON) al script de impresión. |
| 3   | Consulta de Datos | form\_imprimir\_gantt.php<br><br>(Gestor::recuperaAlumnosCurso(),<br><br>Gestor::recuperaFaltasMesAno()) | Se recuperan todos los alumnos del curso y, por cada uno, solo sus faltas registradas en el mes/año especificado. |
| 4   | Generación<br><br>del PDF | form\_imprimir\_gantt.php<br><br>(TCPDF) | *   Se itera por los grupos del curso.<br>*   Se imprime la cabecera de 31 días.<br>*   Por cada alumno en ese grupo, se imprime su nombre y, por cada día del mes, una “X” si el alumno tiene una falta registrada ese día (Alumno::getCalendario()). |
| 5   | Salida | form\_imprimir\_gantt.php | El PDF se envía al navegador ($pdf->Output(‘faltasGantt.pdf’, ‘I’)). |

## Flujo de Gestión de Usuarios (CRUD - Solo Admin)

Este flujo permite al Administrador gestionar los usuarios que acceden a la plataforma.

### Creación de usuarios

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso | form\_listado\_alumnos.php \-><br><br>form\_listado\_usuarios.php \-><br><br>form\_registro\_usuarios.php | El Administrador navega al listado de usuarios y hace clic en “Crear usuarios”. |
| 2   | Creación | form\_registro\_usuarios.php | El Administrador introduce nombre, apellidos, email, rol y la contraseña (doble confirmación). |
| 3   | Inserción en BBDD | form\_registro\_usuarios.php<br><br>(Gestor::insertaUsuario()) | *   Se hashea la contraseña (password\_hash()).<br>*   Se crea un objeto Usuario y se inserta en la tabla usuarios. |

### Modificación de usuarios

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso | form\_listado\_usuarios.php \-><br><br>form\_modificar\_usuario.php | El Administrador hace clic en “Modificar” en el listado. Se pasa el email a la sesión. |
| 2   | Recuperación de Datos | form\_modificar\_usuario.php<br><br>(Gestor::recuperaUsuario()) | Se recuperan los datos del usuario por email y se cargan en el formulario. |
| 3   | Guardado | form\_modificar\_usuario.php | El Administrador envía el formulario. Se requiere la doble confirmación de una nueva contraseña. |
| 4   | Actualización en BBDD | form\_modificar\_usuario.php<br><br>(Gestor::actualizaUsuario()) | Se hashea la nueva contraseña y se llama a Gestor::actualizaUsuario() para modificar todos los campos. |

### Eliminación de usuario

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso/Activación | form\_listado\_usuarios.php / función deshabilitarBotones() de JavaScript | El Administrador activa el modo “Eliminar” con el switch de la tabla. El JS deshabilita/habilita los botones de eliminación. |
| 2   | Eliminación (AJAX) | form\_listado\_usuarios.php (JS) \-> fetch.php | Al hacer clic en el botón “Eliminar”, se llama a fetch.php con el método borrarUsuario($email) tras una confirmación previa (función eliminaUsuario() en JavaScript). |
| 3   | Ejecución en BBDD | fetch.php<br><br>(Gestor::eliminaUsuario()) | Se recupera el objeto Usuario por email y se llama a Gestor::eliminaUsuario(). |

## Flujo de Gestión de Actividades (CRUD - Solo Admin)

Este flujo permite al Administrador definir las actividades extraescolares disponibles.

### Creación de actividades

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | 1\. Acceso | form\_listado\_alumnos.php \-><br><br>form\_listado\_actividades.php \-><br><br>form\_registro\_actividades.php | El Administrador navega al listado de actividades y hace clic en “Crear actividad”. |
| 2   | 2\. Creación | form\_registro\_actividades.php | El Administrador introduce el nombre, la hora de inicio y fin, la ubicación y selecciona los días de la semana. |
| 3   | 3\. Inserción en BBDD | form\_registro\_actividades.php<br><br>(Gestor::insertaActividad()) | *   Se crea el objeto Actividad.<br>*   Se inserta en la tabla actividades (los días se almacenan como un string separado por comas). |

### Modificación de actividades

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso | form\_listado\_actividades.php \-><br><br>form\_modificar\_actividades.php | El Administrador hace clic en “Modificar” en el listado. Se pasa el id\_actividad a la sesión. |
| 2   | Recuperación de Datos | form\_modificar\_actividades.php<br><br>(Gestor::recuperaActividad()) | Se recuperan los datos de la actividad y se cargan en el formulario, marcando los días correspondientes. |
| 3   | Guardado | form\_modificar\_actividades.php | El Administrador envía el formulario con los nuevos valores. |
| 4   | Actualización en BBDD | form\_modificar\_actividades.php<br><br>(Gestor::actualizaActividad()) | Se crea un nuevo objeto Actividad con los datos modificados y se llama a Gestor::actualizaActividad(). |

### Eliminación de actividades

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso/Advertencia | form\_listado\_actividades.php / Función deshabilitarBotones() de JavaScript | El Administrador ve una advertencia y debe activar un switch para habilitar el botón “Eliminar”. |
| 2   | Eliminación (AJAX) | form\_listado\_actividades.php (JS) \-> fetch.php | Al hacer clic en “Eliminar”, se llama a fetch.php con el método borrarActividad($id\_actividad) tras una confirmación previa. |
| 3   | Ejecución en BBDD | fetch.php<br><br>(Gestor::eliminaActividad()) | Se recupera el objeto Actividad por ID y se llama a Gestor::eliminaActividad(). |

## Flujos de Herramientas de Importación Y Exportación (Solo Admin)

### Descarga de plantilla CSV

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Solicitud | form\_subida\_multiple.php | El Administrador hace clic en “Descargar plantilla”. |
| 2   | Generación y Descarga | plantilla\_alumnos.php | Se establecen los encabezados HTTP para la descarga de un archivo CSV. Se utiliza fputcsv() para generar el archivo con los campos requeridos (Nombre, Apellido1, Apellido2, Curso, etc.) y codificación UTF-8 (BOM) para compatibilidad con Excel. |

### Descarga de instrucciones

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Solicitud | form\_subida\_multiple.php | El Administrador hace clic en “Instrucciones”. |
| 2   | Generación y Descarga | instrucciones\_plantilla.php | Se establecen los encabezados HTTP para la descarga de un archivo de texto (.txt). Se genera y envía al navegador un contenido predefinido con las reglas de formato, los valores permitidos y consejos de importación para el archivo CSV. |

### Eliminación masiva de alumnos / reseteo de BBDD[^2]

   
| #   | Paso | Archivo | Descripción del Flujo |
| --- | --- | --- | --- |
| 1   | Acceso/Advertencia | form\_eliminar\_alumnos.php /<br><br>Función deshabilitarBotones() de JavaScript | El Administrador ve una advertencia y debe activar un switch para habilitar el botón “Eliminar”. |
| 2   | Eliminación Individual (AJAX) | form\_eliminar\_alumnos.php (JS) \-> fetch.php | El Administrador hace clic en “Eliminar” tras la activación del switch. Se llama a fetch.php con el método eliminarAlumno($id\_alumno) tras una confirmación previa. |
| 3   | Ejecución en BBDD | fetch.php<br><br>(Gestor::eliminaAlumno) | Se recupera el alumno por ID y se llama a Gestor::eliminaAlumno. |
| 4   | Reseteo Completo (POST) | form\_eliminar\_alumnos.php<br><br>(Comentado/Alternativo) | Esta lógica está comentada y se aplica a un botón llamado “Restablecer BBDD”. Si se activara, llamaría a Gestor::reseteaBBDD(), que ejecuta comandos DELETE y ALTER TABLE AUTO\_INCREMENT = 0 para varias tablas. |

# Aspectos de Implementación

## Tecnologías y Librerías Utilizadas

El sistema utiliza HTML, CSS, JavaScript y PHP como lenguajes base, junto con una base de datos MySQL/MariaDB. A mayores, se utiliza la librería TCPDF para la generación de informes en formato PDF y la Fetch API de JavaScript para la comunicación asíncrona (AJAX).

## Lógica de Control de UI / Microinteracciones

La interfaz de usuario implementa microinteracciones cruciales para guiar al usuario y evitar errores, gestionadas principalmente a través de JavaScript:

*   Bloqueo de botones: una vez que se ha generado una asistencia (✓) o falta (X) vinculada a un alumno en el día en curso, los botones “Asiste” y “Falta” se deshabilitan automáticamente para evitar doble registro. Sólo el botón “Borrar A/F” permanece activo.
*   Rehabilitación de botones: el botón “Borrar A/F” ejecuta una función que elimina los registros tanto de asistencias como faltas, la marca (✓ / X) y rehabilita los botones “Asiste” y “Falta” mediante JavaScript.
*   Avisos temporales: los mensajes de éxito o error se muestran en la interfaz y son eliminados automáticamente tras un período de 3 a 5 segundos (función eliminaAvisos() y lógica onload).
*   Activación de eliminación: En los listados de gestión (form\_eliminar\_alumnos.php, form\_listado\_usuarios.php, form\_listado\_actividades.php), la acción de “Eliminar” se bloquea por defecto y se requiere de la activación de un interruptor tipo switch y una confirmación adicional (función confirm()) para ejecutarse, previniendo la eliminación accidental de datos sensibles.

# Pruebas y QA

## Estrategia de Pruebas

Debido a que la metodología de trabajo es híbrida y el proyecto es de escala moderada, la estrategia de Calidad (QA) se dividió en tres niveles principales durante el desarrollo:

*   Pruebas Unitarias (capa de dominio/persistencia): verificación del comportamiento individual de cada clase. Para ello, se realizan una serie de pruebas rigurosas a nivel de código para asegurar que los métodos de clase devuelven exactamente los parámetros o estados configurados, aislando la lógica de la base de datos y la interfaz.
*   Pruebas de Integración y Funcionalidad (pruebas de Rol): Confirmación de que los componentes trabajan correctamente juntos y que los permisos de usuario se aplican de forma adecuada. Verificación de los flujos de trabajo clave desde la perspectiva del usuario final (Admin y Profesor), incluyendo la correcta generación de informes PDF. Para ello se realizaron comprobaciones exhaustivas de todos los flujos de la aplicación tanto desde la vista Profesor (acceso limitado a la búsqueda y registro diario) como desde la vista Administrador (acceso completo a todas las gestiones y reportes), garantizando que las acciones se reflejen de manera coherente en la BBDD.
*   Pruebas de Aceptación (UAT o Feedback real): validación de que la aplicación satisface las necesidades del usuario final y funciona en el entorno de destino. Para ello se instaló una versión preliminar en el centro educativo para que el personal autorizado (los usuarios finales) pudiese probar el sistema en un entorno real, permitiendo la detección de errores de usabilidad y la implementación de mejoras basadas en el feedback recibido.

## Casos de Prueba Relevantes

   
| ID Caso | Flujo | Descripción | Resultado Esperado |
| --- | --- | --- | --- |
| CP01 | Autenticación | Intentar acceder con credenciales de Administrador. | Redirección exitosa a form\_listado\_alumnos.php (Admin). |
| CP02 | Asistencia Diaria | Marcar “Asiste” a un alumno que no tiene registro hoy. | Se inserta registro en asistencias. Columna “Comedor” muestra ✓. Botones “Asiste” y “Falta” se deshabilitan. |
| CP03 | Registro Duplicado | Intentar marcar “Falta” a un alumno que ya tiene la asistencia registrada (CP02). | La llamada AJAX debe fallar (error en la capa de negocio/BD). No se altera el estado. |
| CP04 | Borrado y Reactivación | Pulsar “Borrar A/F” sobre el alumno de CP02. | Se elimina el registro de asistencias. Columna “Comedor” se vacía. Botones “Asiste” y “Falta” se rehabilitan. |
| CP05 | CRUD Actividad | Crear una actividad, modificar su horario y eliminarla (con switch activado). | El ciclo CRUD se completa sin errores, verificando la eliminación de la actividad en BBDD. |
| CP06 | Informe Diario | Generar el informe PDF “Faltas comedor” en un día con faltas registradas. | Se abre el PDF conteniendo únicamente a los alumnos con marca X hoy, incluyendo su información de alergias y actividades del día. |
| CP07 | Subida Masiva | Intentar subir un CSV con un alumno duplicado y un alumno nuevo. | El alumno nuevo se inserta. Se muestra un mensaje de éxito general y un aviso de error específico listando al alumno duplicado. |

# Manual de usuario

La documentación correspondiente al manejo de la aplicación está disponible [a través de este enlace](/docs/pdf/manual-de-usuario.pdf). 

- - -

[^1]: En Galicia, concretamente, de acuerdo al Decreto 132/2013, de 1 de agosto, por el que se regulan los comedores escolares de los centros docentes públicos no universitarios dependientes de la consellería con competencias en materia de educación, se establecen tres modalidades: directa (comedores in situ), indirecta (comedores que entran a concurso público; empresas de cátering) y gestionados por las AMPA (o sus federaciones).

[^2]: En el momento de la creación de esta documentación, esta opción se ha dejado implementada, pero con el código comentado. De tal manera que sólo un técnico con conocimientos del lenguaje podría habilitarlo de nuevo. Se ha tomado esta decisión por una cuestión de seguridad.
