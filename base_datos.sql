CREATE DATABASE IF NOT EXISTS practica_alumnado CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE practica_alumnado;

CREATE TABLE IF NOT EXISTS alumnado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    curso ENUM('1 ESO','2 ESO','3 ESO','4 ESO') NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL
);

-- La comprobación del máximo de 25 alumnos por curso se realiza desde PHP.
