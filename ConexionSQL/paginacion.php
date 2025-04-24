<?php

function obtenerDatosPaginados($conn, $sqlBase, $paginaActual, $registrosPorPagina = 10) {
    $offset = ($paginaActual - 1) * $registrosPorPagina;
    $sql = $sqlBase . " LIMIT $registrosPorPagina OFFSET $offset";
    return $conn->query($sql);
}

function obtenerTotalPaginas($conn, $sqlBase, $registrosPorPagina = 5) {
    $sqlCount = "SELECT COUNT(*) as total FROM (" . $sqlBase . ") as subquery";
    $result = $conn->query($sqlCount);
    $row = $result->fetch_assoc();
    $totalRegistros = $row['total'];
    return ceil($totalRegistros / $registrosPorPagina);
}

function mostrarPaginacion($paginaActual, $totalPaginas) {
    echo "<div class='paginacion'>";
    for ($i = 1; $i <= $totalPaginas; $i++) {
        if ($i == $paginaActual) {
            echo "<span class='pagina-actual'>$i</span>";
        } else {
            echo "<a href='?pagina=$i'>$i</a>";
        }
    }
    echo "</div>";
}
?>
