
CREATE DATABASE IF NOT EXISTS sistemaglobal9;
USE sistemaglobal9;

-- Tabla: alumno
CREATE TABLE alumno (
    carnet VARCHAR(6),
    apellido VARCHAR(20)
);

-- Tabla: detalle

--ESTA TABLA CONTIENE INFORMACION PERO SOLO LA UEDO VER EN ITCA-FEPADE
CREATE TABLE detalle (
    id_detalle INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_d INT(10) NULL,
    id_g INT(10) NULL,
    id_m INT(10) NULL,
    aula VARCHAR(20) NULL,
    ha TIME NULL,
    hf TIME NULL,
    ciclo VARCHAR(4) NULL,
    year INT(4) NULL,
    dia VARCHAR(25) NULL,
    grupo VARCHAR(20) NULL,
    tipo VARCHAR(4) NULL,
    horas INT(2) NULL,
    version VARCHAR(2) NULL,
    fechaini DATE NULL,
    fechafin DATE NULL,
    comentarioreserva BLOB NULL,
    cametusuario VARCHAR(20) NULL,
    cod_alldetalle VARCHAR(100) NULL
);

-- Tabla: docente
CREATE TABLE docente (
    id_docente INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom_usuario VARCHAR(50) NULL,
    ape_usuario VARCHAR(50) NULL
);
