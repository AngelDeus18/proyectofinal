<?php
include __DIR__ . '/../conexion.php';

var_dump($_POST);
if (!empty($_POST["nombre"]) && !empty($_POST["descripcion"]) && !empty($_POST["estado"]) && !empty($_POST["fecha-registro"])) {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $estado = $_POST["estado"];
    $fhregistro = $_POST["fecha-registro"];
    $id = $_POST["id"];

    $sql = $conn->query("UPDATE insumos SET NomInsumo='$nombre', Descripcion='$descripcion', Estado='$estado', FechaRegistro='$fhregistro' WHERE id='$id'");
    if ($sql) {
        header("location: /proyectofinal/php/administrador/admin-insumos.php");
        exit();
    } else {
        echo "Error al actualizar el usuario: " . $conn->error;
    }
} else {
    echo "Campos vacíos";
}
?>