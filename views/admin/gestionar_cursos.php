<?php /* Archivo: views/admin/gestionar_cursos.php */ ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Repositorio de Cursos</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearCursoModal"><i class="fas fa-plus"></i> Crear Curso</button>
    </div>
    <?php if (isset($_SESSION['error_message_curso'])): ?><div class="alert alert-danger"><?php echo $_SESSION['error_message_curso']; unset($_SESSION['error_message_curso']); ?></div><?php endif; ?>
    
    <table class="table table-striped">
        <thead class="table-dark"><tr><th>ID</th><th>Nombre</th><th>Horario</th><th>Profesor</th><th>Año</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php while($row = $listaCursos->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $row['id_curso']; ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_curso']); ?></td>
                    <td><?php echo htmlspecialchars($row['horario']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_profesor'].' '.$row['apellido_profesor']); ?></td>
                    <td><?php echo $row['anio_academico']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="cargarCurso(<?php echo $row['id_curso']; ?>)" data-bs-toggle="modal" data-bs-target="#editarCursoModal"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarCurso(<?php echo $row['id_curso']; ?>)"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="crearCursoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Admin&action=crearCurso" method="POST">
                <div class="modal-header"><h5 class="modal-title">Nuevo Curso</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label>Nombre</label><input type="text" name="nombre_curso" class="form-control" required></div>
                    <div class="mb-3"><label>Descripción</label><textarea name="descripcion" class="form-control"></textarea></div>
                    <div class="mb-3"><label>Horario (Ej: Lu 8-10)</label><input type="text" name="horario" class="form-control" required></div>
                    <div class="mb-3"><label>Profesor</label>
                        <select name="id_profesor" class="form-select" required>
                            <?php foreach($listaProfesores as $p): ?><option value="<?php echo $p['id_usuario']; ?>"><?php echo htmlspecialchars($p['apellido'].' '.$p['nombre']); ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label>Año</label><input type="number" name="anio_academico" class="form-control" value="<?php echo date('Y'); ?>" required></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Guardar</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarCursoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Admin&action=editarCurso" method="POST">
                <div class="modal-header"><h5 class="modal-title">Editar Curso</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <input type="hidden" name="edit_id_curso" id="edit_id">
                    <div class="mb-3"><label>Nombre</label><input type="text" name="edit_nombre_curso" id="edit_nombre" class="form-control" required></div>
                    <div class="mb-3"><label>Descripción</label><textarea name="edit_descripcion" id="edit_desc" class="form-control"></textarea></div>
                    <div class="mb-3"><label>Horario</label><input type="text" name="edit_horario" id="edit_horario" class="form-control" required></div>
                    <div class="mb-3"><label>Profesor</label>
                        <select name="edit_id_profesor" id="edit_prof" class="form-select" required>
                            <?php foreach($listaProfesores as $p): ?><option value="<?php echo $p['id_usuario']; ?>"><?php echo htmlspecialchars($p['apellido'].' '.$p['nombre']); ?></option><?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3"><label>Año</label><input type="number" name="edit_anio_academico" id="edit_anio" class="form-control" required></div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Actualizar</button></div>
            </form>
        </div>
    </div>
</div>

<script>
function cargarCurso(id) {
    fetch('index.php?controller=Admin&action=getCurso&id='+id).then(r=>r.json()).then(d=>{
        document.getElementById('edit_id').value=d.id_curso;
        document.getElementById('edit_nombre').value=d.nombre_curso;
        document.getElementById('edit_desc').value=d.descripcion;
        document.getElementById('edit_horario').value=d.horario;
        document.getElementById('edit_prof').value=d.id_profesor;
        document.getElementById('edit_anio').value=d.anio_academico;
    });
}
function eliminarCurso(id) {
    if(confirm('¿Eliminar curso?')) {
        fetch('index.php?controller=Admin&action=eliminarCurso', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'id_curso='+id})
        .then(r=>r.json()).then(d=>{ if(d.success) location.reload(); });
    }
}
</script>