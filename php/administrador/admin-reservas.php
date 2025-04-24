<?php
session_start();
$rolesPermitidos = [1];

include "../../ConexionSQL/admin-scripts/eliminar-reserva.php";
include "../../ConexionSQL/verificar-acceso.php";
include "../../ConexionSQL/paginacion.php";


if (isset($_SESSION['usuario_id']) && isset($_SESSION['nombre'])) {
    $nombreUsuario = $_SESSION['nombre'];
} else {
    $nombreUsuario = "";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu-abajo.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/reserva-admin.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/paginacion.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/alerts.css">
    <link rel="stylesheet" href="../fontawesome/fontawesome-free-6.5.1-web/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@500&family=Phudu:wght@500&family=Prompt:ital,wght@1,900&family=Rubik:wght@500&family=Urbanist&display=swap"
        rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <nav>
        <input type="checkbox" id="toogle">
        <div class="logo"> Software 4U</div>
        <ul class="list">
            <li><a href="admin-inicio.php">Inicio</a></li>
            <li><a href="administrador-gestion-usuario.php">Usuarios</a></li>
            <li><a href="admin-insumos.php">Insumos</a></li>
            <i class="fa-solid fa-user"></i>
            <li>
                <?php echo $nombreUsuario . " "; ?>
            </li>
            <li><a href="../../ConexionSQL/cerrar.php">Salir</a></li>
        </ul>

        <label for="toogle" class="icon-bars">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </label>
    </nav>
    <nav class="menu_abajo">
        <ul class="lista_abajo">
            <li><a href="admin-insumos.php">Insumos</a></li>
            <li><a href="admin-reservas.php">Reservas</a></li>
            <!-- <li><a href="../fpdf/ReporteReservas.php" class="btn-reporte" target="_blank"><i
                        class="fa-solid fa-download"></i> Generar reporte</a></li> -->
        </ul>
    </nav>
    <main>
        <div class="container-form">
            <div class="crud">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre prestador </th>
                            <th>Cedula</th>
                            <th>Nombre insumo</th>
                            <th>Descripcion</th>
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
                        $sqlBase = "SELECT r.id, u.nombre AS NombreUsuario, u.cedula AS CedulaUsuario, i.NomInsumo, i.Descripcion,
                        i.Estado AS EstadoInsumo, r.FechaInicio, r.FechaFin
                        FROM reservas r
                        JOIN usuarios u ON r.UsuarioID = u.id
                        JOIN insumos i ON r.InsumoID = i.id";
                        $result = obtenerDatosPaginados($conn, $sqlBase, $paginaActual, $registrosPorPagina);
                        $totalPaginas = obtenerTotalPaginas($conn, $sqlBase, $registrosPorPagina);

                        if ($result === false) {
                            echo "Error en la consulta: " . $conn->error;
                        } else {
                            if ($result->num_rows > 0) {
                                while ($datos = $result->fetch_object()) {
                                    echo "<tr>";
                                    echo "<td>" . ($datos->NombreUsuario ?? '') . "</td>";
                                    echo "<td>" . ($datos->CedulaUsuario ?? '') . "</td>";
                                    echo "<td>" . ($datos->NomInsumo ?? '') . "</td>";
                                    echo "<td>" . ($datos->Descripcion ?? '') . "</td>";
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
        </div>
    </main>
</body>
<script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>

</html>