<?php
include __DIR__ . '/../conexion.php';
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST["nombre"]) && !empty($_POST["descripcion"]) && !empty($_POST["estado"]) && !empty($_POST["fecha-registro"])) {
        $sql = "INSERT INTO insumos (NomInsumo, Descripcion, Estado, FechaRegistro) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $_POST["nombre"], $_POST["descripcion"], $_POST["estado"], $_POST["fecha-registro"]);

        if ($stmt->execute()) {
            $mensaje = '<div class="alert success">✅ Registrando insumo correctamente.</div>';
            echo '$mensaje';
        } else {
            $mensaje = '<div class="alert eror">✅ Error al registrar el insumo: ' . $stmt->error . '</div>';
            echo '$mensaje ' . $stmt->error . '</div>';
        }

        $stmt->close();
    } else {
        $mensaje = '<div class="alert error">⚠️ Todos los campos son obligatorios.</div>';
        echo '$mensaje';
    }
}
