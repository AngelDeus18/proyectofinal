<?php 
include __DIR__ . '/../conexion.php';

header('Content-Type: application/json');

$respose= ['success' => false, 'message' => 'ID no válido'];

if (!empty($_GET["id"])) {
    $id = $conn->real_escape_string($_GET["id"]);
    $sql = $conn->query("DELETE FROM usuarios WHERE id='$id'");

    if ($sql) {
        $respose = ['success' => true, 'message' => 'Usuario eliminado correctamente'];
    } else {
        $respose = ['success' => false, 'message' => 'Error al eliminar: ' . $conn->error];
    }
}
echo json_encode($respose);
?>
