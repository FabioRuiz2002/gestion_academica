<?php
// Archivo: views/admin/gestionar_usuarios.php
// (Añadido campo 'Plan de Estudio' a la tabla y modales)
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestionar Usuarios</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">
            <i class="fas fa-plus me-2"></i> Crear Nuevo Usuario
        </button>
    </div>

    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Plan de Estudio (Malla)</th> <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $listaUsuarios->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($row['nombre_rol']); ?></span></td>
                        <td>
                            <?php if ($row['nombre_plan']): ?>
                                <span class="badge bg-info text-dark"><?php echo htmlspecialchars($row['nombre_plan']); ?></span>
                                <br><small class="text-muted"><?php echo htmlspecialchars($row['nombre_escuela']); ?></small>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?php echo $row['estado'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>'; ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="cargarDatosUsuario(<?php echo $row['id_usuario']; ?>)" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="confirmarEliminarUsuario(<?php echo $row['id_usuario']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="index.php?controller=Admin&action=index" class="btn btn-secondary mt-3">Volver al Panel</a>
</div>

<div class="modal fade" id="crearUsuarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Admin&action=crearUsuario" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_rol" class="form-label">Rol</label>
                        <select class="form-select" id="id_rol" name="id_rol" required onchange="togglePlanEstudio('crear')">
                            <option value="">Seleccione un rol</option>
                            <?php foreach ($listaRoles as $rol): ?>
                                <option value="<?php echo $rol['id_rol']; ?>"><?php echo htmlspecialchars($rol['nombre_rol']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3" id="campo_plan_crear" style="display: none;">
                        <label for="id_plan_estudio" class="form-label">Plan de Estudio (Malla)</label>
                        <select class="form-select" id="id_plan_estudio" name="id_plan_estudio">
                            <option value="">Seleccione un plan...</option>
                            <?php foreach ($listaPlanes as $plan): ?>
                                <option value="<?php echo $plan['id_plan_estudio']; ?>">
                                    <?php echo htmlspecialchars($plan['nombre_escuela'] . ' - ' . $plan['nombre_plan']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarUsuarioModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Admin&action=editarUsuario" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_usuario" name="edit_id_usuario">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre" name="edit_nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="edit_apellido" name="edit_apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="edit_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Nueva Contraseña (Opcional)</label>
                        <input type="password" class="form-control" id="edit_password" name="edit_password">
                        <small class="form-text text-muted">Dejar en blanco para no cambiarla.</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_rol" class="form-label">Rol</label>
                        <select class="form-select" id="edit_id_rol" name="edit_id_rol" required onchange="togglePlanEstudio('editar')">
                            <?php foreach ($listaRoles as $rol): ?>
                                <option value="<?php echo $rol['id_rol']; ?>"><?php echo htmlspecialchars($rol['nombre_rol']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3" id="campo_plan_editar" style="display: none;">
                        <label for="edit_id_plan_estudio" class="form-label">Plan de Estudio (Malla)</label>
                        <select class="form-select" id="edit_id_plan_estudio" name="edit_id_plan_estudio">
                            <option value="">Seleccione un plan...</option>
                            <?php foreach ($listaPlanes as $plan): ?>
                                <option value="<?php echo $plan['id_plan_estudio']; ?>">
                                    <?php echo htmlspecialchars($plan['nombre_escuela'] . ' - ' . $plan['nombre_plan']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función para mostrar/ocultar el dropdown de Plan de Estudio
function togglePlanEstudio(tipo) {
    let rol_id, campo_plan_id;
    if (tipo === 'crear') {
        rol_id = document.getElementById('id_rol').value;
        campo_plan_id = 'campo_plan_crear';
    } else {
        rol_id = document.getElementById('edit_id_rol').value;
        campo_plan_id = 'campo_plan_editar';
    }
    
    let campo_plan = document.getElementById(campo_plan_id);
    
    // Si el rol es 'Estudiante' (ID 3), muestra el campo. Si no, ocúltalo.
    if (rol_id == 3) {
        campo_plan.style.display = 'block';
    } else {
        campo_plan.style.display = 'none';
        // Si se oculta, resetea el valor para no enviar un plan a un profesor
        campo_plan.querySelector('select').value = ''; 
    }
}

// Lógica para el modal de EDICIÓN
function cargarDatosUsuario(id) {
    fetch('index.php?controller=Admin&action=getUsuario&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                document.getElementById('edit_id_usuario').value = data.id_usuario;
                document.getElementById('edit_nombre').value = data.nombre;
                document.getElementById('edit_apellido').value = data.apellido;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_id_rol').value = data.id_rol;
                
                // Asigna el plan de estudio guardado
                document.getElementById('edit_id_plan_estudio').value = data.id_plan_estudio;
                
                // Llama a la función para mostrar/ocultar el campo al cargar
                togglePlanEstudio('editar'); 
            }
        });
}

function confirmarEliminarUsuario(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
        fetch('index.php?controller=Admin&action=eliminarUsuario', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_usuario=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Error al eliminar el usuario: ' + data.message);
            }
        });
    }
}
</script>