<?php
header('Content-Type: application/json');
include __DIR__ . '/../conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['id'])) {
    $reserva_id = $_GET['id'];

    $sql = "SELECT * FROM Reservas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reserva_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $reserva = $result->fetch_assoc();
        $cantidad = $reserva['CantidadPrestada'];
        $insumo_id = $reserva['InsumoID'];

        $sqlUpdateReserva = "UPDATE Reservas SET Estado = 'Devuelto' WHERE id = ?";
        $stmtReserva = $conn->prepare($sqlUpdateReserva);
        $stmtReserva->bind_param("i", $reserva_id);
        $stmtReserva->execute();

        $sqlUpdateInsumo = "UPDATE Insumos SET Cantidad = Cantidad + ? WHERE id = ?";
        $stmtInsumo = $conn->prepare($sqlUpdateInsumo);
        $stmtInsumo->bind_param("ii", $cantidad, $insumo_id);
        $stmtInsumo->execute();

        if ($stmtReserva->affected_rows > 0 && $stmtInsumo->affected_rows > 0) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "No se pudo actualizar la base de datos."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Reserva no encontrada."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Solicitud inválida."]);
}
