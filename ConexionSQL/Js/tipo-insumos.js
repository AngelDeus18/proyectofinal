// Asignar data-id a filas existentes
document.querySelectorAll('tbody tr').forEach(row => {
    const idCell = row.querySelector('td');
    if (idCell) {
        row.setAttribute('data-id', idCell.textContent.trim());
    }
});

document.querySelector('.formulario form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const isEditMode = form.action.includes('edit-tipo-insumo.php');
    const url = isEditMode
        ? '../../ConexionSQL/admin-scripts/edit-tipo-insumo.php'
        : '../../ConexionSQL/admin-scripts/add-tipo-insumo.php';

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        const messageContainer = document.querySelector('.formulario h1');
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${data.success ? 'success' : 'error'}`;
        alertDiv.textContent = data.message;
        messageContainer.insertAdjacentElement('afterend', alertDiv);

        if (data.success) {
            form.reset();
            form.action = '../../ConexionSQL/admin-scripts/add-tipo-insumo.php';
            form.querySelector('input[type="submit"]').value = 'Agregar';

            const id = isEditMode ? form['id'].value : data.insert_id ?? '—';
            const tipoinsumo = formData.get("tipoinsumo");

            if (!isEditMode) {
                const tableBody = document.querySelector('tbody');
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-id', id);
                newRow.innerHTML = `
                    <td>${id}</td>
                    <td>${tipoinsumo}</td>
                    <td>
                        <a class='my-button-editar'>Editar</a>
                        <button class='my-button-eliminar'>Eliminar</button>
                    </td>
                `;
                tableBody.prepend(newRow);
                // Re-asignar data-id a las nuevas filas (aunque ya se asignó al crear)
                newRow.querySelectorAll('td').forEach(cell => {
                    if (cell === newRow.querySelector('td')) {
                        newRow.setAttribute('data-id', cell.textContent.trim());
                    }
                });
            } else {
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.querySelector('td:nth-child(2)').textContent = tipoinsumo;
                }
                // Si estamos en modo edición y hay un término de búsqueda,
                // necesitamos volver a ejecutar la búsqueda para actualizar la tabla filtrada.
                const searchTerm = document.querySelector('.input-search').value.trim();
                if (searchTerm.length >= 1) {
                    buscarInsumos(searchTerm);
                }
            }
        }

        setTimeout(() => alertDiv.remove(), 2000);

    } catch (error) {
        console.error('Error en la solicitud AJAX:', error);
    }
});
//edit tipo insumo
document.querySelector('tbody').addEventListener('click', function (e) {
    if (e.target.classList.contains('my-button-editar')) {
        const button = e.target;
        const form = document.querySelector('.formulario form ');
        const row = button.closest('tr');
        const rowData = row.querySelectorAll('td');

        form['id'].value = rowData[0].innerText;
        form.tipoinsumo.value = rowData[1].innerText;

        form.action = '../../ConexionSQL/admin-scripts/edit-tipo-insumo.php';

        const submitButton = document.querySelector('.form_submit');
        submitButton.value = 'Editar Tipo deInsumo';
    }
});
//delete tipo insumo
document.querySelector('tbody').addEventListener('click', async function (e) {
    if (e.target.classList.contains('my-button-eliminar')) {
        const row = e.target.closest('tr');
        const id = row.getAttribute('data-id');

        if (confirm(`¿Estás seguro de eliminar el insumo con ID ${id}?`)) {
            try {
                const response = await fetch(`../../ConexionSQL/admin-scripts/delete-tipo-insumo.php?id=${id}`);
                const data = await response.json();

                const messageContainer = document.querySelector('.formulario h1');
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert ${data.success ? 'success' : 'error'}`;
                alertDiv.textContent = data.message;
                messageContainer.insertAdjacentElement('afterend', alertDiv);

                if (data.success) {
                    row.remove();
                    // Si estamos en modo búsqueda, también necesitamos actualizar la tabla filtrada
                    const searchTerm = document.querySelector('.input-search').value.trim();
                    if (searchTerm.length >= 1) {
                        buscarInsumos(searchTerm);
                    }
                }

                setTimeout(() => alertDiv.remove(), 2000);

            } catch (error) {
                console.error('Error al eliminar:', error);
            }
        }
    }
});
//buscar tipo insumo
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.input-search');
    const tablaInsumosBody = document.querySelector('.crud tbody');
    const paginationDiv = document.querySelector('.pagination');

    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.trim();
        if (searchTerm.length >= 1) {
            buscarInsumos(searchTerm);

            if (paginationDiv) {
                paginationDiv.style.display = 'none';
            }
        } else {
            window.location.reload();
        }
    });

    function buscarInsumos(searchTerm) {
        fetch('../../ConexionSQL/admin-scripts/search-tipo-insumo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'searchTerm=' + encodeURIComponent(searchTerm)
        })
        .then(response => response.json())
        .then(data => {
            tablaInsumosBody.innerHTML = '';
            if (data.length > 0) {
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.id}</td>
                        <td>${item.nombre}</td>
                        <td>
                            <a class='my-button-editar'>Editar</a>
                            <button class='my-button-eliminar'>Eliminar</button>
                        </td>
                    `;
                    row.setAttribute('data-id', item.id); // Asegurar que el data-id se asigna a las nuevas filas
                    tablaInsumosBody.appendChild(row);
                });
            } else {
                tablaInsumosBody.innerHTML = '<tr><td colspan="3">No se encontraron resultados.</td></tr>';
            }
            // La paginación se muestra de nuevo al recargar la página
        })
        .catch(error => {
            console.error('Error al buscar insumos:', error);
            tablaInsumosBody.innerHTML = '<tr><td>Error al realizar la búsqueda.</td></tr>';
            if (paginationDiv) {
                paginationDiv.style.display = 'flex';
            }
        });
    }
});