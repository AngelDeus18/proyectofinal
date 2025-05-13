<?php
session_start();
$rolesPermitidos = [1];
$pageTitle = 'Usuarios administrador';
include '../../assets/includes/header.php';
include "../../ConexionSQL/verificar-acceso.php";
include "../../ConexionSQL/paginacion.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/administrador.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/paginacion.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/alerts.css">
    <link rel="stylesheet" href="../fontawesome/fontawesome-free-6.5.1-web/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@500&family=Phudu:wght@500&family=Prompt:ital,wght@1,900&family=Rubik:wght@500&family=Urbanist&display=swap"
        rel="stylesheet">
    <title>Gestión de Usuarios</title>
</head>

<body>
    <div class="boton-modal">
        <label for="btn-modal" class="boton-new">Nuevo</label>
    </div>
    <div class="alertas">
        <div><?=
                $mensaje = "";
                $mensaje ?></div>
    </div>
    <input type="checkbox" id="btn-modal">
    <div class="container-form">
        <div class="crud">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Cedula</th>
                        <th>Contraseña</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                    $registrosPorPagina = 5;

                    $sqlBase = "SELECT usuarios.id, usuarios.nombre, usuarios.email, usuarios.cedula, usuarios.contraseña, roles.descripcion AS rol 
                    FROM usuarios 
                    INNER JOIN roles ON usuarios.id_roles = roles.id
                    ORDER BY usuarios.id ASC";

                    $result = obtenerDatosPaginados($conn, $sqlBase, $paginaActual, $registrosPorPagina);
                    $totalPaginas = obtenerTotalPaginas($conn, $sqlBase, $registrosPorPagina);

                    if ($result === false) {
                        echo "Error en la consulta: " . $conn->error;
                    } else {
                        if ($result->num_rows > 0) {
                            while ($datos = $result->fetch_object()) {
                                echo "<tr>";
                                echo "<td>" . $datos->id . "</td>";
                                echo "<td>" . $datos->nombre . "</td>";
                                echo "<td>" . $datos->email . "</td>";
                                echo "<td>" . $datos->cedula . "</td>";
                                echo "<td>" . $datos->contraseña . "</td>";
                                echo "<td>" . $datos->rol . "</td>";
                                echo "<td><a id='editar" . $datos->id . "' class='my-button-editar'>Editar</a>  
                                    <button class='my-button-eliminar' data-id='" . $datos->id . "'>Eliminar</button>";
                            }
                        } else {
                            echo "No se encontraron resultados.";
                        }
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

    </div>
    <?php mostrarPaginacion($paginaActual, $totalPaginas); ?>

    <div class="container-modal">
        <div class="content-modal">
            <div class="modal-header">
                <h2><span id="modal-title">Nueva</span> Persona</h2>
            </div>
            <div class="formulario">
                <form method="POST" action="" id="formulario-usuario">
                    <div class="cotainer">
                        <div class="congrup">
                            <input type="hidden" id="id" class="form_input" name="id">
                        </div>
                        <div class="congrup">
                            <input type="text" id="user" class="form_input" placeholder=" " name="nombre">
                            <label for="user" class="form_label">Nombre</label>
                            <span class="form_line"></span>
                        </div>
                        <div class="congrup">
                            <input type="text" id="mail" class="form_input" placeholder=" " name="email">
                            <label for="mail" class="form_label">Email</label>
                            <span class="form_line"></span>
                        </div>
                        <div class="congrup">
                            <input type="text" id="cedula" class="form_input" placeholder=" " name="cedula">
                            <label for="cedula" class="form_label">Cedula</label>
                            <span class="form_line"></span>
                        </div>
                        <div class="congrup">
                            <input type="text" id="contraseña" class="form_input" placeholder=" " name="contraseña">
                            <label for="contraseña" class="form_label">Contraseña</label>
                            <span class="form_line"></span>
                        </div>
                        <div class="congrup">
                            <label for="rol" class="form_label"></label>
                            <select id="rol" name="rol" class="form_input" required>
                                <option disabled selected>Selecciona un rol</option>
                                <option value="1">Administrador</option>
                                <option value="2">Supervisor</option>
                                <option value="3">Funcionario</option>
                            </select>
                            <span class="form_line"></span>
                        </div>
                    </div>
                    <div class="btn-cerrar">
                        <label for="btn-modal">Cerrar</label>
                        <input class="form_submit" type="submit" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <label for="btn-modal" class="cerrar-modal"></label>
    </div>
    <script src="../../ConexionSQL/Js/users.js"></script>
</body>
<script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>

</html>