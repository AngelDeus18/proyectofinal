<?php
include "../../ConexionSQL/conexion.php";

if (isset($_SESSION['usuario_id']) && isset($_SESSION['nombre'])) {
    $nombreUsuario = $_SESSION['nombre'];
} else {
    $nombreUsuario = "";
}

$nombreUsuario = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Mi Aplicación'; ?></title>
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu.css">
</head>

<body>
    <nav>
        <input type="checkbox" id="toogle">
        <div class="logo"> Software 4U</div>
        <ul class="list">
            <li><a href="admin-inicio.php">Inicio</a></li>
            <li><a href="administrador-gestion-usuario.php">Usuarios</a></li>
            <li><a href="admin-insumos.php">Insumos</a></li>
            <i class="fa-solid fa-user"></i>
            <li>
                <?php echo $_SESSION['nombre'] ?? ''; ?>
            </li>
            <li><a href="../../ConexionSQL/cerrar.php">Salir</a></li>
        </ul>
        <label for="toogle" class="icon-bars">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </label>
    </nav>
</body>