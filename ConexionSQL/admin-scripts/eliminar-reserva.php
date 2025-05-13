<?php
include __DIR__ . '/../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $idReserva = (int)$_GET['id'];

    $sqlEliminarReserva = "DELETE FROM Reservas WHERE id = ?";
    $stmtEliminarReserva = $conn->prepare($sqlEliminarReserva);

    if ($stmtEliminarReserva) {
        $stmtEliminarReserva->bind_param("i", $idReserva);

        if ($stmtEliminarReserva->execute()) {
            header("Location: /proyectofinal/php/administrador/admin-reservas.php?mensaje=eliminado");
            exit;
        } else {
            echo "Error al eliminar la reserva: " . $stmtEliminarReserva->error;
        }

        $stmtEliminarReserva->close();
    } else {
        echo "Error al preparar la eliminación: " . $conn->error;
    }
}
?>
