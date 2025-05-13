document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.querySelector('.input-search');
    const gridContainer = document.querySelector('.grid-container');
    let noDataMessage = document.querySelector('.no-data-message');

    const removeNoDataMessage = () => {
        if (noDataMessage) {
            noDataMessage.style.display = 'none';
        }
    };

    searchInput.addEventListener('input', async () => {
        const value = searchInput.value.toLowerCase().trim();

        removeNoDataMessage();
        if (value === "") {
            location.reload();
            return;
        }

        try {
            const response = await fetch(`/proyectofinal/ConexionSQL/admin-scripts/buscar-insumo-prestado.php?q=${encodeURIComponent(value)}`);
            const results = await response.json();

            gridContainer.innerHTML = ''; 

            if (results.length === 0) {
                const noResults = document.createElement('div');
                noResults.className = 'no-data-message';
                noResults.innerHTML = `
                    <i class="fa-solid fa-box-open"></i>
                    <p>No se encontraron resultados.</p>
                `;
                gridContainer.appendChild(noResults);
            } else {
                results.forEach(row => {
                    const card = document.createElement('div');
                    card.classList.add('card');
                    card.setAttribute('data-id', row.reserva_id);

                    card.innerHTML = `
                        <h2 class='card-title'>${row.nomInsumo}</h2>
                        <div class='card-details'>
                            <p><i class='fas fa-box-open'></i> Cantidad: <span>${row.cantidad}</span></p>
                            <p><i class="fa-solid fa-pen-to-square"></i> Descripción: <span>${row.insumo}</span></p>
                            <p><i class='fas fa-user'></i> Funcionario: <span>${row.nombre_funcionario}</span></p>
                            <p><i class='fas fa-id-card'></i> Cédula: <span>${row.cedula_funcionario}</span></p>
                        </div>
                        <div class='card-actions'>
                            <button type='button' class='my-button-eliminar' data-id='${row.reserva_id}'>
                                <i class='fas fa-trash-alt'></i> Remover
                            </button>
                        </div>
                    `;
                    gridContainer.appendChild(card);
                });

                agregarEventosEliminar();
            }

        } catch (err) {
            console.error("Error al buscar insumos", err);
        }
    });

    function agregarEventosEliminar() {
        const eliminarBtns = document.querySelectorAll('.my-button-eliminar');
        eliminarBtns.forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                if (!confirm(`¿Seguro que deseas eliminar la reserva #${id}?`)) return;
                try {
                    const response = await fetch(`/proyectofinal/ConexionSQL/admin-scripts/eliminar-insumo-prestado.php?id=${id}`, {
                        method: 'GET',
                    });
                    if (response.ok) {
                        btn.closest('.card').remove();
                    } else {
                        alert('Error al eliminar la reserva.');
                    }
                } catch (error) {
                    alert('Error de red al intentar eliminar.');
                    console.error(error);
                }
            });
        });
    }

    // Agregar eventos al cargar
    agregarEventosEliminar();
});
