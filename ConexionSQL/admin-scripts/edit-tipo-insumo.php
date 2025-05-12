<?php
header('Content-Type: application/json');
include __DIR__ . '/../conexion.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['tipoinsumo'])) {
    $id = $_POST['id'];
    $tipoInsumo = $_POST['tipoinsumo'];

    $stmt = $conn->prepare("UPDATE tipos_insumos SET nombre = ? WHERE id = ?");
    $stmt->bind_param("si", $tipoInsumo, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response['success'] = true;
            $response['message'] = "Tipo de insumo actualizado correctamente.";
        } else {
            $response['success'] = false;
            $response['message'] = "No se encontraron tipos de insumo con ese ID o no se realizaron cambios.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Error al actualizar el tipo de insumo: " . $stmt->error;
    }

    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "Solicitud inválida: Se requiere ID y tipoinsumo.";
}

echo json_encode($response);
?>