<?php 
include __DIR__ . '/../conexion.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'ID no válido'];

if (!empty($_GET["id"])) {
    $id = $conn->real_escape_string($_GET["id"]);
    $sql = $conn->query("DELETE FROM insumos WHERE id='$id'");

    if ($sql) {
        $response = ['success' => true, 'message' => 'Insumo eliminado correctamente'];
    } else {
        $response = ['success' => false, 'message' => 'Error al eliminar: ' . $conn->error];
    }
}

echo json_encode($response);
