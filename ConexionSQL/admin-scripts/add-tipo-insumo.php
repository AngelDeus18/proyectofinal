<?php
header('Content-Type: application/json');
include __DIR__ . '/../conexion.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tipoinsumo'])) {
    $tipoInsumo = $_POST['tipoinsumo'];

    $stmt = $conn->prepare("INSERT INTO tipos_insumos (nombre) VALUES (?)");
    $stmt->bind_param("s", $tipoInsumo);

    if ($stmt->execute()) {
        $lastInsertId = $stmt->insert_id; 
        $response['success'] = true;
        $response['message'] = "Nuevo tipo de insumo agregado correctamente.";
        $response['insert_id'] = $lastInsertId;
    } else {
        $response['success'] = false;
        $response['message'] = "Error al agregar tipo de insumo: " . $stmt->error;
    }

    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "Solicitud inválida.";
}

echo json_encode($response);
?>
