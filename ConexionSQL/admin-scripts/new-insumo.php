<?php
include __DIR__ . '/../conexion.php';
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST["nombre"]) && !empty($_POST["descripcion"]) && !empty($_POST["estado"]) && !empty($_POST["fecha-registro"])) {
        $sql = "INSERT INTO insumos (NomInsumo, Descripcion, Estado, FechaRegistro) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $_POST["nombre"], $_POST["descripcion"], $_POST["estado"], $_POST["fecha-registro"]);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Insumo registrado correctamente.';
            $response['insert_id'] = $stmt->insert_id;
        } else {
            $response['message'] = 'Error al registrar el insumo: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = 'Todos los campos son obligatorios.';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
