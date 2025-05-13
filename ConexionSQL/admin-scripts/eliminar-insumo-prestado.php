<?php
include __DIR__ . '/../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reserva_id = $_POST['reserva_id'];
    $cantidad = $_POST['cantidad'];
    $insumo_id = $_POST['insumo_id'];

    $sqlUpdateReserva = "UPDATE Reservas SET Estado = 'Devuelto' WHERE id = ?";
    $stmtReserva = $conn->prepare($sqlUpdateReserva);
    $stmtReserva->bind_param("i", $reserva_id);
    $stmtReserva->execute();

    $sqlUpdateInsumo = "UPDATE Insumos SET Cantidad = Cantidad + ? WHERE id = ?";
    $stmtInsumo = $conn->prepare($sqlUpdateInsumo);
    $stmtInsumo->bind_param("ii", $cantidad, $insumo_id);
    $stmtInsumo->execute();

    header("Location: ../../php/administrador/admin-insumo-prestado.php");
    exit();
}
?>
