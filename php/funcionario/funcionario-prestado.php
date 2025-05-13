<?php
session_start();
$rolesPermitidos = [3];
include "../../ConexionSQL/conexion.php";

// Actualizar estados vencidos
// $conn->query("
//     UPDATE Insumos i
//     JOIN Reservas r ON i.id = r.InsumoID
//     SET i.Estado = 'Disponible', r.Estado = 'Disponible'
//     WHERE r.FechaFin < NOW() AND r.Estado = 'No disponible'
// ");

$nombreUsuario = $_SESSION['nombre'] ?? '';
$usuarioID = $_SESSION['usuario_id'] ?? 0;
$insumosPrestados = [];

if ($usuarioID) {
    $sql = "SELECT 
            r.id AS reserva_id,
            t.nombre AS nomInsumo,
            i.Descripcion AS Descripcion,
            i.id AS insumo_id,
            r.CantidadPrestada AS cantidad,
            u.nombre AS nombre_funcionario,
            u.cedula AS cedula_funcionario,
            r.FechaInicio,
            r.FechaFin
        FROM 
            Reservas r
        INNER JOIN 
            usuarios u ON r.UsuarioID = u.id
        INNER JOIN 
            Insumos i ON r.InsumoID = i.id
        INNER JOIN
            tipos_insumos t ON i.tipo_id_nombre = t.id
        WHERE 
            r.Estado = 'Prestado'
        ORDER BY r.id ASC
            ";

    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $insumosPrestados[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insumos Prestados</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/funcionario-prestado.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu-abajo.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@500&family=Phudu:wght@500&family=Prompt:ital,wght@1,900&family=Rubik:wght@500&family=Urbanist&display=swap"
        rel="stylesheet">
</head>

<body>
    <nav>
        <input type="checkbox" id="toogle">
        <div class="logo"> Software 4U</div>
        <ul class="list">
            <li><a href="funcionario-inicio.php">Inicio</a></li>
            <li><a href="funcionario-insumos.php">Insumos</a></li>
            <li><a href="funcionario-prestado.php"><i class="fa-solid fa-plus"></i> Prestado</a></li>
            <li><i class="fa-solid fa-user"></i> <?php echo $nombreUsuario; ?></li>
            <li><a href="../../ConexionSQL/cerrar.php">Salir</a></li>
        </ul>

        <label for="toogle" class="icon-bars">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </label>
    </nav>
    <main class="container">
        <section class="content">
            <h1>Hola <?php echo htmlspecialchars($nombreUsuario); ?> 👋</h1>
            <p class="subtitulo">Estos son los insumos que tienes prestados actualmente:</p>

            <?php if (empty($insumosPrestados)): ?>
                <p class="mensaje-vacio">No tienes ningún insumo prestado actualmente.</p>
            <?php else: ?>
                <ul class="lista-insumos">
                    <?php foreach ($insumosPrestados as $insumo): ?>
                        <li class="item-insumo">
                            <div class="info-principal">
                                <i class="fa-solid fa-box icono-insumo"></i>
                                <h3 class="nombre-insumo"><?php echo htmlspecialchars($insumo['nomInsumo']); ?></h3>
                            </div>
                            <p class="descripcion-insumo"><?php echo htmlspecialchars($insumo['Descripcion']); ?></p>
                            <div class="detalles-insumo">
                                <p><i class="fa-solid fa-plus icono-detalle"></i> Cantidad: <span class="valor-detalle"><?php echo $insumo['cantidad']; ?></span></p>
                                <p><i class="fa-regular fa-calendar icono-detalle"></i> Desde: <span class="valor-detalle"><?php echo date('d/m/Y H:i', strtotime($insumo['FechaInicio'])); ?></span></p>
                                <p><i class="fa-regular fa-calendar-check icono-detalle"></i> Hasta: <span class="valor-detalle"><?php echo date('d/m/Y H:i', strtotime($insumo['FechaFin'])); ?></span></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </main>
    <script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>
</body>

</html>