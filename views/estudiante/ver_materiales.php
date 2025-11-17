<?php
/*
 * Archivo: views/estudiante/ver_materiales.php
 * (Botón "Volver" estandarizado con el componente)
 */
?>
<div class="container mt-4">
    <h2>Material de Clase</h2>
    <h3 class.text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
    <hr>

    <?php if (empty($listaMateriales)): ?>
        <div class.alert alert-info">Tu profesor aún no ha subido materiales para este curso.</div>
    <?php else: ?>
        <ul class.list-group">
            <?php foreach ($listaMateriales as $material): ?>
                <li class.list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class.fas fa-file-alt me-2 text-primary"></i>
                        <a href="<?php echo htmlspecialchars($material['ruta_archivo']); ?>" target="_blank" class="fs-5">
                            <?php echo htmlspecialchars($material['nombre_archivo']); ?>
                        </a>
                        <br>
                        <small class.text-muted">Subido el: <?php echo date('d/m/Y H:i', strtotime($material['fecha_subida'])); ?></small>
                    </div>
                    <a href="<?php echo htmlspecialchars($material['ruta_archivo']); ?>" target="_blank" class="btn btn-primary btn-sm">
                        <i class.fas fa-download me-1"></i> Descargar
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>