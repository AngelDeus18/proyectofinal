<?php
session_start();
include "conexion.php";

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['codigo'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';

    if (empty($cedula) || empty($contraseña)) {
        $mensaje = '<div class="alert error">Por favor, completa todos los campos.</div>';
    } else {
        $consulta = $conn->prepare("SELECT * FROM usuarios WHERE cedula = ? AND contraseña = ?");
        $consulta->bind_param("ss", $cedula, $contraseña);
        $consulta->execute();

        $resultado = $consulta->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            $idRol = $fila['id_roles'];
            $idUsuario = $fila['id'];
            $nombreUsuario = $fila['nombre'];

            $_SESSION['usuario_id'] = $idUsuario;
            $_SESSION['nombre'] = $nombreUsuario;
            $_SESSION['rol'] = $idRol;

            $mensaje = '<div class="alert success">¡Bienvenido ' . htmlspecialchars($nombreUsuario) . '! Redirigiendo...</div>';

            echo "<script>
                    setTimeout(() => {
                        window.location.href = '" . (
                            $idRol == 1 ? "administrador/admin-inicio.php" :
                            ($idRol == 2 ? "supervisor/supervisor-inicio.php" :
                            "funcionario/funcionario-inicio.php")
                        ) . "';
                    }, 1200);
                  </script>";
        } else {
            $mensaje = '<div class="alert error">⚠️ Código o contraseña incorrectos. Intenta nuevamente.</div>';
        }

        $consulta->close();
    }

    $conn->close();
}
?>
