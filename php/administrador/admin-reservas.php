<?php
session_start();
$rolesPermitidos = [1];

include "../../ConexionSQL/admin-scripts/eliminar-reserva.php";
include "../../ConexionSQL/verificar-acceso.php";
include "../../ConexionSQL/paginacion.php";
include '../../assets/includes/header.php';
include '../../assets/includes/sidebar.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/reserva-admin.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/paginacion.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/alerts.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@500&family=Phudu:wght@500&family=Prompt:ital,wght@1,900&family=Rubik:wght@500&family=Urbanist&display=swap"
        rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <main>
        <section class="header">
            <div class="buttons-group">
                <a href="admin-reservas.php"><button class="btn"><i class="fa-solid fa-clock-rotate-left"></i> Historial de reservas</button></a>
                <a href="admin-insumo-prestado.php"><button class="btn"><i class="fa-regular fa-clipboard"></i> Insumos prestados</button></a>
                <button class="btn-imprimir"><i class="fa-solid fa-print"></i> Imprimir</button>
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Buscar insumo..." class="input-search">
            </div>
        </section>
        <section class="content">
            <div class="crud">
                <table>
                    <thead>
                        <tr>
                            <th>Id reserva </th>
                            <th>Nombre prestador </th>
                            <th>Cedula prestador</th>
                            <th>Nombre insumo</th>
                            <th>Descripcion insumo</th>
                            <th>Cantidad Prestada</th>
                            <th>Estado insumo</th>
                            <th>Fecha inicio</th>
                            <th>Fecha entrega</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                        $registrosPorPagina = 6;
                        $sqlBase = "SELECT r.id, u.nombre AS NombreUsuario, u.cedula AS CedulaUsuario, 
                        t.nombre AS NomInsumo, i.Descripcion, r.CantidadPrestada, 
                        r.Estado AS EstadoInsumo, r.FechaInicio, r.FechaFin
                        FROM reservas r
                        JOIN usuarios u ON r.UsuarioID = u.id
                        JOIN insumos i ON r.InsumoID = i.id
                        JOIN tipos_insumos t ON i.tipo_id_nombre = t.id
                        ORDER BY r.id ASC
                        ";
                        $result = obtenerDatosPaginados($conn, $sqlBase, $paginaActual, $registrosPorPagina);
                        $totalPaginas = obtenerTotalPaginas($conn, $sqlBase, $registrosPorPagina);

                        if ($result === false) {
                            echo "Error en la consulta: " . $conn->error;
                        } else {
                            if ($result->num_rows > 0) {
                                while ($datos = $result->fetch_object()) {
                                    echo "<tr>";
                                    echo "<td>" . ($datos->id ?? '') . "</td>";
                                    echo "<td>" . ($datos->NombreUsuario ?? '') . "</td>";
                                    echo "<td>" . ($datos->CedulaUsuario ?? '') . "</td>";
                                    echo "<td>" . ($datos->NomInsumo ?? '') . "</td>";
                                    echo "<td>" . ($datos->Descripcion ?? '') . "</td>";
                                    echo "<td>" . ($datos->CantidadPrestada ?? '') . "</td>";
                                    echo "<td>" . ($datos->EstadoInsumo ?? '') . "</td>";
                                    echo "<td>" . ($datos->FechaInicio ?? '') . "</td>";
                                    echo "<td>" . ($datos->FechaFin ?? '') . "</td>";
                                    echo "<td><a href='admin-reservas.php?id=" . ($datos->id ?? '') . "'>
                                    <button class='my-button-eliminar'>Eliminar</button></a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                $mensaje = '<div class="alert error">⚠️ No se encontraron resultados.</div>';
                                echo "<tr><td colspan='10'>$mensaje</td></tr>";
                            }
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="paginacion-wrapper">
                <?php mostrarPaginacion($paginaActual, $totalPaginas); ?>
            </div>
        </section>
    </main>
</body>
<script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>

</html>