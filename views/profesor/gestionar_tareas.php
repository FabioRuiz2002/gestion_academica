<?php
/*
 * Archivo: views/profesor/gestionar_tareas.php
 * Propósito: Formulario para crear tareas y lista de tareas creadas.
 */
?>
<div class="container mt-4">
    <h2 class="mb-4">Gestionar Tareas: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h2>
    <hr>
    
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['success_message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-plus me-2"></i> Crear Nueva Tarea</h5>
        </div>
        <div class="card-body">
            <form action="index.php?controller=Profesor&action=crearTarea" method="POST">
                <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($infoCurso['id_curso']); ?>">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título de la Tarea (*)</label>
                    <input class="form-control" type="text" id="titulo" name="titulo" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción / Instrucciones</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="fecha_limite" class="form-label">Fecha Límite (Opcional)</label>
                    <input class="form-control" type="datetime-local" id="fecha_limite" name="fecha_limite">
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-2"></i> Crear Tarea
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i> Tareas del Curso</h5>
        </div>
        <div class="card-body">
            <?php if (empty($listaTareas)): ?>
                <div class="alert alert-info mb-0">No se han creado tareas para este curso.</div>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($listaTareas as $tarea): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?php echo htmlspecialchars($tarea['titulo']); ?></h5>
                                <p class="mb-1"><?php echo htmlspecialchars($tarea['descripcion']); ?></p>
                                <small class="text-muted">
                                    Límite: 
                                    <?php if ($tarea['fecha_limite']): ?>
                                        <?php echo date('d/m/Y h:i A', strtotime($tarea['fecha_limite'])); ?>
                                    <?php else: ?>
                                        Sin fecha límite
                                    <?php endif; ?>
                                </small>
                            </div>
                            <div>
                                <a href="index.php?controller=Profesor&action=verEntregas&id_tarea=<?php echo $tarea['id_tarea']; ?>" class="btn btn-primary btn-sm me-2">
                                    <i class="fas fa-eye me-1"></i> Ver Entregas
                                </a>
                                <a href="index.php?controller=Profesor&action=eliminarTarea&id_tarea=<?php echo $tarea['id_tarea']; ?>&id_curso=<?php echo $infoCurso['id_curso']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarea? Todas las entregas de los estudiantes serán borradas.');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="index.php?controller=Profesor&action=panelCurso&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Curso
        </a>
    </div>
</div>