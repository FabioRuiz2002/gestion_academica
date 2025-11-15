<?php
/*
 * Archivo: views/estudiante/ver_materiales.php
 * Propósito: Muestra la lista de materiales descargables para un curso.
 * (Corregido el enlace del botón 'Volver')
 */
?>
<div class="container mt-4">
    <h2 class="mb-4">Materiales: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h2>
    <hr>
    
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i> Archivos Disponibles</h5>
        </div>
        <div class="card-body">
            <?php if (empty($listaMateriales)): ?>
                <div class="alert alert-info mb-0">Tu profesor aún no ha subido materiales para este curso.</div>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($listaMateriales as $material): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-alt me-2 text-muted"></i>
                                <a href="<?php echo htmlspecialchars($material['ruta_archivo']); ?>" download="<?php echo htmlspecialchars($material['nombre_archivo']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($material['nombre_archivo']); ?>
                                </a>
                                <br>
                                <small class="text-muted">Subido: <?php echo date('d/m/Y', strtotime($material['fecha_subida'])); ?></small>
                            </div>
                            <a href="<?php echo htmlspecialchars($material['ruta_archivo']); ?>" download="<?php echo htmlspecialchars($material['nombre_archivo']); ?>" target="_blank" class="btn btn-success btn-sm">
                                <i class="fas fa-download me-1"></i> Descargar
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="index.php?controller=Estudiante&action=panelCurso&id_curso=<?php echo $infoCurso['id_curso']; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Volver al Curso
        </a>
    </div>
</div>