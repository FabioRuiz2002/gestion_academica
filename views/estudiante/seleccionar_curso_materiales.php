<?php
/*
 * Archivo: views/estudiante/seleccionar_curso_materiales.php
 * Propósito: Muestra la lista de cursos matriculados para ver materiales.
 */
?>
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-download me-2"></i> Material de Clase</h2>
    <p class="lead">Selecciona uno de tus cursos para ver y descargar los materiales subidos por tu profesor.</p>
    
    <?php if (empty($cursosMatriculados)): ?>
        <div class="alert alert-warning">
            Aún no estás matriculado en ningún curso.
        </div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($cursosMatriculados as $curso): ?>
                <a href="index.php?controller=Estudiante&action=verMateriales&id_curso=<?php echo $curso['id_curso']; ?>" 
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><?php echo htmlspecialchars($curso['nombre_curso']); ?></h5>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            <?php echo htmlspecialchars($curso['horario'] ?? 'Horario no definido'); ?>
                        </small>
                    </div>
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="index.php?controller=Estudiante&action=index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Panel
        </a>
    </div>
</div>