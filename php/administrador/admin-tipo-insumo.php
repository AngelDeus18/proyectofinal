<?php
session_start();
$rolesPermitidos = [1];
include "../../ConexionSQL/verificar-acceso.php";
include "../../ConexionSQL/paginacion.php";
include '../../assets/includes/header.php';
include '../../assets/includes/sidebar.php';

if (isset($_SESSION['usuario_id']) && isset($_SESSION['nombre'])) {
    $nombreUsuario = $_SESSION['nombre'];
} else {
    $nombreUsuario = "";
}

$sqlTiposInsumos = "SELECT id, nombre FROM tipos_insumos";
$resultTiposInsumos = $conn->query($sqlTiposInsumos);
$tiposInsumos = [];
if ($resultTiposInsumos) {
    while ($row = $resultTiposInsumos->fetch_assoc()) {
        $tiposInsumos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu-abajo.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/tipo-insumos.css">
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
    <main>
        <div class="container">
            <section class="header">
                <div class="buttons-group">
                    <a href="admin-insumos.php"><button class="btn"><i class="fa-solid fa-boxes-stacked"></i> Insumos</button></a>
                    <a href="admin-tipo-insumo.php"><button class="btn"><i class="fa-solid fa-tag"></i> Nuevo Tipo Insumo</button></a>
                    <button class="btn-imprimir"><i class="fa-solid fa-print"></i> Imprimir</button>
                </div>
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Buscar insumo..." class="input-search">
                </div>
            </section>
            <section class="content">
                <div class="grid-container">
                    <div class="formulario">
                        <h1>Ingresar Insumo</h1>
                        <form id="form-insumo">
                            <div class="cotainer">
                                <input type="hidden" id="id" name="id">
                                <div class="congrup">
                                    <input type="text" id="typeInsum" name="tipoinsumo" class="form_input" placeholder="Ingrese el tipo de insumo" required>
                                    <label for="typeInsum" class="form_label">Tipo de insumo</label>
                                    <span class="form_line"></span>
                                </div>
                                <input type="submit" value="Registrar" class="form_submit">
                            </div>
                        </form>
                    </div>
                    <div class="container-tabla">
                        <div class="crud">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre Insumo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                                    $registrosPorPagina = 6;
                                    $sqlBase = "SELECT * FROM tipos_insumos ORDER BY id ASC";

                                    $result = obtenerDatosPaginados($conn, $sqlBase, $paginaActual, $registrosPorPagina);
                                    $totalPaginas = obtenerTotalPaginas($conn, $sqlBase, $registrosPorPagina);

                                    if ($result && $result->num_rows > 0) {
                                        while ($datos = $result->fetch_object()) {
                                            echo "<tr>
                                                    <td>{$datos->id}</td>
                                                    <td class='descripcion'>" . htmlspecialchars($datos->nombre) . "</td>
                                                    <td class='acciones'>
                                                        <a id='editar-{$datos->id}' class='my-button-editar'>Editar</a>
                                                        <button type='button' class='my-button-eliminar' data-id='{$datos->id}'>Eliminar</button>
                                                    </td>
                                                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No se encontraron registros.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="pagination">
                            <?php mostrarPaginacion($paginaActual, $totalPaginas); ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script src="../../ConexionSQL/Js/tipo-insumos.js"></script>
</body>
<script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>

</html>