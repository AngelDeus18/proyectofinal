<?php
include __DIR__ . '/../conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["insprestado"]) && !empty($_POST["fecha-prestamo"]) && !empty($_POST["fecha-entrega"])) {
    $usuarioID = $_POST["UsuarioID"];
    $insumoID = $_POST["InsumoID"];
    $fechaInicio = date("Y-m-d H:i:s", strtotime($_POST["fecha-prestamo"]));
    $fechaFin = date("Y-m-d H:i:s", strtotime($_POST["fecha-entrega"]));
    $estadoReserva = "No disponible";

    $sqlReserva = "INSERT INTO Reservas (UsuarioID, InsumoID, FechaInicio, FechaFin, Estado) VALUES (?, ?, ?, ?, ?)";
    $stmtReserva = $conn->prepare($sqlReserva);
    $stmtReserva->bind_param("iissi", $usuarioID, $insumoID, $fechaInicio, $fechaFin, $estadoReserva);

    if ($stmtReserva->execute()) {
        $sqlActualizarInsumo = "UPDATE Insumos SET Estado = 'No disponible' WHERE id = ?";
        $stmtActualizarInsumo = $conn->prepare($sqlActualizarInsumo);
        $stmtActualizarInsumo->bind_param("i", $insumoID);
        
        if ($stmtActualizarInsumo->execute()) {
            header("Location: /proyectofinal/php/funcionario/funcionario-insumos.php");
        } else {
            echo "Problemas al actualizar el estado del insumo: " . $stmtActualizarInsumo->error;
        }

        $stmtActualizarInsumo->close();
    } else {
        echo "Problemas al dar de alta la reserva: " . $stmtReserva->error;
    }

    $stmtReserva->close();
} else {
    echo "Campos vacíos o método incorrecto, Recuerda llenar la fecha de salida";
}
?>