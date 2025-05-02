<?php
include __DIR__ . '/../conexion.php';

$responese = ['success' => false, 'message' => 'Error al actualizar el usuario.'];

if (!empty($_POST["nombre"]) && !empty($_POST["email"]) && !empty($_POST["cedula"]) && !empty($_POST["contraseña"])) {
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $cedula = $_POST["cedula"];
    $contraseña = $_POST["contraseña"];
    $id_roles = $_POST["rol"];
    $id = $_POST["id"];

    if (isset($id) && is_numeric($id)) {
        $sql = $conn->query("UPDATE usuarios SET nombre='$nombre', email='$email', cedula='$cedula', contraseña='$contraseña', id_roles='$id_roles' WHERE id='$id'");

        if ($sql) {
            $responese = ['success' => true, 'message' => 'Usuario actualizado correctamente.'];
        } else {
            $responese['message'] = 'Error al actualizar: ' . $conn->error;
        }
    } else {
        $responese['message'] = 'ID no válido.';
    }
} else {
    $responese['message'] = 'Campos vacíos, por favor rellene todos los campos.';
}
echo json_encode($responese);
?>