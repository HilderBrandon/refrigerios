<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Refrigerios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1>🥗 Gestión de Refrigerios</h1>
                <p>Administra refrigerios y comidas</p>
            </div>
            <a href="../" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>

        <div id="alertas"></div>

        <div class="row mb-4">
            <div class="col-lg-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title form-title">➕ Crear Nuevo Refrigerio</h5>
                        <form id="formRefrigerio">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" placeholder="Ej: Café con pan" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" rows="3" placeholder="Describe los detalles del producto"></textarea>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="comida">
                                <label class="form-check-label" for="comida">
                                    ¿Es comida completa? (Si es TRUE, no especificar bebida ni acompañante)
                                </label>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-secondary" id="btnCancelar" onclick="cancelarEdicion()" style="display: none;">
                                    ❌ Cancelar
                                </button>
                                <button type="submit" class="btn btn-crear">
                                    ➕ Crear Refrigerio
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">📋 Refrigerios Registrados</h5>
                        <div id="lista"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let edicionId = null;

        document.addEventListener('DOMContentLoaded', () => {
            cargar();
            document.getElementById('formRefrigerio').addEventListener('submit', (e) => {
                e.preventDefault();
                edicionId ? actualizar() : crear();
            });
        });

        function crear() {
            const nombre = document.getElementById('nombre').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            const comida = document.getElementById('comida').checked;

            if (!nombre) {
                mostrarAlerta('Completa todos los campos requeridos', 'warning');
                return;
            }

            const fd = new FormData();
            fd.append('nombre', nombre);
            fd.append('descripcion', descripcion);
            if (comida) fd.append('comida', '1');

            fetch('../api/refrigerios_crear.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        mostrarAlerta('Refrigerio creado exitosamente', 'success');
                        document.getElementById('formRefrigerio').reset();
                        cargar();
                    } else {
                        mostrarAlerta(d.message, 'danger');
                    }
                });
        }

        function cargar() {
            fetch('../api/refrigerios_leer.php')
                .then(r => r.json())
                .then(d => {
                    const lista = document.getElementById('lista');
                    if (d.success && d.data.length > 0) {
                        lista.innerHTML = d.data.map(item => `
                            <div class="task-item">
                                <div class="task-title">${escapeHtml(item.nombre)}</div>
                                <div class="task-description">${escapeHtml(item.descripcion || 'Sin descripción')}</div>
                                <div class="task-date">
                                    ${item.comida ? '🍽️ Comida completa' : '☕ Refrigerio'}
                                    <br>📅 ${new Date(item.fecha_creacion).toLocaleDateString('es-ES')}
                                </div>
                                <div class="task-actions">
                                    <button class="btn btn-editar" onclick="prepararEdicion(${item.id}, '${escapeAttr(item.nombre)}', '${escapeAttr(item.descripcion || '')}', ${item.comida})">✏️ Editar</button>
                                    <button class="btn btn-eliminar" onclick="confirmarEliminar(${item.id}, '${escapeAttr(item.nombre)}')">🗑️ Eliminar</button>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        lista.innerHTML = '<div class="empty-state"><p class="no-tasks">No hay refrigerios registrados</p></div>';
                    }
                });
        }

        function prepararEdicion(id, nombre, descripcion, comida) {
            edicionId = id;
            document.getElementById('nombre').value = nombre;
            document.getElementById('descripcion').value = descripcion;
            document.getElementById('comida').checked = comida == 1;
            document.querySelector('.form-title').textContent = '✏️ Editar Refrigerio';
            document.querySelector('button[type="submit"]').textContent = '✏️ Actualizar Refrigerio';
            document.getElementById('btnCancelar').style.display = 'block';
            document.getElementById('nombre').focus();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function actualizar() {
            const nombre = document.getElementById('nombre').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            const comida = document.getElementById('comida').checked;

            if (!nombre) {
                mostrarAlerta('Completa todos los campos requeridos', 'warning');
                return;
            }

            const fd = new FormData();
            fd.append('id', edicionId);
            fd.append('nombre', nombre);
            fd.append('descripcion', descripcion);
            if (comida) fd.append('comida', '1');

            fetch('../api/refrigerios_actualizar.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        mostrarAlerta('Refrigerio actualizado exitosamente', 'success');
                        cancelarEdicion();
                        cargar();
                    } else {
                        mostrarAlerta(d.message, 'danger');
                    }
                });
        }

        function confirmarEliminar(id, nombre) {
            if (confirm(`¿Eliminar "${nombre}"?`)) {
                eliminar(id);
            }
        }

        function eliminar(id) {
            const fd = new FormData();
            fd.append('id', id);

            fetch('../api/refrigerios_eliminar.php', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        mostrarAlerta('Refrigerio eliminado exitosamente', 'success');
                        cargar();
                    } else {
                        mostrarAlerta(d.message, 'danger');
                    }
                });
        }

        function cancelarEdicion() {
            edicionId = null;
            document.getElementById('formRefrigerio').reset();
            document.querySelector('.form-title').textContent = '➕ Crear Nuevo Refrigerio';
            document.querySelector('button[type="submit"]').textContent = '➕ Crear Refrigerio';
            document.getElementById('btnCancelar').style.display = 'none';
        }

        function mostrarAlerta(msg, tipo) {
            const contenedor = document.getElementById('alertas');
            const alerta = document.createElement('div');
            alerta.className = `alert alert-${tipo} alert-dismissible fade show`;
            alerta.innerHTML = `${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
            contenedor.appendChild(alerta);
            setTimeout(() => alerta.remove(), 5000);
        }

        function escapeHtml(text) {
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return text.replace(/[&<>"']/g, m => map[m]);
        }

        function escapeAttr(text) {
            return text.replace(/'/g, "\\'").replace(/"/g, '\\"');
        }
    </script>
</body>
</html>
