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

    const isEditMode = form.action.includes('modificar-usuario.php');
    const url = isEditMode
        ? '../../ConexionSQL/admin-scripts/modificar-usuario.php'
        : '../../ConexionSQL/admin-scripts/ingresar.php';

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        const messageContainer = document.querySelector('.alertas');

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${data.success ? 'success' : 'error'}`;
        alertDiv.textContent = data.message;
        messageContainer.insertAdjacentElement('afterend', alertDiv);

        if (data.success) {
            form.reset();
            form.action = '../../ConexionSQL/admin-scripts/ingresar.php';
            form.querySelector('input[type="submit"]').value = 'Registrar'; 

            const id = isEditMode ? form['id'].value : data.insert_id ?? '—';
            const nombre = formData.get("nombre");
            const email = formData.get("email");
            const cedula = formData.get("cedula");
            const contraseña = formData.get("contraseña");
            const id_roles = formData.get("rol");

            // Asignar el nombre del rol basado en el id
            const rolNombre = obtenerNombreRol(id_roles);

            if (!isEditMode) {
                const tableBody = document.querySelector('tbody');
                const newRow = document.createElement('tr');
                newRow.setAttribute('data-id', id);
                newRow.innerHTML = `
                    <td>${id}</td>
                    <td>${nombre}</td>
                    <td class="email">${email}</td>
                    <td>${cedula}</td>
                    <td>${contraseña}</td>
                    <td>${rolNombre}</td> <!-- Mostrar nombre del rol -->
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
                    row.querySelector('td:nth-child(3)').textContent = email;
                    row.querySelector('td:nth-child(4)').textContent = cedula;
                    row.querySelector('td:nth-child(5)').textContent = contraseña;
                    row.querySelector('td:nth-child(6)').textContent = rolNombre; // Actualizar nombre del rol
                }
            }
        }

        setTimeout(() => alertDiv.remove(), 2000);

    } catch (error) {
        console.error('Error en la solicitud AJAX:', error);
    }
});

// Función para obtener el nombre del rol a partir del id
function obtenerNombreRol(id) {
    switch (id) {
        case '1':
            return 'Administrador';
        case '2':
            return 'Supervisor';
        case '3':
            return 'Funcionario';
        default:
            return '';
    }
}

// Delegación para botones "Editar"
document.querySelector('tbody').addEventListener('click', (e) => {
    if (e.target.classList.contains('my-button-editar')) {
        const modal = document.querySelector('.container-modal');
        const modalHeader = document.querySelector('.modal-header h2');
        const form = document.querySelector('#formulario-usuario');

        modalHeader.innerText = 'Editar Persona';
        const rowData = e.target.closest('tr').querySelectorAll('td');

        form.id.value = rowData[0].innerText;
        form.nombre.value = rowData[1].innerText;
        form.email.value = rowData[2].innerText;
        form.cedula.value = rowData[3].innerText;
        form.contraseña.value = rowData[4].innerText;
        const rolNombre = rowData[5].innerText.trim();
        let rolValue = '';
        switch (rolNombre) {
            case 'Administrador':
                rolValue = '1';
                break;
            case 'Supervisor':
                rolValue = '2';
                break;
            case 'Funcionario':
                rolValue = '3';
                break;
            default:
                rolValue = '';
        }
        form.rol.value = rolValue;

        form.action = '../../ConexionSQL/admin-scripts/modificar-usuario.php';
        document.querySelector('.form_submit').value = 'Actualizar';

        modal.style.display = 'block';
    }
});

// Delegación para botones "Eliminar"
document.querySelector('tbody').addEventListener('click', async function (e) {
    if (e.target.classList.contains('my-button-eliminar')) {
        const row = e.target.closest('tr');
        const id = row.getAttribute('data-id');

        if (confirm(`¿Estás seguro de eliminar el usuario con ID ${id}?`)) {
            try {
                const response = await fetch(`../../ConexionSQL/admin-scripts/eliminar.php?id=${id}`);
                const data = await response.json();

                const messageContainer = document.querySelector('.formulario');
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

// Abrir modal para nuevo usuario
document.querySelector('.boton-modal').addEventListener('click', () => {
    const form = document.querySelector('#formulario-usuario');
    form.reset();

    form.action = '../../ConexionSQL/admin-scripts/ingresar.php';
    document.querySelector('#id').value = '';
    document.querySelector('.modal-header h2').innerText = 'Nueva Persona';
    document.querySelector('.form_submit').value = 'Guardar';

    const modal = document.querySelector('.container-modal');
    modal.style.display = 'block';
});

document.querySelector('.btn-cerrar').addEventListener('click', () => {
    document.querySelector('.container-modal').style.display = 'none';
});
