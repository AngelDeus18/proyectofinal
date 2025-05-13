<?php
include "../../ConexionSQL/conexion.php";

$sql = "SELECT Insumos.id, tipos_insumos.nombre AS TipoNombre, Insumos.Descripcion, Insumos.Cantidad, Insumos.Estado 
        FROM Insumos
        LEFT JOIN tipos_insumos ON Insumos.tipo_id_nombre = tipos_insumos.id
        WHERE Insumos.Estado = 'Disponible' AND Insumos.Cantidad > 0";

$result = $conn->query($sql);

$insumos = [];
while ($row = $result->fetch_assoc()) {
    $insumos[] = $row; 
}

echo json_encode($insumos); 
$conn->close();
?>
