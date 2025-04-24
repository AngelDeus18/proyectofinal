<?php
include "../ConexionSQL/validar.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@500&family=Phudu:wght@500&family=Prompt:ital,wght@1,900&family=Rubik:wght@500&family=Urbanist&display=swap"
        rel="stylesheet">
    <title>Software 4U</title>
</head>

<body>
    <ul class="background">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
    <div class="formulario">
        <h1>¡Bienvenido de nuevo 👋!</h1>
        <?= $mensaje ?>
        <form method="post" onsubmit="mostrarLoader()">
            <div class="container">
                <div class="congrup">
                    <input type="text" id="user" class="form_input" placeholder=" " name="codigo" required>
                    <label for="user" class="form_label">Tu código de usuario</label>
                </div>
                <div class="congrup">
                    <input type="password" id="contra" class="form_input" placeholder=" " name="contraseña" required>
                    <label for="contra" class="form_label">Tu clave contraseña</label>
                </div>
                <div class="extras">
                    <label><input type="checkbox" onclick="togglePassword()"> Mostrar contraseña</label>
                </div>
                <input class="form_submit" type="submit" value="Iniciar sesión">
                <div class="loader" id="loader"></div>
            </div>
        </form>
        <script>
            function togglePassword() {
                const input = document.getElementById("contra");
                input.type = input.type === "password" ? "text" : "password";
            }

            function mostrarLoader() {
                document.getElementById("loader").style.display = "block";
            }
        </script>
</body>

</html>