<?php
session_start();
$rolesPermitidos = [3];
include "../../ConexionSQL/conexion.php";

$updateSQL = "
    UPDATE Insumos i
    JOIN Reservas r ON i.id = r.InsumoID
    SET i.Estado = 'Disponible', r.Estado = 'Disponible'
    WHERE r.FechaFin < NOW() AND r.Estado = 'No disponible'
";
$conn->query($updateSQL);

$nombreUsuario = "";
$insumosPrestados = "";

if (isset($_SESSION['usuario_id']) && isset($_SESSION['nombre'])) {
    $nombreUsuario = $_SESSION['nombre'];
    $usuarioID = $_SESSION['usuario_id'];

    $sql = "SELECT Insumos.NomInsumo, Reservas.FechaInicio, Reservas.FechaFin,Insumos.Descripcion
            FROM Reservas
            INNER JOIN Insumos ON Reservas.InsumoID = Insumos.id
            WHERE Reservas.UsuarioID = $usuarioID AND Reservas.Estado = 0 ";

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $insumosPrestados .= "<p> Un <i>{$row['NomInsumo']}</i> {$row['Descripcion']} desde {$row['FechaInicio']} hasta {$row['FechaFin']}</p>";
        }
    } else {
        echo "Error en la consulta: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/inicio-admin.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu-abajo.css">
    <link rel="stylesheet" href="http://localhost/proyectofinal/assets/css/menu.css">
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
            <li><a href="funcionario-inicio.php">Inicio</a></li>
            <li><a href="funcionario-insumos.php">Insumos</a></li>
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
            <li><a href="funcionario-insumos.php">Insumos</a></li>
            <li><a href="funcionario-prestado.php"><i class="fa-solid fa-plus"></i> Prestado</a></li>
        </ul>
    </nav>
    <main class="welcome-container">
        <div class="welcome-text">
            <h1>Estimado funcionario
                <?php echo $nombreUsuario . " "; ?> usted por el momento tiene prestado:
                <?php echo $insumosPrestados; ?>
            </h1>

        </div>
    </main>
</body>
<script src="https://kit.fontawesome.com/69aa482bca.js" crossorigin="anonymous"></script>

</html>