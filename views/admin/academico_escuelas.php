<?php
/*
 * Archivo: views/admin/academico_escuelas.php
 * (Botón "Volver" estandarizado con el componente)
 */
?>
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?controller=Academico&action=index">Gestión Académica</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($infoFacultad['nombre_facultad']); ?></li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">Facultad:</h2>
            <h3 class="text-primary"><?php echo htmlspecialchars($infoFacultad['nombre_facultad']); ?></h3>
        </div>
        </div>
    <p class="lead">Gestiona las Escuelas Profesionales (carreras) que pertenecen a esta facultad.</p>
    
    <?php
    if (isset($_SESSION['error_message_academico'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message_academico'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['error_message_academico']);
    }
    ?>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Escuelas</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearEscuelaModal">
                <i class="fas fa-plus me-1"></i> Crear Escuela
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre de la Escuela</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listaEscuelas)): ?>
                            <tr>
                                <td colspan="3" class="text-center">No hay escuelas creadas para esta facultad.</td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php foreach ($listaEscuelas as $esc): ?>
                        <tr>
                            <td><?php echo $esc['id_escuela']; ?></td>
                            <td><?php echo htmlspecialchars($esc['nombre_escuela']); ?></td>
                            <td>
                                <a href="index.php?controller=Academico&action=verEscuela&id_escuela=<?php echo $esc['id_escuela']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i> Ver Planes de Estudio
                                </a>
                                <button class="btn btn-warning btn-sm" onclick="cargarDatosEscuela(<?php echo $esc['id_escuela']; ?>)" data-bs-toggle="modal" data-bs-target="#editarEscuelaModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="confirmarEliminarEscuela(<?php echo $esc['id_escuela']; ?>, <?php echo $infoFacultad['id_facultad']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
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
                    <input type="hidden" name="id_facultad" value="<?php echo $infoFacultad['id_facultad']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Facultad</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($infoFacultad['nombre_facultad']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_escuela" class="form-label">Nombre de la Nueva Escuela</label>
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
                    <input type="hidden" name="id_facultad_redirect" value="<?php echo $infoFacultad['id_facultad']; ?>">
                    
                    <div class="mb-3">
                        <label for="edit_id_facultad" class="form-label">Facultad</label>
                        <select class="form-select" id="edit_id_facultad" name="edit_id_facultad" required>
                             <?php foreach ($listaFacultades as $fac): ?>
                                <option value="<?php echo $fac['id_facultad']; ?>" <?php echo ($fac['id_facultad'] == $infoFacultad['id_facultad']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($fac['nombre_facultad']); ?>
                                </option>
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

<script>
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
function confirmarEliminarEscuela(id, id_facultad) {
    if (confirm('¿Estás seguro de que deseas eliminar esta escuela? Solo funcionará si no tiene planes de estudio asociados.')) {
        fetch('index.php?controller=Academico&action=eliminarEscuela', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id + '&id_facultad_redirect=' + id_facultad
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) { window.location.reload(); } 
            else { alert('Error al eliminar: ' + data.message); }
        });
    }
}
</script>