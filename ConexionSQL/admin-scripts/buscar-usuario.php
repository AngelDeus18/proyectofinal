<?php
include __DIR__ . '/../conexion.php';

if (isset($_POST['searchTerm'])) {
    $searchTerm = $conn->real_escape_string($_POST['searchTerm']);
    $sql = "SELECT Usuarios.id, Usuarios.nombre, Usuarios.email, Usuarios.cedula, Usuarios.contraseña, roles.descripcion AS rol 
            FROM Usuarios 
            INNER JOIN roles ON Usuarios.id_roles = roles.id
            WHERE roles.descripcion LIKE '%$searchTerm%'
               OR Usuarios.nombre LIKE '%$searchTerm%'
               OR Usuarios.email LIKE '%$searchTerm%'
               OR Usuarios.cedula LIKE '%$searchTerm%'
               OR Usuarios.contraseña LIKE '%$searchTerm%'";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($datos = $result->fetch_object()) {
            echo "<tr>
                    <td>{$datos->id}</td>
                    <td>" . htmlspecialchars($datos->nombre) . "</td>
                    <td class='email'>" . htmlspecialchars($datos->email) . "</td>
                    <td>{$datos->cedula}</td>
                    <td>{$datos->contraseña}</td>
                    <td>{$datos->rol}</td>
                    <td class='acciones'>
                        <a id='editar-{$datos->id}' class='my-button-editar'>Editar</a>
                        <button type='button' class='my-button-eliminar' data-id='{$datos->id}'>Eliminar</button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No se encontraron usuarios con ese término.</td></tr>";
    }
} else {
    echo "<tr><td colspan='7'>Término de búsqueda no proporcionado.</td></tr>";
}

$conn->close();
?>