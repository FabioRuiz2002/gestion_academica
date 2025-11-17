<?php
/*
 * Archivo: views/profesor/gestionar_materiales.php
 * (CORREGIDO: 'class.' y botón "Volver" estandarizado)
 */
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>Material de Clase:</h2>
            <h3 class="text-primary"><?php echo htmlspecialchars($infoCurso['nombre_curso']); ?></h3>
        </div>
    </div>
    
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['success_message']) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>
    
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h5 class="mb-0">Subir Nuevo Material</h5>
        </div>
        <div class="card-body">
            <form action="index.php?controller=Profesor&action=subirMaterial" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_curso" value="<?php echo $infoCurso['id_curso']; ?>">
                <div class="mb-3">
                    <label for="archivo_material" class="form-label">Seleccionar archivo (PDF, PPT, DOCX, ZIP, etc.)</label>
                    <input type="file" class="form-control" name="archivo_material" id="archivo_material" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-2"></i> Subir Archivo</button>
            </form>
        </div>
    </div>
    
    <h4>Materiales Subidos</h4>
    <?php if (empty($listaMateriales)): ?>
        <div class="alert alert-info">Aún no has subido ningún material para este curso.</div>
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
                        <small class="text-muted">Subido el: <?php echo date('d/m/Y H:i', strtotime($material['fecha_subida'])); ?></small>
                    </div>
                    <a href="index.php?controller=Profesor&action=eliminarMaterial&id_material=<?php echo $material['id_material']; ?>&id_curso=<?php echo $infoCurso['id_curso']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo?');">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <?php include_once VIEW_PATH . 'layouts/boton_volver.php'; ?>
</div>