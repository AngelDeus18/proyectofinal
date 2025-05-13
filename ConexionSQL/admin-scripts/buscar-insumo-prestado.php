<?php
include "../../ConexionSQL/conexion.php";

$busqueda = $_GET['q'] ?? '';
$busqueda = strtolower(trim($busqueda));

$sql = "SELECT 
            r.id AS reserva_id,
            t.nombre AS nomInsumo,
            i.Descripcion AS insumo,
            r.CantidadPrestada AS cantidad,
            u.nombre AS nombre_funcionario,
            u.cedula AS cedula_funcionario
        FROM Reservas r
        INNER JOIN usuarios u ON r.UsuarioID = u.id
        INNER JOIN Insumos i ON r.InsumoID = i.id
        INNER JOIN tipos_insumos t ON i.tipo_id_nombre = t.id
        WHERE r.Estado = 'Prestado' AND (
            LOWER(t.nombre) LIKE '%$busqueda%' OR 
            LOWER(i.Descripcion) LIKE '%$busqueda%' OR 
            LOWER(u.nombre) LIKE '%$busqueda%' OR 
            u.cedula LIKE '%$busqueda%' OR 
            CAST(r.CantidadPrestada AS CHAR) LIKE '%$busqueda%'
        )
        ORDER BY r.id ASC";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
