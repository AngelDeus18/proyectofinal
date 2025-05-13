DROP DATABASE IF EXISTS Datos;

CREATE DATABASE Datos;

USE Datos;

-- Tabla de roles
CREATE TABLE
    roles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descripcion VARCHAR(250) NOT NULL
    );

CREATE TABLE
    usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(250) NOT NULL,
        email VARCHAR(250) NOT NULL,
        cedula INT NOT NULL UNIQUE,
        contraseña VARCHAR(250),
        id_roles INT,
        FOREIGN KEY (id_roles) REFERENCES roles (id) ON DELETE CASCADE ON UPDATE CASCADE
    );

CREATE TABLE
    tipos_insumos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL UNIQUE
    );

CREATE TABLE
    Insumos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tipo_id_nombre INT,
        Descripcion TEXT,
        Cantidad INT DEFAULT 1,
        Estado VARCHAR(15),
        FechaRegistro DATETIME,
        FOREIGN KEY (tipo_id_nombre) REFERENCES tipos_insumos (id) ON DELETE SET NULL ON UPDATE CASCADE
    );

CREATE TABLE
    Reservas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        UsuarioID INT,
        InsumoID INT,
        FechaInicio DATETIME,
        FechaFin DATETIME DEFAULT NULL,
        Estado ENUM ('Pendiente', 'Prestado', 'Devuelto') DEFAULT 'Pendiente',
        CantidadPrestada INT NOT NULL DEFAULT 1,
        FOREIGN KEY (UsuarioID) REFERENCES usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (InsumoID) REFERENCES Insumos (id) ON DELETE CASCADE ON UPDATE CASCADE,
        CHECK (
            FechaInicio < FechaFin
            OR FechaFin IS NULL
        )
    );

INSERT INTO
    roles (id, descripcion)
VALUES
    (1, 'Administrador'),
    (2, 'Supervisor'),
    (3, 'Funcionario');

INSERT INTO
    usuarios (id, nombre, email, cedula, contraseña, id_roles)
VALUES
    (1, 'Angel', 'jorge@gmail.com', 1, 'admin', 1),
    (2, 'Pablo', 'pablo@gmail.com', 123, 'pablo123', 2),
    (
        3,
        'Pedro',
        'pedro@gmail.com',
        3,
        'funcionario',
        3
    );

INSERT INTO
    `tipos_insumos` (`id`, `nombre`)
VALUES
    (5, 'Borrador'),
    (7, 'Lapiceros'),
    (3, 'Marcadores'),
    (8, 'Mouse'),
    (2, 'Portatiles'),
    (1, 'Salones'),
    (9, 'Tablets'),
    (6, 'Teclados '),
    (4, 'VideoBean');