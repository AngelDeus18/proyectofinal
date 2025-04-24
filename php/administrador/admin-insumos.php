<?php
session_start();
$rolesPermitidos = [1];
include "../../ConexionSQL/verificar-acceso.php";
include "../../ConexionSQL/conexion.php";
include "../../ConexionSQL/admin-scripts/eliminar-insumos.php";
include "../../ConexionSQL/admin-scripts/modificar-insumos.php";
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
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/admi-insumos.css">
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
            <!-- <li><a href="../fpdf/ReporteInsumos.php" class="btn-reporte" target="_blank"><i class="fa-solid fa-download"></i> Generar reporte</a></li> -->
        </ul>
    </nav>
    <main>
        <div class="formulario">
            <h1>INGRESAR INSUMO</h1>
            <div><?=
                    $mensaje = "";
                    $mensaje ?></div>
            <form id="form-insumo">
                <div class="cotainer">
                    <div class="congrup">
                        <input type="hidden" id="id" class="form_input" name="id">
                    </div>
                    <div class="congrup">
                        <input type="text" id="name" class="form_input" placeholder=" " name="nombre">
                        <label for="name" class="form_label">Nombre Insumo</label>
                        <span class="form_line"></span>
                    </div>
                    <div class="congrup">
                        <input type="text" id="description" class="form_input" placeholder=" " name="descripcion">
                        <label for="description" class="form_label">Descripcion</label>
                        <span class="form_line"></span>
                    </div>
                    <div class="congrup">
                        <select id="estado" class="form_input" placeholder=" " name="estado">
                            <option value="1"></option>
                            <option value="Disponible">Disponible</option>
                            <option value="No disponibe">No disponible</option>
                            <option value="Averiado">Averiado</option>
                        </select>
                        <label for="estado" class="form_label">Estado</label>
                        <span class="form_line"></span>
                    </div>
                    <div class="congrup">
                        <input type="datetime-local" id="fecha-registro" class="form_input" placeholder=" "
                            name="fecha-registro">
                        <label for="fecha-registro" class="form_label">Fecha registro</label>
                        <span class="form_line"></span>
                    </div>
                    <input class="form_submit" type="submit" value="Registrar">
                </div>
            </form>
        </div>
        <div class="container-form">
            <div class="crud">
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre Insumo</th>
                            <th>Descripcion</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                        $registrosPorPagina = 6;
                        $sqlBase = "SELECT * FROM insumos";
                        $result = obtenerDatosPaginados($conn, $sqlBase, $paginaActual, $registrosPorPagina);
                        $totalPaginas = obtenerTotalPaginas($conn, $sqlBase, $registrosPorPagina);

                        if ($result === false) {
                            echo "Error en la consulta: " . $conn->error;
                        } else {
                            if ($result->num_rows > 0) {
                                while ($datos = $result->fetch_object()) {
                                    echo "<tr>";
                                    echo "<td>" . $datos->id . "</td>";
                                    echo "<td>" . $datos->NomInsumo . "</td>";
                                    echo "<td class='descripcion'>" . $datos->Descripcion . "</td>";
                                    echo "<td>" . $datos->Estado . "</td>";
                                    echo "<td>" . $datos->FechaRegistro . "</td>";
                                    echo "<td><a id='editar-" . $datos->id . "' class='my-button-editar'>Editar</a>  
                                <a href='admin-insumos.php?id=" . $datos->id . "'><button class='my-button-eliminar'>Eliminar</button></a></td>";
                                }
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
    <script src="../../ConexionSQL/Js/insumos.js"></script>

</body>
<script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>

</html>