<?php
// Archivo: views/profesor/seleccionar_curso_asistencia.php
// (Añadido el campo 'horario')
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-calendar-check me-2"></i> Seleccionar Curso para Asistencia</h2>
    <p class="lead">Elige uno de tus cursos para proceder al registro de asistencia.</p>
    <?php if (empty($cursos)): ?>
        <div class="alert alert-warning">No tienes cursos asignados.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($cursos as $curso): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($curso['nombre_curso']); ?></h5>
                            <p class="card-text text-muted">
                                <i class="fas fa-clock me-1"></i> 
                                <?php echo htmlspecialchars($curso['horario'] ?? 'Horario no definido'); ?>
                            </p>
                            <p class="card-text"><?php echo htmlspecialchars($curso['descripcion']); ?></p>
                            <a href="index.php?controller=Profesor&action=tomarAsistencia&id_curso=<?php echo $curso['id_curso']; ?>" class="btn btn-primary mt-3 me-2">
                                <i class="fas fa-check-square me-2"></i> Tomar Asistencia
                            </a>
                            <a href="index.php?controller=Profesor&action=verHistoricoAsistencia&id_curso=<?php echo $curso['id_curso']; ?>" class="btn btn-secondary mt-3">
                                <i class="fas fa-history me-2"></i> Ver Historial
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="mt-4">
        <a href="index.php?controller=Profesor&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Panel
        </a>
    </div>
</div>