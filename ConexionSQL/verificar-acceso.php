<?php

if (!isset($_SESSION['rol'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($rolesPermitidos) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
    header("Location: ../pagina-error.php");
    exit();
}
?>
