<?php
session_start();
$rolesPermitidos = [1];
include "../../ConexionSQL/verificar-acceso.php";
include "../../ConexionSQL/paginacion.php";
include "../../ConexionSQL/admin-scripts/buscar-insumo.php";
include '../../assets/includes/header.php';
include '../../assets/includes/sidebar.php';

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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Insumos</title>
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/admi-insumos.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/paginacion.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/alerts.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@500&family=Phudu:wght@500&family=Prompt:ital,wght@1,900&family=Rubik:wght@500&family=Urbanist&display=swap" rel="stylesheet">
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
                                    <select id="name" name="nombre" class="form_input" required>
                                        <option value="" disabled selected>Selecciona un tipo</option>
                                        <?php foreach ($tiposInsumos as $tipo): ?>
                                            <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="name" class="form_label">Nombre Insumo</label>
                                    <span class="form_line"></span>
                                </div>

                                <div class="congrup">
                                    <input type="text" id="description" name="descripcion" class="form_input" placeholder="Ingrese la descripción" required>
                                    <label for="description" class="form_label">Descripción</label>
                                    <span class="form_line"></span>
                                </div>

                                <div class="congrup">
                                    <input type="number" id="quantity" name="cantidad" class="form_input" placeholder="Ingrese la cantidad" required>
                                    <label for="quantity" class="form_label">Cantidad</label>
                                    <span class="form_line"></span>
                                </div>

                                <div class="congrup">
                                    <select id="estado" name="estado" class="form_input" required>
                                        <option value="" disabled selected>Selecciona un estado</option>
                                        <option value="Disponible">Disponible</option>
                                        <option value="No disponible">No disponible</option>
                                    </select>
                                    <label for="estado" class="form_label">Estado</label>
                                    <span class="form_line"></span>
                                </div>

                                <div class="congrup">
                                    <input type="datetime-local" id="fecha-registro" name="fecha-registro" class="form_input" required>
                                    <label for="fecha-registro" class="form_label">Fecha registro</label>
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
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>Estado</th>
                                        <th>Fecha Registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                                    $registrosPorPagina = 6;
                                    $sqlBase = "SELECT Insumos.id, tipos_insumos.nombre AS TipoNombre, Insumos.Descripcion, Insumos.Cantidad, Insumos.Estado, Insumos.FechaRegistro
                                                FROM Insumos
                                                LEFT JOIN tipos_insumos ON Insumos.tipo_id_nombre = tipos_insumos.id
                                                ORDER BY Insumos.id ASC";

                                    $result = obtenerDatosPaginados($conn, $sqlBase, $paginaActual, $registrosPorPagina);
                                    $totalPaginas = obtenerTotalPaginas($conn, $sqlBase, $registrosPorPagina);

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

    <!-- Scripts -->
    <script src="../../ConexionSQL/Js/insumos.js"></script>
    <script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>
</body>

</html>