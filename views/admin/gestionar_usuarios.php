<?php /* Archivo: views/admin/gestionar_usuarios.php */ ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestionar Usuarios</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal"><i class="fas fa-user-plus me-2"></i> Nuevo Usuario</button>
    </div>

    <div class="card mb-3 bg-light border-0"><div class="card-body p-2"><div class="input-group"><span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span><input type="text" id="inputBusqueda" class="form-control border-start-0" placeholder="Buscar por nombre, DNI o código..." onkeyup="filtrarUsuarios()"></div></div></div>

    <?php if (isset($_SESSION['error_message'])): ?><div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
    <?php if (isset($_SESSION['success_message'])): ?><div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

    <ul class="nav nav-tabs" id="userTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" id="estudiantes-tab" data-bs-toggle="tab" data-bs-target="#estudiantes" type="button">Estudiantes</button></li>
        <li class="nav-item"><button class="nav-link" id="profesores-tab" data-bs-toggle="tab" data-bs-target="#profesores" type="button">Profesores</button></li>
        <li class="nav-item"><button class="nav-link" id="admins-tab" data-bs-toggle="tab" data-bs-target="#admins" type="button">Administradores</button></li>
    </ul>

    <div class="tab-content mt-3" id="userTabsContent">
        <div class="tab-pane fade show active" id="estudiantes">
            <div class="accordion" id="accordionFacultades">
                <?php if (empty($listaEstudiantesAgrupados)): ?><div class="alert alert-info">No hay estudiantes.</div><?php endif; ?>
                <?php foreach ($listaEstudiantesAgrupados as $facultad => $escuelas): $fid = md5($facultad); ?>
                    <div class="accordion-item usuario-row" data-texto="<?php echo strtolower($facultad); ?>">
                        <h2 class="accordion-header" id="h-<?php echo $fid; ?>"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c-<?php echo $fid; ?>"><i class="fas fa-building me-2"></i> <?php echo htmlspecialchars($facultad); ?></button></h2>
                        <div id="c-<?php echo $fid; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFacultades">
                            <div class="accordion-body">
                                <?php foreach ($escuelas as $escuela => $estudiantes): ?>
                                    <h5 class="text-primary mt-3"><?php echo htmlspecialchars($escuela); ?></h5>
                                    <div class="table-responsive"><table class="table table-sm table-hover align-middle"><thead class="table-light"><tr><th>Código</th><th>DNI</th><th>Nombre</th><th>Email</th><th class="text-end">Acciones</th></tr></thead><tbody>
                                    <?php foreach ($estudiantes as $row): $textoBusqueda = strtolower($row['dni'] . ' ' . $row['nombre'] . ' ' . $row['apellido'] . ' ' . $row['codigo_estudiante']); ?>
                                        <tr class="usuario-item" data-texto="<?php echo $textoBusqueda; ?>">
                                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($row['codigo_estudiante']); ?></span></td>
                                            <td><?php echo htmlspecialchars($row['dni']); ?></td>
                                            <td><?php echo htmlspecialchars($row['apellido'] . ', ' . $row['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td class="text-end">
                                                <a href="index.php?controller=Admin&action=gestionarMatriculaAlumno&id_estudiante=<?php echo $row['id_usuario']; ?>" class="btn btn-info btn-sm text-white" title="Ver Matrícula"><i class="fas fa-book-open"></i></a>
                                                <button class="btn btn-warning btn-sm" onclick="cargarUsuario(<?php echo $row['id_usuario']; ?>)" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(<?php echo $row['id_usuario']; ?>)"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?></tbody></table></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="profesores">
            <div class="table-responsive"><table class="table table-striped align-middle"><thead class="table-dark"><tr><th>DNI</th><th>Nombre</th><th>Email</th><th class="text-end">Acciones</th></tr></thead><tbody>
            <?php foreach ($listaProfesores as $row): $textoBusqueda = strtolower($row['dni'] . ' ' . $row['nombre'] . ' ' . $row['apellido']); ?>
                <tr class="usuario-item" data-texto="<?php echo $textoBusqueda; ?>">
                    <td><?php echo htmlspecialchars($row['dni']); ?></td><td><?php echo htmlspecialchars($row['apellido'] . ', ' . $row['nombre']); ?></td><td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="text-end"><button class="btn btn-warning btn-sm" onclick="cargarUsuario(<?php echo $row['id_usuario']; ?>)" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal"><i class="fas fa-edit"></i></button><button class="btn btn-danger btn-sm" onclick="eliminarUsuario(<?php echo $row['id_usuario']; ?>)"><i class="fas fa-trash"></i></button></td>
                </tr>
            <?php endforeach; ?></tbody></table></div>
        </div>

        <div class="tab-pane fade" id="admins">
            <div class="table-responsive"><table class="table table-striped align-middle"><thead class="table-dark"><tr><th>DNI</th><th>Nombre</th><th>Email</th><th class="text-end">Acciones</th></tr></thead><tbody>
            <?php foreach ($listaAdmins as $row): $textoBusqueda = strtolower($row['dni'] . ' ' . $row['nombre'] . ' ' . $row['apellido']); ?>
                <tr class="usuario-item" data-texto="<?php echo $textoBusqueda; ?>">
                    <td><?php echo htmlspecialchars($row['dni']); ?></td><td><?php echo htmlspecialchars($row['apellido'] . ', ' . $row['nombre']); ?></td><td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td class="text-end"><button class="btn btn-warning btn-sm" onclick="cargarUsuario(<?php echo $row['id_usuario']; ?>)" data-bs-toggle="modal" data-bs-target="#editarUsuarioModal"><i class="fas fa-edit"></i></button></td>
                </tr>
            <?php endforeach; ?></tbody></table></div>
        </div>
    </div>

    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>

<div class="modal fade" id="crearUsuarioModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form action="index.php?controller=Admin&action=crearUsuario" method="POST"><div class="modal-header"><h5 class="modal-title">Nuevo Usuario</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="mb-3"><label>Nombre</label><input type="text" name="nombre" class="form-control" required></div><div class="mb-3"><label>Apellido</label><input type="text" name="apellido" class="form-control" required></div><div class="mb-3"><label>DNI</label><input type="text" name="dni" class="form-control" required></div><div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div><div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div><div class="mb-3"><label>Rol</label><select name="id_rol" id="rol_crear" class="form-select" required onchange="togglePlan('crear')"><option value="">Seleccione...</option><?php foreach($listaRoles as $r): ?><option value="<?php echo $r['id_rol']; ?>"><?php echo $r['nombre_rol']; ?></option><?php endforeach; ?></select></div><div class="mb-3" id="plan_crear" style="display:none;"><label>Plan de Estudio</label><select name="id_plan_estudio" class="form-select"><option value="">Seleccione...</option><?php foreach($listaPlanes as $p): ?><option value="<?php echo $p['id_plan_estudio']; ?>"><?php echo htmlspecialchars($p['nombre_escuela'].' - '.$p['nombre_plan']); ?></option><?php endforeach; ?></select></div></div><div class="modal-footer"><button type="submit" class="btn btn-primary">Guardar</button></div></form></div></div></div>

<div class="modal fade" id="editarUsuarioModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form action="index.php?controller=Admin&action=editarUsuario" method="POST"><div class="modal-header"><h5 class="modal-title">Editar Usuario</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><input type="hidden" name="edit_id_usuario" id="edit_id"><div class="mb-3"><label>Nombre</label><input type="text" name="edit_nombre" id="edit_nombre" class="form-control" required></div><div class="mb-3"><label>Apellido</label><input type="text" name="edit_apellido" id="edit_apellido" class="form-control" required></div><div class="mb-3"><label>DNI</label><input type="text" name="edit_dni" id="edit_dni" class="form-control" required></div><div class="mb-3"><label>Email</label><input type="email" name="edit_email" id="edit_email" class="form-control" required></div><div class="mb-3"><label>Password</label><input type="password" name="edit_password" class="form-control"></div><div class="mb-3"><label>Rol</label><select name="edit_id_rol" id="edit_rol" class="form-select" required onchange="togglePlan('editar')"><?php foreach($listaRoles as $r): ?><option value="<?php echo $r['id_rol']; ?>"><?php echo $r['nombre_rol']; ?></option><?php endforeach; ?></select></div><div class="mb-3" id="plan_editar" style="display:none;"><label>Plan de Estudio</label><select name="edit_id_plan_estudio" id="edit_plan" class="form-select"><option value="">Seleccione...</option><?php foreach($listaPlanes as $p): ?><option value="<?php echo $p['id_plan_estudio']; ?>"><?php echo htmlspecialchars($p['nombre_escuela'].' - '.$p['nombre_plan']); ?></option><?php endforeach; ?></select></div></div><div class="modal-footer"><button type="submit" class="btn btn-primary">Actualizar</button></div></form></div></div></div>

<script>
function filtrarUsuarios() { let input = document.getElementById('inputBusqueda').value.toLowerCase(); let filas = document.querySelectorAll('.usuario-item'); filas.forEach(fila => { let texto = fila.getAttribute('data-texto'); fila.style.display = texto.includes(input) ? '' : 'none'; }); let acordeones = document.querySelectorAll('.accordion-collapse'); acordeones.forEach(acc => { if (input.length > 0) acc.classList.add('show'); else acc.classList.remove('show'); }); }
function togglePlan(modo) { let rol = document.getElementById(modo === 'crear' ? 'rol_crear' : 'edit_rol').value; document.getElementById(modo === 'crear' ? 'plan_crear' : 'plan_editar').style.display = (rol == 3) ? 'block' : 'none'; }
function cargarUsuario(id) { fetch('index.php?controller=Admin&action=getUsuario&id='+id).then(r=>r.json()).then(d=>{ document.getElementById('edit_id').value=d.id_usuario; document.getElementById('edit_nombre').value=d.nombre; document.getElementById('edit_apellido').value=d.apellido; document.getElementById('edit_dni').value=d.dni; document.getElementById('edit_email').value=d.email; document.getElementById('edit_rol').value=d.id_rol; document.getElementById('edit_plan').value=d.id_plan_estudio; togglePlan('editar'); }); }
function eliminarUsuario(id) { if(confirm('¿Eliminar?')) fetch('index.php?controller=Admin&action=eliminarUsuario', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'id_usuario='+id}).then(r=>r.json()).then(d=>{ if(d.success) location.reload(); else alert('Error al eliminar'); }); }
</script>