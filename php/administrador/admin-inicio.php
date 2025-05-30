<?php
session_start();
$rolesPermitidos = [1];

include "../../ConexionSQL/verificar-acceso.php";
$pageTitle = 'Inicio Administración'; 
include '../../assets/includes/header.php'; 
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/inicio-admin.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu.css">
    <link rel="stylesheet" href="../fontawesome/fontawesome-free-6.5.1-web/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@500&family=Phudu:wght@500&family=Prompt:ital,wght@1,900&family=Rubik:wght@500&family=Urbanist&display=swap"
        rel="stylesheet">
    <title>Document</title>
</head>
<main class="welcome-container">
    <div class="welcome-text">
        <h1>Bienvenido <?php echo $_SESSION['nombre'] ?? ''; ?> a nuestro Sistema</h1>
        <p> Confiamos en que puedas realizar préstamos de insumos de manera cómoda a través de Software 4U.
            <br>Estamos aquí para hacer que tu experiencia sea lo más fácil y conveniente posible."
        </p>
        <div class="cta-button"><i class="fa-solid fa-handshake-simple"></i></div>
    </div>
</main>
</body>
<script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>

</html>