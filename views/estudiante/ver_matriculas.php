<?php
// Archivo: views/estudiante/ver_matriculas.php
// (Añadido el campo 'horario')
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-book-open me-2"></i> Mis Cursos y Matrícula</h2>
    <?php 
    if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?php echo $_SESSION['mensaje']['tipo']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['mensaje']['texto']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>
    <ul class="nav nav-tabs mb-4" id="matriculaTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="matriculados-tab" data-bs-toggle="tab" data-bs-target="#matriculados" type="button" role="tab" aria-controls="matriculados" aria-selected="true">
            <i class="fas fa-check-circle me-1"></i> Cursos Matriculados
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="disponibles-tab" data-bs-toggle="tab" data-bs-target="#disponibles" type="button" role="tab" aria-controls="disponibles" aria-selected="false">
            <i class="fas fa-plus me-1"></i> Cursos Disponibles (<?php echo count($cursosDisponibles); ?>)
        </button>
      </li>
    </ul>
    <div class="tab-content" id="matriculaTabsContent">
        <div class="tab-pane fade show active" id="matriculados" role="tabpanel" aria-labelledby="matriculados-tab">
            <h4 class="mb-3">Cursos donde ya estás inscrito:</h4>
            <?php if (empty($cursosMatriculados)): ?>
                <div class="alert alert-warning">No estás matriculado en ningún curso aún. Utiliza la pestaña "Cursos Disponibles".</div>
            <?php else: ?>
                <ul class="list-group shadow-sm">
                    <?php foreach ($cursosMatriculados as $curso): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1"><?php echo htmlspecialchars($curso['nombre_curso']); ?></h5>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo htmlspecialchars($curso['horario'] ?? 'Horario no definido'); ?>
                                </small>
                            </div>
                            <span class="badge bg-primary rounded-pill">Matriculado</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <div class="tab-pane fade" id="disponibles" role="tabpanel" aria-labelledby="disponibles-tab">
            <h4 class="mb-3">Cursos disponibles para matrícula:</h4>
            <?php if (empty($cursosDisponibles)): ?>
                <div class="alert alert-success">¡No hay más cursos disponibles para matricular!</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover shadow-sm">
                        <thead class="table-info">
                            <tr>
                                <th>Curso</th>
                                <th>Profesor</th>
                                <th>Horario</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cursosDisponibles as $curso): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($curso['nombre_curso']); ?></td>
                                    <td><?php echo htmlspecialchars($curso['nombre_profesor'] . ' ' . $curso['apellido_profesor']); ?></td>
                                    <td>
                                        <i class="fas fa-clock me-1 text-muted"></i>
                                        <?php echo htmlspecialchars($curso['horario'] ?? 'No definido'); ?>
                                    </td>
                                    <td>
                                        <form method="POST" action="index.php?controller=Estudiante&action=matricularCurso">
                                            <input type="hidden" name="id_curso" value="<?php echo $curso['id_curso']; ?>">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-user-plus"></i> Matricular
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="mt-4">
        <a href="index.php?controller=Estudiante&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Panel
        </a>
    </div>
</div>