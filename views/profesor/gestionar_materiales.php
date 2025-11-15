<?php
/*
 * Archivo: views/profesor/gestionar_materiales.php
 * (Corregido el botón 'Volver')
 */
?>
<div class="container mt-4">
    <h2 class="mb-4">Gestionar Materiales: <span class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></span></h2>
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
            <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i> Subir Nuevo Material</h5>
        </div>
        <div class="card-body">
            <form action="index.php?controller=Profesor&action=subirMaterial" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_curso" value="<?php echo htmlspecialchars($infoCurso['id_curso']); ?>">
                <div class="mb-3">
                    <label for="archivo_material" class="form-label">Seleccionar archivo (PDF, DOCX, PPTX, ZIP, etc.)</label>
                    <input class="form-control" type="file" id="archivo_material" name="archivo_material" required>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i> Subir Archivo
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i> Materiales del Curso</h5>
        </div>
        <div class="card-body">
            <?php if (empty($listaMateriales)): ?>
                <div class="alert alert-info mb-0">No se han subido materiales para este curso.</div>
            <?php else: ?>
                <ul class="list-group">
                    <?php foreach ($listaMateriales as $material): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-file-alt me-2 text-muted"></i>
                                <a href="<?php echo htmlspecialchars($material['ruta_archivo']); ?>" target="_blank">
                                    <?php echo htmlspecialchars($material['nombre_archivo']); ?>
                                </a>
                                <br>
                                <small class="text-muted">Subido: <?php echo date('d/m/Y H:i', strtotime($material['fecha_subida'])); ?></small>
                            </div>
                            <a href="index.php?controller=Profesor&action=eliminarMaterial&id_material=<?php echo $material['id_material']; ?>&id_curso=<?php echo $infoCurso['id_curso']; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('¿Estás seguro de que deseas eliminar este material?');">
                                <i class="fas fa-trash"></i>
                            </a>
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