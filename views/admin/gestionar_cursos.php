<?php
/*
 * Archivo: views/admin/gestionar_cursos.php
 * Propósito: Página independiente para el CRUD de Cursos.
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestionar Cursos (Repositorio General)</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearCursoModal">
            <i class="fas fa-plus me-2"></i> Crear Nuevo Curso
        </button>
    </div>
    <p>Este es el repositorio general de todos los cursos. Para asignarlos a una malla, ve a "Gestión Académica".</p>

    <?php
    if (isset($_SESSION['error_message_curso'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error_message_curso'] . '</div>';
        unset($_SESSION['error_message_curso']);
    }
    ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Curso</th>
                    <th>Horario</th>
                    <th>Profesor</th>
                    <th>Año</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $listaCursos->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id_curso']; ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_curso']); ?></td>
                        <td><?php echo htmlspecialchars($row['horario']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_profesor'] . ' ' . $row['apellido_profesor']); ?></td>
                        <td><?php echo htmlspecialchars($row['anio_academico']); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="cargarDatosCurso(<?php echo $row['id_curso']; ?>)" data-bs-toggle="modal" data-bs-target="#editarCursoModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="confirmarEliminarCurso(<?php echo $row['id_curso']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="index.php?controller=Admin&action=index" class="btn btn-secondary mt-3">Volver al Panel Principal</a>
</div>

<div class="modal fade" id="crearCursoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Admin&action=crearCurso" method="POST">
                <div class="modal-header"><h5 class="modal-title">Crear Nuevo Curso</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_curso" class="form-label">Nombre del Curso</label>
                        <input type="text" class="form-control" id="nombre_curso" name="nombre_curso" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="horario" class="form-label">Horario (Ej: Lu 9-11, Mi 9-11)</label>
                        <input type="text" class="form-control" id="horario" name="horario" placeholder="Ej: Lu 9-11, Mi 9-11">
                    </div>
                    <div class="mb-3">
                        <label for="id_profesor" class="form-label">Profesor</label>
                        <select class="form-select" id="id_profesor" name="id_profesor" required>
                            <option value="">Seleccione un profesor</option>
                            <?php foreach ($listaProfesores as $profesor): ?>
                                <option value="<?php echo $profesor['id_usuario']; ?>"><?php echo htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellido']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="anio_academico" class="form-label">Año Académico</label>
                        <input type="number" class="form-control" id="anio_academico" name="anio_academico" min="2020" max="2099" value="<?php echo date('Y'); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Curso</button>
                </div>
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
                    <input type="hidden" id="edit_id_curso" name="edit_id_curso">
                    <div class="mb-3">
                        <label for="edit_nombre_curso" class="form-label">Nombre del Curso</label>
                        <input type="text" class="form-control" id="edit_nombre_curso" name="edit_nombre_curso" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción (Opcional)</label>
                        <textarea class="form-control" id="edit_descripcion" name="edit_descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_horario" class="form-label">Horario (Ej: Lu 9-11, Mi 9-11)</label>
                        <input type="text" class="form-control" id="edit_horario" name="edit_horario" placeholder="Ej: Lu 9-11, Mi 9-11">
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_profesor" class="form-label">Profesor</label>
                        <select class="form-select" id="edit_id_profesor" name="edit_id_profesor" required>
                            <?php foreach ($listaProfesores as $profesor): ?>
                                <option value="<?php echo $profesor['id_usuario']; ?>"><?php echo htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellido']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_anio_academico" class="form-label">Año Académico</label>
                        <input type="number" class="form-control" id="edit_anio_academico" name="edit_anio_academico" min="2020" max="2099" required>
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
function cargarDatosCurso(id) {
    fetch('index.php?controller=Admin&action=getCurso&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) { alert(data.error); } 
            else {
                document.getElementById('edit_id_curso').value = data.id_curso;
                document.getElementById('edit_nombre_curso').value = data.nombre_curso;
                document.getElementById('edit_descripcion').value = data.descripcion;
                document.getElementById('edit_horario').value = data.horario;
                document.getElementById('edit_id_profesor').value = data.id_profesor;
                document.getElementById('edit_anio_academico').value = data.anio_academico;
            }
        });
}
function confirmarEliminarCurso(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este curso? Se eliminarán todas las matrículas y asignaciones a mallas.')) {
        fetch('index.php?controller=Admin&action=eliminarCurso', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_curso=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) { window.location.reload(); } 
            else { alert('Error al eliminar el curso: ' + data.message); }
        });
    }
}
</script>