<?php 
include __DIR__ . '/../conexion.php';

if (!empty($_POST["nombre"]) && !empty($_POST["email"]) && !empty($_POST["cedula"]) && !empty($_POST["contraseña"]) && !empty($_POST["rol"])) {

    $sql = "INSERT INTO usuarios (nombre, email, cedula, contraseña, id_roles) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $_POST["nombre"], $_POST["email"], $_POST["cedula"], $_POST["contraseña"], $_POST["rol"]);

    if ($stmt->execute()) {
        header("Location: /proyectofinal/php/administrador/administrador-gestion-usuario.php");
        exit();
    } else {
        echo "Problemas al dar de alta el usuario: " . $stmt->error;
    }

    $stmt->close();
}
?>