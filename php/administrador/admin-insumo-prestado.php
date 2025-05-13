<?php
session_start();
$rolesPermitidos = [1];
include "../../ConexionSQL/admin-scripts/eliminar-reserva.php";
include "../../ConexionSQL/verificar-acceso.php";
include "../../ConexionSQL/paginacion.php";
include '../../assets/includes/header.php';
include '../../assets/includes/sidebar.php';
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registrosPorPagina = 6;
$sql = "SELECT 
            r.id AS reserva_id,t.nombre AS nomInsumo,
            i.Descripcion AS insumo,
            i.id AS insumo_id,
            r.CantidadPrestada AS cantidad,
            u.nombre AS nombre_funcionario,
            u.cedula AS cedula_funcionario
        FROM 
            Reservas r
        INNER JOIN 
            usuarios u ON r.UsuarioID = u.id
        INNER JOIN 
            Insumos i ON r.InsumoID = i.id
        INNER JOIN
            tipos_insumos t ON i.tipo_id_nombre = t.id
        WHERE 
            r.Estado = 'Prestado'";

$result = obtenerDatosPaginados($conn, $sql, $paginaActual, $registrosPorPagina);
$totalPaginas = obtenerTotalPaginas($conn, $sql, $registrosPorPagina);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/admin-insumo-prestado.css">
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
            </div>
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Buscar insumo..." class="input-search">
            </div>
        </section>
        <section class="content">
            <div class='grid-container'>
                <?php if ($result->num_rows === 0): ?>
                    <div class="no-data-message">
                        <i class="fa-solid fa-box-open"></i>
                        <p>No hay insumos prestados en este momento.</p>
                    </div>

                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class='card'>
                            <h2 class='card-title'><?= htmlspecialchars($row['nomInsumo']) ?></h2>
                            <div class='card-details'>
                                <p><i class='fas fa-box-open'></i> Cantidad: <span><?= $row['cantidad'] ?></span></p>
                                <p><i class="fa-solid fa-pen-to-square"></i> Descripcion: <span><?= $row['insumo'] ?></span></p>
                                <p><i class='fas fa-user'></i> Funcionario: <span><?= htmlspecialchars($row['nombre_funcionario']) ?></span></p>
                                <p><i class='fas fa-id-card'></i> Cédula: <span><?= $row['cedula_funcionario'] ?></span></p>
                            </div>
                            <div class='card-actions'>
                                <form method='POST' action='../../ConexionSQL/admin-scripts/eliminar-insumo-prestado.php'>
                                    <input type='hidden' name='reserva_id' value='<?= $row['reserva_id'] ?>'>
                                    <input type='hidden' name='cantidad' value='<?= $row['cantidad'] ?>'>
                                    <input type='hidden' name='insumo_id' value='<?= $row['insumo_id'] ?>'>
                                    <button type='submit' class='button-eliminar'><i class='fas fa-trash-alt'></i> Remover</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            <div class="pagination">
                <?php mostrarPaginacion($paginaActual, $totalPaginas); ?>
            </div>
        </section>

    </main>
</body>
<script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>

</html>