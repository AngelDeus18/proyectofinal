<?php 
include __DIR__ . '/../conexion.php';

if (!empty($_GET["id"])) {
    $id = $_GET["id"];
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../../php/administrador/administrador-gestion-usuario.php");
        exit(); 
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
