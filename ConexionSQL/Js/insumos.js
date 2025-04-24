// Asignar data-id a filas existentes (por si vienen del servidor sin ese atributo)
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
            form.querySelector('input[type="submit"]').value = isEditMode ? 'Editar Insumo' : 'Registrar';

            const id = isEditMode ? form['id'].value : data.insert_id ?? '—';
            const nombre = formData.get("nombre");
            const descripcion = formData.get("descripcion");
            const estado = formData.get("estado");
            const fecha = formData.get("fecha-registro");

            if (!isEditMode) {
                const tableBody = document.querySelector('tbody');
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-id', id);
                newRow.innerHTML = `
                    <td>${id}</td>
                    <td>${nombre}</td>
                    <td class="descripcion">${descripcion}</td>
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
                    row.querySelector('td:nth-child(2)').textContent = nombre;
                    row.querySelector('td:nth-child(3)').textContent = descripcion;
                    row.querySelector('td:nth-child(4)').textContent = estado;
                    row.querySelector('td:nth-child(5)').textContent = fecha;
                }
            }
        }

        setTimeout(() => alertDiv.remove(), 2000);

    } catch (error) {
        console.error('Error en la solicitud AJAX:', error);
    }
});

// Delegación de evento para botones "Editar" dentro de la tabla
document.querySelector('tbody').addEventListener('click', function (e) {
    if (e.target.classList.contains('my-button-editar')) {
        const button = e.target;
        const form = document.querySelector('.formulario form');
        const row = button.closest('tr');
        const rowData = row.querySelectorAll('td');

        form['id'].value = rowData[0].innerText;
        form.nombre.value = rowData[1].innerText;
        form.descripcion.value = rowData[2].innerText;
        form.estado.value = rowData[3].innerText;
        form['fecha-registro'].value = rowData[4].innerText;

        form.action = '../../ConexionSQL/admin-scripts/modificar-insumos.php';

        const submitButton = document.querySelector('.form_submit');
        submitButton.value = 'Editar Insumo';
    }
});
