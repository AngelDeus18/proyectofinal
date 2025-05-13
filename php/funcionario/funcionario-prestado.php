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
    $sql = "SELECT tipos_insumos.nombre AS NomInsumo, Reservas.FechaInicio, Reservas.FechaFin, Insumos.Descripcion
        FROM Reservas
        INNER JOIN Insumos ON Reservas.InsumoID = Insumos.id
        INNER JOIN tipos_insumos ON Insumos.tipo_id_nombre = tipos_insumos.id
        WHERE Reservas.UsuarioID = $usuarioID AND Reservas.Estado = 0";

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
    <div class="container">
        <div class="content">
            <h2>Hola <?php echo htmlspecialchars($nombreUsuario); ?> 👋</h2>
            <p>Tienes prestado:</p>

            <?php if (empty($insumosPrestados)): ?>
                <p style="color: #7f8c8d;">No tienes ningún insumo prestado actualmente.</p>
            <?php else: ?>
                <ul class="insumos-list">
                    <?php foreach ($insumosPrestados as $insumo): ?>
                        <li>
                            <i class="fa-solid fa-box"></i>
                            <strong><?php echo htmlspecialchars($insumo['NomInsumo']); ?></strong> —
                            <?php echo htmlspecialchars($insumo['Descripcion']); ?><br>
                            <small><i class="fa-regular fa-calendar"></i> Desde: <?php echo date('d/m/Y H:i', strtotime($insumo['FechaInicio'])); ?> hasta <?php echo date('d/m/Y H:i', strtotime($insumo['FechaFin'])); ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>
</body>

</html>