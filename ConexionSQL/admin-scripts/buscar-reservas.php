<?php
include '../../ConexionSQL/conexion.php';

$q = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';

$sql = "SELECT r.id, u.nombre AS NombreUsuario, u.cedula AS CedulaUsuario, 
               t.nombre AS NomInsumo, i.Descripcion, r.CantidadPrestada, 
               r.Estado AS EstadoInsumo, r.FechaInicio, r.FechaFin
        FROM reservas r
        JOIN usuarios u ON r.UsuarioID = u.id
        JOIN insumos i ON r.InsumoID = i.id
        JOIN tipos_insumos t ON i.tipo_id_nombre = t.id
        WHERE LOWER(u.nombre) LIKE '%$q%'
           OR LOWER(u.cedula) LIKE '%$q%'
           OR LOWER(t.nombre) LIKE '%$q%'
           OR LOWER(i.Descripcion) LIKE '%$q%'
           OR LOWER(r.Estado) LIKE '%$q%'
           OR LOWER(r.FechaInicio) LIKE '%$q%'
           OR LOWER(r.FechaFin) LIKE '%$q%'
           OR CAST(r.CantidadPrestada AS CHAR) LIKE '%$q%'
           OR CAST(r.id AS CHAR) LIKE '%$q%'"; 

$resultado = $conn->query($sql);
$data = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
?>
