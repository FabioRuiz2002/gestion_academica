<?php /* Archivo: views/estudiante/mis_materiales.php */ ?>
<div class="container mt-4">
    <h3>Materiales de Clase: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h3>
    <hr>

    <div class="row">
        <?php if ($listaMateriales->rowCount() > 0): ?>
            <?php while ($mat = $listaMateriales->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-file-pdf fa-4x text-danger"></i>
                            </div>
                            <h5 class="card-title text-truncate" title="<?php echo htmlspecialchars($mat['nombre_archivo']); ?>">
                                <?php echo htmlspecialchars($mat['nombre_archivo']); ?>
                            </h5>
                            <p class="card-text small text-muted">
                                Subido el: <?php echo date('d/m/Y', strtotime($mat['fecha_subida'])); ?>
                            </p>
                            <a href="<?php echo $mat['ruta_archivo']; ?>" class="btn btn-primary w-100" download>
                                <i class="fas fa-download me-2"></i> Descargar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-folder-open me-2"></i> El profesor aún no ha subido materiales para este curso.
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>