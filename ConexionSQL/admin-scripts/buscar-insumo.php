<?php
include __DIR__ . '/../conexion.php';

if (isset($_POST['searchTerm'])) {
    $searchTerm = $conn->real_escape_string($_POST['searchTerm']);
    $sql = "SELECT Insumos.id, tipos_insumos.nombre AS TipoNombre, Insumos.Descripcion, Insumos.Cantidad, Insumos.Estado, Insumos.FechaRegistro
            FROM Insumos
            LEFT JOIN tipos_insumos ON Insumos.tipo_id_nombre = tipos_insumos.id
            WHERE tipos_insumos.nombre LIKE '%$searchTerm%'
               OR Insumos.Descripcion LIKE '%$searchTerm%'
               OR Insumos.Cantidad LIKE '%$searchTerm%'
               OR Insumos.Estado LIKE '%$searchTerm%'";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($datos = $result->fetch_object()) {
            echo "<tr>
                    <td>{$datos->id}</td>
                    <td>" . htmlspecialchars($datos->TipoNombre) . "</td>
                    <td class='descripcion'>" . htmlspecialchars($datos->Descripcion) . "</td>
                    <td>{$datos->Cantidad}</td>
                    <td>{$datos->Estado}</td>
                    <td>{$datos->FechaRegistro}</td>
                    <td class='acciones'>
                        <a id='editar-{$datos->id}' class='my-button-editar'>Editar</a>
                        <button type='button' class='my-button-eliminar' data-id='{$datos->id}'>Eliminar</button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No se encontraron insumos con ese término.</td></tr>";
    }
} else {
    echo "<tr><td colspan='7'>Término de búsqueda no proporcionado.</td></tr>";
}

$conn->close();
