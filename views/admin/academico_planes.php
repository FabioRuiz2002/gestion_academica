<?php
/*
 * Archivo: views/admin/academico_planes.php
 * Propósito: PÁGINA 3 - Lista y CRUD de Planes para una Escuela.
 */
?>
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php?controller=Academico&action=index">Gestión Académica</a></li>
            <li class="breadcrumb-item"><a href="index.php?controller=Academico&action=verFacultad&id_facultad=<?php echo $infoFacultad['id_facultad']; ?>"><?php echo htmlspecialchars($infoFacultad['nombre_facultad']); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($infoEscuela['nombre_escuela']); ?></li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">Escuela:</h2>
            <h3 class="text-primary"><?php echo htmlspecialchars($infoEscuela['nombre_escuela']); ?></h3>
        </div>
        <a href="index.php?controller=Admin&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Panel Principal
        </a>
    </div>
    <p class="lead">Gestiona los Planes de Estudio (Mallas Curriculares) que pertenecen a esta escuela.</p>
    
    <?php
    if (isset($_SESSION['error_message_academico'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . $_SESSION['error_message_academico'] . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        unset($_SESSION['error_message_academico']);
    }
    ?>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Planes de Estudio</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearPlanModal">
                <i class="fas fa-plus me-1"></i> Crear Plan
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Plan (Malla)</th>
                            <th>Año</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listaPlanes)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay planes creados para esta escuela.</td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php foreach ($listaPlanes as $plan): ?>
                        <tr>
                            <td><?php echo $plan['id_plan_estudio']; ?></td>
                            <td><?php echo htmlspecialchars($plan['nombre_plan']); ?></td>
                            <td><?php echo htmlspecialchars($plan['anio']); ?></td>
                            <td>
                                <a href="index.php?controller=Academico&action=gestionarMalla&id_plan=<?php echo $plan['id_plan_estudio']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-tasks me-1"></i> Gestionar Malla
                                </a>
                                <button class="btn btn-warning btn-sm" onclick="cargarDatosPlan(<?php echo $plan['id_plan_estudio']; ?>)" data-bs-toggle="modal" data-bs-target="#editarPlanModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="confirmarEliminarPlan(<?php echo $plan['id_plan_estudio']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="crearPlanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Academico&action=crearPlan" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Plan de Estudio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_escuela" value="<?php echo $infoEscuela['id_escuela']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Escuela</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($infoEscuela['nombre_escuela']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="nombre_plan" class="form-label">Nombre del Plan (Ej: Malla 2024)</label>
                        <input type="text" class="form-control" id="nombre_plan" name="nombre_plan" required>
                    </div>
                    <div class="mb-3">
                        <label for="anio" class="form-label">Año</label>
                        <input type="number" class="form-control" id="anio" name="anio" min="2020" max="2099" value="<?php echo date('Y'); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarPlanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Academico&action=editarPlan" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Plan de Estudio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_plan_estudio" name="edit_id_plan_estudio">
                    <input type="hidden" name="id_escuela_redirect" value="<?php echo $infoEscuela['id_escuela']; ?>">
                    
                    <div class="mb-3">
                        <label for="edit_id_escuela" class="form-label">Escuela</Telebel>
                        <select class="form-select" id="edit_id_escuela" name="edit_id_escuela" required>
                            <?php foreach ($listaEscuelas as $esc): ?>
                                <option value="<?php echo $esc['id_escuela']; ?>" <?php echo ($esc['id_escuela'] == $infoEscuela['id_escuela']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($esc['nombre_escuela']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nombre_plan" class="form-label">Nombre del Plan (Ej: Malla 2024)</label>
                        <input type="text" class="form-control" id="edit_nombre_plan" name="edit_nombre_plan" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_anio" class="form-label">Año</label>
                        <input type="number" class="form-control" id="edit_anio" name="edit_anio" min="2020" max="2099" required>
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
function cargarDatosPlan(id) {
    fetch('index.php?controller=Academico&action=getPlan&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) { alert(data.error); } 
            else {
                document.getElementById('edit_id_plan_estudio').value = data.id_plan_estudio;
                document.getElementById('edit_nombre_plan').value = data.nombre_plan;
                document.getElementById('edit_anio').value = data.anio;
                document.getElementById('edit_id_escuela').value = data.id_escuela;
            }
        });
}
function confirmarEliminarPlan(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este Plan de Estudio? Solo funcionará si no tiene cursos o estudiantes asociados.')) {
        fetch('index.php?controller=Academico&action=eliminarPlan', {
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
</script>