<?php
include __DIR__ . '/../conexion.php';

if (!empty($_GET["id"])) {
    $id = $_GET["id"];

    $sqlObtenerInsumoID = $conn->prepare("SELECT InsumoID FROM reservas WHERE id = ?");
    $sqlObtenerInsumoID->bind_param("i", $id);
    $sqlObtenerInsumoID->execute();
    $sqlObtenerInsumoID->bind_result($insumoID);
    $sqlObtenerInsumoID->fetch();
    $sqlObtenerInsumoID->close();

    $sqlEliminarReserva = $conn->prepare("DELETE FROM reservas WHERE id = ?");
    $sqlEliminarReserva->bind_param("i", $id);

    if ($sqlEliminarReserva->execute()) {
        $sqlActualizarInsumo = "UPDATE insumos SET Estado = 'Disponible' WHERE id = ?";
        $stmtActualizarInsumo = $conn->prepare($sqlActualizarInsumo);
        $stmtActualizarInsumo->bind_param("i", $insumoID);

        if ($stmtActualizarInsumo->execute()) {
            header("location: /proyectofinal/php/administrador/admin-reservas.php");
            exit();
        } else {
            echo "Problemas al actualizar el estado del insumo: " . $stmtActualizarInsumo->error;
        }

        $stmtActualizarInsumo->close();
    } else {
        echo "Error al eliminar la reserva: " . $sqlEliminarReserva->error;
    }

    $sqlEliminarReserva->close();
}
?>
