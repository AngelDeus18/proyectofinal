document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.input-search');
    const tablaInsumosBody = document.querySelector('.crud tbody');
    const paginationDiv = document.querySelector('.pagination');

    searchInput.addEventListener('input', function() {
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
        fetch('../../ConexionSQL/admin-scripts/buscar-insumo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'searchTerm=' + encodeURIComponent(searchTerm)
        })
        .then(response => response.text())
        .then(data => {
            tablaInsumosBody.innerHTML = data;
            document.querySelectorAll('.crud tbody tr').forEach(row => {
                const idCell = row.querySelector('td');
                if (idCell) {
                    row.setAttribute('data-id', idCell.textContent.trim());
                }
            });
        })
        .catch(error => {
            console.error('Error al buscar insumos:', error);
            tablaInsumosBody.innerHTML = '<tr><td>Error al realizar la búsqueda.</td></tr>';
            if (paginationDiv) {
                paginationDiv.style.display = 'flex';
            }
        });
    }

    // Asignar data-id a filas cargadas inicialmente
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

        const isEditMode = form.action.includes('modificar-insumos.php');
        const url = isEditMode
            ? '../../ConexionSQL/admin-scripts/modificar-insumos.php'
            : '../../ConexionSQL/admin-scripts/new-insumo.php';

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
                form.action = '../../ConexionSQL/admin-scripts/new-insumo.php';
                form.querySelector('input[type="submit"]').value = 'Registrar';

                const id = isEditMode ? form['id'].value : data.insert_id ?? '—';
                const nombreTipoInsumo = data.nombre_tipo_insumo;
                const descripcion = formData.get("descripcion");
                const cantidad = formData.get("cantidad");
                const estado = formData.get("estado");
                const fechaRaw = formData.get("fecha-registro");
                const fecha = fechaRaw.replace('T', ' ');

                if (!isEditMode) {
                    const tableBody = document.querySelector('tbody');
                    const newRow = document.createElement('tr');
                    newRow.setAttribute('data-id', id);
                    newRow.innerHTML = `
                        <td>${id}</td>
                        <td>${nombreTipoInsumo}</td>
                        <td class="descripcion">${descripcion}</td>
                        <td>${cantidad}</td>
                        <td>${estado}</td>
                        <td>${fecha}</td>
                        <td>
                            <a class='my-button-editar'>Editar</a>
                            <button class='my-button-eliminar'>Eliminar</button>
                        </td>
                    `;
                    tableBody.prepend(newRow);
                } else {
                    const row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        row.querySelector('td:nth-child(2)').textContent = nombreTipoInsumo;
                        row.querySelector('td:nth-child(3)').textContent = descripcion;
                        row.querySelector('td:nth-child(4)').textContent = cantidad;
                        row.querySelector('td:nth-child(5)').textContent = estado;
                        row.querySelector('td:nth-child(6)').textContent = fecha;
                    }
                    // Si estamos en modo edición y hay un término de búsqueda,
                    // necesitamos volver a ejecutar la búsqueda para actualizar la tabla filtrada.
                    const searchTerm = searchInput.value.trim();
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

    // Delegación para botones "Editar"
    document.querySelector('tbody').addEventListener('click', function (e) {
        if (e.target.classList.contains('my-button-editar')) {
            const button = e.target;
            const form = document.querySelector('.formulario form');
            const row = button.closest('tr');
            const rowData = row.querySelectorAll('td');

            form['id'].value = rowData[0].innerText;
            form.nombre.value = rowData[1].innerText;
            form.descripcion.value = rowData[2].innerText;
            form.cantidad.value = rowData[3].innerText;
            form.estado.value = rowData[4].innerText;
            form['fecha-registro'].value = rowData[5].innerText;

            const select = form.nombre;
            const option = Array.from(select.options).find(opt => opt.text === rowData[1].innerText);
            if (option) {
                select.value = option.value;
            }

            form.action = '../../ConexionSQL/admin-scripts/modificar-insumos.php';

            const submitButton = document.querySelector('.form_submit');
            submitButton.value = 'Editar Insumo';
        }
    });

    // Delegación para botones "Eliminar" (sin cambios necesarios aquí)
    document.querySelector('tbody').addEventListener('click', async function (e) {
        if (e.target.classList.contains('my-button-eliminar')) {
            const row = e.target.closest('tr');
            const id = row.getAttribute('data-id');

            if (confirm(`¿Estás seguro de eliminar el insumo con ID ${id}?`)) {
                try {
                    const response = await fetch(`../../ConexionSQL/admin-scripts/eliminar-insumos.php?id=${id}`);
                    const data = await response.json();

                    const messageContainer = document.querySelector('.formulario h1');
                    const alertDiv = document.createElement('div');
                    alertDiv.className = `alert ${data.success ? 'success' : 'error'}`;
                    alertDiv.textContent = data.message;
                    messageContainer.insertAdjacentElement('afterend', alertDiv);

                    if (data.success) {
                        row.remove();
                    }

                    setTimeout(() => alertDiv.remove(), 2000);

                } catch (error) {
                    console.error('Error al eliminar:', error);
                }
            }
        }
    });
});