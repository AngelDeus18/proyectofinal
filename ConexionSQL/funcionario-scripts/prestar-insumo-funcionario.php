<?php 
include __DIR__ . '/../conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["insprestado"]) && !empty($_POST["cantidad"]) && !empty($_POST["fecha-prestamo"]) && !empty($_POST["fecha-entrega"])) {
    $usuarioID = $_POST["UsuarioID"];
    $insumoID = $_POST["InsumoID"];
    $cantidadPrestada = (int)$_POST["cantidad"];
    $fechaInicio = date("Y-m-d H:i:s", strtotime($_POST["fecha-prestamo"]));
    $fechaFin = date("Y-m-d H:i:s", strtotime($_POST["fecha-entrega"]));
    $estadoReserva = "Prestado";
    
    // Verificar cantidad disponible en el insumo
    $sqlCantidad = "SELECT Cantidad FROM Insumos WHERE id = ?";
    $stmtCantidad = $conn->prepare($sqlCantidad);
    $stmtCantidad->bind_param("i", $insumoID);
    $stmtCantidad->execute();
    $resultado = $stmtCantidad->get_result();
    $fila = $resultado->fetch_assoc();

    if (!$fila) {
        echo "Insumo no encontrado.";
        exit;
    }

    $cantidadDisponible = (int)$fila['Cantidad'];

    if ($cantidadPrestada > $cantidadDisponible) {
        echo "No hay suficiente cantidad disponible del insumo.";
        exit;
    }

    // Insertar la reserva en la tabla Reservas
    $sqlReserva = "INSERT INTO Reservas (UsuarioID, InsumoID, FechaInicio, FechaFin, Estado, CantidadPrestada) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtReserva = $conn->prepare($sqlReserva);
    $stmtReserva->bind_param("iisssi", $usuarioID, $insumoID, $fechaInicio, $fechaFin, $estadoReserva, $cantidadPrestada);

    if ($stmtReserva->execute()) {
        $nuevaCantidad = $cantidadDisponible - $cantidadPrestada;

        $stmtUpdate = $conn->prepare("UPDATE Insumos SET Cantidad = ? WHERE id = ?");
        $stmtUpdate->bind_param("ii", $nuevaCantidad, $insumoID);
        $stmtUpdate->execute();

        header("Location: /proyectofinal/php/funcionario/funcionario-insumos.php");
    } else {
        echo "Problemas al dar de alta la reserva: " . $stmtReserva->error;
    }

    $stmtReserva->close();
} else {
    echo "Campos vacíos o método incorrecto, recuerda llenar todos los campos requeridos.";
}
?>
