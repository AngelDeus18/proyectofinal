document.addEventListener('DOMContentLoaded', function () {
    const mensajeContenedor = document.getElementById('mensaje');

    function mostrarMensaje(texto, tipo = 'exito') {
        mensajeContenedor.textContent = texto;
        mensajeContenedor.className = tipo === 'exito' ? 'mensaje-exito' : 'mensaje-error';
        mensajeContenedor.style.display = 'block';
        setTimeout(() => {
            mensajeContenedor.style.display = 'none';
        }, 4000);
    }
    document.querySelectorAll('.my-button-prestar').forEach((button) => {
        button.addEventListener('click', () => {
            const form = document.querySelector('.formulario form');
            const rowData = button.closest('tr').querySelectorAll('td');

            form.insprestado.value = rowData[0].innerText;
            form.descripcion.value = rowData[2].innerText;

            const insumoID = button.getAttribute('data-insumoid');
            form.InsumoID.value = insumoID;
            form.action = '../../ConexionSQL/funcionario-scripts/prestar-insumo-funcionario.php';

            const now = new Date();
            const localISOTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
            form['fecha-prestamo'].value = localISOTime;
            form['fecha-prestamo'].readOnly = true;
            form['fecha-prestamo'].style.backgroundColor = '#eee';
            form['fecha-prestamo'].addEventListener('keydown', e => e.preventDefault());
        });
    });

    // Filtro por categoría
    document.querySelectorAll('.categoria-btn').forEach(button => {
        button.addEventListener('click', () => {
            const categoria = button.getAttribute('data-categoria');
            const url = new URL(window.location.href);
            url.searchParams.set('categoria', categoria);
            window.location.href = url.toString();
        });
    });

    // Validación y envío del formulario
    const form = document.querySelector('.formulario form');
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const insumo = form.insprestado.value.trim();
        const cantidad = form.cantidad.value.trim();
        const fechaPrestamo = new Date(form['fecha-prestamo'].value);
        const fechaEntregaStr = form['fecha-entrega'].value.trim();

        // Validar campos vacíos
        if (!insumo || !cantidad || !fechaEntregaStr) {
            mostrarMensaje('Todos los campos son obligatorios.', 'error');
            return;
        }

        const fechaEntrega = new Date(fechaEntregaStr);

        // Validar fecha de entrega
        if (fechaEntrega < fechaPrestamo) {
            mostrarMensaje('La fecha de entrega no puede ser menor que la fecha de préstamo.', 'error');
            return;
        }

        // Enviar datos vía fetch
        const formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())  
            .then(data => {
                if (data.status === "exito") {
                    mostrarMensaje("Préstamo realizado exitosamente.");
                    form.reset();
                    actualizarTablaInsumos(); 
                } else if (data.status === "error") {
                    mostrarMensaje(data.mensaje, 'error');
                }
            })
            .catch(error => {
                mostrarMensaje("Error en la petición: " + error, 'error');
            });
    });

    function actualizarTablaInsumos() {
        const tabla = document.querySelector('#tabla-insumos tbody');
        const formData = new FormData();

        fetch('../../ConexionSQL/funcionario-scripts/obtener-insumos.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json()) 
            .then(insumos => {
                tabla.innerHTML = ''; 

                insumos.forEach(insumo => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${insumo.TipoNombre}</td>
                        <td>${insumo.Cantidad}</td>
                        <td>${insumo.Descripcion}</td>
                        <td>${insumo.Estado}</td>
                        <td><a data-insumoid="${insumo.id}" class="my-button-prestar">Prestar</a></td>
                    `;
                    tabla.appendChild(fila);
                });

                document.querySelectorAll('.my-button-prestar').forEach((button) => {
                    button.addEventListener('click', () => {
                        const form = document.querySelector('.formulario form');
                        const rowData = button.closest('tr').querySelectorAll('td');

                        form.insprestado.value = rowData[0].innerText;
                        form.cantidad.value = rowData[1].innerText;
                        form.descripcion.value = rowData[2].innerText;

                        const insumoID = button.getAttribute('data-insumoid');
                        form.InsumoID.value = insumoID;
                        form.action = '../../ConexionSQL/funcionario-scripts/prestar-insumo-funcionario.php';

                        const now = new Date();
                        const localISOTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
                        form['fecha-prestamo'].value = localISOTime;
                        form['fecha-prestamo'].readOnly = true;
                        form['fecha-prestamo'].style.backgroundColor = '#eee';
                        form['fecha-prestamo'].addEventListener('keydown', e => e.preventDefault());
                    });
                });
            })
            .catch(error => console.error("Error fetching insumos:", error));
    }
});
