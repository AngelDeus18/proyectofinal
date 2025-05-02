<?php 
include __DIR__ . '/../conexion.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si los campos están vacíos
    if (!empty($_POST["nombre"]) && !empty($_POST["email"]) && !empty($_POST["cedula"]) && !empty($_POST["contraseña"]) && !empty($_POST["rol"])) {

        // Verificar si la cédula ya está en uso
        $cedula = $_POST["cedula"];
        $checkSql = "SELECT id FROM usuarios WHERE cedula = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $cedula);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $response['message'] = ' La cédula ya está en uso.';
        } else {
            // Insertar usuario
            $sql = "INSERT INTO usuarios (nombre, email, cedula, contraseña, id_roles) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisi", $_POST["nombre"], $_POST["email"], $_POST["cedula"], $_POST["contraseña"], $_POST["rol"]);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Usuario registrado correctamente.';
                $response['insert_id'] = $stmt->insert_id;
            } else {
                $response['message'] = '❌ Error al registrar el usuario: ' . $stmt->error;
            }

            $stmt->close();
        }

        $checkStmt->close();
    } else {
        $response['message'] = 'Todos los campos son obligatorios.';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

header('Content-Type: application/json');
echo json_encode($response);
?>
