<?php
session_start();
$rolesPermitidos = [3];
include "../../ConexionSQL/conexion.php";
include "../../ConexionSQL/paginacion.php";

$usuarioID = $_SESSION['usuario_id'];
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
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/profe-insumos.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/paginacion.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/alerts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
        <div class="logo">Software 4U</div>

        <label for="toogle" class="icon-bars">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </label>

        <ul class="list">
            <li><a href="funcionario-inicio.php">Inicio</a></li>
            <li><a href="funcionario-insumos.php">Insumos</a></li>
            <li><a href="funcionario-prestado.php"><i class="fa-solid fa-plus"></i> Prestado</a></li>
            <li><i class="fa-solid fa-user"></i> <?php echo $nombreUsuario; ?></li>
            <li><a href="../../ConexionSQL/cerrar.php">Salir</a></li>
        </ul>
    </nav>
    <div class="categorias">
        <button class="categoria-btn" data-categoria="1"><i class="fa-solid fa-school"></i><br>Salones</button>
        <button class="categoria-btn" data-categoria="2"><i class="fa-solid fa-laptop"></i><br>Portatiles</button>
        <button class="categoria-btn" data-categoria="3"><i class="fa-solid fa-pen"></i><br>Marcadores</button>
        <button class="categoria-btn" data-categoria="4"><i class="fa-solid fa-video"></i><br>Proyectores</button>
        <button class="categoria-btn" data-categoria="5"><i class="fa-solid fa-eraser"></i><br>Borradores</button>
        <button class="categoria-btn" data-categoria="6"><i class="fa-solid fa-keyboard"></i></i><br>Teclados</button>
        <button class="categoria-btn" data-categoria="7"><i class="fa-solid fa-pencil"></i><br>Lapiceros</button>
        <button class="categoria-btn" data-categoria="8"><i class="fa-solid fa-computer-mouse"></i></i><br>Mouse</button>
        <button class="categoria-btn" data-categoria="9"><i class="fa-solid fa-tablet"></i><br>Tablets</button>
        <button class="categoria-btn" data-categoria="todos"><i class="fa-solid fa-list"></i><br>Todos</button>
    </div>

    <main>
        <section class="content">
            <div class="grid-container">
                <div class="formulario">
                    <h1>PRESTAR INSUMO</h1>
                    <div id="mensaje"></div>
                    <form method="post">
                        <div class="cotainer">
                            <div class="congrup">
                                <input type="text" id="name" class="form_input" placeholder="Insumo a prestar" name="insprestado" readonly>
                                <label for="name" class="form_label">Insumo Prestado</label>
                                <span class="form_line"></span>
                            </div>
                            <div class="congrup">
                                <input type="number" id="cantidad" class="form_input" placeholder="Ingrese la cantidad a prestar" name="cantidad">
                                <label for="cantidad" class="form_label">Cantidad</label>
                                <span class="form_line"></span>
                            </div>
                            <div class="congrup">
                                <input type="text" id="descripcion" class="form_input" placeholder="Descripcion insumo" name="descripcion" readonly>
                                <label for="descripcion" class="form_label">Descripcion</label>
                                <span class="form_line"></span>
                            </div>
                            <div class="congrup">
                                <input type="datetime-local" id="fecha-inicio" class="form_input" placeholder=" "
                                    name="fecha-prestamo" readonly>
                                <label for="fecha-inicio" class="form_label">Fecha Inicio</label>
                                <span class="form_line"></span>
                            </div>
                            <div class="congrup">
                                <input type="datetime-local" id="fecha-entrega" class="form_input" placeholder=" "
                                    name="fecha-entrega">
                                <label for="fecha-registro" class="form_label">Fecha Entrega</label>
                                <span class="form_line"></span>
                            </div>
                            <input type="hidden" name="UsuarioID" value="<?php echo $usuarioID; ?>">
                            <input type="hidden" name="InsumoID" value="<?php echo $insumoID; ?>">
                            <input class="form_submit" type="submit" value="Prestar Insumo">
                        </div>
                    </form>
                </div>
                <div class="container-form">
                    <div class="crud">
                        <table id="tabla-insumos">
                            <thead>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Descripcion</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $mensaje = "";
                                $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                                $registrosPorPagina = 6;
                                $categoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : 'todos';

                                $sqlBase = "SELECT Insumos.id, tipos_insumos.nombre AS TipoNombre, Insumos.Descripcion, Insumos.Cantidad, Insumos.Estado 
                                FROM Insumos
                                LEFT JOIN tipos_insumos ON Insumos.tipo_id_nombre = tipos_insumos.id
                                WHERE Insumos.Estado = 'Disponible'
                                AND Insumos.Cantidad > 0";

                                if ($categoriaSeleccionada !== 'todos') {
                                    $categoriaSeleccionada = intval($categoriaSeleccionada);
                                    $sqlBase .= " AND Insumos.tipo_id_nombre = $categoriaSeleccionada";
                                }

                                $result = obtenerDatosPaginados($conn, $sqlBase, $paginaActual, $registrosPorPagina);
                                $totalPaginas = obtenerTotalPaginas($conn, $sqlBase, $registrosPorPagina);

                                if ($result === false) {
                                    echo "Error en la consulta: " . $conn->error;
                                } else {
                                    if ($result->num_rows > 0) {
                                        while ($datos = $result->fetch_object()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($datos->TipoNombre) . "</td>";
                                            echo "<td>" . $datos->Cantidad . "</td>";
                                            echo "<td class='descripcion'>" . $datos->Descripcion . "</td>";
                                            echo "<td>" . $datos->Estado . "</td>";
                                            echo "<td><a data-insumoid='" . $datos->id . "' class='my-button-prestar'>Prestar</a>";
                                        }
                                    } else {
                                        $mensaje = '<div class="alert error">⚠️ No se encontraron resultados.</div>';
                                        echo "<tr><td colspan='5'>$mensaje</td></tr>";
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
            </div>
        </section>
        <script src="../../ConexionSQL/Js/prestar-insumo-funcionario.js"></script>

        <script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>
    </main>
</body>

</html>