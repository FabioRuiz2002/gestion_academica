<?php
/*
 * Archivo: views/admin/gestionar_usuarios.php
 * (REDISEÑADO con Pestañas, N°, ID, DNI, Código y Estudiantes agrupados)
 * (CORREGIDOS todos los errores de 'class.')
 */
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
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <ul class="nav nav-tabs" id="userTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="estudiantes-tab" data-bs-toggle="tab" data-bs-target="#estudiantes" type="button" role="tab">
                <i class="fas fa-user-graduate me-1"></i> Estudiantes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="profesores-tab" data-bs-toggle="tab" data-bs-target="#profesores" type="button" role="tab">
                <i class="fas fa-chalkboard-teacher me-1"></i> Profesores
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="admins-tab" data-bs-toggle="tab" data-bs-target="#admins" type="button" role="tab">
                <i class="fas fa-user-shield me-1"></i> Administradores
            </button>
        </li>
    </ul>

    <div class="tab-content" id="userTabsContent">
        
        <div class="tab-pane fade show active" id="estudiantes" role="tabpanel">
            <div class="accordion mt-3" id="accordionFacultades">
                <?php if (empty($listaEstudiantesAgrupados)): ?>
                    <div class="alert alert-info mt-3">No hay estudiantes registrados.</div>
                <?php endif; ?>

                <?php foreach ($listaEstudiantesAgrupados as $facultad => $escuelas): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-fac-<?php echo preg_replace('/[^a-z0-9]/i', '', $facultad); ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-fac-<?php echo preg_replace('/[^a-z0-9]/i', '', $facultad); ?>">
                                <i class="fas fa-building me-2"></i> <?php echo htmlspecialchars($facultad); ?>
                            </button>
                        </h2>
                        <div id="collapse-fac-<?php echo preg_replace('/[^a-z0-9]/i', '', $facultad); ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFacultades">
                            <div class="accordion-body">
                                <?php foreach ($escuelas as $escuela => $estudiantes): ?>
                                    <h5 class="text-primary"><?php echo htmlspecialchars($escuela); ?></h5>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>ID</th>
                                                    <th>Código</th>
                                                    <th>DNI</th>
                                                    <th>Nombres y Apellidos</th>
                                                    <th>Email</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; foreach ($estudiantes as $row): ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><?php echo $row['id_usuario']; ?></td>
                                                        <td><?php echo htmlspecialchars($row['codigo_estudiante']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['dni']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['apellido'] . ', ' . $row['nombre']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                        <td><?php echo $row['estado'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>'; ?></td>
                                                        <td>
                                                            <button class="btn btn-warning btn-sm" onclick="cargarDatosUsuario(<?php echo $row['id_usuario']; ?>)" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal" title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="tab-pane fade" id="profesores" role="tabpanel">
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>N°</th>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombres y Apellidos</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listaProfesores)): ?>
                            <tr><td colspan="7" class="text-center">No hay profesores registrados.</td></tr>
                        <?php endif; ?>
                        <?php $i = 1; foreach ($listaProfesores as $row): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $row['id_usuario']; ?></td>
                                <td><?php echo htmlspecialchars($row['dni']); ?></td>
                                <td><?php echo htmlspecialchars($row['apellido'] . ', ' . $row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo $row['estado'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>'; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="cargarDatosUsuario(<?php echo $row['id_usuario']; ?>)" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="tab-pane fade" id="admins" role="tabpanel">
             <div class="table-responsive mt-3">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>N°</th>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombres y Apellidos</th>
                            <th>Email</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listaAdmins)): ?>
                            <tr><td colspan="7" class="text-center">No hay administradores registrados.</td></tr>
                        <?php endif; ?>
                        <?php $i = 1; foreach ($listaAdmins as $row): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $row['id_usuario']; ?></td>
                                <td><?php echo htmlspecialchars($row['dni']); ?></td>
                                <td><?php echo htmlspecialchars($row['apellido'] . ', ' . $row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo $row['estado'] ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-danger">Inactivo</span>'; ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="cargarDatosUsuario(<?php echo $row['id_usuario']; ?>)" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <a href="index.php?controller=Admin&action=index" class="btn btn-secondary mt-3">
        <i class="fas fa-arrow-left me-2"></i> Volver
    </a>
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
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="dni" name="dni" required maxlength="15">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" 
                               required 
                               minlength="8"
                               pattern="(?=.*[a-z])(?=.*[A-Z]).{8,}"
                               title="Mínimo 8 caracteres, al menos una mayúscula y una minúscula.">
                        <small class="form-text text-muted">Mín. 8 caracteres, 1 mayúscula y 1 minúscula.</small>
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
                        <label for="edit_dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="edit_dni" name="edit_dni" required maxlength="15">
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="edit_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Nueva Contraseña (Opcional)</label>
                        <input type="password" class="form-control" id="edit_password" name="edit_password"
                               minlength="8"
                               pattern="(?=.*[a-z])(?=.*[A-Z]).{8,}"
                               title="Mínimo 8 caracteres, al menos una mayúscula y una minúscula.">
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
    if (rol_id == 3) {
        campo_plan.style.display = 'block';
    } else {
        campo_plan.style.display = 'none';
        campo_plan.querySelector('select').value = ''; 
    }
}
// SCRIPT MODIFICADO: Ahora carga el DNI
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
                document.getElementById('edit_dni').value = data.dni;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_id_rol').value = data.id_rol;
                document.getElementById('edit_id_plan_estudio').value = data.id_plan_estudio;
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
                alert('Error al eliminar el usuario: ' . data.message);
            }
        });
    }
}
</script>