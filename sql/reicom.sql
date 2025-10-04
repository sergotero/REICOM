DROP DATABASE IF EXISTS reicom;
CREATE DATABASE IF NOT EXISTS reicom CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

USE reicom;

CREATE TABLE tarifas(
	id INT UNSIGNED AUTO_INCREMENT,
    tarifa DECIMAL(4,2),
		CONSTRAINT pk_tarifa PRIMARY KEY (id),
        CONSTRAINT uq_tarifa UNIQUE (tarifa)
)CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

CREATE TABLE actividades(
	id INT UNSIGNED AUTO_INCREMENT,
    actividad VARCHAR(255),
    h_inicio TIME,
    h_fin TIME,
    ubicacion VARCHAR(255),
    dias VARCHAR(255),
		CONSTRAINT pk_actividades PRIMARY KEY (id),
        CONSTRAINT uq_actividades UNIQUE (actividad)
)CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

CREATE TABLE alumnos(
    id INT UNSIGNED AUTO_INCREMENT,
    nombre VARCHAR (70) NOT NULL,
    apellido1 VARCHAR (70) NOT NULL,
    apellido2 VARCHAR (70) NOT NULL,
    curso ENUM("4º Inf.", "5º Inf.", "6º Inf.", "1º Pri.", "2º Pri.", "3º Pri.", "4º Pri.", "5º Pri.", "6º Pri.") NOT NULL,
    grupo ENUM("A", "B", "C", "D", "E") NOT NULL,
    f_nacimiento DATE NOT NULL,
    alergias VARCHAR(255),
    id_tarifa INT UNSIGNED NOT NULL,
		CONSTRAINT pk_alumnos PRIMARY KEY (id),
		CONSTRAINT fk_tarifa FOREIGN KEY (id_tarifa) REFERENCES tarifas(id)
			ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT uq_alumno UNIQUE (nombre, apellido1, apellido2, f_nacimiento)
)CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

CREATE TABLE asistencias(
	id INT UNSIGNED AUTO_INCREMENT,
    id_alumno INT UNSIGNED NOT NULL,
    f_asistencia DATE NOT NULL,
		CONSTRAINT pk_asistencias PRIMARY KEY (id),
        CONSTRAINT fk_asistencias FOREIGN KEY (id_alumno) REFERENCES alumnos(id)
			ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT uq_asistencias UNIQUE(id_alumno, f_asistencia)
)CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

CREATE TABLE faltas(
	id INT UNSIGNED AUTO_INCREMENT,
    id_alumno INT UNSIGNED NOT NULL,
    f_falta DATE NOT NULL,
    emisor_aviso VARCHAR(255),
    h_aviso TIME,
		CONSTRAINT pk_faltas PRIMARY KEY (id),
        CONSTRAINT fk_faltas FOREIGN KEY (id_alumno) REFERENCES alumnos(id)
			ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT uq_faltas UNIQUE(id_alumno, f_falta)
)CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

CREATE TABLE actividades_alumno(
	id INT UNSIGNED AUTO_INCREMENT,
    id_alumno INT UNSIGNED NOT NULL,
    id_actividad INT UNSIGNED NOT NULL,
		CONSTRAINT pk_actividades_alumno PRIMARY KEY (id),
        CONSTRAINT fk_actividades_alumnos FOREIGN KEY (id_alumno) REFERENCES alumnos(id)
			ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT fk_actividades_actividades FOREIGN KEY (id_actividad) REFERENCES actividades(id)
			ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT uq_actividades_alumno UNIQUE(id_alumno, id_actividad)
)CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

CREATE TABLE usuarios(
	id INT UNSIGNED AUTO_INCREMENT,
    nombre VARCHAR (100) NOT NULL,
    apellido1 VARCHAR (100) NOT NULL,
    apellido2 VARCHAR (100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    pass VARCHAR(255) NOT NULL,
    rol ENUM("Administrador", "Profesor"),
		CONSTRAINT pk_usuarios PRIMARY KEY (id),
        CONSTRAINT uq_usuarios UNIQUE(nombre, email)
)CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

/*
Para crear un nuevo usuario en MySQL, usamos la siguiente sintaxis:

CREATE USER "usuario"@"hostname" IDENTIFIED BY "password";

-- Es importante tener en cuenta que las comillas se deben mantener --

IMPORTANTE:

-- "localhost" significa que el usuario solo puede conectarse desde la misma máquina donde se ejecuta el servidor MySQL, mientras que "%" significa que el usuario puede conectarse desde cualquier host. También puede especificar una dirección IP o un nombre de dominio específicos.

Otorgamiento de privilegios:

GRANT SELECT, INSERT, UPDATE, DELETE ON mibasededatos.* TO "usuario_de_prueba"@"%"; 
GRANT ALL PRIVILEGES ON mydatabase.* TO "testuser"@"%";

Limpieza de privilegios:
Después de crear usuarios y otorgar privilegios, conviene limpiarlos para garantizar que los cambios surtan efecto inmediatamente.


FLUSH PRIVILEGES;
*/

-- Este usuario/contraseña puede ser cambiado de acuerdo a las necesidades de la aplicación.
CREATE USER IF NOT EXISTS "gestion_reicom"@"localhost" IDENTIFIED BY "oido_cocina123!";
GRANT ALL PRIVILEGES ON reicom.* TO "gestion_reicom"@"localhost";

