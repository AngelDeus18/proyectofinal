document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.input-search');
    const tablaInsumosBody = document.querySelector('.crud tbody');
    const paginationDiv = document.querySelector('.pagination');

    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.trim();
        if (searchTerm.length >= 1) {
            buscarUsuarios(searchTerm);
            if (paginationDiv) {
                paginationDiv.style.display = 'none';
            }
        } else {
            window.location.reload();
        }
    });

    function buscarUsuarios(searchTerm) {
        fetch('../../ConexionSQL/admin-scripts/buscar-usuario.php', {
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
                console.error('Error al buscar usuarios:', error);
                tablaInsumosBody.innerHTML = '<tr><td>Error al realizar la búsqueda.</td></tr>';
                if (paginationDiv) {
                    paginationDiv.style.display = 'flex';
                }
            });
    }

    document.querySelectorAll('tbody tr').forEach(row => {
        const idCell = row.querySelector('td');
        if (idCell) {
            row.setAttribute('data-id', idCell.textContent.trim());
        }
    });

    function obtenerNombreRol(id) {
        switch (id) {
            case '1': return 'Administrador';
            case '2': return 'Supervisor';
            case '3': return 'Funcionario';
            default: return '';
        }
    }

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

            document.querySelector('.alertas').innerHTML = '';
            document.querySelector('.alertas-modal').innerHTML = '';

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${data.success ? 'success' : 'error'}`;
            alertDiv.textContent = data.message;

            document.querySelector('.alertas').insertAdjacentElement('afterend', alertDiv.cloneNode(true));
            document.querySelector('.alertas-modal').appendChild(alertDiv);

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
                        <td>${rolNombre}</td>
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
                        row.querySelector('td:nth-child(6)').textContent = rolNombre;
                    }
                }

                document.getElementById('btn-modal').checked = false;
            }

            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(el => el.remove());
            }, 2000);

        } catch (error) {
            console.error('Error en la solicitud AJAX:', error);
        }
    });

    document.querySelector('tbody').addEventListener('click', (e) => {
        if (e.target.classList.contains('my-button-editar')) {
            const form = document.querySelector('#formulario-usuario');
            const modalTitle = document.querySelector('#modal-title');
            const rowData = e.target.closest('tr').querySelectorAll('td');

            form.id.value = rowData[0].innerText;
            form.nombre.value = rowData[1].innerText;
            form.email.value = rowData[2].innerText;
            form.cedula.value = rowData[3].innerText;
            form.contraseña.value = rowData[4].innerText;

            const rolNombre = rowData[5].innerText.trim();
            let rolValue = '';
            switch (rolNombre) {
                case 'Administrador': rolValue = '1'; break;
                case 'Supervisor': rolValue = '2'; break;
                case 'Funcionario': rolValue = '3'; break;
            }
            form.rol.value = rolValue;

            modalTitle.innerText = 'Editar Persona';
            form.action = '../../ConexionSQL/admin-scripts/modificar-usuario.php';
            form.querySelector('input[type="submit"]').value = 'Actualizar';

            document.getElementById('btn-modal').checked = true;
        }
    });

    document.querySelector('tbody').addEventListener('click', async function (e) {
        if (e.target.classList.contains('my-button-eliminar')) {
            const row = e.target.closest('tr');
            const id = row.getAttribute('data-id');

            if (confirm(`¿Estás seguro de eliminar el usuario con ID ${id}?`)) {
                try {
                    const response = await fetch(`../../ConexionSQL/admin-scripts/eliminar.php?id=${id}`);
                    const data = await response.json();

                    const alertDiv = document.createElement('div');
                    alertDiv.className = `alert ${data.success ? 'success' : 'error'}`;
                    alertDiv.textContent = data.message;

                    document.querySelector('.formulario').insertAdjacentElement('afterend', alertDiv.cloneNode(true));
                    document.querySelector('.alertas-modal').appendChild(alertDiv);

                    if (data.success) {
                        row.remove();
                    }

                    setTimeout(() => {
                        document.querySelectorAll('.alert').forEach(el => el.remove());
                    }, 2000);

                } catch (error) {
                    console.error('Error al eliminar:', error);
                }
            }
        }
    });

    document.querySelector('#btn-nuevo')?.addEventListener('click', () => {
        const form = document.querySelector('#formulario-usuario');
        const modalTitle = document.querySelector('#modal-title');
        form.reset();
        form.action = '../../ConexionSQL/admin-scripts/ingresar.php';
        form.querySelector('input[type="submit"]').value = 'Registrar';
        modalTitle.innerText = 'Registrar Persona';
    });
});
