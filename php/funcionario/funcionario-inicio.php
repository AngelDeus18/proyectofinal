<?php
session_start();
$rolesPermitidos = [3];

include "../../ConexionSQL/conexion.php";
include "../../ConexionSQL/verificar-acceso.php";

if (isset($_SESSION['usuario_id']) && isset($_SESSION['nombre'])) {
    $nombreUsuario = $_SESSION['nombre'];
} else {
    $nombreUsuario = "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/inicio-admin.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/alerts.css">
    <link rel="stylesheet" href="../fontawesome/fontawesome-free-6.5.1-web/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@500&family=Phudu:wght@500&family=Prompt:ital,wght@1,900&family=Rubik:wght@500&family=Urbanist&display=swap"
        rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <nav>
        <input type="checkbox" id="toogle">
        <div class="logo"> Software 4U</div>
        <ul class="list">
            <li><a href="funcionario-inicio.php">Inicio</a></li>
            <li><a href="funcionario-insumos.php">Insumos</a></li>
            <i class="fa-solid fa-user"></i>
            <li>
                <?php echo $nombreUsuario . " "; ?>
            </li>
            <li><a href="../../ConexionSQL/cerrar.php">Salir</a></li>
        </ul>
        <label for="toogle" class="icon-bars">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </label>
    </nav>
    <main class="welcome-container">
        <div class="welcome-text">
            <h1>Bienvenido estimado funcionario
                <?php echo $nombreUsuario . " "; ?> a nuestro Sistema
            </h1>
            <p> Confiamos en que puedas realizar préstamos de insumos de manera cómoda a través de Software 4U.
                <br>Estamos aquí para hacer que tu experiencia sea lo más fácil y conveniente posible."
            </p>
            <div class="cta-button"><i class="fa-solid fa-handshake-simple"></i></div>
        </div>
    </main>
    
</body>
<script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>
</html>