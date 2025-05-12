<?php
include '../../ConexionSQL/conexion.php';

if (isset($_POST['searchTerm'])) {
    $searchTerm = $conn->real_escape_string($_POST['searchTerm']);

    $sql = "SELECT id, nombre FROM tipos_insumos WHERE nombre LIKE '%" . $searchTerm . "%' ORDER BY id ASC";
    $result = $conn->query($sql);

    $resultados = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
        }
    }

    header('Content-Type: application/json'); // Importante: Establecer el tipo de contenido como JSON
    echo json_encode($resultados); // Devolver solo el JSON
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'No se proporcionó el término de búsqueda.']);
}

$conn->close();
?>