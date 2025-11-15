<?php
/*
 * Archivo: views/admin/gestionar_academico.php
 * (CRUD completo para Facultades y Escuelas)
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Gestión de Estructura Académica</h2>
        <a href="index.php?controller=Admin&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Panel Principal
        </a>
    </div>
    <p class="lead">Gestiona la estructura de Facultades, Escuelas, Mallas y Cursos del sistema.</p>
    
    <?php
    if (isset($_SESSION['error_message_academico'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message_academico'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['error_message_academico']);
    }
    // Mensaje de error específico para cursos
    if (isset($_SESSION['error_message_curso'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message_curso'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['error_message_curso']);
    }
    ?>

    <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-facultades-tab" data-bs-toggle="pill" data-bs-target="#pills-facultades" type="button" role="tab">
                <i class="fas fa-building me-1"></i> Facultades
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-escuelas-tab" data-bs-toggle="pill" data-bs-target="#pills-escuelas" type="button" role="tab">
                <i class="fas fa-graduation-cap me-1"></i> Escuelas
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-planes-tab" data-bs-toggle="pill" data-bs-target="#pills-planes" type="button" role="tab">
                <i class="fas fa-clipboard-list me-1"></i> Planes de Estudio (Mallas)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-cursos-tab" data-bs-toggle="pill" data-bs-target="#pills-cursos" type="button" role="tab">
                <i class="fas fa-book me-1"></i> Cursos (Repositorio)
            </button>
        </li>
    </ul>
    
    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-facultades" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p>Lista de facultades en el sistema.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearFacultadModal">
                    <i class="fas fa-plus me-1"></i> Crear Facultad
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Facultad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaFacultades as $fac): ?>
                        <tr>
                            <td><?php echo $fac['id_facultad']; ?></td>
                            <td><?php echo htmlspecialchars($fac['nombre_facultad']); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="cargarDatosFacultad(<?php echo $fac['id_facultad']; ?>)" data-bs-toggle="modal" data-bs-target="#editarFacultadModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="confirmarEliminarFacultad(<?php echo $fac['id_facultad']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="tab-pane fade" id="pills-escuelas" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p>Lista de escuelas profesionales y la facultad a la que pertenecen.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearEscuelaModal">
                    <i class="fas fa-plus me-1"></i> Crear Escuela
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Escuela</th>
                            <th>Facultad a la que pertenece</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaEscuelas as $esc): ?>
                        <tr>
                            <td><?php echo $esc['id_escuela']; ?></td>
                            <td><?php echo htmlspecialchars($esc['nombre_escuela']); ?></td>
                            <td><?php echo htmlspecialchars($esc['nombre_facultad']); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="cargarDatosEscuela(<?php echo $esc['id_escuela']; ?>)" data-bs-toggle="modal" data-bs-target="#editarEscuelaModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="confirmarEliminarEscuela(<?php echo $esc['id_escuela']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="tab-pane fade" id="pills-planes" role="tabpanel">
            <p>Lista de mallas curriculares. Haz clic en "Gestionar Malla" para asignar cursos a cada plan.</p>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre del Plan (Malla)</th>
                            <th>Año</th>
                            <th>Escuela</th>
                            <th>Facultad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($listaPlanes as $plan): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($plan['nombre_plan']); ?></td>
                            <td><?php echo htmlspecialchars($plan['anio']); ?></td>
                            <td><?php echo htmlspecialchars($plan['nombre_escuela']); ?></td>
                            <td><?php echo htmlspecialchars($plan['nombre_facultad']); ?></td>
                            <td>
                                <a href="index.php?controller=Academico&action=gestionarMalla&id_plan=<?php echo $plan['id_plan_estudio']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-tasks me-1"></i> Gestionar Malla
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="tab-pane fade" id="pills-cursos" role="tabpanel">
            <?php include_once 'gestionar_cursos_content.php'; ?>
        </div>
        
    </div>
</div>

<div class="modal fade" id="crearFacultadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Academico&action=crearFacultad" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nueva Facultad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_facultad" class="form-label">Nombre de la Facultad</label>
                        <input type="text" class="form-control" id="nombre_facultad" name="nombre_facultad" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Facultad</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarFacultadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Academico&action=editarFacultad" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Facultad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_facultad" name="edit_id_facultad">
                    <div class="mb-3">
                        <label for="edit_nombre_facultad" class="form-label">Nombre de la Facultad</label>
                        <input type="text" class="form-control" id="edit_nombre_facultad" name="edit_nombre_facultad" required>
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

<div class="modal fade" id="crearEscuelaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Academico&action=crearEscuela" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nueva Escuela</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_facultad" class="form-label">Facultad</label>
                        <select class="form-select" id="id_facultad" name="id_facultad" required>
                            <option value="">Seleccione una facultad</option>
                            <?php foreach ($listaFacultades as $fac): ?>
                                <option value="<?php echo $fac['id_facultad']; ?>"><?php echo htmlspecialchars($fac['nombre_facultad']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_escuela" class="form-label">Nombre de la Escuela</label>
                        <input type="text" class="form-control" id="nombre_escuela" name="nombre_escuela" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Escuela</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarEscuelaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Academico&action=editarEscuela" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Escuela</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_escuela" name="edit_id_escuela">
                    <div class="mb-3">
                        <label for="edit_id_facultad" class="form-label">Facultad</label>
                        <select class="form-select" id="edit_id_facultad" name="edit_id_facultad" required>
                            <option value="">Seleccione una facultad</option>
                            <?php foreach ($listaFacultades as $fac): ?>
                                <option value="<?php echo $fac['id_facultad']; ?>"><?php echo htmlspecialchars($fac['nombre_facultad']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nombre_escuela" class="form-label">Nombre de la Escuela</label>
                        <input type="text" class="form-control" id="edit_nombre_escuela" name="edit_nombre_escuela" required>
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

<div class="modal fade" id="crearCursoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Admin&action=crearCurso" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
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
                <div class="modal-header">
                    <h5 class="modal-title">Editar Curso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
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
// --- JS para Cursos ---
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

// --- JS para Facultades ---
function cargarDatosFacultad(id) {
    fetch('index.php?controller=Academico&action=getFacultad&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) { alert(data.error); } 
            else {
                document.getElementById('edit_id_facultad').value = data.id_facultad;
                document.getElementById('edit_nombre_facultad').value = data.nombre_facultad;
            }
        });
}
function confirmarEliminarFacultad(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta facultad? Solo funcionará si no tiene escuelas asociadas.')) {
        fetch('index.php?controller=Academico&action=eliminarFacultad', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) { window.location.reload(); } 
            else { alert('Error al eliminar: ' + data.message); }
        });
    }
}

// --- JS para Escuelas ---
function cargarDatosEscuela(id) {
    fetch('index.php?controller=Academico&action=getEscuela&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) { alert(data.error); } 
            else {
                document.getElementById('edit_id_escuela').value = data.id_escuela;
                document.getElementById('edit_nombre_escuela').value = data.nombre_escuela;
                document.getElementById('edit_id_facultad').value = data.id_facultad;
            }
        });
}
function confirmarEliminarEscuela(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta escuela? Solo funcionará si no tiene planes de estudio asociados.')) {
        fetch('index.php?controller=Academico&action=eliminarEscuela', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) { window.location.reload(); } 
            else { alert('Error al eliminar: ' + data.message); }
        });
    }
}

// --- JS para Pestañas (Recordar la última pestaña activa) ---
document.addEventListener('DOMContentLoaded', (event) => {
    let activeTab = localStorage.getItem('activeTab');
    
    // Si hay una pestaña guardada, ábrela
    if (activeTab) {
        let tabElement = document.querySelector(activeTab + '-tab');
        if(tabElement) {
            new bootstrap.Tab(tabElement).show();
        }
    } else {
        // Si no hay nada guardado, abre la primera pestaña por defecto
        let firstTab = document.querySelector('#pills-facultades-tab');
        if (firstTab) {
            new bootstrap.Tab(firstTab).show();
        }
    }

    // Guardar la pestaña activa en el localStorage cuando se cambia
    const tabButtons = document.querySelectorAll('button[data-bs-toggle="pill"]');
    tabButtons.forEach(tab => {
        tab.addEventListener('shown.bs.tab', (event) => {
            localStorage.setItem('activeTab', event.target.getAttribute('data-bs-target'));
        });
    });
});
</script>