<?php
include __DIR__ . '/../conexion.php';
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !empty($_POST["nombre"]) &&
        !empty($_POST["descripcion"]) &&
        !empty($_POST["cantidad"]) &&
        !empty($_POST["estado"]) &&
        !empty($_POST["fecha-registro"])
    ) {
        $tipo_id = $_POST["nombre"];

        // Consulta para obtener el nombre del tipo de insumo
        $sql_nombre = "SELECT nombre FROM tipos_insumos WHERE id = ?";
        $stmt_nombre = $conn->prepare($sql_nombre);
        $stmt_nombre->bind_param("i", $tipo_id);
        $stmt_nombre->execute();
        $result_nombre = $stmt_nombre->get_result();
        $nombre_tipo_insumo = '';
        if ($row_nombre = $result_nombre->fetch_assoc()) {
            $nombre_tipo_insumo = $row_nombre['nombre'];
        }
        $stmt_nombre->close();

        // Insertar el insumo usando el ID del tipo
        $stmt = $conn->prepare("INSERT INTO insumos (tipo_id_nombre, Descripcion, Cantidad, Estado, FechaRegistro) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssiss",
            $tipo_id, // Usamos el ID aquí para la inserción en la base de datos
            $_POST["descripcion"],
            $_POST["cantidad"],
            $_POST["estado"],
            $_POST["fecha-registro"]
        );

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Insumo registrado correctamente.';
            $response['insert_id'] = $stmt->insert_id;
            $response['nombre_tipo_insumo'] = $nombre_tipo_insumo; // Enviamos el nombre
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
?>