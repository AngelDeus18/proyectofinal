CREATE DATABASE Datos;

USE Datos;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(250) NOT NULL
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(250) NOT NULL,
    email VARCHAR(250) NOT NULL,
    cedula INT NOT NULL UNIQUE,
    contraseña VARCHAR(250),
    id_roles INT,
    FOREIGN KEY (id_roles) REFERENCES roles (id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Insumos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    NomInsumo VARCHAR(100),
    Descripcion TEXT,
    Estado VARCHAR(15),
    FechaRegistro DATETIME
);

CREATE TABLE Reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    UsuarioID INT,
    InsumoID INT,
    FechaInicio DATETIME,
    FechaFin DATETIME DEFAULT NULL,
    Estado VARCHAR(15),
    FOREIGN KEY (UsuarioID) REFERENCES usuarios(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (InsumoID) REFERENCES Insumos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CHECK (FechaInicio < FechaFin OR FechaFin IS NULL)
);

INSERT INTO `roles` (`id`, `descripcion`) VALUES
(1, 'Administrador'), (2, 'Supervisor'), (3, 'Funcionario');

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `cedula`, `contraseña`, `id_roles`) VALUES
(1, 'Angel', 'jorge@gmail.com', 12, 'angel123', 1), (2, 'Pablo', 'pablo@gmail.com', 123, 'pablo123', 2), (3, 'Pedro', 'pedro@gmail.com',1234, 'pedro123', 3);
INSERT INTO `insumos` (`id`, `NomInsumo`, `Descripcion`, `Estado`, `FechaRegistro`) VALUES
(1, 'Laptop', 'hp#14', 'Disponible', '2023-11-08 11:18:00'),
(2, 'Laptop', 'Asus #15', 'Disponible', '2023-11-08 11:18:00'),
(3, 'VideoBeam', 'Lg #20 Problemas de proyeccion', 'No disponibe', '2023-11-08 11:18:00'),
(4, 'VideoBeam', 'Sony #10', 'Disponible', '2023-11-08 11:18:00'),
(5, 'Mouse', 'logitech #2', 'Disponible', '2023-11-08 11:18:00'),
(6, 'Laptop', 'Lenovo #16 Problemas sistema', 'Disponible', '2023-11-08 11:18:00');