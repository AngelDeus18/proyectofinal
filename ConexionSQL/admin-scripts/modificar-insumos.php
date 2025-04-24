<?php
include __DIR__ . '/../conexion.php';

$response = ['success' => false, 'message' => 'Error al actualizar el insumo.'];

if (!empty($_POST["nombre"]) && !empty($_POST["descripcion"]) && !empty($_POST["estado"]) && !empty($_POST["fecha-registro"])) {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $estado = $_POST["estado"];
    $fhregistro = $_POST["fecha-registro"];
    $id = $_POST["id"];

    if (isset($id) && is_numeric($id)) {
        $sql = $conn->query("UPDATE insumos SET NomInsumo='$nombre', Descripcion='$descripcion', Estado='$estado', FechaRegistro='$fhregistro' WHERE id='$id'");

        if ($sql) {
            $response = ['success' => true, 'message' => 'Insumo actualizado correctamente.'];
        } else {
            $response['message'] = 'Error al actualizar: ' . $conn->error;
        }
    } else {
        $response['message'] = 'ID no válido.';
    }
} else {
    $response['message'] = 'Campos vacíos, por favor rellene todos los campos.';
}

echo json_encode($response);
?>
