document.addEventListener('DOMContentLoaded', () => {
    const eliminarBtns = document.querySelectorAll('.my-button-eliminar');
    eliminarBtns.forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.getAttribute('data-id');

            if (!confirm(`¿Seguro que deseas eliminar la reserva #${id}?`)) return;

            try {
                const response = await fetch(`/proyectofinal/ConexionSQL/admin-scripts/eliminar-reserva.php?id=${id}`, {
                    method: 'GET',
                });

                if (response.ok) {
                    btn.closest('tr').remove();
                } else {
                    alert('Error al eliminar la reserva.');
                }
            } catch (error) {
                alert('Error de red al intentar eliminar.');
                console.error(error);
            }
        });
    });

    // Búsqueda AJAX
    const searchInput = document.querySelector('.input-search');
    const tablaInsumosBody = document.querySelector('tbody');

    searchInput.addEventListener('input', async () => {
        const value = searchInput.value.toLowerCase().trim();

        if (value === "") {
            location.reload(); // Si el campo está vacío, recarga la vista con paginación
            return;
        }

        try {
            const response = await fetch(`/proyectofinal/ConexionSQL/admin-scripts/buscar-reservas.php?q=${encodeURIComponent(value)}`);
            const resultados = await response.json();

            tablaInsumosBody.innerHTML = ""; // Limpiar tabla

            if (resultados.length === 0) {
                tablaInsumosBody.innerHTML = `<tr><td colspan="10">⚠️ No se encontraron resultados.</td></tr>`;
                return;
            }

            resultados.forEach(reserva => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                    <td>${reserva.id}</td>
                    <td>${reserva.NombreUsuario}</td>
                    <td>${reserva.CedulaUsuario}</td>
                    <td>${reserva.NomInsumo}</td>
                    <td>${reserva.Descripcion}</td>
                    <td>${reserva.CantidadPrestada}</td>
                    <td>${reserva.EstadoInsumo}</td>
                    <td>${reserva.FechaInicio}</td>
                    <td>${reserva.FechaFin}</td>
                    <td>
                        <button class='my-button-eliminar' data-id='${reserva.id}'>Eliminar</button>
                    </td>
                `;
                tablaInsumosBody.appendChild(fila);
            });

            // Reasignar eventos a nuevos botones
            const nuevosBtns = document.querySelectorAll('.my-button-eliminar');
            nuevosBtns.forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.getAttribute('data-id');
                    if (!confirm(`¿Seguro que deseas eliminar la reserva #${id}?`)) return;

                    try {
                        const response = await fetch(`/proyectofinal/ConexionSQL/admin-scripts/eliminar-reserva.php?id=${id}`, {
                            method: 'GET',
                        });

                        if (response.ok) {
                            btn.closest('tr').remove();
                        } else {
                            alert('Error al eliminar la reserva.');
                        }
                    } catch (error) {
                        alert('Error de red al intentar eliminar.');
                        console.error(error);
                    }
                });
            });

        } catch (error) {
            console.error('Error al buscar reservas:', error);
        }
    });
});
